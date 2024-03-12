<!-- SAE -->
<?php

/**
 * @file creaDicoSynTag.php
 * @author Yannis Duvignau
 * @brief Fichier contenant la fonction creaDicoSynTag
 * @details Contient la focntion qui permet de créer le dictionnaire des synonyme de tags
 * @version 1.0
 */


    // Récupératiob de l'API
    include_once "module.php";

    /**
     * @brief Créer le dictionnaire des synonyme de Tags
     * @param Tag[]
     * @return array
     */
    function creaDicoSynTag($CORPUS_TAG)
    {
        echo "Lancement création dicoSynTag <br><br>";
        $CORPUS_TAG = array_map('strtolower',$CORPUS_TAG);

        // VARIABLES
        $dicoSynTag = array(); // Dictionnaire résultat de la fonction

        // TRAITEMENTS
        // Ajout des tags au dicoSynTag pour qu'ils soient également détectés
        foreach ($CORPUS_TAG as $tagCourant) { // Pour chaque tag
            $dicoSynTag += array(strtolower($tagCourant)=>array(strtolower($tagCourant))); // Ajout dans le dico tagCourant : tagCourant pour que les tags puissent être directement relié à eux-mêmes
        }
    
        // Enrichissement au premier degré du dictionnaire avec les synonymes des tags
        foreach ($CORPUS_TAG as $tagCourant) // Pour chaque tag
        {
            // Synonymes
            /* $listeSynTagCourant = synAvecAPI(strtolower(tradMotFrToAng($tagCourant))); // On exploite l'API pour récupérer les synonymes du tagCourant
            
            foreach($listeSynTagCourant as $synTagCrt) // Pour chaque synonyme du tagCourant
            {
                $synTagCourant = strtolower(tradMotAngToFr($synTagCrt));
                if(array_key_exists($synTagCourant,$dicoSynTag)){ // Si le synonyme est déjà présent comme clé
                    if(!(in_array(strtolower($tagCourant),$dicoSynTag[$synTagCourant]))){ // Si le tag est pas déjà dans la liste
                        array_push($dicoSynTag[$synTagCourant],strtolower($tagCourant)); // On l'ajoute
                    }
                }
                else // Sinon le synonyme est pas encore présent comme clé
                {
                    $dicoSynTag[$synTagCourant] = array(strtolower($tagCourant)); // Donc on l'ajoute associé à son tag
                }
            } */

            // Mots liés
            /* $listeTrgTagCourant = trgAvecAPIEtTopics(strtolower(tradMotFrToAng($tagCourant))); // On exploite l'API pour récupérer les synonymes du tagCourant
            
            foreach($listeTrgTagCourant as $trgTagCrt) // Pour chaque synonyme du tagCourant
            {
                $trgTagCourant = strtolower(tradMotAngToFr($trgTagCrt));
                if(array_key_exists($trgTagCourant,$dicoSynTag)){ // Si le synonyme est déjà présent comme clé
                    if(!(in_array(strtolower($tagCourant),$dicoSynTag[$trgTagCourant]))){ // Si le tag est pas déjà dans la liste
                        array_push($dicoSynTag[$trgTagCourant],strtolower($tagCourant)); // On l'ajoute
                    }
                }
                else // Sinon le triggered est pas encore présent comme clé
                {
                    $dicoSynTag[$trgTagCourant] = array(strtolower($tagCourant)); // Donc on l'ajoute associé à son tag
                }
            } */

            // Mots génériques
            $listeGenTagCourant = genAvecAPI(strtolower(tradMotFrToAng($tagCourant))); // On exploite l'API pour récupérer les synonymes du tagCourant
            
            foreach($listeGenTagCourant as $genTagCrt) // Pour chaque synonyme du tagCourant
            {
                $genTagCourant = strtolower(tradMotAngToFr($genTagCrt));
                if(array_key_exists($genTagCourant,$dicoSynTag)){ // Si le synonyme est déjà présent comme clé
                    if(!(in_array(strtolower($tagCourant),$dicoSynTag[$genTagCourant]))){ // Si le tag est pas déjà dans la liste
                        array_push($dicoSynTag[$genTagCourant],strtolower($tagCourant)); // On l'ajoute
                    }
                }
                else // Sinon le générique est pas encore présent comme clé
                {
                    $dicoSynTag[$genTagCourant] = array(strtolower($tagCourant)); // Donc on l'ajoute associé à son tag
                }
            }
        }

        echo "encodage<br>";
        // Destination du fichier à modifier si jamais (ou ajouter un paramètres)
        file_put_contents('./data/dicoSynTagGen.json',json_encode($dicoSynTag,JSON_PRETTY_PRINT));
        echo "encodage terminé<br><br>";

        return $dicoSynTag;
    }

    // Corpus plus court pour les tests
    $CORPUS_TAG_Test = ["Tournoi", "Gastronomie", "Ambiance", "Bingo", "Atelier", "Film", "Formation", "Cinema", "musique", "Solidarite", "Detente", "Festival", "Loisir", 'Tennis', 'Finance', 'Charite', 'Jeu de Plateau', 'Chant', 'Paysages', 'jeux', 'Degustation', 'Concert', 'Banquet', 'Blues', 'Musique', 'Rencontre', 'Futsal', 'Pays Basque', 'Football', 'Marche', 'Amusement', 'Course', 'Investissement', 'Seniors', 'Balade', 'Pratique', 'Oenologie', 'Competition', 'culture', 'Jeu de Societe', 'voyage', 'Decouverte', 'Exposition', 'Loto', 'Amical', 'Cuisine', 'Musee', 'Terroir', 'Plein Air', 'Charcuterie', 'Vin', 'Cafe', 'Match', 'Buvette', 'Divertissement', 'Diner', 'Creation', 'Italie', 'Association', 'Randonnee', 'Echange', 'Partage', 'Discussion', 'Jazz', 'Aperitif', 'Omelette', 'Lecture', 'Jeu de societe', 'Activite physique', 'Fete', 'lecture', 'Entrainement', 'Hunger Games', 'Economie', 'Montagne', 'Convivialite', 'Caritatif', 'Viande', 'Festin', 'sport', 'Raquette', 'Culture', 'Plaisir', 'festival', 'Argent', 'Livre', 'Sport', 'jeu de cartes', 'Conference', 'Repas', 'Renforcement musculaire', 'Aventure', 'Nature', 'Soiree'];

    // Vrai cropus de tags
    $CORPUS_TAG = [
        "Cuisine", "Art",
        "Musique", "Dessin", "Sport", "Entraînement", "Social",
        "Discussion", "Méditation", "Détente", "Lecture", "Écoute","Rire",
        "Divertissement", "Fête", "Exploration", "Voyage", "Découverte", 
        "Enseignement", "Travail", "Créativité", "Construction",
        "Jardinage", "Photographie", "Film", "Danse", "Chant", 
        "Instrument", "Collection", "Informatique", "Réflexion",
        "Engagement", "Volontariat", "Organisation",
        "Exercice", "Expérience",
        "Développement", "Amélioration", "Innovation", "Économie",
        "Partage", "Influence", "Motivation",
        "Inspiration", "Amusement",
        "Célébration",
        "Imagination", "Jeux", "Festival",
        "Culture", "Concert", "Repas", "Aperitif", "Alcool",
        "Association", "Rencontre",
        "Marche", "Amical",
        "Plaisir", "Jeu de société", "Animaux",
        "Soiree", "Nature", "Paysages", "Atelier", 
        "Gastronomie", "Dégustation", "Exposition", "Musee",
        "Dîner", "Caritatif", "Solidarité", "Loisir",
        "Competition", "Tournoi", "Montagne",
        "Finance", "Formation","Océan"
    ];

    //creaDicoSynTag($CORPUS_TAG);

    // Fonction pour fusionner correctement
    function fusionnerBienCorpus($c1,$c2){
        // Récupération des données
        $jsonCorpus1 = file_get_contents('./data/'.$c1.'.json');
        $Corpus1 = json_decode($jsonCorpus1);
        $jsonCorpus2 = file_get_contents('./data/'.$c2.'.json');
        $Corpus2 = json_decode($jsonCorpus2);

        $res = array();

        // Parcours et traitements
        // Premier corpus
        foreach ($Corpus1 as $syn => $listeTag) { // Pour chaque enregistrement (ligne) du json
            if (!(array_key_exists($syn,$res))) { // Si le synonyme n'est pas déjà dans le résultat
                $res += array($syn=>$listeTag); // On l'ajoute
            }
            else { // Sinon
                foreach ($listeTag as $tag) { // Pour chaque de la liste de tag associé au synonyme
                    if(!(in_array($tag,$res[$syn]))){ // Si le tag n'est pas déjà associé au synonyme
                        array_push($res[$syn],$tag); // On l'ajoute au tag associé à ce synonyme
                    }
                    // Sinon rien
                }
            }
        }

        // Second corpus
        foreach ($Corpus2 as $syn => $listeTag) { // Pour chaque enregistrement (ligne) du json
            if (!(array_key_exists($syn,$res))) { // Si le synonyme n'est pas déjà dans le résultat
                $res += array($syn=>$listeTag); // On l'ajoute
            }
            else { // Sinon
                foreach ($listeTag as $tag) { // Pour chaque de la liste de tag associé au synonyme
                    if(!(in_array($tag,$res[$syn]))){ // Si le tag n'est pas déjà associé au synonyme
                        array_push($res[$syn],$tag); // On l'ajoute au tag associé à ce synonyme
                    }
                    // Sinon rien
                }
            }
        }

        return $res;
    }

    function retirerAntonymes($CORPUS_TAG){
        echo "Lancement retrait antonymes dicoSynTag <br><br>";
        $CORPUS_TAG = array_map('strtolower',$CORPUS_TAG);

        // Lorsqu'on souhaite retirer les antonymes
        $dicoSynTagJson = file_get_contents('./data/dicoSynTag.json');
        $dicoSynTag = json_decode($dicoSynTagJson, true, JSON_PRETTY_PRINT);//JSON_UNESCAPED_UNICODE);

        // Retirer les antonymes des tags
        foreach ($CORPUS_TAG as $tagCourant) // Pour chaque tag
        {
            $listeAntTagCourant = antAvecAPI(strtolower(tradMotFrToAng($tagCourant))); // On exploite l'API pour récupérer les antonymes du tagCourant
            
            foreach($listeAntTagCourant as $antTagCrt) // Pour chaque antonyme du tagCourant
            {
                $antTagCourant = strtolower(tradMotAngToFr($antTagCrt));
                if(array_key_exists($antTagCourant,$dicoSynTag)){ // Si l'antonyme est présent comme clé
                    if(array_equal($dicoSynTag[$antTagCourant],array($tagCourant))) // S'il est seulement lié à notre tag
                    {
                        if(!(in_array($antTagCourant,$CORPUS_TAG))) // Si l'antonyme n'est pas un tag
                        {
                            echo 'Retrait de ' . $antTagCourant . ' du dicoSynTag<br/>';
                            unset($dicoSynTag[$antTagCourant]); // On le retire
                        }
                        
                    }
                    else // Sinon on l'enlève seulement de la liste
                    {
                        if (sizeof($dicoSynTag[$antTagCourant])==1) { // S'il y a un seul élément
                            echo 'Retrait de ' . $tagCourant . ' de la liste de tag liée à '. $antTagCourant .' dans dicoSynTag. Liste vide donc retrait de ' . $antTagCourant .' du dicoSynTag.<br/>';
                            unset($dicoSynTag[$antTagCourant]); // On le retire
                        }
                        else { // Sinon
                            echo 'Retrait de ' . $tagCourant . ' de la liste de tag liée à '. $antTagCourant .' dans dicoSynTag<br/>';
                            unset($dicoSynTag[$antTagCourant][array_search(strtolower($tagCourant),$dicoSynTag[$antTagCourant])]);
                            $dicoSynTag[$antTagCourant] = array_values($dicoSynTag[$antTagCourant]); // Pour remettre les indexs dans le bonne ordre
                            //$dicoSynTag[$antTagCourant] = array_merge(array_slice($dicoSynTag[$antTagCourant],0,array_search(strtolower($tagCourant),$dicoSynTag[$antTagCourant])),
                            //                                          array_slice($dicoSynTag[$antTagCourant]),array_search(strtolower($tagCourant),$dicoSynTag[$antTagCourant])+1);
                        }
                        
                    }
                }
            }
        }

        echo "encodage<br>";
        // Destination du fichier à modifier si jamais (ou ajouter un paramètres)
        file_put_contents('./data/dicoSynTag.json',json_encode($dicoSynTag,JSON_PRETTY_PRINT));//JSON_UNESCAPED_UNICODE));
        echo "encodage terminé<br><br>";

        return $dicoSynTag;
    }

    //retirerAntonymes($CORPUS_TAG);

    // Pour tout fusionner
    //file_put_contents('./data/dicoSynTagTmp2.json',json_encode(fusionnerBienCorpus('dicoSynTagSyn','dicoSynTagTrg'),JSON_PRETTY_PRINT));
    //file_put_contents('./data/dicoSynTag.json',json_encode(fusionnerBienCorpus('dicoSynTagGen','dicoSynTagTmp2'),JSON_PRETTY_PRINT));
?>