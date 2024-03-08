<?php
session_start();

// Détruire la session
session_destroy();

// Rediriger vers la page de connexion (ou toute autre page)
header("Location: index.php");
exit;