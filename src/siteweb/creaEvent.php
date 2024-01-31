<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="CSS/global.css" />
    <link rel="stylesheet" href="CSS/styleCreaEvent.css" />
	<title>ME - Créer un évènement</title>
  </head>
  <body>
    <!-- Barre de Navigation -->
    <div class="nav-bar">
      <div class="logo-mtvnt">LOGO</div>
      <div class="contact" href="#">Contact</div>
      <i class="connexion" style="font-size: 28px;"></i>
    </div>
    <!-- Contenu principal -->
    <div class="hero">
        <div class="contenuFormCrea">
          <div class="titre">
            <h1>Création d'un évènement</h1>
          </div>
          <div class="obligatoire">
            <h3>* champs obligatoires</h3>
          </div>
          <div class="formulaire">
            <div class="gauche">
              <div class="inputTitre">
                <label style="grid-row: 1;"><b>Titre</b> <span class="starOblig">*</span></label>
                <input style="grid-row: 2;" type="text" placeholder="Nom de l'évènement">
              </div>
              <div class="inputDateHeure">
                <label style="grid-row: 1; grid-column: 1/-1;"><b>Date et heure</b> <span class="starOblig">*</span></label>
                <input style="grid-row: 2; grid-column: 1;" type="date" placeholder="Date (jj/mm/aaaa)">
                <input style="grid-row: 2; grid-column: 2;" type="time" placeholder="Heure (hh:mm)">
              </div>
              <div class="inputLieu">
                <label style="grid-row: 1; grid-column: 1/-1;"><b>Lieu</b> <span class="starOblig">*</span></label>
                <input style="grid-row: 2; grid-column: 1; padding-left: 20%;" type="text" placeholder="Ville">
                <input style="grid-row: 2; grid-column: 2; padding-left: 20%;" type="number" placeholder="Code postale" min="01000" max="100000" step="10"><!-- ??? -->
                <input style="grid-row: 3; grid-column: 1/-1;" type="text" placeholder="Adresse">
              </div>
              <div class="inputType">
                <label style="grid-row: 1; grid-column: 1/-1;"><b>Type d'évènement</b> <span class="starOblig">*</span></label>
                <div class="radiobox">
                  <input style="grid-row: 1/4; grid-column: 1;" type="radio" id="typePublic" value="public" name="type">
                  <label style="grid-row: 2; grid-column: 2;" for="typePublic">Évènement public (accessible par tous)</label>
                  <input style="grid-row: 5/8; grid-column: 1;" type="radio" id="typePrive" value="prive" name="type">
                  <label style="grid-row: 6; grid-column: 2;" for="typePrive">Évènement privé</label>
                </div>
              </div>
              <div class="inputNbParticip">
                <label style="grid-row: 2; grid-column: 1;"><b>Nombre de participants maximum</b></label>
                <input style="grid-row: 1/-1; grid-column: 2;" type="number" placeholder="Nombre" min="1" max="100000" step="1">
              </div>
            </div>
            <div class="separateur"></div>
            <div class="droite">
              <div class="inputPhotos">
                <label style="grid-row: 2; grid-column: 2; text-align: center;"><b>+ Ajouter jusqu'à 3 photos</b></label>
                <input type="file" accept="image/png, image/jpeg">
              </div>
              <div class="inputParticip">
                <label style="grid-row: 1;"><b>Participants</b></label>
                <input style="grid-row: 2;" type="email" placeholder="participant1@mail.com, participant2@mail.com, ...">
              </div>
              <div class="inputMsgInvit">
                <label style="grid-row: 1;"><b>Message personnalisé d'invitation</b></label>
                <input style="grid-row: 2;" type="text" placeholder="Hey ! Je t'invite à mon évènement ! ...">
              </div>
              <div class="btnFin">
                <button class="btnAnnuler">Annuler</button>
                <button class="btnCreer">Créer</button>
              </div>
            </div>
          </div>
      </div>
    </div>
    <!-- <footer> -->
      <!-- Le footer -->
    <!-- </footer> -->
  </body>
</html>