<?php
session_start();
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
require_once "./class/Synonyme.php";
require_once "./class/ApiDeTraduction.php";
require_once "./class/ApiDictionnaireFr.php";
require_once "./class/ApiSynonyme.php";
require_once "./class/Corpus.php";
require_once "./class/DicoSynTags.php";
require_once "./class/Evenement.php";
require_once "./class/Mot.php";
require_once "./class/Recommandation.php";
require_once "./class/Tag.php";
require_once "./class/Utilisateur.php";

// Lire le contenu JSON depuis le fichier
$contenuJSON = file_get_contents('./data/donnees.json');
$donnees = json_decode($contenuJSON, true);

//____________________________________________________________________________________//
//____________________________________________________________________________________//
//                                                                                    //
//                                 INITIALISATION                                     //
//                                                                                    //
//____________________________________________________________________________________//
//____________________________________________________________________________________//

//Transformer les tags en objet Tag
$liste = [
    "Cuisine", "Art",
    "Musique", "Dessin", "Sport", "Entraînement", "Social",
    "Discussion", "Méditation", "Détente", "Lecture", "Écoute","Rire",
    "Divertissement", "Fête", "Exploration", "Voyage", "Découverte", 
    "Enseignement", "Travail", "Créativité", "Construction",
    "Jardinage", "Photographie", "Film", "Danse", "Chant", 
    "Instrument", "Collection", "Informatique", "Réflexion",
    "Engagement", "Volontariat", "Organisation",
    "Exercice", "Expérience", "Test",
    "Développement", "Amélioration", "Innovation", "Économie",
    "Partage", "Influence", "Motivation",
    "Inspiration", "Amusement",
    "Célébration", "Changement",
    "Imagination", "Jeux", "Festival",
    "Culture", "Concert", "Repas", "Aperitif", "Alcool",
    "Association", "Rencontre",
    "Marche", "Amical",
    "Plaisir", "Jeu de société", "Animaux",
    "Soiree", "Nature", "Paysages", "Atelier", 
    "Gastronomie", "Dégustation", "Exposition", "Musee",
    "Dîner", "Caritatif", "Solidarité", "Loisir",
    "Competition", "Tournoi", "Montagne",
    "Finance", "Formation","Océan"];
foreach ($liste as $tag) {
    $list_tag_corpus[] = new Tag(strtolower($tag));
}
//Création du corpus de Tag
$corpusTag = new Corpus(1, $list_tag_corpus);

// Récupération du dicoSynTag
$jsonDicoSynTag = file_get_contents('./data/dicoSynTag.json');
$dicoSynTag = json_decode($jsonDicoSynTag, true);



//Liste utilisé pour l'ACM
$eventsAndUserPreferences = [];

//-------------------------------------------------------------//
//                      Partie Evenement                       //
//-------------------------------------------------------------//

//Recuperer tous les evenement des données et les affecter dans objetEvenement
$objetEvenement = [];
//Affichage des événement et de leur tag
//echo "</br>" . "///////////////////////////////////////////" . "</br>" . "/// Evenement et leurs Tags ///" . "</br>" . "///////////////////////////////////////////" . "</br>";
//$n = 0;
foreach ($donnees['evenements'] as $element) {
    foreach ($element['tags'] as $tag) {
        $list_tags[] = new Tag($tag);
    }
    $objetEvenement[] = new Evenement($element['id'], $list_tags);
    $list_tags = [];
}

// Transformation de tous les evenements
//On parcours tous les evenements existant
foreach ($objetEvenement as $event) {
    $eventsB = [];  //Liste pour UN evenement
    //Pour tous les tags de L'evenement
    $objetEvenementElement = $event->getTags();

    //On regarde si ils sont present dans le corpus tag et on y attribut un 1 sinon 0
    foreach ($corpusTag->getMesTags() as $corpusTagElement) {
        $eventsB[] = (int) in_array($corpusTagElement, $objetEvenementElement);
    }
    //On ajoute l'evenement (avec valeurs binaires) à l'ensemble des évènements
    $eventsAndUserPreferences[] = $eventsB;
}

//____________________________________________________________________________________//
//____________________________________________________________________________________//
//                                                                                    //
//                                     TRAITEMENT                                     //
//                                                                                    //
//____________________________________________________________________________________//
//____________________________________________________________________________________//

//Récupérer la liste de tous les utilisateurs pour vérifier si celui saisi existe
$listeIdUtilisateur = [];
foreach ($donnees['utilisateurs'] as $user) {
    $listeIdUtilisateur[] = $user['id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST["idUser"]) && in_array($_POST["idUser"], $listeIdUtilisateur)) {
        $idUserConnected = $_POST["idUser"];

        //Dico avec tous les utilisateurs et leurs tags associés
        $dicoUser = [];
        foreach ($donnees['utilisateurs'] as $element) {
            foreach ($element['tags'] as $tag) {
                $list_tag_user[] = new Tag($tag);
            }
            $dicoUser[$element['id']] = $list_tag_user;
            $list_tag_user = [];
        }

        //créer l'utilisateur connecté
        $userConnected = new Utilisateur($idUserConnected, $dicoUser[$idUserConnected]);

        //On créé l'utilisateur à ajouter en dernier
        $user = [];
        foreach ($corpusTag->getMesTags() as $tagElement) {
            $user[] = (int) in_array($tagElement, $userConnected->getTags());
        }
        //On ajoute l'utilisateur (avec valeurs binaires) à l'ensemble des évènements
        $eventsAndUserPreferences[] = $user;


        // Resultat Recommandation
        // Initialisation d'un objet Recommandation
        $recommandation = new Recommandation($userConnected);
         //Calcul de la suggestion entre tous les événements et l'utilisateur
        $recommandation->calculerSuggestion($eventsAndUserPreferences, $objetEvenement);

        //Création de la liste des événements à suggérer en fonction de l'utilisateur
        $listEventaRecommander = $userConnected->creerListeSuggest();

    } elseif (isset($_POST["action"])) {
        $action = $_POST['action'];
        // Inclure la logique pour chaque action
        switch ($action) {
            case 'ajouterDescriptionUtilisateur':
                // Logique pour créer un utilisateur
                $user_courrant = new Utilisateur($_SESSION["user_id"],[]);
                
                // Récupère la liste de mots envoyée par le formulaire
                $motsListe = isset($_POST['motsListe']) ? json_decode($_POST['motsListe']) : [];

                //on crée ajout l'utilisateur dans la BD
                /* $user_courrant->setId($id_user);
                $user_courrant->setNom($nom); */
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

                require_once "../../gestionBD/database.php";
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
                $sql_insert = "INSERT INTO Associer (idUtilisateur, idTag) VALUES (?, ?)";
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


                header("Location: ../pageSuggestion.php");
                break;



            case 'creerEvenement':
                // Logique pour créer un événement
                $titre = $_POST['titre'];
                $date = $_POST['date'];
                $heure = $_POST['heure'];
                $lieu = $_POST['lieu'];
                $id_event = $donnees['evenements'][count($donnees['evenements']) - 1]['id'] + 1;
                $event_courrant = new Evenement($id_event,[]);

                // Récupère la liste de mots envoyée par le formulaire
                $motsListe = isset($_POST['motsListeEvenement']) ? json_decode($_POST['motsListeEvenement']) : [];

                //on crée ajout l'utilisateur dans la BD
                $event_courrant->setId($id_event);
                $event_courrant->setTitre($titre);
                $event_courrant->setDate($date);
                $event_courrant->setHeure($heure);
                $event_courrant->setLieu($lieu);
                //Création liste de mot objet
                $listeMot_objet = array();
                foreach($motsListe as $motX){
                    $listeMot_objet[]= new Mot($motX);
                }
                $event_courrant->setMots($listeMot_objet);
                $event_courrant->definirDescription($dicoSynTag);
                break;
            default:
                // Action non reconnue
                break;
        }
    } else {
        echo "</br>ID de l'utilisateur inconnu.";
    }
}