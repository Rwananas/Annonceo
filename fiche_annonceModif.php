<?php
require_once('include/init.php');

// code a venir

// récupération de l'annonce

//REQUETE POUR RECUPERER TOUT 
if(!empty($_GET['id_annonce'])) {

    $recup_annonce = $pdo->prepare("SELECT annonce.*, pseudo, prenom, telephone, email, categorie.titre AS titre_categorie FROM annonce, categorie, membre WHERE id_membre = membre_id AND id_categorie = categorie_id AND id_annonce = :id_annonce");
    $recup_annonce->bindParam(':id_annonce', $_GET['id_annonce']);
    $recup_annonce->execute();

    // POUR RECUPERER LES PHOTOS
    if($recup_annonce->rowCount() > 0) {
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
            <?php echo '<pre>'; print_r($infos_annonce); echo '</pre>'; ?>
            <?php echo '<pre>'; var_dump($infos_photos_annexes); echo '</pre>'; ?>
        </div> -->




        </div>

<div class="container-fluid">
    <div class="row">
        <!-- debut de la colonne qui va afficher les categories -->
        <div class="col-md-2">

            <div class="list-group text-center">

                <?php while ($menuCategorie = $afficheMenuCategories->fetch(PDO::FETCH_ASSOC)) : ?>
                    <a class="btn btn-outline-success my-2" href="<?= URL ?>?categorie=<?= $menuCategorie['titre'] ?>"><?= $menuCategorie['titre'] ?></a>
                <?php endwhile; ?>

            </div>

        </div>
        <!-- fin de la colonne catégories -->
        <div class="col-md-8">

            <h2 class='text-center my-5'>
                <div class="badge badge-dark text-wrap p-3">Fiche du produit <?= substr($detail['categorie_id'], 0, -1) . " " . $detail['titre'] ?></div>
            </h2>

            <div class="row justify-content-around text-center py-5">
                <div class="card shadow p-3 mb-5 bg-white rounded" style="width: 22rem;">
                    <img src="<?= URL . 'img/' . $detail['photo'] ?>" class="card-img-top" alt="image du produit <?= substr($detail['categorie_id'], 0, -1) . " " . $detail['titre'] ?>">
                    <div class="card-body">
                        <h3 class="card-title">
                            <div class="badge badge-dark text-wrap"><?= $detail['prix'] ?> €</div>
                        </h3>
                        <p class="card-text"><?= $detail['description_courte'] ?></p>
                        <!-- ------------------- -->
                        <!-- condition pour savoir si on affiche un sélecteur pour choisir le nombre de produits que l'on veut (s'il y a du stock) ou si on afiche le message d'alerte qui indique une rupture de stock -->
                        <?php if ($detail['titre'] > 0) : ?>
                            <!-- La quantité désirée sera récupérée sur la page panier (pour savoir combien il veut acheter), donc on indique dans l'attribut action, le nom du fichier panier.php -->
                            <form method="POST" action="panier.php">
                                <input type="hidden" name="id_annonce" value="<?= $detail['id_annonce'] ?>">
                                <label for="quantite">J'en achète</label>
                                <select class="form-control col-md-5 mx-auto" name="quantite" id="quantite">
                                    <!-- ----------- -->
                                    <!-- boucle qui va récupérer la quantité en stock pour permettre de choisir la quantité -->
                                    <?php for ($quantite = 1; $quantite <= min($detail['stock'], 5); $quantite++) : ?>
                                        <!-- la fonction prédéfinie min (au dessus) permet de n'afficher que 5 au maximum dans le sélecteur, même si j'en ai plus en stock. Volontairement, après reflexion, je ne veux pas vendre plus de 5 articles du même produit durant la même vente  -->
                                        <option class="bg-dark text-light" value="<?= $quantite ?>"><?= $quantite ?></option>
                                    <?php endfor; ?>
                                    <!-- ----------- -->
                                </select>
                                <button type="submit" class="btn btn-outline-success my-2" name="ajout_panier" value="ajout_panier"><i class="bi bi-plus-circle"></i> Panier <i class="bi bi-cart3"></i></button>
                            </form>
                        <?php else : ?>
                            <!-- ----------- -->
                            <p class="card-text">
                            <div class="badge badge-danger text-wrap p-3">Produit en rupture de stock</div>
                            </p>
                        <?php endif; ?>
                        <!-- ------------ -->
                        <!-- lien pour retourner voir tous les produits de la même catégorie -->
                        <p>Voir tous les modèles <a href="<?= URL ?>?categorie=<?= $detail['categorie_id'] ?>">de la même catégorie</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">

    <?php require_once('include/footer.php') ?>