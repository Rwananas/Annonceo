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
    <div class="row">
        <!-- debut de la colonne qui va afficher les categories -->
        <div class="col-md-2">

            <div class="list-group text-center">

                <?php while ($menuCategorie = $afficheMenuCategories->fetch(PDO::FETCH_ASSOC)) : ?>
                    <a class="btn btn-outline-light my-2" href="<?= URL ?>?categorie=<?= $menuCategorie['titre'] ?>"><?= $menuCategorie['titre'] ?></a>
                <?php endwhile; ?>

            </div>

        </div>
        <!-- fin de la colonne catégories -->
        <div class="col-md-8">

            <h2 class='text-center my-5'>
                <div class="badge badge-dark text-wrap p-3">Fiche de l'annonce <?= substr($detail['categorie_id'], 0, -1) . " " . $detail['titre'] ?></div>
            </h2>

            <div class="row justify-content-around text-center py-5">
                <div class="card shadow p-3 mb-5 bg-white rounded" style="width: 22rem;">
                    <img src="<?= URL . 'img/' . $detail['photo'] ?>" class="card-img-top" alt="image du produit <?= substr($detail['categorie_id'], 0, -1) . " " . $detail['titre'] ?>">
                    <div class="card-body">
                        <h3 class="card-title">
                            <div class="badge badge-dark text-wrap"><?= $detail['prix'] ?> €</div> <br>
                            <div class="badge badge-dark text-wrap"><?= $detail['description_courte'] ?></div>

                        </h3>
                        <p class="card text-dark"><?= $detail['description_longue'] ?></p>
                        <!-- ------------------- -->
                        <!-- condition pour savoir si on affiche un sélecteur pour choisir le nombre de produits que l'on veut (s'il y a du stock) ou si on afiche le message d'alerte qui indique une rupture de stock -->
                        <?php if ($detail['titre'] > 0) : ?>
                            <!-- La quantité désirée sera récupérée sur la page panier (pour savoir combien il veut acheter), donc on indique dans l'attribut action, le nom du fichier panier.php -->
                            
                        <?php else : ?>
                            <!-- ----------- -->
                            <p class="card-text">
                            <div class="badge badge-danger text-wrap p-3">Produit en rupture de stock</div>
                            </p>
                        <?php endif; ?>
                        <!-- ------------ -->
                        <p class="text-dark">Voir tous les modèles <a href="<?= URL ?>?categorie=<?= $detail['categorie_id'] ?>">de la même catégorie</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">

    <?php require_once('include/footer.php') ?>