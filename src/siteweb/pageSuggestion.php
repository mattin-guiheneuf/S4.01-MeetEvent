<?php
session_start();
// Vérifiez si l'utilisateur est connecté en vérifiant la présence de ses informations d'identification dans la session
if (!isset($_SESSION['user_id'])) {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: connexion.php");
    exit; // Assurez-vous de terminer le script après la redirection
} else {
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
    <link rel="stylesheet" href="CSS/style4.css" />
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.1.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Inclure les styles de Slick Slider -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
</head>

<body>
    <div style="background-color:#6040fe;">
        <!-- Barre de Navigation -->
        <nav class="navbar sticky-top navbar-expand-lg" style="background-color: #6040fe;padding : 1%;">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">
                    <img src="img/MeetEvent_Logo_blanc.png" alt="Bootstrap" width="50" height="40">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item" style="padding-left: 30px;padding-right: 30px;">
                            <a class="nav-link nav-link-transition_Suggest" href="MesEvent.php" style="color: white;font-size: 5vw;">Gérer mes événements</a>
                        </li>
                    </ul>
                    <span class="navbar-text">
                        <a class="nav-link" href="logout.php" style="color: white;font-size: 5vw;"><?php echo $user_name; ?></a>
                    </span>
                    <span class="navbar-text">
                        <i class="fi fi-sr-user" style="font-size: 5vw;color: white;"></i>
                    </span>
                </div>
            </div>
        </nav>
        <!-- Contenu principal -->
        <div class="hero">
            <!-- 1ere section -->
            <div class="section1">
                <p class="accroche1">
                    <span>Rechercher des événements qui VOUS correspondent</span>
                </p>
                <!-- Barre de Recherche -->
                <p class="lb-exemple">
                    ex. : “Bal de promo 2024”,&nbsp;&nbsp;le“25/05/2024”&nbsp;&nbsp; à “Anglet”
                </p>
                <div class="barre-de-recherche">
                    <div class="items">
                        <div class="item">
                            <img class="i-evenement" src="img/semaine-calendaire.png" />
                            <input type="text" name="nomEvenement" placeholder="Nom de l’événement" class="lb-event" id="eventName" />
                        </div>
                        <span class="vertical-line"></span>
                        <div class="item">
                            <img class="i-date" src="img/horloge-deux-heures-et-demie.png" />
                            <input type="text" name="nomEvenement" placeholder="Date (jj/mm/aaaa)" class="lb-date" id="eventDate" />
                        </div>
                        <span class="vertical-line"></span>
                        <div class="item">
                            <img class="i-ville" src="img/localisation-du-terrain.png" />
                            <input type="text" name="nomEvenement" placeholder="Ville" class="lb-ville" id="eventCity" />
                        </div>
                    </div>
                    <button class="btn_search" onclick="searchEvents()">RECHERCHER</button>
                </div>
            </div>
            <!-- 2eme section -->
            <!-- <div class="catgorie">
                    <div class="titre-cat-gorie">Catégories</div>
                    <div class="catgories">
                        <div class="cat">
                            CATÉGORIE 1
                        </div>
                        <div class="cat">
                            CATÉGORIE 2
                        </div>
                        <div class="cat">
                            CATÉGORIE 3
                        </div>
                        <div class="cat">
                            CATÉGORIE 4
                        </div>
                        <div class="cat">
                            CATÉGORIE 5
                        </div>
                        <div class="cat">
                            CATÉGORIE 6
                        </div>
                        <div class="cat">
                            CATÉGORIE 7
                        </div>
                        <div class="cat">
                            CATÉGORIE 8
                        </div>
                        <div class="cat">
                            CATÉGORIE 9
                        </div>
                        <div class="cat">
                            CATÉGORIE 10
                        </div>
                    </div>
                </div> -->
        </div>
    </div>
    <!-- Suggestion -->
    <div class="part_suggest">
        <div class="suggestion">
            <div class="titre-suggestion">Suggestion d’événements</div>
            <div class="events">

            </div>
        </div>
    </div>
    <!-- Recherche -->
    <div class="part_suggest">
        <div class="suggestion">
            <div id="titre_result">

            </div>
            <div id="searchResults">

            </div>
        </div>
    </div>
    <!-- Btn de Retour -->
    <div class="retourEnHaut">
        <a href="#" class="btn-retour">
            <span>REVENEZ EN HAUT POUR FAIRE UNE RECHERCHE</span>
            <!--                 <img class="i-retour" src="img/i-retour.png" />
 --> </a>
    </div>

    <!-- Fenetre modale -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <h3>Voulez-vous vraiment rejoindre cet événement ?</h3>
            <p>Vous allez vous incrire pour cet événement. Vous ouvez vous désinscrire à tout moment via l'onglet "Gérer mes Evenements".</p>
            <div class="container-btn">
                <input type="hidden" class="modalRecupEvent" value="">
                <button class="btn_modal" style="color:white;background-color:#6040fe;" onclick="rejoindreEvent()">Oui, je rejoins</button>
                <button class="btn_modal" style="color:#6040fe;background-color:white;border:2px solid #6040fe;" onclick="document.getElementById('myModal').style.display = 'none'">Non</button>
            </div>
        </div>
    </div>

    <footer class="event-footer">
        <!-- Barre supérieure -->
        <div class="footer-top">

            <div class="item_footer">
                <i class="fas fa-map-marker-alt"></i>
                <div class="text_item">
                    <p style="font-weight:bold;">Adresse</p>
                    <p>3 rue de Cassou, 64600 Anglet</p>
                </div>

            </div>
            <div class="item_footer">
                <i class="fas fa-envelope"></i>
                <div class="text_item">
                    <p style="font-weight:bold;">Email</p>
                    <p>contact@meetevent.com</p>
                </div>

            </div>
            <div class="item_footer">
                <i class="fas fa-phone-alt"></i>
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
                        <li><a href="index.php">Page d'accueil</a></li>
                        <li><a href="pageSuggestion.php">Page de recherche d'événement</li>
                        <li><a href="MesEvent.php">Page événements (admis/créer)</li>
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
                    <form class="form_newsletter">
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


    <script>
        /* Afficher au chargement de la page les suggestion de l'individu */
        $(document).ready(function() {
            // Exécuter la requête AJAX au chargement de la page
            $.ajax({
                url: 'get_suggestion.php',
                method: 'GET',
                success: function(response) {
                    // Traitement du résultat ici
                    var response = response.split("<br>");
                    response.pop();
                    console.log(response); // Affiche le résultat dans la console
                    suggestEvent(response);
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors de la requête AJAX:', error);
                }
            });
        });

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

        /* Fonction pour afficher les événements suggérés */
        function suggestEvent(listeEvent) {
            // Requête AJAX vers le serveur pour récupérer les événements correspondants à la recherche
            $.ajax({
                type: 'POST',
                url: 'ajax.php',
                dataType: 'json',
                data: {
                    listeEvent: listeEvent
                },
                success: function(response) {
                    displaySearchResults(response);
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors de la requête AJAX:', error);
                }
            });
        }

        /* Fonction pour afficher la recherche */
        function searchEvents() {
            var eventName = document.getElementById("eventName").value;
            console.log(eventName)
            var eventDate = document.getElementById("eventDate").value;
            console.log(eventDate)
            var eventCity = document.getElementById("eventCity").value;
            console.log(eventCity)

            // Masquer la section de suggestion lorsque la recherche est effectuée
            var suggestionSection = document.getElementsByClassName("part_suggest")[0];
            suggestionSection.style.display = "none";

            // Masquer la section catégorie
            /* var catSection = document.getElementsByClassName("catgorie")[0];
            catSection.style.display = "none"; */

            // Exemple d'affichage des résultats (remplacez avec votre logique de rendu des résultats)
            var searchResultsDiv = document.getElementById("titre_result");
            searchResultsDiv.innerHTML = ""; // Efface les résultats précédents

            searchQuery = "";

            // Vérifier si au moins un champ de recherche est rempli
            if (eventName || eventDate || eventCity) {
                var searchQuery = "<div class='titre-suggestion'>Recherche ";
                if (eventName) {
                    searchQuery += "pour <span style='color:#4060fe;'> " + eventName + "</span>, ";
                }
                if (eventDate) {
                    searchQuery += "le <span style='color:#4060fe;'> " + eventDate + "</span>, ";
                }
                if (eventCity) {
                    searchQuery += "à <span style='color:#4060fe;'> " + eventCity + "</span></div>, ";
                }
                searchQuery = searchQuery.slice(0, -2); // Supprime la virgule finale et l'espace


            } else {
                // Montrer la section de suggestion lorsque la recherche est effectuée
                suggestionSection.style.display = "grid";
                /* catSection.style.display = "block"; */
            }
            searchResultsDiv.innerHTML = searchQuery;

            // Requête AJAX vers le serveur pour récupérer les événements correspondants à la recherche
            $.ajax({
                type: 'POST',
                url: 'ajax.php',
                dataType: 'json',
                data: {
                    eventName: eventName,
                    eventDate: eventDate,
                    eventCity: eventCity
                },
                success: function(response) {
                    displaySearchResults(response);
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors de la requête AJAX:', error);
                }
            });

        }

        function displaySearchResults(results) {
            var searchResultsDiv = document.getElementById("searchResults");
            /*             searchResultsDiv.innerHTML = ""; // Effacer les résultats précédents
             */
            if (results.length > 0) {
                // Construction de la structure HTML pour afficher les résultats
                html = "";
                for (var i = 0; i < results.length; i++) {


                    html += '<div class="event">';
                    html += '<div class="part1" style="background-image:url(\'' + results[i].chemImages + '\');">';
                    /* if(results[i].idEvenement == results2[0].idEvenement){
                        html += '<a href="#" class="btn_join" style="border-color:green;color:green;display:flex;gap:5px;align-items:center;">ADMIS <img src="img/verifier (3).png" width="15px" height="15px"/> </a>';
                    }else{
                        html += '<a href="#" class="btn_join">REJOINDRE</a>';
                    } */
                    console.log(results[i].est_deja_admis + " " + results[i].nom);
                    if (results[i].est_deja_admis == 1) {
                        html += '<a href="#" class="btn_join" style="border-color:green;color:green;display:flex;gap:5px;align-items:center;">ADMIS <img src="img/verifier (3).png" width="15px" height="15px"/> </a>';
                    } else {
                        html += '<button id="openModalBtn" onclick="openModal(' + results[i].idEvenement + ')" class="btn_join">REJOINDRE</button>';
                    }

                    html += '</div>';
                    html += '<div class="part2">';
                    html += '<div class="titre_event">' + results[i].nom + '</div>';
                    html += '<div style="display:flex;align-items:center;gap:10px">';
                    html += '<i class="fi fi-sr-marker localisation_logo" style="font-size: 1.5vw;"></i>';
                    html += '<div onclick="window.open(\'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(results[i].adresse) + '\', \'_blank\')" style="cursor:pointer;" class="adresse">' + results[i].adresse + '</div>';
                    html += '</div>';
                    html += '<div class="modalite">';
                    html += '<div class="calendrier">';
                    html += '<i class="fi fi-sr-invite-alt" style="font-size: 2.8vw"></i>';
                    html += '<div class="infos">';
                    html += '<div  style="font-weight:bold;">Calendrier</div>';
                    html += '<div class="txt_infos" onclick="openGoogleCalendar(\'' + formatDate(results[i].dateEvent) + ' ' + results[i].heure + '\')" style="cursor:pointer;">' + formatDate(results[i].dateEvent) + ' - ' + results[i].heure + '</div>';
                    html += '</div> ';
                    html += '</div>';
                    html += '<div class="places">';
                    html += '<i class="fi fi-sr-users" style="font-size: 2.8vw;"></i>';
                    html += '<div class="infos">';
                    html += '<div style="font-weight:bold;">Places restantes</div>';
                    html += '<div class="txt_infos">' + results[i].nbPlaces + '</div>';
                    html += '</div> ';
                    html += '</div>';
                    html += '</div>';
                    html += '</div> ';
                    html += '<hr style="width:70%;margin:0 15%;">';
                    html += '<div class="part3">';
                    html += '<img src="' + results[i].chemImage + '" alt="icone utilisateur" class="icon"/>';
                    html += '<div class="infos_createur">';
                    html += '<div class="crea" style="font-weight:bold;">Créateur</div>';
                    html += '<div class="nom_crea">' + results[i].nom_organisateur + " " + results[i].prenom_organisateur + '</div>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                }
                searchResultsDiv.innerHTML = html;

            } else {
                searchResultsDiv.innerHTML = "Aucun résultat trouvé.";
            }
        }

        // Fonction pour formater la date et l'heure dans le format requis par Google Calendar
        function formatDateTimeForGoogleCalendar(dateTime) {
            // Vérifier si la date et l'heure sont définies
            if (!dateTime) {
                console.error('La date et l\'heure ne sont pas définies.');
                return null;
            }

            // Diviser la chaîne de date et d'heure en date et heure
            var parts = dateTime.split(' ');

            // Vérifier si la chaîne a été correctement divisée
            if (parts.length !== 2) {
                console.error('La chaîne de date et d\'heure est invalide : ' + dateTime);
                return null;
            }

            var datePart = parts[0];
            var timePart = parts[1];

            // Diviser la date en jour, mois et année
            var dateParts = datePart.split('/');
            var day = dateParts[0];
            var month = dateParts[1];
            var year = dateParts[2];

            // Diviser l'heure en heures et minutes
            var timeParts = timePart.split(':');
            var hour = timeParts[0];
            var minute = timeParts[1];

            // Formater la date et l'heure dans le format YYYYMMDDTHHMMSSZ
            var formattedDateTime = year + month + day + 'T' + hour + minute + '00Z';

            return formattedDateTime;
        }

        // Fonction pour ouvrir l'agenda Google dans une nouvelle fenêtre
        function openGoogleCalendar(dateTime) {
            // Formater la date et l'heure pour Google Calendar
            var formattedDateTime = formatDateTimeForGoogleCalendar(dateTime);

            // Créer l'URL de l'agenda Google avec la date et l'heure spécifiques
            var googleCalendarUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE&dates=' + encodeURIComponent(formattedDateTime) + '/' + encodeURIComponent(formattedDateTime);

            // Ouvrir l'agenda Google dans une nouvelle fenêtre
            window.open(googleCalendarUrl, '_blank');
        }

        // Fonction à exécuter lorsque le bouton est cliqué pour ouvrir la modal
        function openModal(idEvenement) {
            var modal = document.getElementById("myModal");
            modal.style.display = "block";

            var eventSelected = document.getElementsByClassName("modalRecupEvent")[0];
            eventSelected.value = idEvenement;
        }

        // Fermer la modal si l'utilisateur clique en dehors de la modal
        window.onclick = function(event) {
            var modal = document.getElementById("myModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        /* Fonction pour rejoindre l'evenement sélectionné */
        function rejoindreEvent() {
            var eventSelected = document.getElementsByClassName("modalRecupEvent")[0].value;
            // Requête AJAX vers le serveur pour récupérer les événements correspondants à la recherche
            $.ajax({
                type: 'POST',
                url: 'ajax.php',
                data: {
                    eventSelected: eventSelected,
                    type: "rejoindre"
                },
                success: function(response) {
                    console.log(response);
                    window.location.href = "MesEvent.php";
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors de la requête AJAX:', error);
                }
            });
        }
    </script>
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

</body>

</html>