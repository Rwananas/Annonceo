<?php

function debug($var, $mode = 1){

    // fonction prédéfinie qui retourne entre autre le nom du fichier dans lequel on code, la ligne concernée etc...
    $trace = debug_backtrace();
    // array_shift, fonction prédéfinie qui permet de contourner une dimension d'un tableau (exemple pour connaitre le fichier, on aura pas a coder $trace[0][file], mais directement $trace[file])
    $trace = array_shift($trace);

    echo "Debug demandé à la ligne <strong> $trace[line]</strong>,dans le fichier <strong> $trace[file] </strong> ";

    // si on ne donne pas de deuxième parametre, debug() va utiliser un print_r pour analyser notre variable
    if($mode == 1){
        echo "<pre>"; print_r($var); echo "</pre>";
    }else{
        // si on lui donne un second paraùetre, elle fera un var_dump
        echo "<pre>"; var_dump($var); echo "</pre>";
    }

}

// fonction qui détermine si un utilisateur est connecté ou non (pourpersonnaliser son affichage, sa navigation)
function internauteConnecte(){
    // si un fichier contenant une sesssion nommée membre n'existe pas (dans le dossier wamp/tmp), alors la fonction internauteConnecte retourne FALSE, non il n'est pas connecté
    if(!isset($_SESSION['membre'])){
        return FALSE;
    }else{
        // dans le cas ou  ce fichier existe, cette même fonction retournera TRUE, oui il est connecté, car sa session existe
        return TRUE;
    }
}

function internauteConnecteAdmin(){
    if(internauteConnecte() && $_SESSION['membre']['statut'] == 1){
        return TRUE;
    }else{
        return FALSE;
    }
}