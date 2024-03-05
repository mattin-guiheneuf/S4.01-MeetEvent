<?php 
session_start();
// Vérifiez si l'utilisateur est connecté en vérifiant la présence de ses informations d'identification dans la session
if (!isset($_SESSION['user_id'])) {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: connexion.php");
    exit; // Assurez-vous de terminer le script après la redirection
}

?>

<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ME</title>
        <link rel="stylesheet" href="CSS/global.css" />
        <link rel="stylesheet" href="CSS/style5.css" />
        <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.1.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body>
        <!-- Barre de Navigation -->
        <nav class="navbar sticky-top navbar-expand-lg" style="background-color: white;padding : 1%;">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img src="img/MeetEvent_Logo (1).png" alt="Bootstrap" width="50" height="40">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item" style="padding-left: 30px;padding-right: 30px;">
                        <a class="nav-link" href="#" style="color: black;font-size: 18px;">Contact</a>
                    </li>
                    <li class="nav-item" style="padding-left: 30px;padding-right: 30px;">
                        <a class="nav-link" href="creaEvent.php" style="color: black;font-size: 18px;">Créer votre événement</a>
                    </li>
                    <li class="nav-item" style="padding-left: 30px;padding-right: 30px;">
                        <a class="nav-link" href="pageSuggestion.php" style="color: black;font-size: 18px;">Rechercher votre événement</a>
                    </li>
                    </ul>
                    <span class="navbar-text">
                    <a class="nav-link" href="connexion.php"><i class="fi fi-sr-user" style="font-size: 28px;color: black;" ></i></a>
                    </span>
                </div>
            </div>
        </nav>
        <!-- Contenu principal -->
        <div class="hero">
            <!-- Section 1 -->
            <div class="section1">
                <p class="accroche1">
                    <span style="color: #6040fe;">Consultez</span>
                    <span> vos événements ou </span>
                    <span style="color: #6040fe;">Créez</span>
                    <span> votre propre événement</span>
                </p>
                <div class="btns">
                    <button class="btn-consulter">
                            CONSULTEZ
                            <img src="img/angle-petit-droit.png" alt="consulter" width="28px" height="28px" style="color: #6040fe;"/>
                    </button>
                    <button class="btn-creation">
                        CREER VOTRE EVENEMENT
                    </button>
                </div>
            </div>
            <!-- Section 2 -->
            <div class="section2">
                <!-- Les événements participants -->
                <div class="titre_evenement">Evenements auquels je participe</div>
                <div class="events">
                    
                </div>

                <!-- Les événements participants -->
                <div class="titre_evenement">Mes événements</div>
                <div class="events">
                    
                        <div class="part1">
                            <div class="les_boutons">
                                <a href="#" class="btn_supp">SUPPRIMER</a>
                                <a href="#" class="btn_scan">SCANNER</a><img src="" alt="">
                            </div>    
                            <div class="categorie">CATEGORIE</div>
                        </div>
                        <div class="part2">
                            <div class="titre_event">Nom de l’événement</div>
                            <div>Localisation</div>

                            <div class="modalite">
                                <div class="calendrier">
                                    <i class="fi fi-sr-calendar" style="font-size: 28px"></i>
                                    <div class="infos">
                                        <div style="font-weight:bold;">Calendrier</div>
                                        <div>dd/mm/aaaa - hh:mm</div>
                                    </div>    
                                </div>
                                <div class="places">
                                    <i class="fi fi-sr-users-alt" style="font-size: 28px"></i>
                                    <div class="infos">
                                        <div style="font-weight:bold;">Places restantes</div>
                                        <div>nombre</div>
                                    </div>    
                                </div>
                            </div>
                        </div>    
                        <div class="part3_cree">
                            <p style="font-weight: bold;">Type d'événement</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="event-footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-section about">
                        <h2>A propos de nous</h2>
                        <p>Votre contenu à propos de votre application événementielle.</p>
                    </div>
                    <div class="footer-section links">
                        <h2>Liens utiles</h2>
                        <ul>
                            <li><a href="#">Accueil</a></li>
                            <li><a href="#">Événements</a></li>
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </div>
                    <div class="footer-section contact">
                        <h2>Nous contacter</h2>
                        <p>Email: contact@example.com</p>
                    </div>
                </div>
                <div class="newsletter-form">
                    <h2>Abonnez-vous à notre newsletter</h2>
                    <form action="#" method="post">
                        <input type="email" name="email" placeholder="Votre adresse email" required>
                        <button type="submit">S'abonner</button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; 2024 Votre Application Événementielle. Tous droits réservés.
            </div>
        </footer>

    </body>
    <script>
        /* Afficher au chargement de la page les suggestion de l'individu */
        $(document).ready(function() {
            // Exécuter la requête AJAX au chargement de la page
            $.ajax({
                url: 'get_eventsJOIN.php',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Traitement du résultat ici
                    displayResults(response,1);
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors de la requête AJAX:', error);
                }
            });
        });

        /* Afficher au chargement de la page les suggestion de l'individu */
        $(document).ready(function() {
            // Exécuter la requête AJAX au chargement de la page
            $.ajax({
                url: 'get_eventsCREATE.php',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Traitement du résultat ici
                    displayResults(response,2);
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors de la requête AJAX:', error);
                }
            });
        });

        function displayResults(results,nb) {
            if (nb==1) {
                var searchResultsDiv = document.getElementsByClassName("events")[0];
            }else{
                var searchResultsDiv = document.getElementsByClassName("events")[1];
            }
            
/*             searchResultsDiv.innerHTML = ""; // Effacer les résultats précédents
 */
            if (results.length > 0) {
                // Construction de la structure HTML pour afficher les résultats
                html = "";
                for (var i = 0; i < results.length; i++) {


                    html += '<div class="event">';
                    html += '<div class="part1" style="background-image:url(\''+results[i].chemImages+'\');">';
                    /* if(results[i].idEvenement == results2[0].idEvenement){
                        html += '<a href="#" class="btn_join" style="border-color:green;color:green;display:flex;gap:5px;align-items:center;">ADMIS <img src="img/verifier (3).png" width="15px" height="15px"/> </a>';
                    }else{
                        html += '<a href="#" class="btn_join">REJOINDRE</a>';
                    } */
                    if(nb==1){
                        html += '<div class="les_boutons">';
                        html += '<a href="#" class="btn_quit">QUITTER</a>';
                        html += '<a href="#" class="btn_qrcode">QRCODE</a><img src="" alt="">';
                        html += '</div>';
                    } else {
                        html += '<div class="les_boutons">';
                        html += '<a href="#" class="btn_quit">SUPPRIMER</a>';
                        html += '<a href="#" class="btn_modif">MODIFIER</a>';
                        html += '<a href="#" class="btn_qrcode">SCANNER<img src="" alt=""></a>';
                        html += '</div>';
                    }
                    
                    html += '<div class="categorie">'+ results[i].libCat +'</div>';      
                    html += '</div>';
                    html += '<div class="part2">';
                    html += '<div class="titre_event">'+ results[i].nom +'</div>';
                    html += '<div>'+ results[i].adresse +'</div>';
                    html += '<div class="modalite">';
                    html += '<div class="calendrier">';
                    html += '<i class="fi fi-sr-calendar" style="font-size: 40px"></i>';
                    html += '<div class="infos">';
                    html += '<div style="font-weight:bold;">Calendrier</div>';
                    html += '<div>'+ formatDate(results[i].dateEvent) +' - '+ results[i].heure +'</div>';
                    html += '</div> ';   
                    html += '</div>';
                    html += '<div class="places">';
                    html += '<i class="fi fi-sr-users-alt" style="font-size: 40px"></i>';
                    html += '<div class="infos">';
                    html += '<div style="font-weight:bold;">Places restantes</div>';
                    html += '<div>'+ results[i].nbPlaces +'</div>';
                    html += '</div> ';   
                    html += '</div>';
                    html += '</div>';
                    html += '</div> ';   
                    if (nb==1) {
                        html += '<div class="part3">';
                        html += '<img src="'+ results[i].chemImage +'" alt="icone utilisateur" class="icon"/>';
                        html += '<div class="infos_createur">';
                        html += '<div style="font-weight:bold;">Créateur</div>';
                        html += '<div>'+ results[i].nom_organisateur + " " + results[i].prenom_organisateur +'</div>';
                    } else {
                        html += '<div class="part3_cree">';
                        if (results[i].statut==0) {
                            html += '<p style="font-weight: bold;">Evenement privé</p>';
                        }else{
                            html += '<p style="font-weight: bold;">Evenement public</p>';
                        }
                        
                    }
                    
                    html += '</div>';
                    html += '</div>';
                    html += '</div>'
                }
                searchResultsDiv.innerHTML = html;

            } else {
                searchResultsDiv.innerHTML = "Aucun résultat trouvé.";
            }
        }

        /* Fonction pour changer le format de date */
        function formatDate(inputDate) {
            // Séparation de l'année, du mois et du jour
            var parts = inputDate.split("-");
            
            // Création d'un nouvel objet Date avec les parties de la date
            var date = new Date(parts[0], parts[1] - 1, parts[2]);
            
            // Récupération des éléments de la date
            var day = date.getDate();
            var month = date.getMonth() + 1; // Les mois commencent à partir de 0
            var year = date.getFullYear();
            
            // Formatage de la date avec des zéros de remplissage si nécessaire
            var formattedDate = (day < 10 ? '0' : '') + day + '/' + (month < 10 ? '0' : '') + month + '/' + year;
            
            // Retourner la date formatée
            return formattedDate;
        }
    </script>
</html>
