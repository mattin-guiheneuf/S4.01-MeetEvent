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

/*////////////////////////////////////////////
    Mis en place de protection force brute
/////////////////////////////////////////// */

$maxAttempts = 15; // Nombre maximum de tentatives de connexion autorisées
$lockoutDuration = 300; // Durée de verrouillage en secondes (15 minutes)
if (isset($_SESSION['login_attempts'])) {
    // Vérifie si l'utilisateur a dépassé le nombre maximal de tentatives
    if ($_SESSION['login_attempts'] >= $maxAttempts) {
        // Vérifie si le verrouillage a expiré
        if (time() - $_SESSION['last_login_attempt'] < $lockoutDuration) {
            // Compte verrouillé, rediriger vers une page d'attente ou afficher un message d'erreur
            header("Location: account_locked.php");
            exit();
        } else {
            // Réinitialiser le compteur de tentatives après l'expiration du verrouillage
            $_SESSION['login_attempts'] = 0;
        }
    }
}

/*////////////////////////////////////////////
         Génération de jeton CSRF
/////////////////////////////////////////// */

// Génération du jeton CSRF
function generate_csrf_token() {
    return bin2hex(random_bytes(32));
}

// Stockage du jeton CSRF dans une session

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = generate_csrf_token();
}

/*////////////////////////////////////////////
      Code de vérification de la connexion
/////////////////////////////////////////// */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if(isset($_POST["email"]) && isset($_POST["mdp"])){

        function validate($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            $data = htmlentities($data);
            return $data;
        }

        $email = validate($_POST["email"]);
        $mdp = validate($_POST["mdp"]);

        if(empty($email)){
            //header("Location: connexion.php?error=L'addresse mail est requise");
            echo '<script>window.location = "connexion.php?error=L\'addresse mail est requise";</script>';
            exit();
        }else if (empty($mdp)){
            //header("Location: connexion.php?error=Le mot de passe est requis");
            echo '<script>window.location = "connexion.php?error=Le mot de passe est requis";</script>';
            exit();
        }else{

            // Clé de chiffrement (la clé doit être sécurisée et confidentielle)
            $crypt_key = "MaCleSecrete123";

            $mysqli = require "../gestionBD/database.php";

            $sql = sprintf("SELECT * FROM Utilisateur WHERE adrMail = '%s'", $mysqli->real_escape_string($_POST["email"]));

            $result = $mysqli->query($sql);

            $user = $result->fetch_assoc();

            if ($user) {
                if($_POST["mdp"]===openssl_decrypt($user["MotDePasse"], "AES-256-CBC", $crypt_key, 0, "1234567890123456")){/* if(password_verify($_POST["mdp"], $user["MotDePasse"])){ *//* if($_POST["mdp"]===$user["MotDePasse"]){ */

                    // Connexion réussie, réinitialiser le compteur de tentatives
                    $_SESSION['login_attempts'] = 0;

                    //session_start();
                    session_regenerate_id();
                    $_SESSION["user_id"] = $user["idUtilisateur"];

                    /*  */
                    
                    


                    /*  */
                    
                    
                    //header("Location: pageSuggestion.php");
                    echo '<script>window.location = "pageSuggestion.php";</script>';
                    exit();
                }
                else{
                    // Identifiants invalides, incrémenter le compteur de tentatives
                    $_SESSION['login_attempts']++;

                    // Enregistrer l'heure de la dernière tentative de connexion
                    $_SESSION['last_login_attempt'] = time();

                    //header("Location: connexion.php?error=Email ou Mot de passe Incorrect");
                    echo '<script>window.location = "connexion.php?error=Email ou Mot de passe Incorrect";</script>';
                    exit();
                }
            }else{
                // Identifiants invalides, incrémenter le compteur de tentatives
                $_SESSION['login_attempts']++;

                // Enregistrer l'heure de la dernière tentative de connexion
                $_SESSION['last_login_attempt'] = time();

                //header("Location: connexion.php?error=Email ou Mot de passe Incorrect");
                echo '<script>window.location = "connexion.php?error=Email ou Mot de passe Incorrect";</script>';
                exit();
            }

        }
    }
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/styles2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Modern login page</title>
</head>
<body>
    <!-- Si erreur -->
    <?php if (isset($_GET['error'])) { ?>
        <p class="error"><?php echo $_GET['error']; ?></p>
    <?php } ?>

    <div class="container <?php if(isset($_GET["sign-up_error"])) { echo "active"; } ?>" id="container">
        <div class="form-container sign-up">
            <form action="enregistrement.php" method="post" novalidate>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <h1>Rejoignez nous</h1>
                <input type="email" placeholder="Email" name="email" value="<?= htmlspecialchars( $_POST["email"] ?? "") ?>">
                <input type="text" placeholder="Pseudo" name="pseudo" value="<?= htmlspecialchars( $_POST["pseudo"] ?? "") ?>">
                <input type="datetime" placeholder="Date de Naissance (dd/mm/aaaa)" name="dateNaiss" value="<?= htmlspecialchars( $_POST["dateNaiss"] ?? "") ?>">
                <div style="display: flex;gap:10px;">
                    <span><input type="text" placeholder="Ville" name="ville" value="<?= htmlspecialchars( $_POST["ville"] ?? "") ?>"></span>
                    <span><input type="text" placeholder="Code Postal" name="cp" value="<?= htmlspecialchars( $_POST["cp"] ?? "") ?>"></span>
                </div>
                <input type="password" placeholder="Mot de passe" name="mdp" id="passwordInput" value="<?= htmlspecialchars( $_POST["mdp"] ?? "") ?>">
                <!-- Etat du mdp -->
                <div id="passwordStrength" style="font-size: 16px; display: flex; align-items: center; gap: 10px;">
                    <div id="lengthIndicator">
                        <span><i id="lengthIcon" class="fas fa-minus" style="color: gray;"></i></span>
                        <span>8 caractères</span>
                    </div>
                    <div id="numberIndicator">
                        <span><i id="numberIcon" class="fas fa-minus" style="color: gray;"></i></span>
                        <span>1 chiffre</span>
                    </div>
                </div>

                <button type="submit">Je m'inscris</button>
            </form>
        </div>

        <div class="form-container sign-in">
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?> ">            
                <h1>Connexion</h1>
                <input type="email" placeholder="Email" name="email" value="<?= htmlspecialchars( $_POST["email"] ?? "") ?>">
                <input type="password" placeholder="Mot de passe" name="mdp">
                <a href="#">Mot de passe oublié ?</a>
                <button type="submit">Je me connecte</button>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Bon retour !</h1>
                    <p>Connectez-vous pour utiliser toutes les fonctionnalités de MeetEvent</p>
                    <button class="hidden" id="login">Connectez-vous</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Bonjour !</h1>
                    <p>Inscrivez-vous pour utiliser toutes les fonctionnalités de MeetEvent</p>
                    <button class="hidden" id="register">Créez votre compte</button>
                </div>
            </div>
        </div>
    </div>




    <script>

        const container = document.getElementById('container');
        const registerBtn = document.getElementById('register');
        const loginBtn = document.getElementById('login');

        registerBtn.addEventListener('click', () => {
            container.classList.add('active');
        });

        loginBtn.addEventListener('click', () => {
            container.classList.remove('active');
        });

    </script>

<script>
    const passwordInput = document.getElementById('passwordInput');
    const lengthIndicator = document.getElementById('lengthIndicator');
    const numberIndicator = document.getElementById('numberIndicator');
    const lengthIcon = document.getElementById('lengthIcon');
    const numberIcon = document.getElementById('numberIcon');

    passwordInput.addEventListener('input', function() {
        const password = passwordInput.value;

        // Vérifie si le mot de passe a une longueur d'au moins 8 caractères
        const hasMinLength = password.length >= 8;
        // Vérifie si le mot de passe contient au moins un chiffre
        const hasNumber = /\d/.test(password);

        // Met à jour les styles et les icônes des indicateurs en fonction de la validité du mot de passe
        lengthIndicator.style.color = hasMinLength ? 'green' : 'red';
        lengthIcon.className = hasMinLength ? 'fas fa-check' : 'fas fa-times';
        lengthIcon.style.color = hasMinLength ? 'green' : 'red';

        numberIndicator.style.color = hasNumber ? 'green' : 'red';
        numberIcon.className = hasNumber ? 'fas fa-check' : 'fas fa-times';
        numberIcon.style.color = hasNumber ? 'green' : 'red';
    });
</script>

</body>
</html>