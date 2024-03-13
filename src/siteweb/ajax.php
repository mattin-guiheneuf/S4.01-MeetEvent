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

$userConnected = $_SESSION['user_id'];
/* ------------------------------------------- */
/* Si la requete AJAX provient de la recherche */
/* ------------------------------------------- */

if(isset($_POST['eventName']) or isset($_POST['eventDate']) or isset($_POST['eventCity'])){

    // Récupérer les données envoyées par AJAX et les valider
    $eventName = isset($_POST['eventName']) ? $_POST['eventName'] : '';
    $eventDate = isset($_POST['eventDate']) ? $_POST['eventDate'] : '';
    $eventCity = isset($_POST['eventCity']) ? $_POST['eventCity'] : '';

    // Construire la requête SQL paramétrée en fonction des critères de recherche fournis
    $sql = "SELECT e.*,u.chemImage as chemImage, u.nom as nom_organisateur, u.prenom as prenom_organisateur, c.libelle as libCat, e.effMax-COUNT(p.idUtilisateur) as nbPlaces, (SELECT CASE WHEN COUNT(*)>0 THEN 1 ELSE 0 END from participer p where e.idEvenement=p.idEvenement and p.idUtilisateur=$userConnected AND p.participationAnnulee = 0) as est_deja_admis
            FROM Evenement e 
            JOIN Categorie c ON e.idCategorie = c.idCategorie 
            JOIN Utilisateur u ON e.idOrganisateur = u.idUtilisateur 
            LEFT JOIN Participer p ON e.idEvenement = p.idEvenement
            WHERE e.idOrganisateur != $userConnected AND e.statut = 1";

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
    $sql .= " GROUP BY e.idEvenement";

    // Exécuter la requête SQL
    $result = $connexion->query($sql);

    // Vérifier si la requête a réussi
    if ($result) {
        // Récupérer les résultats de la requête
        $results = $result->fetch_all(MYSQLI_ASSOC);
        /* echo $results; */

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
    $listeEvent = isset($_POST['listeEvent']) ? $_POST['listeEvent'] : [1,2,3,4,5];
    //$userConnected = 14;
    // Initialiser un tableau pour stocker les résultats
    $results = [];

    // Pour chaque événement dans la liste
    foreach ($listeEvent as $eventId) {
        // Construire la requête SQL paramétrée en fonction des critères de recherche fournis
        $sql = "SELECT e.*, u.nom as nom_organisateur, u.prenom as prenom_organisateur, c.libelle as libCat, e.effMax-COUNT(p.idUtilisateur) as nbPlaces, (SELECT CASE WHEN COUNT(*)>0 THEN 1 ELSE 0 END from participer where idUtilisateur=$userConnected AND idEvenement = $eventId AND participationAnnulee = 0) as est_deja_admis
                FROM Evenement e 
                JOIN Categorie c ON e.idCategorie = c.idCategorie 
                JOIN Utilisateur u ON e.idOrganisateur = u.idUtilisateur 
                LEFT JOIN Participer p ON e.idEvenement = p.idEvenement 
                WHERE e.idEvenement = $eventId AND e.idOrganisateur != $userConnected
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

/* ----------------------------------------------------------- */
/* Si la requete AJAX provient de la l'adhésion à un événement */
/* ----------------------------------------------------------- */

if(isset($_POST['eventSelected'])){
    if($_POST['type']=="rejoindre"){
        // Récupérer les données envoyées par AJAX et les valider
        $eventSelected = isset($_POST['eventSelected']) ? $_POST['eventSelected'] : '';
        $idUtilisateur = $_SESSION['user_id'];

        // Vérifier si le couple (idUtilisateur, idEvenement) existe déjà
        $sql_check = "SELECT * FROM Participer WHERE idUtilisateur=$idUtilisateur AND idEvenement=$eventSelected";
        $result_check = $connexion->query($sql_check);

        if ($result_check->num_rows > 0) {
            // Si le couple existe déjà, effectuer une mise à jour
            $sql_update = "UPDATE Participer SET participationAnnulee=0 WHERE idUtilisateur=$idUtilisateur AND idEvenement=$eventSelected";
            $result_update = $connexion->query($sql_update);
            
            echo "Evenement rejoint avec succès";
        } else {
            // Si le couple n'existe pas, effectuer une insertion
            $sql_insert = "INSERT INTO Participer VALUES ($idUtilisateur, $eventSelected, 'lienQRCode', 0)";
            $result_insert = $connexion->query($sql_insert);
            
            echo "Evenement rejoint avec succès";
        }
        // Fermer la connexion à la base de données
        $connexion->close();
        
        echo "Evenement rejoins avec succès";
    }elseif($_POST['type']=="quitter"){
        // Récupérer les données envoyées par AJAX et les valider
        $eventSelected = isset($_POST['eventSelected']) ? $_POST['eventSelected'] : '';
        $idUtilisateur = $_SESSION['user_id'];

        //Faire la requète d'insertion
        $sql = "UPDATE Participer SET participationAnnulee=1 WHERE idUtilisateur=$idUtilisateur AND idEvenement=$eventSelected";
        // Exécuter la requête SQL
        $result = $connexion->query($sql);
        // Fermer la connexion à la base de données
        $connexion->close();
        
        echo "Evenement quitter avec succès";
    }else{
        // Récupérer les données envoyées par AJAX et les valider
        $eventSelected = isset($_POST['eventSelected']) ? $_POST['eventSelected'] : '';

        $sql_evenement = "DELETE FROM evenement WHERE idEvenement = $eventSelected";

        if ($connexion->query($sql_evenement) === TRUE) {
            echo "Événement supprimé avec succès";
        } else {
            echo "Erreur lors de la suppression de l'événement : " . $connexion->error;
        }

        // Suppression des relations dans la table participer
        $sql_participer = "DELETE FROM participer WHERE idEvenement = $eventSelected";

        if ($connexion->query($sql_participer) === TRUE) {
            echo "Relations supprimées avec succès";
        } else {
            echo "Erreur lors de la suppression des relations : " . $connexion->error;
        }

        // Fermeture de la connexion
        $connexion->close();

        echo "Evenement et ses relations supprimés avec succès";
    }
    
}