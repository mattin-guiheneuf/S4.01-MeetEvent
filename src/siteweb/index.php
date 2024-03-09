<?php
//include_once "../gestionBD/creation_et_peuplement.php";

session_start();
// Vérifiez si l'utilisateur est connecté en vérifiant la présence de ses informations d'identification dans la session
if (isset($_SESSION['user_id'])) {
  $conn = require "../gestionBD/database.php";
  // L'utilisateur est connecté
  $user_id = $_SESSION['user_id'];
  $query = "SELECT nom FROM Utilisateur WHERE idUtilisateur = '$user_id'";

  $result = $conn->query($query);
  $row = $result->fetch_assoc();
  $user_name = $row["nom"];
  $conn->close();
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ME</title>
  <link rel="stylesheet" href="CSS/global.css" />
  <link rel="stylesheet" href="CSS/style.css" />
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.1.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
  <!-- Barre de Navigation -->
  <nav class="navbar sticky-top navbar-expand-lg" style="background-color: #ffffff;padding : 1%;">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="img/MeetEvent_Logo (1).png" alt="Bootstrap" width="50" height="40">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <?php if (isset($_SESSION['user_id'])) : ?>
            <li class="nav-item" style="padding-left: 30px;padding-right: 30px;">
              <a class="nav-link nav-link-transition nav-link_MesEvent" href="pageSuggestion.php" style="color: black;font-size: 4vw;">Rechercher des événements</a>
            </li>
          <?php endif; ?>
        </ul>
        <?php if (!isset($_SESSION['user_id'])) : ?>
          <span class="navbar-text">
            <a class="nav-link nav-link_MesEvent" href="connexion.php" style="color: black;font-size: 5vw;">Se connecter</a>
          </span>
          <span class="navbar-text">
            <i class="fi fi-sr-user" style="color: black;"></i>
          </span>
        <?php else : ?>
          <span class="navbar-text">
            <a class="nav-link nav-link_MesEvent" href="logout.php" style="color: black;font-size: 4vw;"><?php echo $user_name; ?></a>
          </span>
          <span class="navbar-text">
            <i class="fi fi-sr-user" style="font-size: 5vw;color: black;"></i>
          </span>
        <?php endif; ?>

      </div>
    </div>
  </nav>
  <!-- Contenu principal -->
  <div class="hero">
    <!-- 1ere section -->
    <div class="section1">

      <p class="accroche1">
        <span>L’application événementielle qui </span>
        <span style="color: #6040fe;">facilite la création d’événements personnalisés</span>
      </p>

      <p class="facilite-la-cr">
        Facilite la création de <strong>votre événement</strong> qu’il soit public (ouvert à tous) ou privé. <strong>MeetEvent vous accompagne</strong> dans la création mais également dans <strong>la recherche d’événement</strong> à rejoindre !
      </p>
      <div style="display:flex;gap: 10px;align-items: left;align-self: flex-start;margin-left: 10%;">
        <button class="btn-services" onclick="scrollToService()">
          DECOUVREZ NOS SERVICES
        </button>

        <?php if (!isset($_SESSION['user_id'])) : ?>
          <div class="btn-connexion">
            <a class="connexion" href="connexion.php" style="text-decoration:none;color:#6040fe;">JE ME CONNECTE</a>
          </div>
        <?php else : ?>
          <button class="btn-connexion" onclick="window.location.href='pageSuggestion.php'">
            JE ME LANCE
          </button>
        <?php endif; ?>
      </div>
      <div class="images">
        <img class="image_pres" src="img/mobile.svg" alt="image présentation" />
        <img class="image_pres" src="img/laptop.svg" alt="image présentation" />
      </div>
    </div>
    <!-- barre de séparation -->
    <div class="barre-de-separation"></div>

    <!-- 2eme section -->
    <div class="section2">
      <p class="accroche2">
        <span>Gagnez du temps</span>
        <span style="color: #6040fe;"> dans vos recherches </span>
        <span>et optimisez</span>
        <span style="color: #6040fe;"> votre création </span>
        <span>avec l’application événementielle</span>
        <span style="color: #6040fe;">MeetEvent</span>
      </p>
      <hr>
      <!-- Etape 1 -->
      <div class="etape1">
        <div class="rectangle"><img src="img/creer.svg" alt="createEvent" class="image_pres" style="margin-top:25%;"></div>
        <div>
          <div class="titre_etape1">Créer votre événement</div>
          <div class="texte_etape">
            <p>
              <span style="font-weight:bold;">Venez créer</span> votre propre événement personnalisé. <span style="font-weight:bold;">Sa création est facilitée</span> par notre application et cela ne vous prendra que <span style="font-weight:bold;">quelques minutes !</span><br> <span style="font-weight:bold;">Comment faire ?</span>
            </p>
            <div class="etapes">
              <div class="etape" ><span class="ellipse">1</span><span style="font-weight: bold;"> Connectez-vous</span></div>
              <div class="etape"><span class="ellipse">2</span><span style="font-weight: bold;"> Créez votre événement comme vous le souhaitez</span></div>
              <div class="etape"><span class="ellipse">3</span><span style="font-weight: bold;"> Publiez-le</span></div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <!-- Etape 2 -->
      <div class="etape1">
        <div>
          <div class="titre_etape1">Participer à un événement</div>
          <div class="texte_etape">
            <p>
            <span style="font-weight:bold;">Venez participer</span> à des événements qui vous plaisent et dont <span style="font-weight:bold;">vous ne regretterai pas</span> d'y avoir participé. <span style="font-weight:bold;">Lancez vous</span> dans l'averture avec MeetEvent. <br><span style="font-weight:bold;"> Comment faire ?</span>
            </p>
            <div class="etapes">
              <div class="etape"><span class="ellipse">1</span><span style="font-weight: bold;"> Connectez-vous</span></div>
              <div class="etape"><span class="ellipse">2</span><span style="font-weight: bold;"> Recherchez un événement qui vous correspond</span></div>
              <div class="etape"><span class="ellipse">3</span><span style="font-weight: bold;"> Rejoignez-le</span></div>
            </div>
          </div>
        </div>
        <div class="rectangle"><img src="img/participer.svg" alt="createEvent" class="image_pres" style="margin-top:25%;"></div>
      </div>
    </div>

  </div>
  <footer class="event-footer">
        <!-- Barre supérieure -->
        <div class="footer-top">
            
              <div class="item">
                <i class="fas fa-map-marker-alt" ></i>
                <div class="text_item">
                  <p style="font-weight:bold;">Adresse</p>
                  <p>3 rue de Cassou, 64600 Anglet</p>
                </div>
                
              </div>
              <div class="item">
                <i class="fas fa-envelope" ></i>
                <div class="text_item">
                  <p style="font-weight:bold;">Email</p>
                  <p>contact@meetevent.com</p>
                </div>
                
              </div>
              <div class="item">
                <i class="fas fa-phone-alt" ></i>
                <div class="text_item">
                  <p style="font-weight:bold;">Téléphone</p>
                  <p>06 20 01 69 80</p>
                </div>  
              </div>
            
        </div>

        <!-- Contenu principal -->
        <div class="container_footer">
            <div style="display:flex;justify-content:space-evenly;align-items:center;">
              <img src="img/MeetEvent_Logo_blanc.png" alt="logo" width="auto" height="100w">
              <div class="footer-section navigation">
                  <h2 style="font-weight:bold;color:#ffffff">Navigation</h2>
                  <ul style="color:#ababab">
                      <li>Page d'accueil</li>
                      <li>Page de recherche d'événement</li>
                      <li>Page contact</li>
                  </ul>
              </div>
              <div class="social-icons">
                  <!-- Lien vers Facebook -->
                  <a href="https://www.facebook.com/" target="_blank"><i class="fab fa-facebook"></i></a>

                  <!-- Lien vers Instagram -->
                  <a href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a>

                  <!-- Lien vers Twitter -->
                  <a href="https://twitter.com/" target="_blank"><i class="fab fa-twitter"></i></a>
              </div>
            </div>
            <hr width="80%" style="margin-left:10%;color:#ffffff">
            <div class="newsletter" style="display:flex;justify-content:space-between;padding:2vw 6vw;align-items:center;">
                <div style="font-size:1.6vw;font-weight:bold;text-align:center;white-space: nowrap;color:#ffffff">ABONNEZ-VOUS A NOTRE <br> <span style="color:#6040fe;">NEWSLETTER</span> </div>
                <div style="padding-left:6vw;">
                  <form>
                      <input type="email" placeholder="Votre adresse email">
                      <button type="submit">S'abonner</button>
                  </form>
                  <p style="color:#ababab;font-size:1vw;">Les données que vous nous fournissez sont traitées par MeetEvent aux fins de vous envoyer des offres commerciales et des informations sur nos mise à jours (pour modifier et ne recevoir que un des deux rendez vous sur Gérer mes consentements). Pour en savoir plus sur le traitement de vos données et vos droits, consultez notre Politique de confidentialité. Vous pouvez vous désinscrire à tout moment à l’aide des liens de désinscription.</p>
                </div>
            </div>
            <hr width="80%" style="margin-left:10%;color:#ffffff">
            <div class="section_infos" style="color:#ffffff">
              <div>POLITIQUE DE CONFIDENTIALITE</div>
              <div>|</div>
              <div>MENTIONS LEGALES</div>
              <div>|</div>
              <div>CONDITIONS GENERALES</div>
              <div>|</div>
              <div>GERER MES CONSENTEMENTS</div>
              <div>|</div>
              <div>CONFIDENTIALITE DES DONNEES</div>
            </div>
        </div>

        <!-- Footer bottom -->
        <div class="footer-bottom">
            &copy; 2024 MeetEvent. Tous droits réservés.
        </div>
      </footer>
</body>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var navbarToggler = document.querySelector('.navbar-toggler');
    var navbarCollapse = document.querySelector('.navbar-collapse');

    navbarToggler.addEventListener('click', function() {
      navbarCollapse.classList.toggle('show');
      document.body.classList.toggle('fullscreen-nav-active');
    });

    // Ajouter un événement de clic pour fermer le menu en plein écran
    var closeMenuButton = document.createElement('span');
    closeMenuButton.classList.add('close-menu');
    closeMenuButton.innerHTML = '&times;';
    navbarCollapse.appendChild(closeMenuButton);
    closeMenuButton.addEventListener('click', function() {
      navbarCollapse.classList.remove('show');
      document.body.classList.remove('fullscreen-nav-active');
    });
  });
</script>
<!-- Script JavaScript pour le défilement vers l'ancre -->
<script>
        // Fonction pour faire défiler vers une ancre spécifique
        function scrollToService() {
            var anchor = document.getElementsByClassName("barre-de-separation")[0];
            if (anchor) {
                anchor.scrollIntoView({
                    behavior: 'smooth' // Faites défiler en douceur
                });
            }
        }
    </script>

</html>