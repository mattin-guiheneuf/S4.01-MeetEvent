<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="CSS/global.css" />
  <link rel="stylesheet" href="CSS/styleCreaEvent.css" />
  <title>ME - Créer un évènement</title>
</head>

<body>
  <!-- Contenu principal -->
  <div class="hero">
     <div class="contenuFormCrea">
        <div class="titre">
            <h1>Création d'un évènement</h1>
        </div>
        <div class="obligatoire">
            <h3>* champs obligatoires</h3>
        </div>
        <form method="POST" action="algorithme/index.php" enctype="multipart/form-data">
            <input type="hidden" name="event" value=1>
            <div class="partGauche">
                <!-- Titre -->
                <div class="inputTitre">
                    <label for="titre"><b>Titre</b><span class="starOblig">*</span></label><br>
                    <div class="inputs"><input type="text" name="titre" id="titre" placeholder="Nom de l'évènement" required></div>
                </div>
                <!-- Date et Heure -->
                <div class="inputDateHeure">
                    <label for="date"><b>Date et heure</b> <span class="starOblig">*</span></label>
                    <div class="inputs">
                        <input type="date" name="date" id="date" placeholder="Date (jj/mm/aaaa)" required>
                        <input type="time" name="heure" id="heure" placeholder="Heure (hh:mm)" required>
                    </div>
                </div>
                <!-- Lieu -->
                <div class="inputLieu">
                    <label for="ville"><b>Lieu</b> <span class="starOblig">*</span></label>
                    <div class="inputs">
                        <input type="text" name="ville" id="ville" placeholder="Ville">
                        <input type="number" name="cp" id="cp" placeholder="Code postale" min="01000" max="100000" step="10"><!-- ??? -->
                        <input type="text" name="adresse" id="adresse" placeholder="Adresse">
                    </div>
                </div>
                <!-- Type d'événement -->
                <div class="inputType">
                    <label><b>Type d'évènement</b> <span class="starOblig">*</span></label>
                    <div class="inputs">
                        <div class="radiobox">
                            <input type="radio" id="typePublic" value="public" name="type">
                            <label for="typePublic">Évènement public (accessible par tous)</label>
                        </div>
                        <div class="radiobox">
                            <input type="radio" id="typePrive" value="prive" name="type">
                            <label for="typePrive">Évènement privé</label>
                        </div>
                    </div>
                </div>
                <!-- Nombre de participant -->
                <div class="inputNbParticip">
                    <label for="nbParticip"><b>Nombre de participants maximum</b></label>
                    <div class="inputs"><input type="number" name="nbParticip" id="nbParticip" placeholder="Nombre" min="1" max="100000" step="1"></div>
                </div>
            </div>
            <div class="partDroite">
                <!-- Photos -->
                <div class="inputPhotos">
                    <label for="photo"><b>+ Ajoutez une photo</b></label>
                    <div class="inputs"><input name="photo" id="photo" type="file" accept="image/png, image/jpeg"></div>
                </div>
                <!-- Participants -->
                <div class="inputParticip">
                    <label for="participants"><b>Participants</b></label>
                    <div class="inputs"><input type="email" name="participants" id="participants" placeholder="participant1@mail.com, participant2@mail.com, ..."></div>
                </div>
                <!-- Message de d'invitation -->
                <div class="inputMsgInvit">
                    <label for="mess_invit"><b>Message personnalisé d'invitation</b></label>
                    <div class="inputs"><input type="text" name="mess_invit" id="mess_invit" placeholder="Salut, je t'invite à mon évènement !"></div>
                </div>
                <!-- Btn d'actions -->
                <div class="btnFin">
                    <button class="btnAnnuler" onclick="window.location.href='MesEvent.php'">Annuler</button>
                    <button type="submit" class="btnCreer">Créer</button>
                </div>
            </div>
        </form>
      </div>
  </div>
</body>

</html>