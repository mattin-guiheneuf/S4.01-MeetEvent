<?php
session_start();

// Vérifiez si l'utilisateur est connecté en vérifiant la présence de ses informations d'identification dans la session
if (!isset($_SESSION['user_id'])) {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: connexion.php");
    exit; // Assurez-vous de terminer le script après la redirection
}

// Faire la connexion avec la base de données
include_once "../gestionBD/database.php";

// Vérifier la connexion
if ($connexion->connect_error) {
    die("La connexion a échoué : " . $connexion->connect_error);
}

/* ------------------------------------------- */
/* Si la requete AJAX provient de la recherche */
/* ------------------------------------------- */

if(isset($_POST['eventName']) or isset($_POST['eventDate']) or isset($_POST['eventCity'])){

    // Récupérer les données envoyées par AJAX et les valider
    $eventName = isset($_POST['eventName']) ? $_POST['eventName'] : '';
    $eventDate = isset($_POST['eventDate']) ? $_POST['eventDate'] : '';
    $eventCity = isset($_POST['eventCity']) ? $_POST['eventCity'] : '';

    // Construire la requête SQL paramétrée en fonction des critères de recherche fournis
    $sql = "SELECT e.*,u.chemImage as chemImage, u.nom as nom_organisateur, u.prenom as prenom_organisateur, c.libelle as libCat, e.effMax-COUNT(p.idUtilisateur) as nbPlaces 
            FROM evenement e 
            JOIN categorie c ON e.idCategorie = c.idCategorie 
            JOIN utilisateur u ON e.idOrganisateur = u.idUtilisateur 
            JOIN participer p ON e.idEvenement = p.idEvenement 
            WHERE 1=1";

    // Ajouter les conditions à la requête en fonction des paramètres fournis
    if ($eventName != '') {
        $eventName = '%' . $eventName . '%';
        $sql .= " AND e.nom LIKE '$eventName'";
    }
    if ($eventDate != '') {
        $eventDate = '%' . $eventDate . '%';
        $sql .= " AND e.dateEvent LIKE '$eventDate'";
    }
    if ($eventCity != '') {
        $eventCity = '%' . $eventCity . '%';
        $sql .= " AND e.statut LIKE '$eventCity'";
    }
    //Ajouter le group by pour récupérer le nb de participant
    $sql .= " GROUP BY p.idEvenement";

    // Exécuter la requête SQL
    $result = $connexion->query($sql);

    // Vérifier si la requête a réussi
    if ($result) {
        // Récupérer les résultats de la requête
        $results = $result->fetch_all(MYSQLI_ASSOC);

    } else {
        // En cas d'erreur, renvoyer une erreur au format JSON
        echo json_encode(array('error' => 'Erreur lors de l\'exécution de la requête SQL.'));
        exit;
    }

    // Fermer la connexion à la base de données
    $connexion->close();

    // Convertir les résultats en format JSON
    $json_results = json_encode($results);

    // Renvoyer le JSON en tant que réponse à la requête AJAX
    echo $json_results;
}

/* -------------------------------------------- */
/* Si la requete AJAX provient de la suggestion */
/* -------------------------------------------- */

if(isset($_POST['listeEvent'])){
    // Récupérer les données envoyées par AJAX et les valider
    $listeEvent = isset($_POST['listeEvent']) ? $_POST['listeEvent'] : '';

    // Initialiser un tableau pour stocker les résultats
    $results = [];

    // Pour chaque événement dans la liste
    foreach ($listeEvent as $eventId) {
        // Construire la requête SQL paramétrée en fonction des critères de recherche fournis
        $sql = "SELECT e.*,u.chemImage as chemImage, u.nom as nom_organisateur, u.prenom as prenom_organisateur, c.libelle as libCat, e.effMax-COUNT(p.idUtilisateur) as nbPlaces 
                FROM evenement e 
                JOIN categorie c ON e.idCategorie = c.idCategorie 
                JOIN utilisateur u ON e.idOrganisateur = u.idUtilisateur 
                JOIN participer p ON e.idEvenement = p.idEvenement 
                WHERE e.idEvenement = $eventId
                GROUP BY p.idEvenement";
        // Exécuter la requête SQL
        $result = $connexion->query($sql);

        // Vérifier si la requête a réussi
        if ($result) {
            // Récupérer les résultats de la requête
            $results[] = $result->fetch_assoc();
        } else {
            // En cas d'erreur, renvoyer une erreur au format JSON
            echo json_encode(array('error' => 'Erreur lors de l\'exécution de la requête SQL.'));
            exit;
        }
    }

    // Fermer la connexion à la base de données
    $connexion->close();

    // Convertir les résultats en format JSON
    $json_results = json_encode($results);

    // Renvoyer le JSON en tant que réponse à la requête AJAX
    echo $json_results;
}

