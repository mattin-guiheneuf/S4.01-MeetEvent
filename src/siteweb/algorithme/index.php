<?php session_start(); 
// Vérifiez si l'utilisateur est connecté en vérifiant la présence de ses informations d'identification dans la session
if (!isset($_SESSION['user_id'])) {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: ../connexion.php");
    exit; // Assurez-vous de terminer le script après la redirection
}
//echo $_SESSION["user_id"];

if(!isset($_POST['event'])){
    $isEvent = false;
}else{
    $isEvent = true;

    // Vérifiez si une image a été téléchargée
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        // Chemin où vous souhaitez enregistrer le fichier téléchargé
        $uploadDirectory = '../img/';

        // Chemin complet du fichier téléchargé
        $uploadFilePath = $uploadDirectory . basename($_FILES['photo']['name']);

        // Déplacez le fichier téléchargé vers le répertoire souhaité
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFilePath)) {
            echo "<script>console.log('Le fichier a été téléchargé avec succès.')</script>";
        } else {
            echo "<script>console.log('Une erreur s'est produite lors du téléchargement du fichier.')</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Recommandation</title>

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
            display: flex;
            align-items: center;
            flex-direction: column;
            justify-content: center;
            height: 100vh;
        }

        h1 {
            color: #333;
        }

        form {
            width: 100%;
            display: inline-block;
        }

        button {
            background-color: #6040fe;
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            margin: 50px;
        }

        button:hover {
            background-color: #4d34cc;
        }

        h2 {
            color: #6040fe;
            margin-top: 0;
            margin-bottom: 0;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            /* width: 100%; */
            padding: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }
        /* Ajout de styles pour les éléments désactivés */
        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        /* Style pour les boutons de suppression */
        .btn-delete {
            background-color: #f00;
            color: #fff;
            border: none;
            border-radius: 50%;
            padding: 4px 8px;
            font-size: 12px;
            cursor: pointer;
        }
        #listeMots, #listeMotsEvenement{
            display: flex;
            flex-wrap: wrap;
            padding-left: 80px;
            padding-right: 80px;
            column-gap: 50px;
            justify-content: center;
        }
        #listeMots button,#listeMotsEvenement button{
            margin: 10px 20px;
        }
        #listeMots button:hover,#listeMotsEvenement button:hover{
            background-color:#b72e2e;
        }
        h3{
            font-size: 15px;
            color: #8d8d8d;
            margin-bottom: 4%;
            
        }
    </style>
</head>

<body>
    <!-- <h1>Tester l'intégration de l'agorithme</h1> -->
    <!-- <hr> -->
    <!-- <h2>Simuler la suggestion d'événement</h2> -->
    <!-- Formulaire avec champ pour saisir l'ID de l'utilisateur -->
    <!-- <form action="main.php" method="post">
        <label for="idUser">ID de l'utilisateur :</label>
        <input type="text" id="idUser" name="idUser" required>
        <button type="submit" name="action" value="afficherRecommandations">Afficher les événements recommandés</button>
    </form> -->
    <!-- <hr> -->
    <?php if(!$isEvent):?>
    <h2>Choisis tes centres d'intérêts</h2>
    <h3>Obtient de meilleures Recommandations</h3>
    <!-- Formulaire avec champ pour saisir l'inscription d'un utilisateur -->
    <form action="CreationTag.php" method="post">
        <label for="mot">Mot :</label>
        <input type="text" id="mot" name="mot" list="tagsRecherches" oninput="rechercherMots()" onkeypress="ajouterMotOnEnter(event)">
        <datalist id="tagsRecherches"></datalist>

        <!-- Suppression du bouton "Ajouter le mot à la liste" -->
        <div id="listeMots"></div>

        <!-- Ajout d'un champ caché pour stocker les mots -->
        <input type="hidden" id="motsListeInput" name="motsListe" value="">

        <!-- Ajout de l'attribut "disabled" sur le bouton de soumission -->
        <button type="submit" name="action" value="ajouterDescriptionUtilisateur" id="submitBtn" disabled>Ajouter la description</button>
    </form>
    <!-- <hr> -->
    <?php else:?>
    <h2>Qualifie ton événement</h2>
    <h3>Aide nous à recommander ton événement</h3>
    <!-- Formulaire avec champ pour saisir l'inscription d'un utilisateur -->
    <form action="CreationTag.php" method="post">
        <input type="hidden" id="titre" name="titre" value="<?php echo $_POST["titre"]; ?>">
        <input type="hidden" id="date" name="date" value="<?php echo $_POST["date"]; ?>">
        <input type="hidden" id="heure" name="heure" value="<?php echo $_POST["heure"]; ?>">
        <input type="hidden" id="ville" name="ville" value="<?php echo $_POST["ville"]; ?>">
        <input type="hidden" id="cp" name="cp" value="<?php echo $_POST["cp"]; ?>">
        <input type="hidden" id="adresse" name="adresse" value="<?php echo $_POST["adresse"]; ?>">
        <input type="hidden" id="type" name="type" value="<?php echo $_POST["type"]; ?>">
        <input type="hidden" id="nbParticip" name="nbParticip" value="<?php echo $_POST["nbParticip"]; ?>">
        <input type="hidden" id="photo" name="photo" value="<?php echo $_FILES['photo']['name'] ?>">
        <input type="hidden" id="participants" name="participants" value="<?php echo $_POST["participants"]; ?>">
        <input type="hidden" id="mess_invit" name="mess_invit" value="<?php echo $_POST["mess_invit"]; ?>">
        
        <label for="motEvenement">Mot :</label>
        <input type="text" id="motEvenement" name="motEvenement" oninput="rechercherMots()" onkeypress="ajouterMotEvenementOnEnter(event)">
        <div id="listeMotsEvenement"></div>
        <input type="hidden" id="motsListeEvenementInput" name="motsListeEvenement" value="">
        <button type="submit" name="action" value="creerEvenement" id="submitBtn" disabled>Création d'un événement</button>
    </form>
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

        function rechercherMots() {
            var lettre = document.getElementById('mot').value;
            // Requête AJAX vers le serveur pour récupérer les événements correspondants à la recherche
            $.ajax({
                type: 'POST',
                url: 'get_Tags.php',
                dataType: 'json',
                data: {
                    lettre: lettre
                },
                success: function(response) {
                    afficherOptions(response);
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors de la requête AJAX:', error);
                }
            });
        }

        function afficherOptions(mots) {
            var optionsDatalist = document.getElementById('tagsRecherches');
            optionsDatalist.innerHTML = ''; 
            mots.forEach(function(mot) {
                var option = document.createElement('option');
                option.value = mot.libelle;
                optionsDatalist.appendChild(option);
            });
        }
    </script>
<script>
        

        function ajouterMotOnEnter(event) {
            // Vérifier si la touche appuyée est "Entrée"
            if (event.keyCode === 13) {
                event.preventDefault(); // Empêcher le comportement par défaut de la touche "Entrée"

                // Récupérer la valeur du champ de saisie de mot
                var motSaisi = document.getElementById('mot').value.trim().toLowerCase();

                // Vérifier si le champ est vide ou si le mot est invalide
                if (motSaisi === '' || !saisieVerif(motSaisi)) {
                    alert('Veuillez saisir un mot valide.');
                    return;
                }

                // Ajouter le mot à la liste
                motsListe.push(motSaisi);

                // Afficher la liste de mots
                afficherListeMotsUser();

                // Effacer le champ après ajout
                document.getElementById('mot').value = '';

                // Activer le bouton de soumission s'il y a des mots dans la liste
                if (motsListe.length > 0) {
                    document.getElementById('submitBtn').disabled = false;
                }
            }
        }

        function supprimerMot(index) {
            motsListe.splice(index, 1); // Supprimer le mot du tableau
            afficherListeMotsUser(); // Mettre à jour l'affichage
        }

        function afficherListeMotsUser() {
            var listeMotsDiv = document.getElementById('listeMots');
            listeMotsDiv.innerHTML = ''; // Effacer le contenu existant

            for (var i = 0; i < motsListe.length; i++) {
                var motDiv = document.createElement('div');
                motDiv.textContent = motsListe[i];

                // Bouton de suppression
                var btnDelete = document.createElement('button');
                btnDelete.textContent = '×'; // Afficher une croix
                btnDelete.classList.add('btn-delete'); // Ajouter une classe CSS
                btnDelete.setAttribute('onclick', 'supprimerMot(' + i + ')'); // Appeler la fonction de suppression

                motDiv.appendChild(btnDelete); // Ajouter le bouton à côté du mot

                listeMotsDiv.appendChild(motDiv); // Ajouter le mot à la liste
            }

            // Mettre à jour la valeur de l'input caché avec la liste de mots
            document.getElementById("motsListeInput").value = JSON.stringify(motsListe);

            // Désactiver le bouton de soumission si aucun mot n'est saisi
            if (motsListe.length === 0) {
                document.getElementById('submitBtn').disabled = true;
            }
        }
        /* Pour un événement */
        function ajouterMotEvenementOnEnter(event) {
            if (event.keyCode === 13) {
                event.preventDefault(); // Empêcher le comportement par défaut de la touche "Entrée"
                var motSaisi = document.getElementById('motEvenement').value.trim();
                if (motSaisi === '' || !saisieVerif(motSaisi)) {
                    alert('Veuillez saisir un mot valide.');
                    return;
                }
                motsListeEvenement.push(motSaisi);
                afficherListeMotsEvent();
                document.getElementById('motEvenement').value = ''; // Effacer le champ après ajout

                // Activer le bouton de soumission s'il y a des mots dans la liste
                if (motsListeEvenement.length > 0) {
                    document.getElementById('submitBtn').disabled = false;
                }
            }
        }

        function supprimerMotEvenement(index) {
            motsListeEvenement.splice(index, 1); // Supprimer le mot du tableau
            afficherListeMotsEvent(); // Mettre à jour l'affichage
        }

        function afficherListeMotsEvent() {
            var listeMotsDiv = document.getElementById('listeMotsEvenement');
            listeMotsDiv.innerHTML = '';
            for (var i = 0; i < motsListeEvenement.length; i++) {
                var motDiv = document.createElement('div');
                motDiv.textContent = motsListeEvenement[i];

                // Bouton de suppression
                var btnDelete = document.createElement('button');
                btnDelete.textContent = '×'; // Afficher une croix
                btnDelete.classList.add('btn-delete'); // Ajouter une classe CSS
                btnDelete.setAttribute('onclick', 'supprimerMotEvenement(' + i + ')'); // Appeler la fonction de suppression

                motDiv.appendChild(btnDelete); // Ajouter le bouton à côté du mot

                listeMotsDiv.appendChild(motDiv); // Ajouter le mot à la liste
            }
            document.getElementById('motsListeEvenementInput').value = JSON.stringify(motsListeEvenement);
            // Désactiver le bouton de soumission si aucun mot n'est saisi
            if (motsListe.length === 0) {
                document.getElementById('submitBtn').disabled = true;
            }
        }
    </script>
</body>

</html>