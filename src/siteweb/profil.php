<?php
session_start();
// Vérifiez si l'utilisateur est connecté en vérifiant la présence de ses informations d'identification dans la session
if (!isset($_SESSION['user_id'])) {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: connexion.php");
    exit; // Assurez-vous de terminer le script après la redirection
} else {
    $conn = require "../gestionBD/database.php";
    // L'utilisateur est connecté
    $user_id = $_SESSION['user_id'];
    $query = "SELECT t.libelle, t.idTag
              FROM Tag t 
              JOIN Associer a on t.idTag = a.idTag
              WHERE a.idUtilisateur = '$user_id'";

    $result = $conn->query($query);
    $tags = $result->fetch_all(MYSQLI_ASSOC);

}

// Vérifier si des tags ont été sélectionnés pour suppression
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tags'])) {
    $selectedTags = $_POST['tags'];
    foreach ($selectedTags as $tag_id) {
        // Supprimer la relation entre l'utilisateur et le tag de la table Associer
        $deleteQuery = "DELETE FROM Associer WHERE idUtilisateur = '$user_id' AND idTag = '$tag_id'";
        $conn->query($deleteQuery);
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .tag-container {
            display: flex;
            flex-wrap: wrap;
        }
        .tag {
            background-color: #f1f1f1;
            color: #333;
            border-radius: 4px;
            padding: 5px 10px;
            margin-right: 10px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        .tag .tag-name {
            margin-right: 5px;
        }
        .tag .remove-button {
            cursor: pointer;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Profil Utilisateur</h1>
    
    <h2>Tags Utilisateur</h2>
    <?php if (empty($tags)): ?>
        <p>Aucun tag disponible pour l'utilisateur.</p>
    <?php else: ?>
        <form method="post">
            <div class="tag-container">
                <?php foreach ($tags as $tag): ?>
                    <div class="tag">
                        <span class="tag-name"><?php echo $tag['libelle']; ?></span>
                        <input type="checkbox" name="tags[]" value="<?php echo $tag['idTag']; ?>">
                    </div>
                <?php endforeach; ?>
            </div>
            <input type="submit" value="Supprimer sélection">
        </form>
    <?php endif; ?>

</body>
</html>
