<?php


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
</head>

<body>
  <!-- Barre de Navigation -->
  <nav class="navbar sticky-top navbar-expand-lg" style="background-color: #ffffffe1;padding : 1%;">
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
              <a class="nav-link nav-link-transition nav-link_MesEvent" href="pageSuggestion.php" style="color: black;font-size: 18px;">Rechercher des événements</a>
            </li>
          <?php endif; ?>
        </ul>
        <?php if (!isset($_SESSION['user_id'])) : ?>
          <span class="navbar-text">
            <a class="nav-link nav-link_MesEvent" href="connexion.php" style="color: black;font-size: 18px;">Se connecter</a>
          </span>
          <span class="navbar-text">
            <i class="fi fi-sr-user" style="font-size: 28px;color: black;"></i>
          </span>
        <?php else : ?>
          <span class="navbar-text">
            <a class="nav-link nav-link_MesEvent" href="#" style="color: black;font-size: 18px;"><?php echo $user_name; ?></a>
          </span>
          <span class="navbar-text">
            <i class="fi fi-sr-user" style="font-size: 28px;color: black;"></i>
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
                  <span style="color: #6040fe;">facilite la création d’événements personnalisée</span>
              </p>
 
              <p class="facilite-la-cr">
                  Facilite la création de <strong>votre événement</strong> qu’il soit public (ouvert à tous) ou privé. <strong>MeetEvent vous accompagne</strong> dans la création mais également dans <strong>la recherche d’événement</strong> à rejoindre !
              </p>
              <div style="display:flex;gap: 10px;align-items: left;align-self: flex-start;padding-left: 10%;">
                  <div class="btn-services">
                      <a class="services" href="#">DECOUVREZ NOS SERVICES</a>
                  </div>
                  
                  <div class="btn-connexion">
                      <a class="connexion" href="connexion.php">JE ME CONNECTE</a>
                  </div>
              </div>    
              <img class="image_pres" alt="image présentation"/>
          </div>
          <!-- barre de séparation -->
          <div class="barre-de-separation"></div>

    <!-- 2eme section -->
    <div class="section2">
      <p class="accroche2">
        <span>Gagnez du temps</span>
        <span style="color: #6040fe;"> dans vos recherches et <br /></span>
        <span>optimisez</span>
        <span style="color: #6040fe;"> votre création avec l’application événementielle MeetEvent</span>
      </p>
      <!-- Etape 1 -->
      <div class="etape1">
        <div class="rectangle"></div>
        <div>
          <div class="titre_etape1">Créer votre événement</div>
          <div style="display:flex;flex-direction: column; gap: 40px;padding: 20px;">
            <p>
              Lorem iefjo zifevjdnice, ozefdjzoe aifqndvcosd iedjcnkc zeicnz,osc eaozfnq,s lsrjvnkzl,ds zoedvclkz
              zefdc,s osjdkvnesd zoerpgkvoz,d ozejdvnczlcd zeofcs dczodkczdoc, zefdj.
            </p>
            <div class="etapes">
              <div><span class="ellipse">1</span><span style="font-weight: bold;"> Connectez-vous</span></div>
              <div><span class="ellipse">2</span><span style="font-weight: bold;"> Créez votre événement comme vous le souhaitez</span></div>
              <div><span class="ellipse">3</span><span style="font-weight: bold;"> Publiez-le</span></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Etape 2 -->
      <div class="etape1">
        <div>
          <div class="titre_etape1">Participer à un événement</div>
          <div style="display:flex;flex-direction: column; gap: 40px;padding: 20px;">
            <p>
              Lorem iefjo zifevjdnice, ozefdjzoe aifqndvcosd iedjcnkc zeicnz,osc eaozfnq,s lsrjvnkzl,ds zoedvclkz
              zefdc,s osjdkvnesd zoerpgkvoz,d ozejdvnczlcd zeofcs dczodkczdoc, zefdj.
            </p>
            <div class="etapes">
              <div><span class="ellipse">1</span><span style="font-weight: bold;"> Connectez-vous</span></div>
              <div><span class="ellipse">2</span><span style="font-weight: bold;"> Recherchez un événement qui vous correspond</span></div>
              <div><span class="ellipse">3</span><span style="font-weight: bold;"> Rejoignez-le</span></div>
            </div>
          </div>
        </div>
        <div class="rectangle"></div>
      </div>
    </div>

  </div>
  <footer class="event-footer">
    <div class="footer-top">
      <div class="adresse">
        <div></div>
        <div></div>
        <div></div>
      </div>
      <div class="email">
        <div></div>
        <div></div>
        <div></div>
      </div>
      <div class="tel">
        <div></div>
        <div></div>
        <div></div>
      </div>
    </div>
    <div class="container_footer">
      <div class="footer-section logo">
        <img src="img/MeetEvent_Logo_blanc.png" alt="logo" width="20%" height="auto">
      </div>
      <div class="footer-section navigation">
        <h2>Navigation</h2>
        <ul>
          <li>Page d'accueil</li>
          <li>Page de recherche d'événement</li>
          <li>Page contact</li>
        </ul>
      </div>
      <div class="footer-section aPropos">
        <h2>A Propos</h2>
      </div>
    </div>
    <div class="footer-bottom">
      &copy; 2024 MeetEvent. Tous droits réservés.
    </div>
  </footer>
</body>

</html>