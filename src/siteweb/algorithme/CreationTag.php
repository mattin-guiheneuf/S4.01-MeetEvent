<?php
session_start();

// Vérifiez si l'utilisateur est connecté en vérifiant la présence de ses informations d'identification dans la session
if (!isset($_SESSION['user_id'])) {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: ../connexion.php");
    exit; // Assurez-vous de terminer le script après la redirection
}

/**
 * Nom du fichier : test_script.php
 * Description : Test de l'algorithme et affichages pour vérification
 * 
 * @author : Duvignau Yannis
 * Date de création: 17 décembre 2023 (date du jour mais à changer)
 * Dernière mise à jour : 17 décembre 2023
 * 
 * @copyright Copyright (c) 2023, MeetEvent
 */

//____________________________________________________________________________________//
//____________________________________________________________________________________//
//                                                                                    //
//                                   IMPORTATION                                      //
//                                                                                    //
//____________________________________________________________________________________//
//____________________________________________________________________________________//

//On récupére les classes Evenement, Utilisateur, Recommandation, Tag et Mot 
require_once "class/Synonyme.php";
require_once "class/ApiDeTraduction.php";
require_once "class/ApiDictionnaireFr.php";
require_once "class/ApiSynonyme.php";
require_once "class/Corpus.php";
require_once "class/DicoSynTags.php";
require_once "class/Evenement.php";
require_once "class/Mot.php";
require_once "class/Recommandation.php";
require_once "class/Tag.php";
require_once "class/Utilisateur.php";

//Faire la connection avec la base de données
require_once "../../gestionBD/database.php";


//____________________________________________________________________________________//
//____________________________________________________________________________________//
//                                                                                    //
//                                 INITIALISATION                                     //
//                                                                                    //
//____________________________________________________________________________________//
//____________________________________________________________________________________//


// Récupération du dicoSynTag
$jsonDicoSynTag = file_get_contents('./data/dicoSynTag.json');
$dicoSynTag = json_decode($jsonDicoSynTag, true);


//____________________________________________________________________________________//
//____________________________________________________________________________________//
//                                                                                    //
//                                     TRAITEMENT                                     //
//                                                                                    //
//____________________________________________________________________________________//
//____________________________________________________________________________________//


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST["action"])) {
        $action = $_POST['action'];
        // Inclure la logique pour chaque action
        switch ($action) {
            case 'ajouterDescriptionUtilisateur':
                // Logique pour créer un utilisateur
                $user_courrant = new Utilisateur($_SESSION["user_id"],[]);
                
                // Récupère la liste de mots envoyée par le formulaire
                $motsListe = isset($_POST['motsListe']) ? json_decode($_POST['motsListe']) : [];

                //Création liste de mot objet
                $listeMot_objet = array();
                foreach($motsListe as $motX){
                    $listeMot_objet[]= new Mot($motX);
                }
                //On attributs les mots de l'utilisateur
                $user_courrant->definirDescription($dicoSynTag,$listeMot_objet);

                /*| -------------------------------- |*/
                /*| Mettre a jour la base de données |*/
                /*| -------------------------------- |*/

                $id_utilisateur = $user_courrant->getId(); // L'ID de l'utilisateur dont vous souhaitez mettre à jour la description

                /*| Mettre a jour la description |*/

                // Variables pour les nouvelles données de l'utilisateur
                $nouvelle_description = $user_courrant->getMots();

                $mots_str ="";
                foreach ($nouvelle_description as $id_mot) {
                    $mots_str .=  $id_mot->getLibelle() . " "; 
                }

                // Requête SQL pour mettre à jour la description de l'utilisateur
                $sql = "UPDATE Utilisateur SET description = ? WHERE idUtilisateur = ?";
                $stmt = $connexion->prepare($sql);
                $stmt->bind_param("si", $mots_str, $id_utilisateur);

                
                // Exécution de la requête
                if ($stmt->execute()) {
                    echo "<script>console.log('Description mise à jour avec succès.')</script>";
                } else {
                    echo "<script>console.log('Erreur lors de la mise à jour de la description ')</script>";
                }

                /*| Mettre a jour les tags |*/
                // Fonction pour récupérer l'ID d'un tag à partir de son libellé
                function getTagId($conn, $tag) {
                    $sql = "SELECT idTag FROM Tag WHERE libelle = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $tag);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        return $row['idTag'];
                    }
                }
                // Variable pour les nouvelles données de l'utilisateur
                $nouveaux_tags = $user_courrant->getTags();

                // Supprimer les anciens tags de l'utilisateur de la table d'association
                $sql_delete = "DELETE FROM Associer WHERE idUtilisateur = ?";
                $stmt_delete = $connexion->prepare($sql_delete);
                $stmt_delete->bind_param("i", $id_utilisateur);
                $stmt_delete->execute();

                // Insérer les nouveaux tags de l'utilisateur dans la table d'association
                $sql_insert = "INSERT INTO Associer VALUES (?, ?)";
                $stmt_insert = $connexion->prepare($sql_insert);
                $stmt_insert->bind_param("ii", $id_utilisateur, $id_tag);

                foreach ($nouveaux_tags as $tag) {
                    $id_tag = getTagId($connexion, $tag);
                    $stmt_insert->execute();
                }

                // Vérifier si les opérations se sont déroulées avec succès
                if ($stmt_delete->affected_rows > 0 || $stmt_insert->affected_rows > 0) {
                    echo "<script>console.log('Tags mis à jour avec succès.')</script>";
                } else {
                    echo "<script>console.log('Erreur lors de la mise à jour des tags')</script> ";
                }


                //header("Location: ../pageSuggestion.php");
				echo '<script>window.location = "../pageSuggestion.php";</script>';
                break;



/* A FAIRE */
            case 'creerEvenement':
                // Requête pour récupérer la valeur de idEvenement
                $req_idEvent = "SELECT idEvenement FROM Evenement ORDER BY idEvenement DESC LIMIT 1";

                // Exécution de la requête
                $prep_reqIdEvent = $connexion->prepare($req_idEvent);
                if ($prep_reqIdEvent) {
                    
                    $prep_reqIdEvent->execute();
                    $res_reqIdEvent = $prep_reqIdEvent->get_result();

                    // Vérifier si la requête a réussi
                    if ($res_reqIdEvent) {
                        // Récupérer la première ligne de résultat
                        $row = $res_reqIdEvent->fetch_assoc();
                        // Vérifier si une ligne a été retournée
                        if ($row) {
                            // Extraire la valeur de idEvenement
                            $id_event = $row['idEvenement'] + 1;
                        } else {
                            // Aucune ligne retournée, donc aucune valeur à récupérer
                            echo "<script>console.log('Aucun événement trouvé dans la base de données.')</script>";
                        }
                    } 
                    else {
                        // La requête a échoué
                        echo "<script>console.log('Erreur lors de l'exécution de la requête: " . $connexion->error ."')</script>";
                    }
                }
                else {
                    echo "<script>console.log('Erreur lors de la préparation de la requête: " . $connexion->error."')</script>";
                }

                // Récupération et reféfinition des variables pour créer un événement
                $titre = $_POST['titre'];

                $date = $_POST['date'];
                $heure = $_POST['heure'];

                $ville = $_POST['ville'];
                $cp = $_POST['cp'];
                $adresse = $_POST['adresse'];
                $adresseEvent = $adresse . ', ' . $cp . ' ' . $ville;

                $type = $_POST['type'];
                $type == 'public' ? $statut = 1 : $statut = 0;

                $nbParticip = $_POST['nbParticip'];
                // Vérifiez si le champ 'photo' a été envoyé via POST
                if (isset($_POST['photo'])) {
                    // Récupérez le chemin relatif de l'image depuis $_POST
                    $chemin_relatif_image = "./img/" .$_POST['photo'];

                    // Maintenant, vous pouvez utiliser $chemin_relatif_image comme vous le souhaitez, par exemple, pour l'insérer dans la base de données ou pour d'autres opérations.
                    // Assurez-vous de valider et de sécuriser ce chemin avant de l'utiliser dans votre application.
                } else {
                    // Le champ 'photo' n'a pas été envoyé via POST
                    echo "<script>console.log('Chemin relatif de l'image non trouvé.')</script>";
                }

                $participants = $_POST['participants'];
                $mess_invit = $_POST['mess_invit'];

                $idOrganisateur = $_SESSION['user_id'];

                $nvl_event = new Evenement($id_event,[]);
                // Récupère la liste de mots envoyée par le formulaire
                $motsListe = isset($_POST['motsListeEvenement']) ? json_decode($_POST['motsListeEvenement']) : [];

                //on crée ajout l'utilisateur dans la BD
                //id
                $nvl_event->setId($id_event);
                //titre
                $nvl_event->setTitre($titre);
                //description
                $listeMot_objet = array();
                foreach($motsListe as $motX){
                    $listeMot_objet[]= new Mot($motX);
                }
                $nvl_event->setMots($listeMot_objet);           
                $nvl_event->definirDescription($dicoSynTag);
                $description_event = $nvl_event->getMots();
                $mots_str ="";
                foreach ($description_event as $id_mot) {
                    $mots_str .=  $id_mot->getLibelle() . " "; 
                }
                //date
                $nvl_event->setDate($date);
                //heure
                $nvl_event->setHeure($heure);
                //lieu
                $nvl_event->setLieu($adresseEvent);

                /* ATTRIBUTION POUR LA REQUETE */
                //id
                $id_event = $nvl_event->getId();
                //titre
                $titre_event = $nvl_event->getTitre();
                //description
                $description_event = $mots_str;
                //token
                $token = password_hash(uniqid(rand(), true), PASSWORD_DEFAULT);
                //prix
                $prix =0.00;
                //catégorie
                $idCategorie = 1;
                //image
                $chemImages = "./img/imagerugby.jpg";

                $req_insertEvnt = "INSERT INTO Evenement (idEvenement, nom, description, dateEvent, effMax, statut,prix, heure, adresse, chemImages, token, idCategorie, idOrganisateur) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $res_insertEvnt = $connexion->prepare($req_insertEvnt);
                $res_insertEvnt->bind_param("isssiiissssii", $id_event, $titre_event, $description_event, $date, $nbParticip, $statut, $prix, $heure, $adresseEvent, $chemin_relatif_image, $token, $idCategorie, $idOrganisateur);
                $res_insertEvnt->execute();
                

                /*| Mettre a jour les tags |*/
                // Fonction pour récupérer l'ID d'un tag à partir de son libellé
                function getTagId($conn, $tag) {
                    $sql = "SELECT idTag FROM Tag WHERE libelle = ?";
                    $res_idTag = $conn->prepare($sql);
                    $res_idTag->bind_param("s", $tag);
                    $res_idTag->execute();
                    $resultat = $res_idTag->get_result();
                    if ($resultat->num_rows > 0) {
                        $row = $resultat->fetch_assoc();
                        return $row['idTag'];
                    }
                }
                // Variable pour les nouvelles données de l'utilisateur
                $tags_event = $nvl_event->getTags();

                // Supprimer les anciens tags de l'utilisateur de la table d'association
                $req_delQualif = "DELETE FROM Qualifier WHERE idEvenement = ?";
                $res_delQualif = $connexion->prepare($req_delQualif);
                $res_delQualif->bind_param("i", $id_event);
                $res_delQualif->execute();

                // Insérer les nouveaux tags de l'utilisateur dans la table d'association
                $req_insertQualif = "INSERT INTO Qualifier VALUES (?, ?)";
                $res_insertQualif = $connexion->prepare($req_insertQualif);
                $res_insertQualif->bind_param("ii", $id_event, $id_tag);

                foreach ($tags_event as $tag) {
                    $id_tag = getTagId($connexion, $tag);
                    $res_insertQualif->execute();
                }

                // Vérifier si les opérations se sont déroulées avec succès
                if ($res_delQualif->affected_rows > 0 || $res_insertQualif->affected_rows > 0) {
                    echo "<script>console.log('Tags mis à jour avec succès.')</script>";
                } else {
                    echo "<script>console.log('Erreur lors de la mise à jour des tags')</script> ";
                }

				echo '<script>window.location = "../MesEvent.php";</script>'; 
                break;
            default:
                // Action non reconnue
                break;
        }
    } else {
        echo "<script>console.log('ID de l'utilisateur inconnu.')</script>";
    }
}