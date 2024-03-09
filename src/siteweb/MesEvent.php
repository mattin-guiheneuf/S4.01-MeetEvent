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
            <a class="navbar-brand"  href="index.php">
                <img src="img/MeetEvent_Logo (1).png" alt="Bootstrap" width="50" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item" style="padding-left: 30px;padding-right: 30px;">
                        <a class="nav-link nav-link-transition nav-link_MesEvent" href="creaEvent.php" style="color: black;font-size: 5vw;">Créer votre événement</a>
                    </li>
                    <li class="nav-item" style="padding-left: 30px;padding-right: 30px;">
                        <a class="nav-link nav-link-transition nav-link_MesEvent" href="pageSuggestion.php" style="color: black;font-size: 5vw;">Rechercher des événements</a>
                    </li>
                </ul>
                <span class="navbar-text">   
                    <a class="nav-link nav-link_MesEvent" href="#" style="color: black;font-size: 5vw;"><?php echo $user_name; ?></a>
                </span>
                <span class="navbar-text"> 
                    <i class="fi fi-sr-user" id="icon_MesEvents" style="font-size: 5vw;color: black;"></i>
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
                    CONSULTEZ VOS EVENEMENTS
                </button>
                <button class="btn-creation">
                    CREEZ VOTRE EVENEMENT
                </button>
            </div>
        </div>
        <!-- Section 2 -->
        <div class="section2">
            <!-- Les événements participants -->
            <div class="titre_evenement">Evenements auxquels je participe</div>
            <div class="events">

            </div>

                <!-- Les événements participants -->
                <div class="titre_evenement">Mes événements créés</div>
                <div class="events">
                    
                       
                </div>
            </div>
        </div>

    <!-- Fenetre modale -->
    <div id="myModalForQrcode" class="modal">
        <div class="modal-content-QRcode">
            <h3><strong>Scannez le <span style="color:#6040fe;">QRcode</span> avec votre Smartphone</strong></h3>
            <img id="qrImage" alt="QR Code">
        </div>
    </div>

    <!-- Fenetre modale -->
    <div id="myModalForQuit" class="modal">
        <div class="modal-content">
            <h3>Voulez-vous vraiment quitter cet événement ?</h3>
            <p>Vous allez vous désincrire de cet événement. Vous pouvez vous ré-inscrire à tout moment (si il reste de la place) via la recherche d'événement.</p>
            <div class="container-btn">
                <input type="hidden" class="modalRecupEvent" value="">
                <button class="btn_modal" style="color:white;background-color:#6040fe;" onclick="quitterEvent()">Oui, je le quitte</button>
                <button class="btn_modal" style="color:#6040fe;background-color:white;border:2px solid #6040fe;" onclick="document.getElementById('myModalForQuit').style.display = 'none'">Non</button>
            </div>
        </div>
    </div>

    <!-- Fenetre modale -->
    <div id="myModalForSup" class="modal">
        <div class="modal-content">
            <h3>Voulez-vous vraiment supprimer cet événement ?</h3>
            <p>Vous allez supprimer votre événement. Cette action ne possède pas de possibilité de retour arrière. Votre événement sera perdu et les participants enlevés.</p>
            <div class="container-btn">
                <input type="hidden" class="modalRecupEvent" value="">
                <button class="btn_modal" style="color:white;background-color:#6040fe;" onclick="supprimerEvent()">Oui, je supprime</button>
                <button class="btn_modal" style="color:#6040fe;background-color:white;border:2px solid #6040fe;" onclick="document.getElementById('myModalForQuit').style.display = 'none'">Non</button>
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
                displayResults(response, 1);
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
                displayResults(response, 2);
            },
            error: function(xhr, status, error) {
                console.error('Erreur lors de la requête AJAX:', error);
            }
        });
    });

    function displayResults(results, nb) {
        if (nb == 1) {
            var searchResultsDiv = document.getElementsByClassName("events")[0];
        } else {
            var searchResultsDiv = document.getElementsByClassName("events")[1];
        }

        /*             searchResultsDiv.innerHTML = ""; // Effacer les résultats précédents
         */
        if (results.length > 0) {
            // Construction de la structure HTML pour afficher les résultats
            html = "";
            for (var i = 0; i < results.length; i++) {
                //console.log(results[i].user_id + results[i].event_id+ results[i].token_user+ results[i].token_event);

                html += '<div class="event">';
                html += '<div class="part1" style="background-image:url(\'' + results[i].chemImages + '\');">';
                /* if(results[i].idEvenement == results2[0].idEvenement){
                    html += '<a href="#" class="btn_join" style="border-color:green;color:green;display:flex;gap:5px;align-items:center;">ADMIS <img src="img/verifier (3).png" width="15px" height="15px"/> </a>';
                }else{
                    html += '<a href="#" class="btn_join">REJOINDRE</a>';
                } */
                if (nb == 1) {
                    html += '<div class="les_boutons">';
                    html += '<a class="btn_quit" id="openModalBtn" onclick="openModalQuit(' + results[i].idEvenement + ')">QUITTER</a>';
                    html += '<a class="btn_qrcode" onclick="openQRModal(\'' + results[i].user_id + '\',\'' + results[i].event_id + '\',\'' + results[i].token_user + '\',\'' + results[i].token_event + '\')">QRCODE</a><img src="" alt="">';
                    html += '</div>';
                } else {
                    html += '<div class="les_boutons">';
                    html += '<a href="#" class="btn_quit" id="openModalBtn" onclick="openModalSup(' + results[i].idEvenement + ')">SUPPRIMER</a>';
                    html += '<a href="#" class="btn_modif">MODIFIER</a>';
                    html += '<a href="#" class="btn_qrcode">SCANNER<img src="" alt=""></a>';
                    html += '</div>';
                }

                html += '<div class="categorie">' + results[i].libCat + '</div>';
                html += '</div>';
                html += '<div class="part2">';
                html += '<div class="titre_event">' + results[i].nom + '</div>';
                html += '<div style="display:flex;align-items:center;gap:10px">';
                html += '<i class="fi fi-sr-marker localisation_logo" style="font-size: 1.5vw;"></i>';
                html += '<div onclick="window.open(\'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(results[i].adresse) + '\', \'_blank\')" style="cursor:pointer;">' + results[i].adresse + '</div>';
                html += '</div>';
                html += '<div class="modalite">';
                html += '<div class="calendrier">';
                html += '<i class="fi fi-sr-invite-alt" style="font-size: 2.8vw;"></i>';
                html += '<div class="infos">';
                html += '<div style="font-weight:bold;">Calendrier</div>';
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
                if (nb == 1) {
                    html += '<div class="part3">';
                    html += '<img src="' + results[i].chemImage + '" alt="icone utilisateur" class="icon"/>';
                    html += '<div class="infos_createur">';
                    html += '<div class="crea" style="font-weight:bold;">Créateur</div>';
                    html += '<div class="nom_crea">' + results[i].nom_organisateur + " " + results[i].prenom_organisateur + '</div>';
                } else {
                    html += '<div class="part3_cree">';
                    if (results[i].statut == 0) {
                        html += '<p style="font-weight: bold;">Evenement privé</p>';
                    } else {
                        html += '<p style="font-weight: bold;">Evenement public</p>';
                    }

                }

                html += '</div>';
                html += '</div>';
                html += '</div>';
            }
            searchResultsDiv.innerHTML = html;

            } else {
                if (nb==1) {
                    searchResultsDiv.innerHTML = "Aucun événement rejoint";
                } else {
                    searchResultsDiv.innerHTML = "Aucun événement créé";
                }
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

    /* Fonction pour afficher le QRCODE */
    function openQRModal(userId, eventId, tokenUser, tokenEvent) {
        var modal = document.getElementById("myModalForQrcode");
        modal.style.display = "block";
        var qrImage = document.getElementById('qrImage');
        console.log(userId);
        console.log(eventId);
        console.log(tokenUser);
        console.log(tokenEvent);
        qrImage.src = 'generate_qr.php?userId=' + userId + '&eventId=' + eventId + '&tokenUser=' + tokenUser + '&tokenEvent=' + tokenEvent;
    }

    // Fonction à exécuter lorsque le bouton est cliqué pour ouvrir la modal
    function openModalQuit(idEvenement) {
        var modal = document.getElementById("myModalForQuit");
        modal.style.display = "block";

        var eventSelected = document.getElementsByClassName("modalRecupEvent")[0];
        eventSelected.value = idEvenement;
    }

    // Fonction à exécuter lorsque le bouton est cliqué pour ouvrir la modal
    function openModalSup(idEvenement) {
        var modal = document.getElementById("myModalForSup");
        modal.style.display = "block";

        var eventSelected = document.getElementsByClassName("modalRecupEvent")[0];
        eventSelected.value = idEvenement;
    }


    // Fermer la modal si l'utilisateur clique en dehors de la modal
    window.onclick = function(event) {
        //Pour la modale de supprimer un event
        var modalQuit = document.getElementById("myModalForSup");
        if (event.target == modalQuit) {
            modalQuit.style.display = "none";
        }

        //Pour la modale de quitter un event
        var modalQuit = document.getElementById("myModalForQuit");
        if (event.target == modalQuit) {
            modalQuit.style.display = "none";
        }

        //Pour la modale de QRCODE d'un event
        var modalQRCODE = document.getElementById("myModalForQrcode");
        if (event.target == modalQRCODE) {
            modalQRCODE.style.display = "none";
        }
    }

    /* Fonction pour quitter l'evenement sélectionné */
    function quitterEvent() {
        var eventSelected = document.getElementsByClassName("modalRecupEvent")[0].value;
        // Requête AJAX vers le serveur pour récupérer les événements correspondants à la recherche
        $.ajax({
            type: 'POST',
            url: 'ajax.php',
            data: {
                eventSelected: eventSelected,
                type: "quitter"
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

    /* Fonction pour supprimer l'evenement sélectionné */
    function supprimerEvent() {
        var eventSelected = document.getElementsByClassName("modalRecupEvent")[0].value;
        // Requête AJAX vers le serveur pour récupérer les événements correspondants à la recherche
        $.ajax({
            type: 'POST',
            url: 'ajax.php',
            data: {
                eventSelected: eventSelected,
                type: "supprimer"
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

</html>