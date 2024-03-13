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
require_once "../gestionBD/database.php";

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
    $list_tag_corpus[] = new Tag($tag);
}
//Création du corpus de Tag
$corpusTag = new Corpus(1, $list_tag_corpus);

//Liste utilisé pour l'ACM
$eventsAndUserPreferences = [];

//-------------------------------------------------------------//
//                      Partie Evenement                       //
//-------------------------------------------------------------//

//Recuperer tous les evenement des données et les affecter dans objetEvenement
// Requête SQL pour récupérer tous les événements et leurs tags associés
$sql = "SELECT idEvenement, t.libelle AS tag FROM Qualifier q join Tag t on q.idTag=t.idTag";
$result = $connexion->query($sql);

// Liste pour stocker les objets Evenement
$objetEvenement = [];

// Vérification si des résultats sont retournés
if ($result->num_rows > 0) {
    // Parcourir les résultats de la requête
    while($row = $result->fetch_assoc()) {
        $id_evenement = $row["idEvenement"];
        $tag = $row["tag"];

        // Rechercher si l'événement existe déjà dans la liste d'objets
        $existingEvent = false;
        foreach ($objetEvenement as $evenement) {
            if ($evenement->getId() == $id_evenement) {
                $evenement->addTag(new Tag($tag));
                $existingEvent = true;
                break;
            }
        }
        
        // Si l'événement n'existe pas dans la liste, créer un nouvel objet Evenement
        if (!$existingEvent) {
            $newEvenement = new Evenement($id_evenement, [new Tag($tag)]);
            $objetEvenement[] = $newEvenement;
        }
        
    }
} else {
    echo "Aucun résultat trouvé.";
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


$idUserConnected = $_SESSION["user_id"];

//Dico avec tous les utilisateurs et leurs tags associés
$listeTag_user = [];



// Requête SQL pour récupérer tous les tags associés à l'utilisateur donné
$sql = "SELECT t.libelle as tag_lib FROM Associer a join Tag t on a.idTag=t.idTag WHERE idUtilisateur = ?";
$stmt = $connexion->prepare($sql);
$stmt->bind_param("i", $idUserConnected);
$stmt->execute();
$result = $stmt->get_result();

// Vérification si des résultats sont retournés
if ($result->num_rows > 0) {
    // Parcourir les résultats de la requête
    while($row = $result->fetch_assoc()) {
        // Ajouter le tag à l'utilisateur correspondant dans le dictionnaire
        $listeTag_user[] = new Tag($row["tag_lib"]);
    }
} else {
    echo "Aucun résultat trouvé.";
}



//créer l'utilisateur connecté
$userConnected = new Utilisateur($idUserConnected, $listeTag_user);
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
foreach ($listEventaRecommander as $value) {
    echo $value . "<br>";
}