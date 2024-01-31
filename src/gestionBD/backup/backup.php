<?php

// Paramètres de connexion à la base de données
$database = 'projetME';
$servername = 'localhost';
$username = 'root';
$password = '';

// Connexion à la base de données
$mysqli = new mysqli($servername, $username, $password, $database);

// Vérification de la connexion
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Requête pour obtenir la liste de toutes les tables
$tables_query = "SHOW TABLES";
$tables_result = $mysqli->query($tables_query);

// Parcours de chaque table
while ($table_row = $tables_result->fetch_row()) {
    $table_name = $table_row[0];

    // Requête pour sélectionner toutes les lignes de la table actuelle
    $select_query = "SELECT * FROM $table_name";
    $select_result = $mysqli->query($select_query);

    // Nom du fichier CSV pour la table actuelle
    $csv_file = "$table_name.csv";

    // Ouverture du fichier CSV en mode écriture
    $file = fopen($csv_file, 'w');

    // Vérification si l'ouverture du fichier a réussi
    if (!$file) {
        die("Impossible d'ouvrir le fichier CSV pour la table $table_name.");
    }

    // Écriture de l'en-tête du fichier CSV
    $header = array();
    $fields_result = $mysqli->query("DESCRIBE $table_name");
    while ($field_row = $fields_result->fetch_assoc()) {
        $header[] = $field_row['Field'];
    }
    fputcsv($file, $header);

    // Écriture des données dans le fichier CSV
    if ($select_result->num_rows > 0) {
        while ($row = $select_result->fetch_assoc()) {
            fputcsv($file, $row);
        }
    }

    // Fermeture du fichier CSV
    fclose($file);

    echo "Les données de la table $table_name ont été exportées avec succès vers $csv_file.<br>";
}

// Fermeture de la connexion à la base de données
$mysqli->close();


