<?php

$conn = require "../gestionBD/database.php";

// Récupérer les paramètres de l'URL
$eventId = $_GET['eventId'] ?? '';
$userId = $_GET['userId'] ?? '';
$tokenUser = $_GET['tokenUser'] ?? '';
$tokenEvent = $_GET['tokenEvent'] ?? '';

// Vérification des paramètres dans la base de données
$query = "SELECT * 
          FROM Utilisateur u
          JOIN Participer p on u.idUtilisateur = p.idUtilisateur
          JOIN Evenement e on e.idEvenement = p.idEvenement
          WHERE p.idEvenement = '$eventId' 
          AND p.idUtilisateur = '$userId' AND p.participationAnnulee = 0 AND u.token = '$tokenUser' AND e.token = '$tokenEvent'";

$result = $conn->query($query);

if (mysqli_num_rows($result) > 0) {
    // Si la requête renvoie des résultats, les paramètres sont valides
    $affichage = "reussi";
} else {
    // Si la requête ne renvoie pas de résultats, les paramètres ne sont pas valides
    $affichage = "erreur";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affichage PHP avec des icônes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 3em;
            font-family: "Poppins-Regular", Helvetica;
            font-weight: bold;
        }
        .icon-container {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 20px;
        }
    </style>
</head>
<body>

<?php if ($affichage === "reussi"): ?>
    <div class="icon-container">
        <div >
            <i class="fas fa-check-circle" style="color: green;"></i>
        </div>
        <p>Vérification réussie</p>
    </div>
<?php elseif ($affichage === "erreur"): ?>
    <div class="icon-container">
        <div >
            <i class="fas fa-times-circle" style="color: red;"></i>
        </div>
        <p>Vérification erreur <!-- : paramètres invalides --></p>
    </div>
<?php endif; ?>

</body>
</html>
