<?php
require_once('include/init.php');

// code a venir

require_once('include/affichage.php');
require_once('include/header.php');
?>

</div>
<div class="container-fluid">

    <div class="row my-5">

        <div class="col-md-2">

            <div class="list-group text-center">
            <?php while($menuCategorie = $afficheMenuCategories->fetch(PDO::FETCH_ASSOC)): ?>
                <a class="btn btn-outline-info my-2" href="<?= URL ?>?categorie=<?= $menuCategorie['id_categorie'] ?>"><?= $menuCategorie['titre'] ?></a>
            <?php endwhile; ?>
            </div>

        </div>

        <!-- --------------------------- -->
        <!-- pour afficher les Annonces par catégories -->
        <?php if(isset($_GET['categorie'])): ?>
        <div class="col-md-8">

            <div class="text-center my-5">
                <img class='img-fluid' src="#" alt="Bandeau de La Boutique" loading="lazy">
            </div>

            <div class="row justify-content-around">
                <h2 class="py-5">
                    <div class="badge badge-dark text-wrap">Nos modèles de <?= $titreCategorie['titre'] ?></div>
                </h2>
            </div>

            <div class="row justify-content-around text-center">
                <?php while($produit = $afficheAnnonces->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="card mx-3 shadow p-3 mb-5 bg-white rounded" style="width: 18rem;">
                    <a href="fiche_produit.php?id_produit=<?= $produit['id_annonce'] ?>"><img src="<?= URL . 'img/' . $produit['photo'] ?>" class="card-img-top" alt="..."></a>
                    <div class="card-body">
                        <h3 class="card-title"><?= $produit['titre'] ?></h3>
                        <h3 class="card-title">
                            <div class="badge badge-dark text-wrap"><?= $produit['prix'] ?> €</div>
                        </h3>
                        <p class="card-text"><?= $produit['description_courte'] ?></p>
                        <a href="fiche_produit.php?id_produit=<?= $produit['id_annonce'] ?>" class="btn btn-outline-info"><i class='bi bi-search'></i> Voir Produit</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>

            <nav aria-label="">
                <ul class="pagination justify-content-end">
                    <li class="mx-1 page-item  ">
                        <a class="page-link text-info" href="" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <!--  -->
                    <li class="mx-1 page-item ">
                        <a class="btn btn-outline-info " href=""></a>
                    </li>
                    <!--  -->
                    <li class="mx-1 page-item ">
                        <a class="page-link text-info" href="" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>

        <!-- ----------------------- -->
        <!-- pour afficher les vetements  par public -->
        <?php elseif(isset($_GET['public'])): ?>

        <div class="col-md-8">

            <div class="text-center my-5">
                <img class='img-fluid' src="img/la_boutique_bis.webp" alt="Bandeau de La Boutique" loading="lazy">
            </div>

            <div class="row justify-content-around">

                <h2 class="py-5">
                    <div class="badge badge-dark text-wrap">Nos vêtements <?= ucfirst($titrePublic['public']) ?>s </div>
                </h2>
            </div>

            <div class="row justify-content-around text-center">
            <!-- boucle while qui récupérer tous les vetements s'adressant à un même public ( arelier la requete codées dans affichage.php) -->
            <?php while($produit = $afficheProduits->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="card mx-3 shadow p-3 mb-5 bg-white rounded" style="width: 18rem;">
                    <a href="fiche_produit.php?id_produit=<?= $produit['id_produit'] ?>"><img src="<?= URL . 'img/' . $produit['photo'] ?>" class="card-img-top" alt="Photo de <?= $produit['titre'] ?>"></a>
                    <div class="card-body">
                        <h3 class="card-title"><?= $produit['titre'] ?></h3>
                        <h3 class="card-title">
                            <div class="badge badge-dark text-wrap"><?= $produit['prix'] ?> €</div>
                        </h3>
                        <p class="card-text"><?= $produit['description'] ?></p>
                        <a href="fiche_produit.php?id_produit=<?= $produit['id_produit'] ?>" class="btn btn-outline-info"><i class='bi bi-search'></i> Voir Produit</a>
                    </div>
                </div>
            <?php endwhile; ?>
            </div>

            <nav aria-label="">
                <!-- dans les 3 <a href> je dois faire référence à la catégorie, en plus de la page, sinon cela ne fonctionnera pas -->
                <ul class="pagination justify-content-end">
                    <li class="mx-1 page-item  ">
                        <a class="page-link text-info" href="" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>

                    <li class="mx-1 page-item ">
                        <a class="btn btn-outline-info " href=""></a>
                    </li>

                    <li class="mx-1 page-item ">
                        <a class="page-link text-info" href="" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>

        <!-- ------------------------------ -->
        <?php else: ?>
        <div class="col-md-8">

            <div class="row justify-content-around py-5">
                <img class='img-fluid' src="img/bandAnnonceo.svg" alt="Bandeau de La Boutique" loading="lazy">
            </div>

        </div>
        <?php endif; ?>

    </div>

</div>
<div class="container">

    <?php require_once('include/footer.php') ?>