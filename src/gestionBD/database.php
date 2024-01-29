<!--
/**
    Auteurs : Equipe MeetEvent
    Description : Création de la connection vers la base de données
*/
-->

<?php
//Pour YANNIS
$bdname = 'projetME';
$host = 'localhost';
$username = 'root';
$password = '';

$connexion = new mysqli(hostname : $host, username : $username, password : $password, database : $bdname);

if($connexion->connect_errno){
    die("Connection error:" . $connexion->connect_error);
} 



// Pour Clément
/*$bdname = 'cmourgue_bd';
$host = 'lakartxela.iutbayonne.univ-pau.fr';
$username = 'cmourgue_bd';
$password = 'cmourgue_bd';

$connexion = new mysqli($host, $username, $password, $bdname); // or die ...

if($connexion->connect_errno){
    die("Connection error : " . $connexion->connect_error);
}*/



// Pour Dylan
/*$bdname = 'dvictoras_bd';
$host = 'lakartxela.iutbayonne.univ-pau.fr';
$username = 'dvictoras_bd';
$password = 'dvictoras_bd';

$connexion = new mysqli($host, $username, $password, $bdname); // or die ...

if($connexion->connect_errno){
    die("Connection error : " . $connexion->connect_error);
}*/



// Pour Mattin
/*$bdname = 'mguiheneuf_bd';
$host = 'lakartxela.iutbayonne.univ-pau.fr';
$username = 'mguiheneuf_bd';
$password = 'mguiheneuf_bd';

$connexion = new mysqli($host, $username, $password, $bdname); // or die ...

if($connexion->connect_errno){
    die("Connection error : " . $connexion->connect_error);
}*/

return $connexion;

?>