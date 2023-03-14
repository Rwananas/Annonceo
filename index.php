<?php
require_once('include/init.php');

// PAGINATION GESTION ANNONCES


// Si un indice page existe dans l'URL et qu'on trouve une valeur dedans 
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $pageCourante = (int) strip_tags($_GET['page']);
} else {
    $pageCourante = 1;
}
$queryAnnonces = $pdo->query(" SELECT COUNT(id_annonce) AS nombreAnnonces FROM Annonce ");
$resultatAnnonces = $queryAnnonces->fetch();
$nombreAnnonces = (int) $resultatAnnonces['nombreAnnonces'];
$parPage = 6;
$nombresPages = ceil($nombreAnnonces / $parPage);
$premierAnnonce = ($pageCourante - 1) * $parPage;

// FIN PAGINATION GESTION ANNONCES

require_once('include/affichage.php');
require_once('include/header.php');
?>

</div>
<div class="container-fluid">

    <div class="row my-5">

        <div class="col-md-2">

            <div class="list-group text-center">
                <?php while ($menuCategorie = $afficheMenuCategories->fetch(PDO::FETCH_ASSOC)) : ?>
                    <a class="btn btn-outline-light my-2" href="<?= URL ?>?categorie=<?= $menuCategorie['id_categorie'] ?>"><?= $menuCategorie['titre'] ?></a>
                <?php endwhile; ?>
            </div>

        </div>

        <!-- --------------------------- -->
        <!-- pour afficher les Annonces par catégories -->
        <?php if (isset($_GET['categorie'])) : ?>
            <div class="col-md-8">

                <div class="text-center my-5">
                    <img class='img-fluid' src="./img/bandAnnonceo.svg" alt="Bandeau de La Boutique" loading="lazy">
                </div>

                <div class="row justify-content-around">
                    <h2 class="py-5">
                        <div class="badge badge-dark text-wrap">Nos modèles de <?= $titreCategorie['titre'] ?></div>
                    </h2>
                </div>

                <div class="row justify-content-around text-center">
                    <?php while ($annonce = $afficheAnnonces->fetch(PDO::FETCH_ASSOC)) : ?>
                        <div class="card mx-3 shadow p-3 mb-5 bg-white rounded" style="width: 18rem;">
                            <a href="fiche_annonceModif.php?id_annonce=<?= $annonce['id_annonce'] ?>"><img src="<?= URL . 'img/' . $annonce['photo'] ?>" class="card-img-top" alt="..."></a>
                            <div class="card-body">
                                <h3 class="card-title">
                                <div class="badge badge-dark text-wrap"><?= $annonce['titre'] ?></div>
                            </h3>
                                <h3 class="card-title">
                                    <div class="badge badge-dark text-wrap"><?= $annonce['description_courte'] ?></div>
                                </h3>
                                
                                <a href="fiche_annonceModif.php?id_annonce=<?= $annonce['id_annonce'] ?>" class="btn btn-outline-info"><i class='bi bi-search'></i> Voir Annonce</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <nav>
                    <ul class="pagination justify-content-end">
                        <!-- Dans le cas ou nous sommes sur la page 1, ternaire pour ajouter la class Bootstrap 'disabled' afin de désactiver le bouton précédent car il n'y a pas de page précédente à la 1ere  -->
                        <li class="page-item <?= ($pageCourante == 1) ? 'disabled' : "" ?>">
                            <!-- Si on clique sur la flêche précédente, c'est pour aller à la page précédent, dans ce cas on soustrait à $pageCourante, la valeur de 1 (si pageCourante = 4, on retournera à la page 3) -->
                            <a class="page-link text-dark" href="?page=<?= $pageCourante - 1 ?>" aria-label="Previous">
                                <span aria-hidden="true">précédente</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>

                        <!-- AFFICHAGE DU NOMBRE DE PAGE -->
                        <?php for ($page = 1; $page <= $nombresPages; $page++) : ?>
                            <li class="mx-1 page-item ">
                                <a class="btn btn-outline-dark <?= ($pageCourante == $page) ? 'active' : "" ?>" href="?page=<?= $page ?>"><?= $page ?></a>
                            </li>
                        <?php endfor; ?>
                        <!-- Fin d'affichage nombre de pages -->


                        <li class="page-item <?= ($pageCourante == $nombresPages) ? 'disabled' : "" ?>">
                            <a class="page-link text-dark" href="?page=<?= $pageCourante + 1 ?>" aria-label="Next">
                                <span aria-hidden="true">suivante</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    </ul>
                </nav>

            </div>

            <!-- ----------------------- -->
            <!-- pour afficher les vetements  par public -->




    </div>

    <!-- ------------------------------ -->
<?php else : ?>
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