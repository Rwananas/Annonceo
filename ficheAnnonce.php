<?php
require_once('include/init.php');



// Code à venir

require_once('include/affichage.php');
// Positionner le pageTitle sous le require de l'affichage, car c'est dedans qu'a été declaré le tableau $détail
// Le substr avec la valeur -1 va enlever tous les pluriels (du coups la dernière lettre du mot) pour les catégories(je veux afficher le mot au singulier dans l'onglet title)
// $pageTitle = "Fiche " . substr($detail['categorie'], 0, -1) . " " . $detail['titre'];
require_once('include/header.php');
?>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- debut de la colonne qui va afficher les categories -->
        <div class="col-md-2">

            <?php while ($menuCategorie = $afficheMenuCategories->fetch(PDO::FETCH_ASSOC)) : ?>
                <div class="list-group text-center">
                    <a class="btn btn-outline-success my-2" href="<?= URL ?>?categorie=<?= $menuCategorie['categorie'] ?>"><?= $menuCategorie['categorie']  ?></a>
                </div>
            <?php endwhile; ?>

        </div>


        <!-- fin de la colonne catégories -->
        <div class="col-md-8">

            <h2 class='text-center my-5'>
                <div class="badge badge-dark text-wrap p-3">Fiche du produit <?= substr($detail['categorie'], 0, -1) . " " . $detail['titre'] ?></div>
            </h2>
            <!-- card  -->
            <div class="row justify-content-around text-center py-5">
                <div class="card shadow p-3 mb-5 bg-white rounded" style="width: 22rem;">
                    <img src="<?= URL . 'img/' . $detail['photo'] ?>" class="card-img-top" alt="image du produit <?= substr($detail['categorie'], 0, -1) . " " . $detail['titre'] ?>">
                    <div class="card-body">
                        <h3 class="card-title">
                            <div class="badge badge-dark text-wrap"><?= $detail['prix'] ?>€</div>
                        </h3>
                        <p class="card-text"><?= $detail['description'] ?></p>
                        <!-- ------------------- -->
                        <!-- Condition pour savoir si on affiche un selecteur pour choisir le nombre de produits que l'on veut (si il y a du stock) ou si on affiche le message d'alerte qui indique une rpture de stock -->
                        <?php if ($detail['stock'] > 0) : ?>
                            <!-- La quantité désirée sera récupérée sur la page panier(pour savoir combien il veut acheter), donc on indique dans l'attribut action, le nom du fichier panier.php -->
                            <form method="POST" action="panier.php">

                                <input type="hidden" name="id_produit" value="<?= $detail['id_produit'] ?>">

                                <label for="">J'en achète</label>
                                <select class="form-control col-md-5 mx-auto" name="quantite" id="quantite">
                                    <!-- ----------- -->
                                    <!-- Boucle qui va récupérer la quantité en stock pour permettre de choisir la quantité -->
                                    <?php for ($quantite = 1; $quantite <= min($detail['stock'], 5); $quantite++) : ?>
                                        <!-- La fonction prédéfinie min permet de n'afficher que 5 produit au maximum dans le sélécteur, même si j'en ai beaucoup plus en stock -->
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
                            <!-- ------------ -->
                        <?php endif; ?>
                        <!-- Lien pour retourner voir tous les produits de la même catégorie -->
                        <p><a href="<?= URL ?>?categorie=<?= $detail['categorie'] ?>">Voir tous les modèles de la même catégorie </a></p>
                        <p><a href="<?= URL ?>?public=<?= $detail['public'] ?>">Voir tous les modèles pour le même public</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="container">

    <?php require_once('include/footer.php'); ?>