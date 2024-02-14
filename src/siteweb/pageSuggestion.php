<?php 

// Vérifiez si l'utilisateur est connecté en vérifiant la présence de ses informations d'identification dans la session
/* if (!isset($_SESSION['user_id'])) {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: connexion.php");
    exit; // Assurez-vous de terminer le script après la redirection
} */

// Faire la connexion avec la base de données
include_once "../gestionBD/database.php";

// Vérifier la connexion
if ($connexion->connect_error) {
    die("La connexion a échoué : " . $connexion->connect_error);
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
    </head>
    <body>
        <div style="background-color:#6040fe;">
            <!-- Barre de Navigation -->
            <nav class="navbar sticky-top navbar-expand-lg" style="background-color: transparent;padding : 1%;">
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
                            <a class="nav-link" href="#" style="color: white;font-size: 18px;">Contact</a>
                        </li>
                        <li class="nav-item" style="padding-left: 30px;padding-right: 30px;">
                            <a class="nav-link" href="MesEvent.html" style="color: white;font-size: 18px;">Gérer mes événements</a>
                        </li>
                        </ul>
                        <span class="navbar-text">
                        <a class="nav-link" href="connexion.php"><i class="fi fi-sr-user" style="font-size: 28px;color: white;" ></i></a>
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
                                <input type="text" name="nomEvenement" placeholder="Nom de l’événement" class="lb-event" id="eventName"/>
                            </div>
                            <span class="vertical-line"></span>
                            <div class="item">
                                <img class="i-date" src="img/horloge-deux-heures-et-demie.png" />
                                <input type="text" name="nomEvenement" placeholder="Date (jj/mm/aaaa)" class="lb-date" id="eventDate"/>
                            </div>
                            <span class="vertical-line"></span>
                            <div class="item">
                                <img class="i-ville" src="img/localisation-du-terrain.png" />
                                <input type="text" name="nomEvenement" placeholder="Ville" class="lb-ville" id="eventCity"/>                        
                            </div>
                        </div>
                        <button class="btn_search" onclick="searchEvents()">RECHERCHER</button>
                    </div>
                </div>    
                <!-- 2eme section -->
                <div class="catgorie">
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
                </div>
            </div>
        </div>
        <!-- Suggestion -->
        <div class="part_suggest">
            <div class="suggestion">
                <div class="titre-suggestion">Suggestion d’événement</div>
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
 -->            </a>
        </div>    
    </body>
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
            /* console.log(eventName) */
            var eventDate = document.getElementById("eventDate").value;
            /* console.log(eventDate) */
            var eventCity = document.getElementById("eventCity").value;
            /* console.log(eventCity) */

            // Masquer la section de suggestion lorsque la recherche est effectuée
            var suggestionSection = document.getElementsByClassName("part_suggest")[0];
            suggestionSection.style.display = "none";

            // Exemple d'affichage des résultats (remplacez avec votre logique de rendu des résultats)
            var searchResultsDiv = document.getElementById("titre_result");
            searchResultsDiv.innerHTML = ""; // Efface les résultats précédents

            searchQuery ="";

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

        function displaySearchResults(results,results2) {
            var searchResultsDiv = document.getElementById("searchResults");
/*             searchResultsDiv.innerHTML = ""; // Effacer les résultats précédents
 */
            if (results.length > 0) {
                // Construction de la structure HTML pour afficher les résultats
                html = "";
                for (var i = 0; i < results.length; i++) {
                    html += '<div class="event">';
                    html += '<div class="part1">';
                    /* if(results[i].idEvenement == results2[0].idEvenement){
                        html += '<a href="#" class="btn_join" style="border-color:green;color:green;display:flex;gap:5px;align-items:center;">ADMIS <img src="img/verifier (3).png" width="15px" height="15px"/> </a>';
                    }else{
                        html += '<a href="#" class="btn_join">REJOINDRE</a>';
                    } */
                    html += '<a href="#" class="btn_join">REJOINDRE</a>';
                    html += '<div class="categorie">'+ results[i].libCat +'</div>';      
                    html += '</div>';
                    html += '<div class="part2">';
                    html += '<div class="titre_event">'+ results[i].nom +'</div>';
                    html += '<div>Localisation</div>';
                    html += '<div class="modalite">';
                    html += '<div class="calendrier">';
                    html += '<i class="fi fi-sr-calendar" style="font-size: 28px"></i>';
                    html += '<div class="infos">';
                    html += '<div style="font-weight:bold;">Calendrier</div>';
                    html += '<div>'+ formatDate(results[i].dateEvent) +' - hh:mm</div>';
                    html += '</div> ';   
                    html += '</div>';
                    html += '<div class="places">';
                    html += '<i class="fi fi-sr-users-alt" style="font-size: 28px"></i>';
                    html += '<div class="infos">';
                    html += '<div style="font-weight:bold;">Places restantes</div>';
                    html += '<div>'+ results[i].nbPlaces +'</div>';
                    html += '</div> ';   
                    html += '</div>';
                    html += '</div>';
                    html += '</div> ';   
                    html += '<div class="part3">';
                    html += '<div class="ellipse"></div>';
                    html += '<div class="infos_createur">';
                    html += '<div style="font-weight:bold;">Créateur</div>';
                    html += '<div>'+ results[i].nom_organisateur + " " + results[i].prenom_organisateur +'</div>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>'
                }
                searchResultsDiv.innerHTML = html;
            } else {
                searchResultsDiv.innerHTML = "Aucun résultat trouvé.";
            }
        }

    </script>
</html>
