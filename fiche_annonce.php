<?php
require_once('include/init.php');

// code a venir

// récupération de l'annonce
//REQUETE POUR RECUPERER TOUT 
if (!empty($_GET['id_annonce'])) {

    $recup_annonce = $pdo->prepare("SELECT annonce .*, pseudo, prenom, telephone, email, categorie.titre AS titre_categorie FROM annonce, categorie, membre WHERE id_membre = membre_id AND id_categorie = categorie_id AND id_annonce = :id_annonce");
    $recup_annonce->bindParam(':id_annonce', $_GET['id_annonce']);
    $recup_annonce->execute();

    // POUR RECUPERER LES PHOTOS
    if ($recup_annonce->rowCount() > 0) {
        $infos_annonce = $recup_annonce->fetch(PDO::FETCH_ASSOC);

        $liste_photos_annexes = $pdo->prepare("SELECT * FROM photo WHERE id_photo = :id_photo");
        $liste_photos_annexes->bindParam(':id_photo', $infos_annonce['photo_id']);
        $liste_photos_annexes->execute();

        $infos_photos_annexes = $liste_photos_annexes->fetch(PDO::FETCH_ASSOC);
    } else {
        header('location:index.php');
    }
} else {
    header('location:index.php');
}



require_once('include/affichage.php');
require_once('include/header.php');
?>

<h1> <?= $infos_annonce['titre'] ?> </h1>
<div class="col-md-12">
    <?php echo '<pre>';
    print_r($infos_annonce);
    echo '</pre>'; ?>
    <?php echo '<pre>';
    var_dump($infos_photos_annexes);
    echo '</pre>'; ?>
</div>