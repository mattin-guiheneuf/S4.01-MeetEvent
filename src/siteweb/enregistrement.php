<?php

if (isset($_POST["email"]) || isset($_POST["pseudo"]) || isset($_POST["dateNaiss"]) || isset($_POST["ville"]) || isset($_POST["cp"]) || isset($_POST["mdp"])) {

    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $email = validate($_POST["email"]);
    $pseudo = validate($_POST["pseudo"]);
    $dateNaiss = validate($_POST["dateNaiss"]);
    $ville = validate($_POST["ville"]);
    $cp = validate($_POST["cp"]);
    $mdp = validate($_POST["mdp"]);


    //Vérification de l'adresse mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: connexion.php?error=valid email is required");
        exit();
    }
    //Vérification du pseudo
    else if (empty($pseudo)) {
        header("Location: connexion.php?error=Pseudo is required");
        exit();
    }
    //Vérification de la date de naissance
    else if (empty($dateNaiss)) {
        header("Location: connexion.php?error=Date de Naissance is required");
        exit();
    }
    //Vérification de la ville
    else if (empty($ville)) {
        header("Location: connexion.php?error=Ville is required");
        exit();
    }
    //Vérification du code postal
    else if (empty($cp)) {
        header("Location: connexion.php?error=Code postal is required");
        exit();
    }
    //Vérification du mot de passe
    else if (strlen($mdp) < 8) {
        header("Location: connexion.php?error=Password must be at least 8 characters");
        exit();
    } else if (!preg_match("/[a-z]/i", $mdp)) {
        header("Location: connexion.php?error=Password must contain at least one letter");
        exit();
    } else if (!preg_match("/[0-9]/i", $mdp)) {
        header("Location: connexion.php?error=Password must contain at least one number");
        exit();
    }
    else {
        /* $password_hash = password_hash($_POST["mdp"], PASSWORD_DEFAULT); */

        $mysqli = require "../gestionBD/database.php";
        //Insérer dans la base de donnée
        $sql = "INSERT INTO Utilisateur(nom,prenom, adrMail, trancheAge, description, situation, MotDePasse) VALUES (?,'Dupont', ?, '> 45', 'NULL', 'NULL',?)";

        $stmt = $mysqli->stmt_init();

        if (!$stmt->prepare($sql)) {
            die("SQL error: " . $mysqli->error);
        }


        $stmt->bind_param("sss", $_POST["pseudo"], $_POST["email"], $_POST["mdp"]);
        if ($stmt->execute()) {
            header("Location: connexion.php");
            exit;
        } else {
            if ($mysqli->errno === 80) {
                die("email already taken");
            } else {
                die($mysqli->error . " " . $mysqli->errno);
            }
        }
    }
}