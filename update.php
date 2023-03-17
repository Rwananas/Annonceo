<?php

session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['membre'])) {
    header('Location: inscription.php');
    exit();
}

// Vérifie si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Récupère les nouvelles valeurs pour chaque champ
    $new_pseudo = isset($_POST['new_pseudo']) ? $_POST['new_pseudo'] : $_SESSION['membre']['pseudo'];
    $new_nom = isset($_POST['new_nom']) ? $_POST['new_nom'] : $_SESSION['membre']['nom'];
    $new_prenom = isset($_POST['new_prenom']) ? $_POST['new_prenom'] : $_SESSION['membre']['prenom'];
    $new_mail = isset($_POST['new_mail']) ? $_POST['new_mail'] : $_SESSION['membre']['email'];
    $new_telephone = isset($_POST['new_telephone']) ? $_POST['new_telephone'] : $_SESSION['membre']['telephone'];

    // Met à jour les informations du membre dans la base de données
    // Ici vous pouvez utiliser une requête SQL pour mettre à jour les champs de la table "membre"
    
    // Met à jour les informations de session avec les nouvelles valeurs
    $_SESSION['membre']['pseudo'] = $new_pseudo;
    $_SESSION['membre']['nom'] = $new_nom;
    $_SESSION['membre']['prenom'] = $new_prenom;
    $_SESSION['membre']['email'] = $new_mail;
    $_SESSION['membre']['telephone'] = $new_telephone;
    
    // Redirige vers la page de profil avec un message de confirmation
    header('Location: profil.php?message=success');
    exit();
} else {
    // Si la requête n'est pas une méthode POST, redirige vers la page de profil
    header('Location: profil.php');
    exit();
}
