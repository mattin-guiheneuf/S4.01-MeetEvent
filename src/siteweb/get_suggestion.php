<?php
// Exécutez le code contenu dans algorithme/Suggestion.php
ob_start();
require_once 'algorithme/Suggestion.php';
$listSuggest = ob_get_clean();

echo $listSuggest;

