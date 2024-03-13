<?php
session_start();

if (isset($_GET['token'])) {
    // Vérifier le token et activer le compte de l'utilisateur
    $token = $_GET['token'];

    // Vérifiez si le token est valide et activez le compte
    // Code à implémenter selon votre logique d'activation de compte
    if($activation_token == $token){
        /* session_start(); */
        session_regenerate_id();
        $sql = sprintf("SELECT idUtilisateur FROM Utilisateur WHERE adrMail = '%s'", $mysqli->real_escape_string($_POST["email"]));

        $result = $mysqli->query($sql);

        $user = $result->fetch_assoc();
        $_SESSION["user_id"] = $user["idUtilisateur"];
        
        echo '<script>window.location = "algorithme/index.php";</script>';
        exit;
    }
    
    /* echo "Votre compte a été activé avec succès!"; */
} else {
    echo "Token d'activation manquant.";
}
