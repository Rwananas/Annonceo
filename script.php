<?php
require_once('include/init.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Vérification des champs du formulaire
    if (empty($_POST['commentaire']) || strlen($_POST['commentaire']) < 3 || strlen($_POST['commentaire']) > 500) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format commentaire !</div>';
    }

    if (empty($erreur)) {
        // Enregistrement du commentaire en BDD
        $inscrireUser = $pdo->prepare("INSERT INTO commentaire (membre_id, annonce_id, commentaire, date_enregistrement) VALUES (:membre_id, :annonce_id, :commentaire, NOW())");
        $inscrireUser->bindValue(':membre_id', $_SESSION['membre']['id_membre'], PDO::PARAM_STR);
        $inscrireUser->bindValue(':annonce_id', $_GET['id_annonce'], PDO::PARAM_STR);
        $inscrireUser->bindValue(':commentaire', $_POST['commentaire'], PDO::PARAM_STR);
        $inscrireUser->execute();
    }
}

header("Location: " . URL . "annonce.php?id_annonce=" . $_GET['id_annonce']);
exit();
