<?php
require_once('include/init.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Vérification des champs du formulaire
    if (empty($_POST['commentaire']) || strlen($_POST['commentaire']) < 3 || strlen($_POST['commentaire']) > 500) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format commentaire !</div>';
    }

    $inscrireCommentaire = $pdo->prepare("INSERT INTO commentaire (membre_id, annonce_id, commentaire, date_enregistrement) VALUES (:membre_id, :annonce_id, :commentaire, NOW())");
    $inscrireCommentaire->bindValue(':membre_id', $_SESSION['membre']['id_membre'], PDO::PARAM_STR);

    if (isset($_POST['id_annonce'])) {
        $inscrireCommentaire->bindValue(':annonce_id', $_POST['id_annonce'], PDO::PARAM_STR);
    } else {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur : id_annonce non défini !</div>';
    }

    $inscrireCommentaire->bindValue(':commentaire', $_POST['commentaire'], PDO::PARAM_STR);

    if (empty($erreur)) {
        // Enregistrement du commentaire en BDD

        if (!$inscrireCommentaire->execute()) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur SQL : ' . $inscrireCommentaire->errorInfo()[2] . '</div>';
        } else {
            echo '<div class="alert alert-success" role="alert">Le commentaire a été enregistré avec succès !</div>';
        }
    }
}

// Affichage des messages d'erreur
if (!empty($erreur)) {
    echo $erreur;
}

// Débogage
echo '<pre>';
var_dump($_SESSION['membre']['id_membre']);
var_dump($_POST['id_annonce']);
echo '</pre>';



header("Location: " . URL . "fiche_annonceModif.php?id_annonce=" . $_GET['id_annonce']);
exit();
