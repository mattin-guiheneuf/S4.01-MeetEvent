

<?php

/* *
    Auteurs : Clement Mourgue et Yannis Duvignau
    Description : Création et peuplement des données de base dans une base de données créer préalablement
 */


//connexion
include_once "database.php";

// Noms des tables
$nomTable_Tag = "Tag";
$nomTable_Cat = "Categorie";
$nomTable_User = "Utilisateur";
$nomTable_Event = "Evenement";
$nomTable_Associer = "Associer";
$nomTable_Participer = "Participer";
$nomTable_Reco = "Recommander";
$nomTable_Qualifier = "Qualifier";
$nomTable_Regrouper = "Regrouper";
$tabNomsTables = [$nomTable_Regrouper,$nomTable_Qualifier,$nomTable_Reco,$nomTable_Participer,$nomTable_Associer,$nomTable_Event,$nomTable_User,$nomTable_Cat,$nomTable_Tag];



// Requête pour récupérer le nom de toutes les tables
/*$tables_query = "SHOW TABLES";
$tables_result = $connexion->query($tables_query);

if ($tables_result) {
    while ($row = $tables_result->fetch_row()) {
        $table_name = $row[0];*/
	for($i = 0;$i<sizeof($tabNomsTables);$i++){
        // Requête pour supprimer chaque table
        $drop_query = "DROP TABLE IF EXISTS ".$tabNomsTables[$i]." CASCADE";
        if ($connexion->query($drop_query) === TRUE) {
            echo "Table ".$tabNomsTables[$i]." supprimée avec succès.<br>";
        } else {
            echo "Erreur lors de la suppression de la table ".$tabNomsTables[$i]." : " . $connexion->error . "<br>";
        }
    } /*
} else {
    echo "Erreur lors de la récupération des tables : " . $connexion->error;
} */




//////////////////////////////////////////////
///          Création des tables          ///
/////////////////////////////////////////////


// Requête de création de la table Tag
$sql = "CREATE TABLE IF NOT EXISTS $nomTable_Tag (
    idTag INT AUTO_INCREMENT PRIMARY KEY,
    libelle varchar(50) not null,
    description varchar(200) not null
)";

if ($connexion->query($sql) === TRUE) {
    echo '<script type="text/javascript">console.log("Table Tag créée avec succès ou déjà existante");</script>';
} else {
    echo '<script type="text/javascript">console.log("Erreur lors de la création de la table");</script>';
}

// Requête de création de la table Categorie
$sql = "CREATE TABLE IF NOT EXISTS $nomTable_Cat (
    idCategorie integer AUTO_INCREMENT primary key,
    libelle varchar(50) not null
)";

if ($connexion->query($sql) === TRUE) {
    echo '<script type="text/javascript">console.log("Table Catégorie créée avec succès ou déjà existante");</script>';
} else {
    echo '<script type="text/javascript">console.log("Erreur lors de la création de la table");</script>';
}

// Requête de création de la table Utilisateur
$sql = "CREATE TABLE IF NOT EXISTS $nomTable_User (
    idUtilisateur integer AUTO_INCREMENT primary key,
    nom varchar(50) not null,
    prenom varchar(50) not null,
    adrMail varchar(50) unique not null,
    trancheAge varchar(10) not null,
    description varchar(200) not null,
    situation varchar(50) not null,
    chemImage varchar(200),
    MotDePasse varchar(50) not null,
    constraint verif_mail check(adrMail like '%@%.%'),
    constraint verif_trancheAge check (trancheAge in ('< 18', '18-25', '26-45', '> 45'))
)";

if ($connexion->query($sql) === TRUE) {
    echo '<script type="text/javascript">console.log("Table Utilisateur créée avec succès ou déjà existante");</script>';
} else {
    echo '<script type="text/javascript">console.log("Erreur lors de la création de la table");</script>';
}

// Requête de création de la table Evenement
$sql = "CREATE TABLE IF NOT EXISTS $nomTable_Event (
    idEvenement integer AUTO_INCREMENT primary key,
    nom varchar(50) not null,
    description varchar(200) not null,
    dateEvent date not null,
    effMax integer not null,
    statut integer not null,
    prix decimal not null,
    heure varchar(10) not null,
    adresse varchar(200) not null,
    chemImages varchar(200) not null,
    idCategorie integer not null,
    idOrganisateur integer not null,
    foreign key(idCategorie) references Categorie(idCategorie),
    foreign key(idOrganisateur) references Utilisateur(idUtilisateur),
    constraint verif_statut check (statut in (0, 1))
)";
  
if ($connexion->query($sql) === TRUE) {
    echo '<script type="text/javascript">console.log("Table Evenement créée avec succès ou déjà existante");</script>';
} else {
    echo '<script type="text/javascript">console.log("Erreur lors de la création de la table");</script>';
}

/* $sql = "SET SERVEROUTPUT ON";
$connexion->query($sql); */
/* $sql = "CREATE OR REPLACE TRIGGER EVEN_DATE_TRIGGER
BEFORE INSERT OR UPDATE
ON EVENEMENT
FOR EACH ROW
DECLARE 
  dateActuelle DATE;
BEGIN
  dateActuelle := SYSDATE;
  IF :NEW.DATEEVENT > dateActuelle THEN
    DBMS_OUTPUT.PUT_LINE('Date validée');
  ELSE
    RAISE_APPLICATION_ERROR(-20001, 'La date n''est pas valide.');
  END IF;
END
)";

$connexion->query($sql); */

// Requête de création de la table Associer
$sql = "CREATE TABLE IF NOT EXISTS $nomTable_Associer (
    idUtilisateur integer not null,
    idTag integer not null,
    foreign key (idUtilisateur) references Utilisateur(idUtilisateur),
    foreign key (idTag) references Tag(idTag)
)";

if ($connexion->query($sql) === TRUE) {
    echo '<script type="text/javascript">console.log("Table Associer créée avec succès ou déjà existante");</script>';
} else {
    echo '<script type="text/javascript">console.log("Erreur lors de la création de la table");</script>';
}

// Requête de création de la table $nomTable_Participer
$sql = "CREATE TABLE IF NOT EXISTS $nomTable_Participer (
    idUtilisateur integer not null,
    idEvenement integer not null,
    lienQRCode varchar(50) not null,
    participationAnnulee integer not null,
    foreign key (idUtilisateur) references Utilisateur(idUtilisateur),
    foreign key (idEvenement) references Evenement(idEvenement),
    constraint verif_participationAnnulee check (participationAnnulee in (0, 1))
)";

if ($connexion->query($sql) === TRUE) {
    echo '<script type="text/javascript">console.log("Table $nomTable_Participer créée avec succès ou déjà existante");</script>';
} else {
    echo '<script type="text/javascript">console.log("Erreur lors de la création de la table");</script>';
}

// Requête de création de la table Recommander
$sql = "CREATE TABLE IF NOT EXISTS $nomTable_Reco (
    idUtilisateur integer not null,
    idEvenement integer not null,
    pourcentageSuggestion decimal not null,
    foreign key (idUtilisateur) references Utilisateur(idUtilisateur),
    foreign key (idEvenement) references Evenement(idEvenement)
)";

if ($connexion->query($sql) === TRUE) {
    echo '<script type="text/javascript">console.log("Table Recommander créée avec succès ou déjà existante");</script>';
} else {
    echo '<script type="text/javascript">console.log("Erreur lors de la création de la table");</script>';
}

// Requête de création de la table Qualifier
$sql = "CREATE TABLE IF NOT EXISTS $nomTable_Qualifier (
    idEvenement integer not null,
    idTag integer not null,
    foreign key (idEvenement) references Evenement(idEvenement),
    foreign key (idTag) references Tag(idTag)
)";

if ($connexion->query($sql) === TRUE) {
    echo '<script type="text/javascript">console.log("Table Qualifier créée avec succès ou déjà existante");</script>';
} else {
    echo '<script type="text/javascript">console.log("Erreur lors de la création de la table");</script>';
}

// Requête de création de la table Regrouper
$sql = "CREATE TABLE IF NOT EXISTS $nomTable_Regrouper (
    idTag integer not null,
    idCategorie integer not null,
    foreign key (idTag) references Tag(idTag),
    foreign key (idCategorie) references Categorie(idCategorie)
)";

if ($connexion->query($sql) === TRUE) {
    echo '<script type="text/javascript">console.log("Table Regrouper créée avec succès ou déjà existante");</script>';
} else {
    echo '<script type="text/javascript">console.log("Erreur lors de la création de la table");</script>';
}

//////////////////////////////////////////////
///         Peuplement des tables          ///
/////////////////////////////////////////////


/////////////////////
///   Table Tag   ///
/////////////////////


// Requête pour compter le nombre de lignes dans la table
$resultat = $connexion->query("SELECT COUNT(*) AS total FROM $nomTable_Tag");
$row = mysqli_fetch_assoc($resultat);
$totalLignes = $row['total'];

// Vérifier si la table est vide
if ($totalLignes == 0) {
    // La table est vide
    // Requête d'insertion de données dans la table tag
    $sql = "INSERT INTO $nomTable_Tag (idTag, libelle, description) VALUES 
    ( 1 , 'Cuisine' , ' ' ),
    ( 2 , 'Art' , ' ' ),
    ( 3 , 'Musique' , ' ' ),
    ( 4 , 'Dessin' , ' ' ),
    ( 5 , 'Sport' , ' ' ),
    ( 6 , 'Entraînement' , ' ' ),
    ( 7 , 'Social' , ' ' ),
    ( 8 , 'Discussion' , ' ' ),
    ( 9 , 'Méditation' , ' ' ),
    ( 10 , 'Détente' , ' ' ),
    ( 11 , 'Lecture' , ' ' ),
    ( 12 , 'Écoute' , ' ' ),
    ( 13 , 'Rire' , ' ' ),
    ( 14 , 'Divertissement' , ' ' ),
    ( 15 , 'Fête' , ' ' ),
    ( 16 , 'Exploration' , ' ' ),
    ( 17 , 'Voyage' , ' ' ),
    ( 18 , 'Découverte' , ' ' ),
    ( 19 , 'Enseignement' , ' ' ),
    ( 20 , 'Travail' , ' ' ),
    ( 21 , 'Créativité' , ' ' ),
    ( 22 , 'Construction' , ' ' ),
    ( 23 , 'Jardinage' , ' ' ),
    ( 24 , 'Photographie' , ' ' ),
    ( 25 , 'Film' , ' ' ),
    ( 26 , 'Danse' , ' ' ),
    ( 27 , 'Chant' , ' ' ),
    ( 28 , 'Instrument' , ' ' ),
    ( 29 , 'Collection' , ' ' ),
    ( 30 , 'Informatique' , ' ' ),
    ( 31 , 'Réflexion' , ' ' ),
    ( 32 , 'Engagement' , ' ' ),
    ( 33 , 'Volontariat' , ' ' ),
    ( 34 , 'Organisation' , ' ' ),
    ( 35 , 'Exercice' , ' ' ),
    ( 36 , 'Expérience' , ' ' ),
    ( 37 , 'Test' , ' ' ),
    ( 38 , 'Développement' , ' ' ),
    ( 39 , 'Amélioration' , ' ' ),
    ( 40 , 'Innovation' , ' ' ),
    ( 41 , 'Économie' , ' ' ),
    ( 42 , 'Partage' , ' ' ),
    ( 43 , 'Influence' , ' ' ),
    ( 44 , 'Motivation' , ' ' ),
    ( 45 , 'Inspiration' , ' ' ),
    ( 46 , 'Amusement' , ' ' ),
    ( 47 , 'Célébration' , ' ' ),
    ( 48 , 'Changement' , ' ' ),
    ( 49 , 'Imagination' , ' ' ),
    ( 50 , 'Jeux' , ' ' ),
    ( 51 , 'Festival' , ' ' ),
    ( 52 , 'Culture' , ' ' ),
    ( 53 , 'Concert' , ' ' ),
    ( 54 , 'Repas' , ' ' ),
    ( 55 , 'Aperitif' , ' ' ),
    ( 56 , 'Alcool' , ' ' ),
    ( 57 , 'Association' , ' ' ),
    ( 58 , 'Rencontre' , ' ' ),
    ( 59 , 'Marche' , ' ' ),
    ( 60 , 'Amical' , ' ' ),
    ( 61 , 'Plaisir' , ' ' ),
    ( 62 , 'Jeu de société' , ' ' ),
    ( 63 , 'Animaux' , ' ' ),
    ( 64 , 'Soiree' , ' ' ),
    ( 65 , 'Nature' , ' ' ),
    ( 66 , 'Paysages' , ' ' ),
    ( 67 , 'Atelier' , ' ' ),
    ( 68 , 'Gastronomie' , ' ' ),
    ( 69 , 'Dégustation' , ' ' ),
    ( 70 , 'Exposition' , ' ' ),
    ( 71 , 'Musee' , ' ' ),
    ( 72 , 'Dîner' , ' ' ),
    ( 73 , 'Caritatif' , ' ' ),
    ( 74 , 'Solidarité' , ' ' ),
    ( 75 , 'Loisir' , ' ' ),
    ( 76 , 'Competition' , ' ' ),
    ( 77 , 'Tournoi' , ' ' ),
    ( 78 , 'Montagne' , ' ' ),
    ( 79 , 'Finance' , ' ' ),
    ( 80 , 'Formation' , ' ' ),
    ( 81 , 'Océan' , ' ' )";


    if ($connexion->query($sql) === TRUE) {
        echo '<script type="text/javascript">console.log("Données ajoutées avec succès");</script>';
    } else {
        echo '<script type="text/javascript">console.log("Erreur lors de l`ajout des données");</script';
    }
}else{
    echo '<script type="text/javascript">console.log("La table n`est pas vide");</script>';
}

///////////////////////////
///   Table Catégorie   ///
///////////////////////////


// Requête pour compter le nombre de lignes dans la table
$resultat = $connexion->query("SELECT COUNT(*) AS total FROM $nomTable_Cat");
$row = mysqli_fetch_assoc($resultat);
$totalLignes = $row['total'];

// Vérifier si la table est vide
if ($totalLignes == 0) {
    // La table est vide
    // Requête d'insertion de données dans la table Catégorie
    $sql = "INSERT INTO $nomTable_Cat VALUES
    ( 1 , 'Divertissement et Loisirs' ),
    ( 2 , 'Gastronomie et Culinaire' ),
    ( 3 , 'Social et Rencontres' ),
    ( 4 , 'Education et Culture' ),
    ( 5 , 'Sport et Activites Physiques' )";

    if ($connexion->query($sql) === TRUE) {
        echo '<script type="text/javascript">console.log("Données ajoutées avec succès");</script>';
    } else {
        echo '<script type="text/javascript">console.log("Erreur lors de l`ajout des données");</script';
    }
}else{
    echo '<script type="text/javascript">console.log("La table n`est pas vide");</script>';
}

/////////////////////////////
///   Table Utilisateur   ///
/////////////////////////////


// Requête pour compter le nombre de lignes dans la table
$resultat = $connexion->query("SELECT COUNT(*) AS total FROM $nomTable_User");
$row = mysqli_fetch_assoc($resultat);
$totalLignes = $row['total'];

// Vérifier si la table est vide
if ($totalLignes == 0) {
    // La table est vide
    // Requête d'insertion de données dans la table Utilisateur
    $sql = "INSERT INTO $nomTable_User VALUES
    ( 1 , 'Jean' , 'jean' , 'jean.jean64@gmail.com' , '> 45', 'vieux, laid, ennuyeux',  'sourd' , './img/iconUser1.jpg' ,'cestSecret123'),
    ( 2 , 'Dupont' , 'joseph' , 'dupont.jos64@gmail.com' , '26-45', 'vieux, laid, ennuyeux',  'aucun', './img/iconUser4.png' , 'cestSecret123'),
    ( 3 , 'Dupouille' , 'rodric' , 'dup.rodric40@gmail.com' , '18-25', 'jeune, beau, drole',  'aucun', './img/iconUser2' , 'cestSecret123'),
    ( 4 , 'Capdet' , 'stephane' , 'steph40@gmail.com' , '< 18', 'jeune, beau, drole',  'aucun', './img/iconUser1' , 'cestSecret123'),
    ( 5 , 'Duvignau' , 'yannis' , 'yaya.dudu40@gmail.com' , '18-25', 'jeune, beau, drole',  'aucun', './img/iconUser3' , 'cestSecret123'),
    ( 6 , 'Mourgue' , 'clement' , 'clement.mg40@gmail.com' , '18-25', 'jeune, beau, drole',  'autiste', './img/iconUser1' , 'cestSecret123'),
    ( 7 , 'Victoras' , 'dylan' , 'vivicto.dy64@gmail.com' , '18-25', 'jeune, beau, drole',  'aucun', './img/iconUser5' , 'cestSecret123'),
    ( 8 , 'Guiheuneuf' , 'mattin' , 'guigui64@gmail.com' , '18-25', 'jeune, beau, drole', 'aucun', './img/iconUser3' , 'cestSecret123'),
    ( 9 , 'Marot' , 'lucas' , 'lulu64@gmail.com' , '18-25', 'jeune, beau, drole',  'aucun', './img/iconUser4' , 'cestSecret123'),
    ( 10 , 'Palassin' , 'adrien' , 'ad40@gmail.com' , '< 18', 'jeune, beau, drole',  'aucun', './img/iconUser1' , 'cestSecret123'),
    ( 11 , 'Pierre' , 'Suzon' , 'suzonpierre@gmail.com' , '18-25', 'jeune, beau, drole',  'aucun', './img/iconUser6' , 'cestSecret123'),
    ( 12 , 'Irastorza' , 'Pierre' , 'pirastorza@gmail.com' , '26-45', 'jeune, beau, drole',  'aucun', './img/iconUser5' , 'cestSecret123'),
    ( 13 , 'Moulin' , 'Thibault' , 'thibmoulin@gmail.com' , '26-45', 'jeune, beau, drole',  'aucun', './img/iconUser4' , 'cestSecret123')
    ";

    if ($connexion->query($sql) === TRUE) {
        echo '<script type="text/javascript">console.log("Données de la table Utilisateur ajoutées avec succès");</script>';
    } else {
        echo '<script type="text/javascript">console.log("Erreur lors de l`ajout des données");</script';
    }
}else{
    echo '<script type="text/javascript">console.log("La table n`est pas vide");</script>';
}

///////////////////////////
///   Table Evenement   ///
///////////////////////////


// Requête pour compter le nombre de lignes dans la table
$resultat = $connexion->query("SELECT COUNT(*) AS total FROM $nomTable_Event");
$row = mysqli_fetch_assoc($resultat);
$totalLignes = $row['total'];

// Vérifier si la table est vide
if ($totalLignes == 0) {
    // La table est vide
    // Requête d'insertion de données dans la table Evenement
    $sql = "INSERT INTO $nomTable_Event VALUES
    ( 1 , 'Grosse soiree' , 'Grosse soiree chez moi !' , '2024-12-15' , 10,  0 , 0.00 , '15:30' , '3 rue de Cassou, 64600 Anglet','./img/event1.jpg' , 1 , 1 ),
    ( 2 , 'Soiree FIFA' , 'Tournoi de fou sur FIFA. 3-0, tu lâche la manette !' , '2024-11-20' , 15,  0 , 0.00, '15:30' , '3 rue de Cassou, 64600 Anglet','./img/event2.jpg' , 1 , 2 ),
    ( 3 , 'Soiree au WOK' , 'Il faut rentabiliser le prix.' , '2024-11-14' , 20,  0 , 0.00, '15:30' , '3 rue de Cassou, 64600 Anglet','./img/event3' , 1 , 3 ),
    ( 4 , 'Aquaman 2 au cine' , 'Venez on regarde Aquaman 2 !' , '2024-10-10' , 30,  1 , 0.00, '15:30','3 rue de Cassou, 64600 Anglet','./img/event4' , 1 , 4 ),
    ( 5 , 'Bowling' , 'Soiree chill au bowling' , '2024-10-25' , 5,  0 , 0.00,'15:30', '3 rue de Cassou, 64600 Anglet','./img/event5' , 2 , 5 ),
    ( 6 , 'Billard' , 'Un entrainement au billard' , '2024-10-25' , 3,  0 , 0.00,'15:30', '3 rue de Cassou, 64600 Anglet','./img/event1' , 3 , 6 ),
    ( 7 , 'Laser game' , 'Venez jouer a Call of Duty dans la vraie vie' , '2024-10-30' , 19,  0 , 0.00,'15:30','3 rue de Cassou, 64600 Anglet','./img/event2' , 3 , 7 ),
    ( 8 , 'Atelier de poterie' , 'Venez apprendre la poterie' , '2024-10-06' , 18,  1 , 5.50,'15:30','3 rue de Cassou, 64600 Anglet','./img/event3' , 4 , 8 ),
    ( 9 , 'Réunion litteraire' , 'Parlons de livre !' , '2024-10-11' , 16,  1 , 9.99,'15:30','3 rue de Cassou, 64600 Anglet','./img/event2' , 4 , 9 ),
    ( 10 , 'Foot salle' , '9 contre 9 sur un terrain !' , '2024-11-14' , 18,  0 , 0.00,'15:30','3 rue de Cassou, 64600 Anglet','./img/event4' , 5 , 10 ),
    ( 11 , 'Soirée jeux de société', 'Venez jouer à vos jeux préférés !', '2024-12-22', 12, 0, 0.00,'15:30','3 rue de Cassou, 64600 Anglet','./img/event2', 1, 12 ),
    ( 12 , 'Cours de cuisine' , 'Apprenons à cuisiner ensemble !' , '2024-11-14' , 25,  1 , 15.00,'15:30','3 rue de Cassou, 64600 Anglet','./img/event5' , 4 , 13 ),
    ( 13,  'Concert live', 'Profitez d''un concert en direct !', '2024-10-05', 40, 1, 20.00,'15:30','3 rue de Cassou, 64600 Anglet','./img/event2', 4, 11 ),
    ( 14 , 'Soirée karaoké', 'Montrez vos talents de chanteur !', '2024-10-12', 15, 1, 0.00,'15:30','3 rue de Cassou, 64600 Anglet','./img/event5', 1, 11 ),
    ( 15,  'Randonnée nature', 'Explorer la nature ensemble !', '2024-11-08', 8, 1, 0.00,'15:30','3 rue de Cassou, 64600 Anglet','./img/event1', 3, 1 ),
    ( 16 , 'Projection de films', 'Cinéma à la maison !', '2024-11-30', 20, 0, 5.00,'15:30','3 rue de Cassou, 64600 Anglet','./img/event2', 4, 5 ),
    ( 17, 'Séance de méditation', 'Relaxation et bien-être', '2024-11-17', 10, 1, 3.99,'15:30','3 rue de Cassou, 64600 Anglet','./img/event3', 5, 1 ),
    ( 18 , 'Tournoi de ping-pong', 'Compétition amicale de ping-pong', '2024-12-03', 16, 1, 7.50,'15:30','3 rue de Cassou, 64600 Anglet','./img/event4', 5, 8 ),
    ( 19 , 'Banquet des fêtes', 'Repas convivial pour profiter des fêtes du village', '2024-07-11', 50, 1, 8.00,'15:30','3 rue de Cassou, 64600 Anglet','./img/event5', 2, 8 )
    ";

    if ($connexion->query($sql) === TRUE) {
        echo '<script type="text/javascript">console.log("Données de la table Evenement ajoutées avec succès");</script>';
    } else {
        echo '<script type="text/javascript">console.log("Erreur lors de l`ajout des données");</script';
    }
}else{
    echo '<script type="text/javascript">console.log("La table n`est pas vide");</script>';
}

///////////////////////////
///   Table Associer   ///
///////////////////////////


// Requête pour compter le nombre de lignes dans la table
$resultat = $connexion->query("SELECT COUNT(*) AS total FROM $nomTable_Associer");
$row = mysqli_fetch_assoc($resultat);
$totalLignes = $row['total'];

// Vérifier si la table est vide
if ($totalLignes == 0) {
    // La table est vide
    // Requête d'insertion de données dans la table Associer
    $sql = "INSERT INTO $nomTable_Associer VALUES
    ( 1 , 1 ),
    ( 2 , 2 ),
    ( 3 , 3 ),
    ( 3 , 12 ),
    ( 3 , 13 ),
    ( 3 , 14 ),
    ( 3 , 15 ),
    ( 4 , 1 ),
    ( 4 , 2 ),
    ( 4 , 3 ),
    ( 4 , 4 ),
    ( 4 , 5 ),
    ( 5 , 6 ),
    ( 5 , 7 ),
    ( 5 , 8 ),
    ( 5 , 9 ),
    ( 5 , 10 ),
    ( 6 , 11 ),
    ( 6 , 12 ),
    ( 6 , 13 ),
    ( 6 , 14 ),
    ( 6 , 15 ),
    ( 7 , 1 ),
    ( 7 , 2 ),
    ( 7 , 3 ),
    ( 7 , 4 ),
    ( 7 , 5 ),
    ( 8 , 6 ),
    ( 8 , 7 ),
    ( 8 , 8 ),
    ( 8 , 9 ),
    ( 8 , 10 ),
    ( 9 , 1 ),
    ( 9 , 2 ),
    ( 9 , 3 ),
    ( 9 , 10 ),
    ( 9 , 11 ),
    ( 10 , 2 ),
    ( 10 , 6 ),
    ( 10 , 8 ),
    ( 10 , 9 ),
    ( 10 , 7 )
    ";

    if ($connexion->query($sql) === TRUE) {
        echo '<script type="text/javascript">console.log("Données de la table Associer ajoutées avec succès");</script>';
    } else {
        echo '<script type="text/javascript">console.log("Erreur lors de l`ajout des données");</script';
    }
}else{
    echo '<script type="text/javascript">console.log("La table n`est pas vide");</script>';
}

////////////////////////////
///   Table Participer   ///
////////////////////////////


// Requête pour compter le nombre de lignes dans la table
$resultat = $connexion->query("SELECT COUNT(*) AS total FROM $nomTable_Participer");
$row = mysqli_fetch_assoc($resultat);
$totalLignes = $row['total'];

// Vérifier si la table est vide
if ($totalLignes == 0) {
    // La table est vide
    // Requête d'insertion de données dans la table Participer
    $sql = "INSERT INTO $nomTable_Participer VALUES
    ( 1 , 2 , 'lienQRCode' , 0 ),
    ( 2 , 3 , 'lienQRCode' , 0 ),
    ( 3 , 3 , 'lienQRCode' , 0 ),
    ( 4 , 4 , 'lienQRCode' , 1 ),
    ( 5 , 5 , 'lienQRCode' , 0 ),
    ( 6 , 8 , 'lienQRCode' , 0 ),
    ( 7 , 8 , 'lienQRCode' , 0 ),
    ( 8 , 9 , 'lienQRCode' , 0 ),
    ( 9 , 4 , 'lienQRCode' , 0 ),
    ( 1 , 1 , 'lienQRCode' , 1 ),
    ( 3 , 2 , 'lienQRCode' , 0 ),
    ( 4 , 9 , 'lienQRCode' , 0 ),
    ( 9 , 3 , 'lienQRCode' , 0 ),
    ( 5 , 2 , 'lienQRCode' , 1 ),
    ( 3 , 11 , 'lienQRCode' , 0 ),
    ( 3 , 12 , 'lienQRCode' , 0 ),
    ( 8 , 11 , 'lienQRCode' , 0 ),
    ( 8 , 12 , 'lienQRCode' , 0 ),
    ( 6 , 11 , 'lienQRCode' , 0 ),
    ( 9 , 12 , 'lienQRCode' , 0 ),
    ( 4 , 11 , 'lienQRCode' , 0 ),
    ( 10 , 12 , 'lienQRCode' , 0 )
    ";

    if ($connexion->query($sql) === TRUE) {
        echo '<script type="text/javascript">console.log("Données de la table Participer ajoutées avec succès");</script>';
    } else {
        echo '<script type="text/javascript">console.log("Erreur lors de l`ajout des données");</script';
    }
}else{
    echo '<script type="text/javascript">console.log("La table n`est pas vide");</script>';
}

/////////////////////////////
///   Table Recommander   ///
/////////////////////////////


// Requête pour compter le nombre de lignes dans la table
$resultat = $connexion->query("SELECT COUNT(*) AS total FROM $nomTable_Reco");
$row = mysqli_fetch_assoc($resultat);
$totalLignes = $row['total'];

// Vérifier si la table est vide
if ($totalLignes == 0) {
    // La table est vide
    // Requête d'insertion de données dans la table Recommander
    $sql = "INSERT INTO $nomTable_Reco VALUES
    ( 1 , 1 , 0.89 ),
    ( 1 , 2 , 45.58 ),
    ( 1 , 3 , 54.87 ),
    ( 1 , 4 , 80.50 ),
    ( 1 , 5 , 12.03 ),
    ( 1 , 6 , 25.23 ),
    ( 1 , 7 , 92.99 ),
    ( 1 , 8 , 7.80 ),
    ( 1 , 9 , 19.90 ),
    ( 1 , 10 , 40.40 ),
    ( 1 , 11 , 78.44 ),
    ( 1 , 12 , 15.72),
    ( 1 , 13 , 37.89 ),
    ( 1 , 14 , 64.21),
    ( 1 , 15 , 21.36),
    ( 1 , 16 , 53.76),
    ( 1 , 17 , 89.02),
    ( 1 , 18 , 45.90),
    ( 1 , 19 , 52.45),
    ( 2 , 1 , 13.13 ),
    ( 2 , 2 , 58.85 ),
    ( 2 , 3 , 18.81 ),
    ( 2 , 4 , 65.56 ),
    ( 2 , 5 , 27.37 ),
    ( 2 , 6 , 15.95 ),
    ( 2 , 7 ,  15.95 ),
    ( 2 , 8 , 84.35 ),
    ( 2 , 9 , 79.46 ),
    ( 2 , 10 , 51.37 ),
    ( 2 , 11 , 32.11 ),
    ( 2 , 12 , 09.63 ),
    ( 2 , 13 , 72.15 ),
    ( 2 , 14 , 15.74 ),
    ( 2 , 15 , 46.25 ),
    ( 2 , 16 , 80.03 ),
    ( 2 , 17 , 28.87 ),
    ( 2 , 18 , 60.54 ),
    ( 2 , 19 , 32.96 ),
    ( 3 , 1 , 54.26 ),
    ( 3 , 2 , 25.36 ),
    ( 3 , 3 , 15.96 ),
    ( 3 , 4 ,  48.25 ),
    ( 3 , 5 ,  36.25 ),
    ( 3 , 6 ,  48.02 ),
    ( 3 , 7 ,  12.05 ),
    ( 3 , 8 ,  08.59 ),
    ( 3 , 9 ,  78.89 ),
    ( 3 , 10 ,  68.89 ),
    ( 3 , 11 , 08.56 ),
    ( 3 , 12 , 53.26 ),
    ( 3 , 13 , 27.47 ),
    ( 3 , 14 , 19.22 ),
    ( 3 , 15 , 42.45 ),
    ( 3 , 16 , 75.01 ),
    ( 3 , 17 , 63.84 ),
    ( 3 , 18 , 12.17 ),
    ( 3 , 19 , 63.14 ),
    ( 4 , 1 , 85.22 ),
    ( 4 , 2 , 33.28 ),
    ( 4 , 3 , 52.15  ),
    ( 4 , 4 , 26.32 ),
    ( 4 , 5 , 24.15 ),
    ( 4 , 6 , 61.79 ),
    ( 4 , 7 , 85.33 ),
    ( 4 , 8 , 42.65 ),
    ( 4 , 9 , 48.59 ),
    ( 4 , 10 , 62.36 ),
    ( 4 , 11 , 39.48 ),
    ( 4 , 12 , 68.15 ),
    ( 4 , 13 , 20.38 ),
    ( 4 , 14 , 45.23 ),
    ( 4 , 15 , 83.49 ),
    ( 4 , 16 , 31.75 ),
    ( 4 , 17 , 57.88 ),
    ( 4 , 18 , 64.93 ),
    ( 4 , 19 , 41.90 ),
    ( 5 , 1 ,  34.56 ),
    ( 5 , 2 ,  12.78 ),
    ( 5 , 3 ,  45.23 ),
    ( 5 , 4 ,  87.39 ),
    ( 5 , 5 ,  23.41 ),
    ( 5 , 6 ,  65.12 ),
    ( 5 , 7 ,  10.56 ),
    ( 5 , 8 ,  54.78 ),
    ( 5 , 9 ,  76.21 ),
    ( 5 , 10 ,  98.45 ),
    ( 5 , 11 ,  74.12 ),
    ( 5 , 12 ,  18.99 ),
    ( 5 , 13 ,  32.48 ),
    ( 5 , 14 ,  59.61 ),
    ( 5 , 15 ,  26.74 ),
    ( 5 , 16 ,  48.17 ),
    ( 5 , 17 ,  89.92 ),
    ( 5 , 18 ,  15.83 ),
    ( 5 , 19 , 07.06 ),
    ( 6 , 1 ,  65.32 ),
    ( 6 , 2 ,  43.78 ),
    ( 6 , 3 ,  87.29 ),
    ( 6 , 4 ,  54.10 ),
    ( 6 , 5 ,  32.46 ),
    ( 6 , 6 ,  76.89 ),
    ( 6 , 7 ,  21.54 ),
    ( 6 , 8 ,  67.43 ),
    ( 6 , 9 ,  43.19 ),
    ( 6 , 10 ,  89.76 ),
    ( 6 , 11 ,  46.32 ),
    ( 6 , 12 ,  19.87 ),
    ( 6 , 13 ,  57.42 ),
    ( 6 , 14 ,  32.91 ),
    ( 6 , 15 ,  78.23 ),
    ( 6 , 16 ,  23.67 ),
    ( 6 , 17 ,  65.89 ),
    ( 6 , 18 ,  12.34 ),
    ( 6 , 19 , 12.14 ),
    ( 7 , 1 ,  54.76 ),
    ( 7 , 2 ,  32.09 ),
    ( 7 , 3 ,  76.43 ),
    ( 7 , 4 ,  20.65 ),
    ( 7 , 5 ,  89.34 ),
    ( 7 , 6 ,  43.98 ),
    ( 7 , 7 ,  65.21 ),
    ( 7 , 8 ,  31.54 ),
    ( 7 , 9 ,  54.78 ),
    ( 7 , 10 ,  78.12 ),
    ( 7 , 11 ,  19.87 ),
    ( 7 , 12 ,  76.43 ),
    ( 7 , 13 ,  32.09 ),
    ( 7 , 14 ,  54.76 ),
    ( 7 , 15 ,  87.32 ),
    ( 7 , 16 ,  43.21 ),
    ( 7 , 17 ,  65.98 ),
    ( 7 , 18 ,  31.65 ),
    ( 8 , 1 ,  87.21 ),
    ( 8 , 2 ,  43.65 ),
    ( 8 , 3 ,  21.98 ),
    ( 8 , 4 ,  54.32 ),
    ( 8 , 5 ,  32.76 ),
    ( 8 , 6 ,  65.09 ),
    ( 8 , 7 ,  31.43 ),
    ( 8 , 8 ,  54.87 ),
    ( 8 , 9 ,  76.54 ),
    ( 8 , 10 ,  20.98 ),
    ( 8 , 11 ,  43.65 ),
    ( 8 , 12 ,  87.21 ),
    ( 8 , 13 ,  32.76 ),
    ( 8 , 14 ,  54.32 ),
    ( 8 , 15 ,  76.09 ),
    ( 8 , 16 ,  21.43 ),
    ( 8 , 17 ,  65.87 ),
    ( 8 , 18 ,  31.54 ),
    ( 8 , 19 , 25.47 ),
    ( 9 , 1 , 43.21 ),
    ( 9 , 2 ,  98.76 ),
    ( 9 , 3 ,  54.32 ),
    ( 9 , 4 ,  32.65 ),
    ( 9 , 5 ,  65.21 ),
    ( 9 , 6 ,  21.43 ),
    ( 9 , 7 ,  76.87 ),
    ( 9 , 8 ,  43.09 ),
    ( 9 , 9,  65.98 ),
    ( 9 , 10,  31.54 ),
    ( 9 , 11,  54.87 ),
    ( 9 , 12,  76.54 ),
    ( 9 , 13,  32.76 ),
    ( 9 , 14,  54.32 ),
    ( 9 , 15,  76.09 ),
    ( 9 , 16,  21.43 ),
    ( 9 , 17,  65.87 ),
    ( 9 , 18,  31.54 ),
    ( 9 , 19 , 50.00 ),
    ( 10 , 1,  65.32 ),
    ( 10 , 2,  43.78 ),
    ( 10 , 3,  87.29 ),
    ( 10 , 4,  54.10 ),
    ( 10 , 5,  32.46 ),
    ( 10 , 6,  76.89 ),
    ( 10 , 7,  21.54 ),
    ( 10 , 8,  67.43 ),
    ( 10 , 9,  43.19 ),
    ( 10 , 10,  89.76 ),
    ( 10 , 11,  46.32 ),
    ( 10 , 12,  19.87 ),
    ( 10 , 13,  57.42 ),
    ( 10 , 14,  32.91 ),
    ( 10 , 15,  78.23 ),
    ( 10 , 16,  23.67 ),
    ( 10 , 17,  65.89 ),
    ( 10 , 18,  12.34 ),
    ( 10 , 19 , 34.43 ),
    ( 11 , 1,  54.76 ),
    ( 11 , 2,  32.09 ),
    ( 11 , 3,  76.43 ),
    ( 11 , 4,  20.65 ),
    ( 11 , 5,  89.34 ),
    ( 11 , 6,  43.98 ),
    ( 11 , 7,  65.21 ),
    ( 11 , 8,  31.54 ),
    ( 11 , 9,  54.78 ),
    ( 11 , 10,  78.12 ),
    ( 11 , 11,  19.87 ),
    ( 11 , 12,  76.43 ),
    ( 11 , 13,  32.09 ),
    ( 11 , 14,  54.76 ),
    ( 11 , 15,  87.32 ),
    ( 11 , 16,  43.21 ),
    ( 11 , 17,  65.98 ),
    ( 11 , 18,  31.65 ),
    ( 11 , 19 , 86.16 ),
    ( 12 , 1,  87.21 ),
    ( 12 , 2,  43.65 ),
    ( 12 , 3,  21.98 ),
    ( 12 , 4,  54.32 ),
    ( 12 , 5,  32.76 ),
    ( 12 , 6,  65.09 ),
    ( 12 , 7,  31.43 ),
    ( 12 , 8,  54.87 ),
    ( 12 , 9 , 21.89 ),
    ( 12 , 10 , 15.36),
    ( 12 , 11 , 47.65),
    ( 12 , 12 , 65.89),
    ( 12 , 13 , 78.36),
    ( 12 , 14 , 52.30),
    ( 12 , 15 , 16.32),
    ( 12 , 16 , 48.44),
    ( 12 , 17 , 75.12),
    ( 12 , 18 , 29.48),
    ( 12 , 19 , 67.49 ),
    ( 13 , 1 , 15.26),
    ( 13 , 2 , 47.25),
    ( 13 , 3 , 86.98),
    ( 13 , 4 , 25.70),
    ( 13 , 5 , 26.78),
    ( 13 , 6 , 08.35),
    ( 13 , 7 , 42.87),
    ( 13 , 8 , 69.32),
    ( 13 , 9 , 16.89),
    ( 13 , 10 , 42.15),
    ( 13 , 11 , 62.32),
    ( 13 , 12 , 58.22),
    ( 13 , 13 , 86.33),
    ( 13 , 14 , 78.25),
    ( 13 , 15 , 59.55),
    ( 13 , 16 , 43.15),
    ( 13 , 17 , 96.54),
    ( 13 , 18 , 72.76),
    ( 13 , 19 , 51.46 )
    ";

    if ($connexion->query($sql) === TRUE) {
        echo '<script type="text/javascript">console.log("Données de la table Recommander ajoutées avec succès");</script>';
    } else {
        echo '<script type="text/javascript">console.log("Erreur lors de l`ajout des données");</script';
    }
}else{
    echo '<script type="text/javascript">console.log("La table n`est pas vide");</script>';
}

///////////////////////////
///   Table Qualifier   ///
///////////////////////////


// Requête pour compter le nombre de lignes dans la table
$resultat = $connexion->query("SELECT COUNT(*) AS total FROM $nomTable_Qualifier");
$row = mysqli_fetch_assoc($resultat);
$totalLignes = $row['total'];

// Vérifier si la table est vide
if ($totalLignes == 0) {
    // La table est vide
    // Requête d'insertion de données dans la table Qualifier
    $sql = "INSERT INTO $nomTable_Qualifier VALUES
    ( 1 , 2 ),
    ( 1 , 3 ),
    ( 2 , 1 ),
    ( 2 , 2 ),
    ( 3 , 9 ),
    ( 3 , 10 ),      
    ( 4 , 8 ),
    ( 4 , 9 ),
    ( 5 , 10 ),
    ( 5 , 1 ),
    ( 6 , 6 ),
    ( 6 , 7 ),
    ( 7 , 8 ),
    ( 7 , 7 ),
    ( 8 , 15 ),
    ( 8 , 6 ),
    ( 9 , 10 ),
    ( 9 , 1 ),
    ( 10 , 2 ),
    ( 10 , 3 ),
    ( 1 , 4 ),
    ( 1 , 8 ),
    ( 2 , 3 ),
    ( 2 , 4 ),
    ( 3 , 11 ),
    ( 3 , 1 ),
    ( 4 , 15 ),
    ( 4 , 14 ),
    ( 5 , 14 ),
    ( 5 , 3 ),
    ( 6 , 8 ),
    ( 6 , 9 ),
    ( 7 , 1 ),
    ( 7 , 3 ),
    ( 8 , 10 ),
    ( 8 , 11 ),
    ( 9 , 4 ),
    ( 9 , 5 ),
    ( 10 , 6 ),
    ( 10 , 7 )
    ";

   if ($connexion->query($sql) === TRUE) {
        echo '<script type="text/javascript">console.log("Données de la table Qualifier ajoutées avec succès");</script>';
    } else {
        echo '<script type="text/javascript">console.log("Erreur lors de l`ajout des données");</script';
    }
}else{
    echo '<script type="text/javascript">console.log("La table n`est pas vide");</script>';
}

///////////////////////////
///   Table Regrouper   ///
///////////////////////////


// Requête pour compter le nombre de lignes dans la table
$resultat = $connexion->query("SELECT COUNT(*) AS total FROM $nomTable_Regrouper");
$row = mysqli_fetch_assoc($resultat);
$totalLignes = $row['total'];

// Vérifier si la table est vide
if ($totalLignes == 0) {
    // La table est vide
    // Requête d'insertion de données dans la table Regrouper
    $sql = "INSERT INTO $nomTable_Regrouper VALUES
    ( 1 , 1 ),
    ( 1 , 2 ),
    ( 1 , 3 ),
    ( 1 , 4 ),
    ( 1 , 5 ),
    ( 2 , 1 ),
    ( 2 , 2 ),
    ( 2 , 3 ),
    ( 2 , 4 ),
    ( 2 , 5 ),
    ( 3 , 1 ),
    ( 3 , 2 ),
    ( 3 , 3 ),
    ( 3 , 4 ),
    ( 3 , 5 ),
    ( 4 , 3 ),
    ( 4 , 5 ),
    ( 5 , 5 ),
    ( 6 , 1 ),
    ( 7 , 1 ),
    ( 7 , 2 ),
    ( 7 , 3 ),
    ( 7 , 4 ),
    ( 7 , 5 ),
    ( 8 , 2 ),
    ( 8 , 5 ),
    ( 9 , 2 ),
    ( 9 , 3 ),
    ( 9 , 5 ),
    ( 10 , 1 ),
    ( 11 , 1 ),
    ( 11 , 2 ),
    ( 11 , 3 ),
    ( 12 , 1 ),
    ( 12 , 2 ),
    ( 12 , 3 ),
    ( 12 , 4 ),
    ( 12 , 5 ),
    ( 13 , 1 ),
    ( 13 , 2 ),
    ( 13 , 3 ),
    ( 13 , 4 ),
    ( 14 , 1 ),
    ( 14 , 2 ),
    ( 15 , 1 ),
    ( 15 , 2 ),
    ( 15 , 3 ),
    ( 15 , 4 ),
    ( 15 , 5 )
    ";

    if ($connexion->query($sql) === TRUE) {
        echo '<script type="text/javascript">console.log("Données de la table Regrouper ajoutées avec succès");</script>';
    } else {
        echo '<script type="text/javascript">console.log("Erreur lors de l`ajout des données");</script';
    }
}else{
    echo '<script type="text/javascript">console.log("La table n`est pas vide");</script>';
}




/* //verif le hash
// Sélectionner tous les enregistrements de la table utilisateur
$sql = "SELECT idUtilisateur, MotDePasse FROM utilisateur";
$result = $connexion->query($sql);

if ($result->num_rows > 0) {
    // Parcourir tous les enregistrements
    while($row = $result->fetch_assoc()) {
        $user_id = $row["idUtilisateur"];
        $hashed_password_from_database = $row["MotDePasse"];

        // Supposons que l'utilisateur entre son mot de passe lors de la connexion
        $user_input_password = 'cestSecret123'; 
        
        // Vérifier si le mot de passe saisi correspond au mot de passe haché
        if (password_verify($user_input_password, $hashed_password_from_database)) {
            echo "Mot de passe correct pour l'utilisateur avec l'ID : " . $user_id . "<br>";
        } else {
            echo "Mot de passe incorrect pour l'utilisateur avec l'ID : " . $user_id . "<br>";
        }
    }
} else {
    echo "0 résultats";
} */











$res = [$nomTable_Associer,$nomTable_Cat,$connexion];

return $res;
?>