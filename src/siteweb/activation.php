<?php
session_start();
$mysqli = require "../gestionBD/database.php";

if (isset($_GET['token'])) {
    // Vérifier le token et activer le compte de l'utilisateur
    $token = $_GET['token'];

    // Vérifiez si le token est valide et activez le compte
    // Code à implémenter selon votre logique d'activation de compte
    if(isset($_GET['token'])){
        /* session_start(); */
        session_regenerate_id();
        $sql = sprintf("SELECT idUtilisateur FROM Utilisateur WHERE adrMail = '%s'", $mysqli->real_escape_string($_GET["email"]));

        $result = $mysqli->query($sql);

        $user = $result->fetch_assoc();
        $_SESSION["user_id"] = $user["idUtilisateur"];
        
        echo "<script>window.location.href='algorithme/index.php'</script>";
    }
    
    /* echo "Votre compte a été activé avec succès!"; */
} else {
    echo "<script>console.log('Token d'activation manquant.')</script>";
}
