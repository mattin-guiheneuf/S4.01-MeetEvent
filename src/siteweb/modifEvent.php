<?php
session_start();
// Vérifiez si l'utilisateur est connecté en vérifiant la présence de ses informations d'identification dans la session
if (isset($_SESSION['user_id'])) {
  $conn = require "../gestionBD/database.php";
  // L'utilisateur est connecté
  $event_id = $_GET['event_id'];
  $query = "SELECT * FROM Evenement WHERE idEvenement = $event_id";

  $result = $conn->query($query);
  $row = $result->fetch_assoc();
  $titre = $row["nom"];
  $date = $row["dateEvent"];
  $heure = $row["heure"];
  $adresseArray = explode(',', $row["adresse"]); // Divise la chaîne en un tableau
  $cp = explode(' ', $adresseArray[1])[1];
  $ville = explode(' ', $adresseArray[1])[2]; // Récupère le dernier élément du tableau (censé être la ville)
  // Si vous voulez séparer les mots de la ville
  //$ville = implode(' ', str_split($ville));  
  $adresse = explode(',', $row["adresse"])[0];
  $typeEvent = $row["statut"];
  $nbMaxPersonne = $row["effMax"];
  $photo = $row["chemImages"];
  $participants = '';
  $message = '';

  $conn->close();
}else{
  // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
  header("Location: connexion.php");
  exit; // Assurez-vous de terminer le script après la redirection
}


?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="CSS/global.css" />
  <link rel="stylesheet" href="CSS/styleCreaEvent.css" />
  <title>ME - Modifier un évènement</title>
</head>

<body>
  <!-- Contenu principal -->
  <div class="hero">
     <div class="contenuFormCrea">
        <div class="titre">
            <h1>Modification de l'évènement</h1>
        </div>
        <div class="obligatoire">
            <h3>* champs obligatoires</h3>
        </div>
        <form action="" method="POST">
            <div class="partGauche">
                <!-- Titre -->
                <div class="inputTitre">
                    <label><b>Titre</b><span class="starOblig">*</span></label><br>
                    <div class="inputs"><input type="text" placeholder="Nom de l'évènement" value="<?php echo $titre?>" required></div>
                </div>
                <!-- Date et Heure -->
                <div class="inputDateHeure">
                    <label><b>Date et heure</b> <span class="starOblig">*</span></label>
                    <div class="inputs">
                        <input type="date" placeholder="Date (jj/mm/aaaa)" value="<?php echo $date?>" required>
                        <input type="time" placeholder="Heure (hh:mm)" value="<?php echo $heure?>" required>
                    </div>
                </div>
                <!-- Lieu -->
                <div class="inputLieu">
                    <label><b>Lieu</b> <span class="starOblig">*</span></label>
                    <div class="inputs">
                        <input type="text" placeholder="Ville" value="<?php echo $ville?>" required>
                        <input type="number" placeholder="Code postale" min="01000" max="100000" step="10" value="<?php echo $cp?>" required><!-- ??? -->
                        <input type="text" placeholder="Adresse" value="<?php echo $adresse?>" required>
                    </div>
                </div>
                <!-- Type d'événement -->
                <div class="inputType">
                    <label><b>Type d'évènement</b> <span class="starOblig">*</span></label>
                    <div class="inputs">
                        <div class="radiobox">
                            <input type="radio" id="typePublic" value="public" name="type" checked>
                            <label for="typePublic">Évènement public (accessible par tous)</label>
                        </div>
                        <div class="radiobox">
                            <?php if($typeEvent==0):?>
                            <input type="radio" id="typePrive" value="prive" name="type" checked>
                            <?php else :?>
                            <input type="radio" id="typePrive" value="prive" name="type">
                            <?php endif;?>
                            <label for="typePrive">Évènement privé</label>
                        </div>
                    </div>
                </div>
                <!-- Nombre de participant -->
                <div class="inputNbParticip">
                    <label><b>Nombre de participants maximum</b></label>
                    <div class="inputs"><input type="number" placeholder="Nombre" min="1" max="100000" step="1" value="<?php echo $nbMaxPersonne?>"></div>
                </div>
            </div>
            <div class="partDroite">
                <!-- Photos -->
                <div class="inputPhotos">
                    <label><b>+ Ajoutez une photo</b></label>
                    <div class="inputs">
                        <input type="file" id="inputPhoto" name="photo" accept="image/png, image/jpeg" onchange="afficherImage(this)">
                    </div>
                    <img id="imagePreview" src="<?php echo $photo ?>" alt="Aperçu de l'image" style="max-width: 200px;max-height: 200px;">
                </div>
                <!-- Participants -->
                <div class="inputParticip">
                    <label><b>Participants</b></label>
                    <div class="inputs"><input type="email" placeholder="participant1@mail.com, participant2@mail.com, ..."></div>
                </div>
                <!-- Message de d'invitation -->
                <div class="inputMsgInvit">
                    <label><b>Message personnalisé d'invitation</b></label>
                    <div class="inputs"><input type="text" placeholder="Hey ! Je t'invite à mon évènement ! ..." value="<?php echo $message?>"></div>
                </div>
                <!-- Btn d'actions -->
                <div class="btnFin">
                    <button class="btnAnnuler" onclick="window.location.href='MesEvent.php'">Annuler</button>
                    <button type="submit" class="btnCreer">Modifier</button>
                </div>
            </div>
        </form>
      </div>
  </div>
</body>
<script>
    function afficherImage(input) {
        var imagePreview = document.getElementById('imagePreview');
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            imagePreview.src = "<?php echo $photo ?>"; // Image par défaut depuis la base de données
            imagePreview.style.display = 'block';
        }
    }
</script>

</html>