<?php 
// Affichier la recommandation
/* require_once 'algorithme/Suggestion.php';
 
echo $_SESSION["user_id"]; */

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
                                <input type="text" name="nomEvenement" placeholder="Nom de l’événement" class="lb-event"/>
                            </div>
                            <span class="vertical-line"></span>
                            <div class="item">
                                <img class="i-date" src="img/horloge-deux-heures-et-demie.png" />
                                <input type="text" name="nomEvenement" placeholder="Date (jj/mm/aaaa)" class="lb-date"/>
                            </div>
                            <span class="vertical-line"></span>
                            <div class="item">
                                <img class="i-ville" src="img/localisation-du-terrain.png" />
                                <input type="text" name="nomEvenement" placeholder="Ville" class="lb-ville"/>                        
                            </div>
                        </div>
                        <a class="btn_search" href="#">RECHERCHER</a>
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
                    <!-- Event 1 -->
                    <div class="event">
                        <div class="part1">
                            <a href="#" class="btn_join">REJOINDRE</a>
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
                        <div class="part3">
                            <div class="ellipse"></div>
                            <div class="infos_createur">
                                <div style="font-weight:bold;">Créateur</div>
                                <div>Nom du créateur</div>
                            </div>
                        </div>
                    </div>
                    <!-- Event 2 -->
                    <div class="event">
                        <div class="part1">
                            <a href="#" class="btn_join">REJOINDRE</a>
                            <div class="categorie">CATEGORIE</div>
                            
                        </div>
                        <div class="part2">
                            <div class="titre_event">Nom de l’événement</div>
                            <div>Localisation</div>

                            <div class="modalite">
                                <div class="calendrier">
                                    <img src="img/3914353-1-5.png" width="26px"/>
                                    <div class="infos">
                                        <div style="font-weight:bold;">Calendrier</div>
                                        <div>dd/mm/aaaa - hh:mm</div>
                                    </div>    
                                </div>
                                <div class="places">
                                    <img src="img/3917272-1-4.png" width="26px"/>
                                    <div class="infos">
                                        <div style="font-weight:bold;">Places restantes</div>
                                        <div>nombre</div>
                                    </div>    
                                </div>
                            </div>
                        </div>    
                        <div class="part3">
                            <div class="ellipse"></div>
                            <div class="infos_createur">
                                <div style="font-weight:bold;">Créateur</div>
                                <div>Nom du créateur</div>
                            </div>
                        </div>
                    </div>
                    <!-- Event 3 -->
                    <div class="event">
                        <div class="part1">
                            <a href="#" class="btn_join">REJOINDRE</a>
                            <div class="categorie">CATEGORIE</div>
                            
                        </div>
                        <div class="part2">
                            <div class="titre_event">Nom de l’événement</div>
                            <div>Localisation</div>

                            <div class="modalite">
                                <div class="calendrier">
                                    <img src="img/3914353-1-5.png" width="26px"/>
                                    <div class="infos">
                                        <div style="font-weight:bold;">Calendrier</div>
                                        <div>dd/mm/aaaa - hh:mm</div>
                                    </div>    
                                </div>
                                <div class="places">
                                    <img src="img/3917272-1-4.png" width="26px"/>
                                    <div class="infos">
                                        <div style="font-weight:bold;">Places restantes</div>
                                        <div>nombre</div>
                                    </div>    
                                </div>
                            </div>
                        </div>    
                        <div class="part3">
                            <div class="ellipse"></div>
                            <div class="infos_createur">
                                <div style="font-weight:bold;">Créateur</div>
                                <div>Nom du créateur</div>
                            </div>
                        </div>
                    </div>
                    <!-- Event 4 -->
                    <div class="event">
                        <div class="part1">
                            <a href="#" class="btn_join">REJOINDRE</a>
                            <div class="categorie">CATEGORIE</div>
                            
                        </div>
                        <div class="part2">
                            <div class="titre_event">Nom de l’événement</div>
                            <div>Localisation</div>

                            <div class="modalite">
                                <div class="calendrier">
                                <i class="fi fi-rr-calendar" style="font-size: 28px"></i>
                                    <div class="infos">
                                        <div style="font-weight:bold;">Calendrier</div>
                                        <div>dd/mm/aaaa - hh:mm</div>
                                    </div>    
                                </div>
                                <div class="places">
                                    <img src="img/3917272-1-4.png" width="26px"/>
                                    <div class="infos">
                                        <div style="font-weight:bold;">Places restantes</div>
                                        <div>nombre</div>
                                    </div>    
                                </div>
                            </div>
                        </div>    
                        <div class="part3">
                            <div class="ellipse"></div>
                            <div class="infos_createur">
                                <div style="font-weight:bold;">Créateur</div>
                                <div>Nom du créateur</div>
                            </div>
                        </div>
                    </div>
                    <!-- Event 5 -->
                    <div class="event">
                        <div class="part1">
                            <a href="#" class="btn_join">REJOINDRE</a>
                            <div class="categorie">CATEGORIE</div>
                            
                        </div>
                        <div class="part2">
                            <div class="titre_event">Nom de l’événement</div>
                            <div>Localisation</div>

                            <div class="modalite">
                                <div class="calendrier">
                                    <img src="img/3914353-1-5.png" width="26px"/>
                                    <div class="infos">
                                        <div style="font-weight:bold;">Calendrier</div>
                                        <div>dd/mm/aaaa - hh:mm</div>
                                    </div>    
                                </div>
                                <div class="places">
                                    <img src="img/3917272-1-4.png" width="26px"/>
                                    <div class="infos">
                                        <div style="font-weight:bold;">Places restantes</div>
                                        <div>nombre</div>
                                    </div>    
                                </div>
                            </div>
                        </div>    
                        <div class="part3">
                            <div class="ellipse"></div>
                            <div class="infos_createur">
                                <div style="font-weight:bold;">Créateur</div>
                                <div>Nom du créateur</div>
                            </div>
                        </div>
                    </div>
                    <!-- Event 6 -->
                    <div class="event">
                        <div class="part1">
                            <a href="#" class="btn_join">REJOINDRE</a>
                            <div class="categorie">CATEGORIE</div>
                            
                        </div>
                        <div class="part2">
                            <div class="titre_event">Nom de l’événement</div>
                            <div>Localisation</div>

                            <div class="modalite">
                                <div class="calendrier">
                                    <img src="img/3914353-1-5.png" width="26px"/>
                                    <div class="infos">
                                        <div style="font-weight:bold;">Calendrier</div>
                                        <div>dd/mm/aaaa - hh:mm</div>
                                    </div>    
                                </div>
                                <div class="places">
                                    <img src="img/3917272-1-4.png" width="26px"/>
                                    <div class="infos">
                                        <div style="font-weight:bold;">Places restantes</div>
                                        <div>nombre</div>
                                    </div>    
                                </div>
                            </div>
                        </div>    
                        <div class="part3">
                            <div class="ellipse"></div>
                            <div class="infos_createur">
                                <div style="font-weight:bold;">Créateur</div>
                                <div>Nom du créateur</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Btn de Retour -->
        <div class="retourEnHaut">
            <a href="#" class="btn-retour">
                <span>REVENEZ EN HAUT POUR FAIRE UNE RECHERCHE</span>
                <img class="i-retour" src="img/i-retour.png" />
            </a>
        </div>    
    </body>
</html>
