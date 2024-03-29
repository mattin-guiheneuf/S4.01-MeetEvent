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

// Construire la requête SQL paramétrée en fonction des critères de recherche fournis
$sql = "SELECT DISTINCT(p.idEvenement), e.*, x.chemImage as chemImage,x.nom as nom_organisateur, x.prenom as prenom_organisateur,c.libelle as libCat, e.effMax-COUNT(p.idUtilisateur) as nbPlaces, p.idUtilisateur as user_id, p.idEvenement as event_id, u.token as token_user, e.token as token_event
        FROM Participer p 
        JOIN Evenement e ON p.idEvenement=e.idEvenement
        JOIN Utilisateur u ON p.idUtilisateur=u.idUtilisateur
        JOIN Utilisateur x ON e.idOrganisateur = x.idUtilisateur
        JOIN Categorie c ON  e.idCategorie=c.idCategorie
        WHERE p.idUtilisateur=".$_SESSION['user_id']."
        AND p.participationAnnulee=0
        GROUP BY p.idEvenement";
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