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

/*////////////////////////////////////////////
    Génération code de vérification email
/////////////////////////////////////////// */

function generate_activation_token() {
    // Génération d'un token aléatoire sécurisé
    return bin2hex(random_bytes(32)); // Utilisez une longueur de token adaptée à vos besoins
}

// Générer un token d'activation lors de l'inscription
$activation_token = generate_activation_token();



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
        $sql = "INSERT INTO Utilisateur(nom, prenom, adrMail, trancheAge, description, situation, MotDePasse, token) VALUES (?,'Dupont', ?, '> 45', 'NULL', 'NULL',?,?)";

        $stmt = $mysqli->stmt_init();

        if (!$stmt->prepare($sql)) {
            die("SQL error: " . $mysqli->error);
        }

        $passwd = openssl_encrypt($_POST["mdp"], "AES-256-CBC", $crypt_key, 0, "1234567890123456");
        $token = password_hash(uniqid(rand(), true), PASSWORD_DEFAULT);


        $stmt->bind_param("ssss", $_POST["pseudo"], $_POST["email"], $passwd, $token);
        if ($stmt->execute()) {
                                
            //header("Location: ./algorithme/index.php");
            /* echo '<script>window.location = "algorithme/index.php";</script>';
            exit; */
            $to = $_POST['email'];
            $subject = 'Activation de votre compte';
            $message = '
            <html>
            <head>
            <title>Activation de votre compte</title>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
            <style>
            *{
                font-family: \'Poppins\';
                text-align: center;
            }
            button{
                padding: 10px 20px;
                border-radius: 20px;
                font-weight: bold;
                border: 1px solid #6040fe;
                background-color: #6040fe;
                color: #fff;
            }
            p{
                text-align: left;
            }
            </style>
            </head>
            <body>
            <img src="" alt="">
            <h1 style="color:#6040fe; ">Votre inscription est sur le point de se finaliser !</h1>
            <p>Bienvenue <span style="color:#6040fe;">'. $_POST['pseudo'] .'</span>,</p>
            <p>Nous sommes heureux que vous puissiez participer à l\'aventure MeetEvent.</p>
            <p>Pour explorer MeetEvent App, cliquez sur le bouton ci-dessous pour activer votre compte :</p>
            <a href="http://localhost/testSitME/S4.01-MeetEvent/src/siteweb/activation.php?token=' . $token . '"><button>Activer mon compte</button></a>
            </body>
            </html>
            ';

            $headers =  "MIME-Version:1.0"."\r\n" .
                        "From: contact.meetevent@gmail.com" . "\r\n" .
                        "Content-type: text/html; charset=ISO-8859-1" . "\r\n" .
                        "Reply-To: contact.meetevent@gmail.com" . "\r\n" .
                        "X-Mailer: PHP/" . phpversion();
            $mail_sent = mail($to, $subject, $message, $headers);

            if (!$mail_sent) {
                echo "Erreur lors de l'envoi de l'email d'activation. Veuillez réessayer.";
                exit();
            } else{
                echo "Veuillez vérifier votre adresse mail sur ". $_POST['email'];
            }


        } else {
            if ($mysqli->errno === 80) {
                die("Pseudo déjà utilisé");
            } else {
                die($mysqli->error . " " . $mysqli->errno);
            }
        }
    }
}