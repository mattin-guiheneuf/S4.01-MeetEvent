<?php


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if(isset($_POST["email"]) && isset($_POST["mdp"])){

        function validate($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $email = validate($_POST["email"]);
        $mdp = validate($_POST["mdp"]);

        if(empty($email)){
            header("Location: connexion.php?error=Email is required");
            exit();
        }else if (empty($mdp)){
            header("Location: connexion.php?error=Mot de passe is required");
            exit();
        }else{
            $mysqli = require "../gestionBD/database.php";

            $sql = sprintf("SELECT * FROM Utilisateur WHERE adrMail = '%s'", $mysqli->real_escape_string($_POST["email"]));

            $result = $mysqli->query($sql);

            $user = $result->fetch_assoc();

            if ($user) {
                /* if(password_verify($_POST["mdp"], $user["MotDePasse"])){ */if($_POST["mdp"]===$user["MotDePasse"]){

                    session_start();

                    session_regenerate_id();

                    $_SESSION["user_id"] = $user["idUtilisateur"];
                    
                    header("Location: pageSuggestion.html");
                    exit();
                }
                else{
                    header("Location: connexion.php?error=Email ou Mot de passe Incorrect");
                    exit();
                }
            }else{
                header("Location: connexion.php?error=Email ou Mot de passe Incorrect");
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
    <link rel="stylesheet" href="styles2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Modern login page</title>
</head>
<body>
    <!-- Si erreur -->
    <?php if (isset($_GET['error'])) { ?>
        <p class="error"><?php echo $_GET['error']; ?></p>
    <?php } ?>

    <div class="container <?php if(isset($_POST["sign-up"])) { echo "active"; } ?>" id="container">
        <div class="form-container sign-up">
            <form action="enregistrement.php" method="post" novalidate>
                <h1>Rejoignez nous</h1>
                <input type="email" placeholder="Email" name="email" value="<?= htmlspecialchars( $_POST["email"] ?? "") ?>">
                <input type="text" placeholder="Identifiant" name="pseudo" value="<?= htmlspecialchars( $_POST["pseudo"] ?? "") ?>">
                <input type="datetime" placeholder="Date de Naissance (dd/mm/aaaa)" name="dateNaiss" value="<?= htmlspecialchars( $_POST["dateNaiss"] ?? "") ?>">
                <div style="display: flex;gap:10px;">
                    <span><input type="text" placeholder="Ville" name="ville" value="<?= htmlspecialchars( $_POST["ville"] ?? "") ?>"></span>
                    <span><input type="text" placeholder="Code Postal" name="cp" value="<?= htmlspecialchars( $_POST["cp"] ?? "") ?>"></span>
                </div>
                <input type="password" placeholder="Mot de passe" name="mdp" value="<?= htmlspecialchars( $_POST["mdp"] ?? "") ?>">
                <input type="hidden" name="sign-up">
                <button type="submit">Je m'inscrit</button>
            </form>
        </div>

        <div class="form-container sign-in">
            <form method="post">            
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
</body>
</html>