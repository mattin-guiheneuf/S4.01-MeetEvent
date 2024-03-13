<?php session_start(); 
// Vérifiez si l'utilisateur est connecté en vérifiant la présence de ses informations d'identification dans la session
if (!isset($_SESSION['user_id'])) {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: ../connexion.php");
    exit; // Assurez-vous de terminer le script après la redirection
}
echo $_SESSION["user_id"];

if(!isset($_POST['event'])){
    $isEvent = false;
}else{
    $isEvent = true;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test PHP</title>

    <!--
        Nom du fichier : index.html
        Description : Cette page contient un formulaire de similation d'utilisateur connecté.
        Auteur : Duvignau Yannis
        Date de création : 17 décembre 2023
        Dernière mise  jour : 17 décembre 2023
        Copyright (c) 2023, MeetEvent
    -->

    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            color: #333;
        }

        form {
            margin: 20px;
            display: inline-block;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            margin: 50px;
        }

        button:hover {
            background-color: #45a049;
        }

        h2 {
            color: #45a049;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }
    </style>
</head>

<body>
    <!-- <h1>Tester l'intégration de l'agorithme</h1> -->
    <hr>
    <!-- <h2>Simuler la suggestion d'événement</h2> -->
    <!-- Formulaire avec champ pour saisir l'ID de l'utilisateur -->
    <!-- <form action="main.php" method="post">
        <label for="idUser">ID de l'utilisateur :</label>
        <input type="text" id="idUser" name="idUser" required>
        <button type="submit" name="action" value="afficherRecommandations">Afficher les événements recommandés</button>
    </form> -->
    <!-- <hr> -->
    <?php if(!$isEvent):?>
    <h2>Ajouter la description de l'utilisateur</h2>
    <!-- Formulaire avec champ pour saisir l'inscription d'un utilisateur -->
    <form action="CreationTag.php" method="post">
    
        <label for="mot">Mot :</label>
        <input type="text" id="mot" name="mot">
        <button type="button" onclick="ajouterMot()">Ajouter le mot à la liste</button>
        <div id="listeMots"></div>
    
        <input type="hidden" id="motsListeInput" name="motsListe" value="">
    
        <button type="submit" name="action" value="ajouterDescriptionUtilisateur">Ajouter la description</button>
    </form>
    <!-- <hr> -->
    <?php else:?>
    <h2>Créer un Evenement</h2>
    <!-- Formulaire avec champ pour saisir l'inscription d'un utilisateur -->
    <?php 
    echo `<form action="CreationTag.php" method="post">
        <input type="hidden" id="titre" name="titre" value="`.$_POST["titre"].`">
        <input type="hidden" id="date" name="date" value="`.$_POST["date"].`>
        <input type="hidden" id="heure" name="heure" value="`.$_POST["heure"].`>
        <input type="hidden" id="ville" name="ville" value="`.$_POST["ville"].`>
        <input type="hidden" id="cp" name="cp" value="`.$_POST["cp"].`>
        <input type="hidden" id="adresse" name="adresse" value="`.$_POST["adresse"].`>
        
        <label for="motEvenement">Mot :</label>
        <input type="text" id="motEvenement" name="motEvenement">
        <button type="button" onclick="ajouterMotEvenement()">Ajouter</button>
        <div id="listeMotsEvenement"></div>

            <input type="hidden" id="motsListeEvenementInput" name="motsListeEvenement" value="">

        <button type="submit" name="action" value="creerEvenement">Création d'un événement</button>
    </form>`;
    ?>
    <?php endif;?>

	<?php 
        // Récupération du dicoMotsFr pour la saisieVerif des mots
        $jsonDicoMotsFr = file_get_contents('./data/motsFr.json');
        $dicoMotsFr = json_decode($jsonDicoMotsFr, true);
    ?>
    <script>
        var motsListe = [];
        var motsListeEvenement = [];
        var listeMotsFr = <?php echo $jsonDicoMotsFr; ?>;

        function saisieVerif(mot){
            return listeMotsFr.indexOf(mot) != -1; // mot in listeMotsFr
        }

        function ajouterMot() {
            var motSaisi = document.getElementById('mot').value.trim().toLowerCase();
            if(saisieVerif(motSaisi)){
                if (motSaisi !== '') {
                    motsListe.push(motSaisi);
                    afficherListeMots();
                    document.getElementById('mot').value = ''; // Efface le champ après ajout
                } else {
                    alert('Veuillez saisir un mot.');
                }
            }
            else {
                alert("Mot invalide, veuillez saisir un autre mot...");
            }
        }

        function afficherListeMots() {
            var listeMotsDiv = document.getElementById('listeMots');
            listeMotsDiv.innerHTML = '<p><strong>Liste de mots ajoutés :</strong></p>';
            for (var i = 0; i < motsListe.length; i++) {
                listeMotsDiv.innerHTML += '<p>' + motsListe[i] + '</p>';
            }

            // Mettre à jour la valeur de l'input caché avec la liste de mots
            document.getElementById("motsListeInput").value = JSON.stringify(motsListe);
    
        }

        function ajouterMotEvenement() {
            var motSaisi = document.getElementById('motEvenement').value.trim();
			if(saisieVerif(motSaisi)){
				if (motSaisi !== '') {
					motsListeEvenement.push(motSaisi);
					afficherListeMotsEvenement();
					document.getElementById('motEvenement').value = ''; 
				} else {
					alert('Veuillez saisir un mot.');
				}
			}
			else {
				alert("Mot invalide, veuillez saisir un autre mot...");
            }
        }

        function afficherListeMotsEvenement() {
            var listeMotsDiv = document.getElementById('listeMotsEvenement');
            listeMotsDiv.innerHTML = '<p><strong>Liste de mots ajoutés :</strong></p>';
            for (var i = 0; i < motsListeEvenement.length; i++) {
                listeMotsDiv.innerHTML += '<p>' + motsListeEvenement[i] + '</p>';
            }

            document.getElementById('motsListeEvenementInput').value = JSON.stringify(motsListeEvenement);
        }
    </script>

</body>

</html>