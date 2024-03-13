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
                    echo "Description mise à jour avec succès.";
                } else {
                    echo "Erreur lors de la mise à jour de la description ";
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
                    echo "Tags mis à jour avec succès.";
                } else {
                    echo "Erreur lors de la mise à jour des tags ";
                }


                //header("Location: ../pageSuggestion.php");
				echo '<script>window.location = "../pageSuggestion.php";</script>';
                break;



/* A FAIRE */
            case 'creerEvenement':
                // Requête pour récupérer la valeur de idEvenement
                $req_idEvent = "SELECT idEvenement FROM evenement ORDER BY idEvenement DESC LIMIT 1";

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
                            echo "Aucun événement trouvé dans la base de données.";
                        }
                    } 
                    else {
                        // La requête a échoué
                        echo "Erreur lors de l'exécution de la requête: " . $connexion->error;
                    }
                }
                else {
                    echo "Erreur lors de la préparation de la requête: " . $connexion->error;
                }

                // Récupération et reféfinition des variables pour créer un événement
                $titre = $_POST['titre'];

                $date = $_POST['date'];
                $heure = $_POST['heure'];

                $ville = $_POST['ville'];
                $cp = $_POST['cp'];
                $adresse = $_POST['adresse'];
                $adresseEvent = $adresse . ', ' . $cp . ' ' . $adresse;

                $type = $_POST['type'];
                $type == 'public' ? $statut = 0 : $statut = 1;

                $nbParticip = $_POST['nbParticip'];
                $photo = $_POST['photo'];
                if (is_uploaded_file($_FILES['photo']['tmp_name'])) {
                    $original_filename = $_FILES['photo']['name'];
    
                    // Récupération de l'extension du fichier
                    $file_extension = pathinfo($original_filename, PATHINFO_EXTENSION);
                
                    // Emplacement de destination pour l'image avec le nouveau nom
                    $destination = "../img/";
                    $new_filename = $titre . $id_event . '.' . $file_extension;
                    $destination_path = $destination . $new_filename;

                    move_uploaded_file($_FILES['photo']['tmp_name'], $destination_path);
                    $chemImages = "/img/". $new_filename;
                }

                $participants = $_POST['participants'];
                $mess_invit = $_POST['mess_invit'];

                $idOrganisateur = $_SESSION['user_id'];

                $nvl_event = new Evenement($id_event,[]);
                // Récupère la liste de mots envoyée par le formulaire
                $motsListe = isset($_POST['motsListeEvenement']) ? json_decode($_POST['motsListeEvenement']) : [];

                //on crée ajout l'utilisateur dans la BD
                $nvl_event->setId($id_event);
                $nvl_event->setTitre($titre);
                $nvl_event->setDate($date);
                $nvl_event->setHeure($heure);
                $nvl_event->setLieu($adresseEvent);
                //Création liste de mot objet
                $listeMot_objet = array();
                foreach($motsListe as $motX){
                    $listeMot_objet[]= new Mot($motX);
                }
                $nvl_event->setMots($listeMot_objet);           
                $nvl_event->definirDescription($dicoSynTag);
                
                $id_event = $nvl_event->getId();
                $titre_event = $nvl_event->getTitre();
                $req_insertEvnt = "INSERT INTO evenement (idEvenement, nom, dateEvent, effMax,
                                   statut, heure, adresse, chemImages, idCategorie, idOrganisateur) VALUES (?,?,?,?,?,?,?,?,?,?)"; // Correction à apporter sur la requête car pas possible d'insérer sans Catégorie ni Organisateur 
                $res_insertEvnt = $connexion->prepare($req_insertEvnt);
                $res_insertEvnt->bind_param("issiisssii", $id_event, $titre_event, $date, $nbParticip, $statut,
                                 $heure, $adresseEvent, $chemImages, 1, $idOrganisateur);

                
                //On attributs les mots de l'évènement

                /*| -------------------------------- |*/
                /*| Mettre a jour la base de données |*/
                /*| -------------------------------- |*/
                /*| Mettre a jour la description |*/

                // Variables pour les nouvelles données de l'utilisateur
                $description_event = $nvl_event->getMots();

                $mots_str ="";
                foreach ($description_event as $id_mot) {
                    $mots_str .=  $id_mot->getLibelle() . " "; 
                }

                // Requête SQL pour mettre à jour la description de l'utilisateur
                $req_majDescEvent = "UPDATE evenement SET description = ? WHERE idEvenement = ?";
                $res_updtDescEvent = $connexion->prepare($req_majDescEvent);
                $res_updtDescEvent->bind_param("si", $mots_str, $id_event);

                
                // Exécution de la requête
                if ($res_updtDescEvent->execute()) {
                    echo "Description mise à jour avec succès.";
                } else {
                    echo "Erreur lors de la mise à jour de la description ";
                }

                /*| Mettre a jour les tags |*/
                // Fonction pour récupérer l'ID d'un tag à partir de son libellé
                function getTagId($conn, $tag) {
                    $req_idTag = "SELECT idTag FROM Tag WHERE libelle = ?";
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
                    echo "Tags mis à jour avec succès.";
                } else {
                    echo "Erreur lors de la mise à jour des tags ";
                }

				echo '<script>window.location = "../MesEvent.php";</script>'; 
                break;
            default:
                // Action non reconnue
                break;
        }
    } else {
        echo "</br>ID de l'utilisateur inconnu.";
    }
}