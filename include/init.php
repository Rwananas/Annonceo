<?php

// dans ce fichier init, on va coder tout ce qui va nous servir sur l'intégralité des fichiers de notre boutique

// connexion à la bdd
// $pdo = new PDO('mysql:host=localhost;dbname=annonceo', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8') );
$pdo = new PDO('mysql:host=db5012372986.hosting-data.io;dbname=dbs10405494; charset=utf8', 'dbu2805641', 'Erw4nnIonos!', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8') );

// le session_start obligatoire en haut de chaque fichier
session_start();

// constante pour définir le chemin vers mon projet (je récupère automatiqument C:/wamp/www grace à $_SERVER[DOCUMENT_ROOOT] auquel je concatène le dossier de mon projet)
// ce chemin fonctionnera en local comme en ligne
define('RACINE_SITE', $_SERVER['DOCUMENT_ROOT'] .'/annonceo/' );

// constante URL pour notre projet (a modifier avec le nom de domaine plus tard lorsque le site sera hébergé, mis en ligne)
// define('URL', 'http://localhost/annonceo/');
define('URL', 'https://dwrone.com/annonceo/');

// initialisation de qlq variables utiles sur tout le site
$erreur = "";
$erreur_index = "";
$validate = "";
$validate_index = "";
$content = "";

$servername = 'db5012372986.hosting-data.io';
$username = 'dbu2805641';
$password = 'Erw4nnIonos!';
$dbname = 'dbs10405494';

// protection des formulaires avec une foreach additionnée avec htmlspecialchars
foreach($_POST as $key => $value){
    // on ajoute trim pour des gains en espace mémoire
    // elle va supprimer tous les espaces avant et aprés la valeur renseignée (ils sont inutiles)
    $_POST[$key] = htmlspecialchars(trim($value));
}

// protection des url avec une foreach additionnée avec htmlspecialchars
foreach($_GET as $key => $value){
    $_GET[$key] = htmlspecialchars(trim($value));
}

// inclusion de tout le code de ce fichier, pour le distribuer à toutes les pages du site, en une seule fois
require_once('fonctions.php');