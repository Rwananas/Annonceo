<?php
require_once('include/init.php');

// code a venir

// récupération de l'annonce

//REQUETE POUR RECUPERER TOUT 
if (!empty($_GET['id_annonce'])) {

    $recup_annonce = $pdo->prepare("SELECT annonce.*, pseudo, prenom, telephone, email, categorie.titre AS titre_categorie FROM annonce, categorie, membre WHERE id_membre = membre_id AND id_categorie = categorie_id AND id_annonce = :id_annonce");
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


<!-- <h1><?= $infos_annonce['titre'] ?></h1>
<p><?= " 0" . $infos_annonce['telephone'] ?></p>


<img src="<?= $infos_photos_annexes['photo1'] ?>" alt="">
<div class="col-md-12">
            <?php echo '<pre>';
            print_r($infos_annonce);
            echo '</pre>'; ?>
            <?php echo '<pre>';
            var_dump($infos_photos_annexes);
            echo '</pre>'; ?>
        </div> -->




</div>

<div class="container-fluid">
    <div class="row justify-content-center py-5">
        <div class="col-md-10">
            <h2 class='text-center my-5'>
                <div class="badge badge-dark text-wrap p-3">Fiche de l'annonce <?= substr($detail['categorie_id'], 0, -1) . " " . $detail['titre'] ?></div>
            </h2>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <img src="<?= URL . 'img/' . $detail['photo'] ?>" class="img-fluid" alt="image de l'annonce <?= substr($detail['categorie_id'], 0, -1) . " " . $detail['titre'] ?>">
                </div>
                <div class="col-md-6">
                    <h5 class="card-title"><?= $detail['titre'] ?></h5>
                    <p class="card-text"><?= $detail['description_longue'] ?></p>
                    <p class="card-text">Pays : <?= $detail['pays'] ?></p>
                    <p class="card-text">Ville : <?= $detail['ville'] ?></p>
                    <p class="card-text">Adresse : <?= $detail['adresse'] ?></p>
                    <p class="card-text">Code postal : <?= $detail['code_postal'] ?></p>
                    <p class="card-text">Prix : <?= $detail['prix'] ?> €</p>
                    <p class="card-text"><small class="text-muted">Publiée le <?= $detail['date_enregistrement'] ?></small></p>
                    <a href="<?= URL ?>modifier_annonce.php?id=<?= $detail['id_annonce'] ?>" class="btn btn-sm btn-primary">Sélectionner</a>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="container">

    <?php require_once('include/footer.php') ?>