<?php
session_start();

// Vérifier si l'ID de l'événement est passé en tant que paramètre GET
if (!isset($_GET['event'])) {
    echo "Error: Event ID is missing.";
    exit;
}

// Récupérer l'ID de l'événement à partir des paramètres GET
$event_id = $_GET['event'];

// Concaténer les données pour former le contenu du QR code
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "Unknown"; // Si l'utilisateur n'est pas connecté, afficher "Unknown"
$qr_content = "L'utilisateur ". $_SESSION['user_id'] . " participe bien à l'événement " . $event_id;

// Générer l'URL de l'API QR Code de Google
$api_url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($qr_content);

// Télécharger l'image du code QR
$qr_image = file_get_contents($api_url);

// Vérifier si le téléchargement a réussi
if ($qr_image !== false) {
    // Définir les en-têtes appropriés pour renvoyer une image PNG
    header("Content-type: image/png");

    // Envoyer le contenu de l'image directement dans le corps de la réponse
    echo $qr_image;
} else {
    echo "Erreur lors du téléchargement de l'image du code QR.";
}
