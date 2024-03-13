<?php
    header('Content-Type: text/html; charset=utf-8');
    include_once "../gestionBD/database.php";

    if(isset($_POST['lettre']))
    {
        $connexion->set_charset("utf8");

        // Récupération de la lettre saisie par l'utilisateur
        $lettre = $_POST['lettre'];

        // Requête SQL pour récupérer les mots commençant par la lettre saisie
        $req_Tags = "SELECT libelle FROM Tag WHERE libelle LIKE '$lettre%'";
        $resultat = $connexion->query($req_Tags);

        // Construction de la réponse JSON
        if($resultat) {
            $mots = $resultat->fetch_all(MYSQLI_ASSOC);
        }
        else {
            // En cas d'erreur, renvoyer une erreur au format JSON
            echo json_encode(array('error' => 'Erreur lors de l\'exécution de la requête SQL.'));
            exit;
        }
    }

    // Fermeture de la connexion à la base de données
    $connexion->close();
    // Convertir les résultats en format JSON
    $json_results = json_encode($mots);

    // Renvoyer le JSON en tant que réponse à la requête AJAX
    echo $json_results;
?>