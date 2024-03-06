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
$sql = "SELECT DISTINCT(p.idEvenement), e.*, c.libelle as libCat, e.effMax-COUNT(p.idUtilisateur) as nbPlaces
        FROM participer p 
        JOIN evenement e ON p.idEvenement=e.idEvenement
        JOIN utilisateur u ON p.idUtilisateur=u.idUtilisateur
        JOIN categorie c ON  e.idCategorie=c.idCategorie
        WHERE e.idOrganisateur=".$_SESSION['user_id']."
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