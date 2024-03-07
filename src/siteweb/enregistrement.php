<?php
session_start();

/* // Définit les paramètres de cookie de session
$cookieParams = session_get_cookie_params();
session_set_cookie_params(
    $cookieParams["lifetime"] = 3600, 
    $cookieParams["path"] = "/", 
    $cookieParams["secure"] = true, // Secure : true pour envoyer uniquement sur HTTPS
    $cookieParams["httponly"] = true  // HttpOnly : true pour empêcher l'accès via JavaScript
); */

// Vérifier si le jeton CSRF est présent et valide
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    // Jeton CSRF invalide, traitement de l'erreur
    die('Erreur CSRF : jeton CSRF invalide');
}

if (isset($_POST["email"]) || isset($_POST["pseudo"]) || isset($_POST["dateNaiss"]) || isset($_POST["ville"]) || isset($_POST["cp"]) || isset($_POST["mdp"])) {

    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = htmlentities($data);
        return $data;
    }

    $email = validate($_POST["email"]);
    $pseudo = validate($_POST["pseudo"]);
    $dateNaiss = validate($_POST["dateNaiss"]);
    $ville = validate($_POST["ville"]);
    $cp = validate($_POST["cp"]);
    $mdp = validate($_POST["mdp"]);

    // Expression régulière pour valider une date au format DD/MM/YYYY
    $dateRegex = '/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/';
    /**
     * (0[1-9]|[12][0-9]|3[01]) pour le jour de 01 à 31
     * (0[1-9]|1[0-2])          pour le mois de 01 à 12
     * d{4}                     pour les 4 chiffres de l'année
     */


    //Vérification de l'adresse mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: connexion.php?sign-up_error&error=Une adresse mail valide est requise");
        exit();
    }
    //Vérification du pseudo
    else if (empty($pseudo)) {
        header("Location: connexion.php?sign-up_error&error=Votre pseudo est requis");
        exit();
    }
    //Vérification de la date de naissance
    else if (empty($dateNaiss) || !filter_var($dateNaiss, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>$dateRegex)))) {
        header("Location: connexion.php?sign-up_error&error=Votre date de Naissance est requise");
        exit();
    }
    //Vérification de la ville
    else if (empty($ville)) {
        header("Location: connexion.php?sign-up_error&error=Votre ville est requise");
        exit();
    }
    //Vérification du code postal
    else if (empty($cp) || !filter_var($cp, FILTER_VALIDATE_INT)) {
        header("Location: connexion.php?sign-up_error&error=Votre code postal est requis");
        exit();
    }
    //Vérification du mot de passe
    else if (strlen($mdp) < 8) {
        header("Location: connexion.php?sign-up_error&error=Votre mot de passe doit contenir au moins 8 caractères");
        exit();
    } else if (!preg_match("/[a-z]/i", $mdp)) {
        header("Location: connexion.php?sign-up_error&error=Votre mot de passe doit contenir au moins 1 lettre");
        exit();
    } else if (!preg_match("/[0-9]/i", $mdp)) {
        header("Location: connexion.php?sign-up_error&error=Votre mot de passe doit contenir au moins 1 chiffre");
        exit();
    }
    else {
        /* $password_hash = password_hash($_POST["mdp"], PASSWORD_DEFAULT); */
        // Clé de chiffrement (la clé doit être sécurisée et confidentielle)
        $crypt_key = "MaCleSecrete123";
        
        $mysqli = require "../gestionBD/database.php";
        //Insérer dans la base de donnée
        $sql = "INSERT INTO Utilisateur(nom,prenom, adrMail, trancheAge, description, situation, MotDePasse, token) VALUES (?,'Dupont', ?, '> 45', 'NULL', 'NULL',?,?)";

        $stmt = $mysqli->stmt_init();

        if (!$stmt->prepare($sql)) {
            die("SQL error: " . $mysqli->error);
        }

        $passwd = openssl_encrypt($_POST["mdp"], "AES-256-CBC", $crypt_key, 0, "1234567890123456");
        $token = password_hash(uniqid(rand(), true), PASSWORD_DEFAULT);


        $stmt->bind_param("ssss", $_POST["pseudo"], $_POST["email"], $passwd, $token);
        if ($stmt->execute()) {
            /* session_start(); */
            session_regenerate_id();
            $sql = sprintf("SELECT idUtilisateur FROM Utilisateur WHERE adrMail = '%s'", $mysqli->real_escape_string($_POST["email"]));

            $result = $mysqli->query($sql);

            $user = $result->fetch_assoc();
            $_SESSION["user_id"] = $user["idUtilisateur"];
                    
            //header("Location: ./algorithme/index.php");
            echo '<script>window.location = "algorithme/index.php";</script>';
            exit;
        } else {
            if ($mysqli->errno === 80) {
                die("Pseudo déjà utilisé");
            } else {
                die($mysqli->error . " " . $mysqli->errno);
            }
        }
    }
}