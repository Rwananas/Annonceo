<?php
require_once('include/init.php');

// recupere les annonces dans la base de donnees
$arrayAnnonce = '';
$afficheAnnonce = '';
$add = '';
// recupere les annonces dans la base de donnees
$afficheAnnonce3 = $pdo->query('SELECT * FROM categorie');
// Sélection des villes
$afficheAnnonce4 = $pdo->query('SELECT DISTINCT ville FROM annonce');
// Obtenir tous les membres
$afficheAnnonce5 = $pdo->query('SELECT * FROM membre');
// Obtenir toutes les annonces
$afficheAnnonce6 = $pdo->query('SELECT * FROM annonce');


// si l'annonce n'existe pas
if (isset($_GET['annonce']) && $_GET['annonce'] == "inexistant") {
    $erreur .= "<div class='col-md-6 mx-auto alert alert-danger text-center disparition'>
                        Annonce inexistante
                    </div>";
}
// PAGINATION GESTION ANNONCES


// Si un indice page existe dans l'URL et qu'on trouve une valeur dedans 
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $pageCourante = (int) strip_tags($_GET['page']);
} else {
    $pageCourante = 1;
}
$queryAnnonces = $pdo->query(" SELECT COUNT(id_annonce) AS nombreAnnonces FROM annonce ");
$resultatAnnonces = $queryAnnonces->fetch();
$nombreAnnonces = (int) $resultatAnnonces['nombreAnnonces'];
$parPage = 2;
$nombresPages = ceil($nombreAnnonces / $parPage);
$premierAnnonce = ($pageCourante - 1) * $parPage;

// FIN PAGINATION GESTION ANNONCES

// fin pagination
require_once('include/affichage.php');
require_once('include/header.php');
?>

</div>
<!-- Rubrique des différents catégories -->


<div class="row">

    <!-- --------------------------- -->
    <!-- Afficher les annonces par catégories -->
    <?php if (isset($_GET['categorie'])) : ?>
        <div class="col-md-8">

            <div class="text-center my-5">
                <img class='img-fluid' src="img/imglateralAnnonceo.png" alt="Bandeau de La Boutique" loading="lazy">
            </div>

            <div class="row justify-content-around">
                <h2 class="py-5">
                    <div class="badge badge-dark text-wrap"><?= $titreCategorie['titre'] ?> </div>
                </h2>
            </div>

            <div class="row justify-content-around text-center">
                <?php while ($annonce = $afficheAnnonces->fetch(PDO::FETCH_ASSOC)) : ?>
                    <div class="card mx-3 shadow p-3 mb-5 bg-white rounded">
                        <a href="fiche_annonceModif.php?id_annonce= <?= $annonce['id_annonce'] ?>"><img src="<?= URL . 'img/' . $annonce['photo'] ?>" class="card-img-top" alt="Photo de <?= $annonce['titre'] ?>"></a>
                        <div class="card-body">
                            <h3 class="card-title"><?= $annonce['titre'] ?></h3>
                            <h3 class="card-title">
                                <div class="badge badge-dark text-wrap"><?= $annonce['prix'] ?> €</div>
                            </h3>
                            <p class="card-text"><?= $annonce['description_courte'] ?></p>
                            <!-- Requete pour véhiculer l'id de chaque annonce et pouvoir l'afficher et basculer sur la page fiche annonce  -->
                            <a href="<?= URL ?>fiche_annonceModif.php?id_annonce=<?= $annonce['id_annonce'] ?>" class="btn btn-outline-dark"><i class='bi bi-search'></i> Voir Annonce</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- ----------------------- -->
        <!-- pour afficher les annonces  par titre -->
    <?php elseif (isset($_GET['titre'])) : ?>

        <div class="col-md-8">

            <div class="row justify-content-around">
                <h2 class="py-5">
                    <div class="badge badge-dark text-wrap">Annonce <?= ucfirst($titreAnnonce['titre']) ?>s </div>
                </h2>
            </div>



            <nav aria-label="">
                <!-- dans les 3 <a href> je dois faire référence à la catégorie, en plus de la page, sinon cela ne fonctionnera pas -->
                <ul class="pagination justify-content-end">
                    <li class="mx-1 page-item  ">
                        <a class="page-link text-success" href="" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>

                    <li class="mx-1 page-item ">
                        <a class="btn btn-outline-success " href=""></a>
                    </li>

                    <li class="mx-1 page-item ">
                        <a class="page-link text-success" href="" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>

        <!-- ------------------------------ -->
    <?php else : ?>

</div>
<div class="p-5">
    <form method="post" action="" name="form_filtres">
        <br><br>
        <div class="row">
            <!-- Filtres de recherche -->
            <div class="col-md-4">
                <label for="ordre">Trier</label>
                <select name="ordre" id="ordre" class="form-control col-sm-10">
                    <option value="" disabled selected>Trier</option>
                    <option value="prix_ascendant">Du - cher au + cher</option>
                    <option value="prix_descendant">Du + cher au - cher</option>
                    <option value="date_ascendant">Du - ancien au + ancien</option>
                    <option value="date_descendant">Du + ancien au - ancien</option>
                </select>
                <!-- Categorie -->
                <label for="categorie">Catégorie</label>
                <select name="categorie" id="categorie" class="form-control col-sm-10">
                    <option value="" disabled selected>Toutes les categories</option>
                    <?php while ($arrayCategorie = $afficheAnnonce3->fetch(PDO::FETCH_ASSOC)) : ?>
                        <option value="<?= $arrayCategorie['id_categorie'] ?>" <?php if (isset($_POST['categorie']) && $_POST['categorie'] == $arrayCategorie['id_categorie']) echo "selected" ?>><?= $arrayCategorie['titre']  ?></option>
                    <?php endwhile; ?>
                    <option value=""></option> <!-- Option vide pour réinitialiser le champ -->
                </select>

                <!-- Region -->
                <label for="region">Région</label>
                <select name="region" id="region" class="form-control col-sm-10">
                    <option value="" selected>Toutes les régions</option>
                    <?php while ($arrayRegion = $afficheAnnonce4->fetch(PDO::FETCH_ASSOC)) : ?>
                        <option value="<?= $arrayRegion['ville'] ?>"><?= $arrayRegion['ville']  ?></option>
                    <?php endwhile; ?>
                </select>
                <!-- Membre -->
                <label for="membre">Membre</label>
                <select name="membre" id="membre" class="form-control col-sm-10">
                    <option value="" selected>Tous les membres</option>
                    <?php while ($arrayMembre = $afficheAnnonce5->fetch(PDO::FETCH_ASSOC)) : ?>
                        <option value="<?= $arrayMembre['id_membre'] ?>"><?= $arrayMembre['nom']  ?></option>
                    <?php endwhile; ?>
                </select>
                <!-- Prix -->
                <div class="row">
                    <div class="col-10">
                        <label for="customRange">Prix</label>
                        <input type="range" name="prix" class="custom-range" min="0" max="10000" step="10" value="0" id="customRange">
                        <p>Prix: <span id="prix"></span> €</p>
                        <p class="font-italic font-weight-light"> PS: maximum 10 000 €</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-10 justify-content-center">
                        <input type="submit" value="valider" class="btn btn-primary">
                    </div>
                </div>
            </div>

            <!-- Espacement -->
            <div class="col-sm-1"></div>

            <!-- Annonces -->
            <div class="  col-sm-6 col-12">
            </div>
        </div>
    </form>


<?php endif; ?>

<?= $erreur ?>

<!-- Titre  -->
<div class="py-5">
    <h2 class="text-center pb-5"> Découvrez toutes nos annonces !</h2>
</div>
<!-- AFFICHAGES DE L'ENSEMBLE DES ANNONCES  -->
<?php
$toutesAnnonces = array();

if ($_POST) {

    if (!empty($_POST['categorie'])) {
        $categorie = $_POST['categorie'];

        $toutesAnnonces[] = " categorie_id IN (" . $categorie . ") ";
    }
    if (!empty($_POST['region'])) {
        $text = "'";
        $region = $_POST['region'];

        $toutesAnnonces[] = " ville IN (" . $text . $region . $text . ") ";
    }
    if (!empty($_POST['membre'])) {
        $membre = $_POST['membre'];

        $toutesAnnonces[] = " membre_id IN (" . $membre . ") ";
    }
    if (!empty($_POST['prix'])) {
        $prix = $_POST['prix'];

        $toutesAnnonces[] = " prix < " . $prix . " ";
    }

    if (isset($_POST['ordre']) && $_POST['ordre'] == 'prix_ascendant') {
        $add = 'ORDER BY prix ASC';
    }
    if (isset($_POST['ordre']) && $_POST['ordre'] == 'prix_descendant') {
        $add = 'ORDER BY prix DESC';
    }
    if (isset($_POST['ordre']) && $_POST['ordre'] == 'date_ascendant') {
        $add = 'ORDER BY date_enregistrement ASC';
    }
    if (isset($_POST['ordre']) && $_POST['ordre'] == 'date_descendant') {
        $add = 'ORDER BY date_enregistrement ASC';
    }
}
$condition = "";
if (!empty($toutesAnnonces)) {
    $condition = ' AND ';
}
$allAnonnonces = 'SELECT * FROM annonce WHERE 1 ' . $condition . implode(' AND ', $toutesAnnonces) . $add;
$afficheAnnonce = $pdo->query($allAnonnonces);
?>

<?php
while ($arrayAnnonce = $afficheAnnonce->fetch(PDO::FETCH_ASSOC)) :

    $afficheAnnonce2 = $pdo->prepare('SELECT prenom FROM membre WHERE id_membre = :id_membre');
    $afficheAnnonce2->bindValue(':id_membre', $arrayAnnonce['membre_id'], PDO::PARAM_INT);
    $afficheAnnonce2->execute();

    $arrayMembre = $afficheAnnonce2->fetch(PDO::FETCH_ASSOC);

    $allPhotos = $pdo->prepare('SELECT * FROM photo WHERE id_photo = :id_photo');
    $allPhotos->bindValue(':id_photo', $arrayAnnonce['photo_id'], PDO::PARAM_INT);
    $allPhotos->execute();

    $detail = $allPhotos->fetch(PDO::FETCH_ASSOC);


?>
    <?php //echo debug($arrayAnnonce) 
    ?>

    <!-- AFFICHAGE DES ANNONCES  -->
    <div class="container py-5">
        <?php $afficheAnnonces = $pdo->query("SELECT * FROM annonce ORDER BY prix ASC LIMIT $parPage OFFSET $premierAnnonce") ?>
        <a class="btn border-bottom col-md-12 mt-1 mb-1  " href="fiche_annonceModif.php?id_annonce=<?= $arrayAnnonce['id_annonce'] ?>">
            <div class="row  align-items-center col-md-10  ">
                <!-- Image -->
                <div class="col-sm-6 align-self-center ">
                    <?php if ($arrayAnnonce['photo'] != "") :  ?>
                        <img class='w-100' src="img/<?= $arrayAnnonce['photo'] ?>" alt="<?= $arrayAnnonce['titre'] ?>" title="<?= $arrayAnnonce['titre'] ?>">
                    <?php else :  ?>
                        <img class='w-50' src="img/" alt="image de l'annonce" title="Image par défaut">
                    <?php endif;  ?>
                </div>
                <!-- Description -->
                <div class="col-sm-6">
                    <h6 class="m-2 text-primary text-left"><?= $arrayAnnonce['titre'] ?></h6>
                    <p class="text-left"><?= $arrayAnnonce['description_courte'] ?></p>
                    <div class="row ">
                        <div class="col ">
                            <p class="text-left"><?= $arrayMembre['prenom'] ?> <?= ($arrayAnnonce['membre_id']) ?><i class="bi bi-star-fill" style="color: #FFD700"></i></p>
                        </div>
                        <div class="col">
                            <h6 class="m-2"><?= $arrayAnnonce['prix'] ?> €</h6>
                        </div>
                    </div>
                </div>

            </div>
        </a>
    </div>
<?php endwhile;   ?>
<!-- PAGINATION BOT TAB-->
<nav>
    <ul class="pagination justify-content-end">
        <!-- Dans le cas ou nous sommes sur la page 1, ternaire pour ajouter la class Bootstrap 'disabled' afin de désactiver le bouton précédent car il n'y a pas de page précédente à la 1ere  -->
        <li class="page-item <?= ($pageCourante == 1) ? 'disabled': "" ?>">
        <!-- Si on clique sur la flêche précédente, c'est pour aller à la page précédent, dans ce cas on soustrait à $pageCourante, la valeur de 1 (si pageCourante = 4, on retournera à la page 3) -->
            <a class="page-link text-dark" href="?page=<?= $pageCourante - 1 ?>" aria-label="Previous">
                <span aria-hidden="true">précédente</span>
                <span class="sr-only">Previous</span>
            </a>
        </li>
        
        <!-- AFFICHAGE DU NOMBRE DE PAGE -->
        <?php for($page = 1; $page <= $nombresPages; $page++): ?>
        <li class="mx-1 page-item ">
            <a class="btn btn-outline-dark <?= ($pageCourante == $page) ? 'active': "" ?>" href="?page=<?= $page?>"><?= $page?></a>
        </li>
        <?php endfor; ?>
        <!-- Fin d'affichage nombre de pages -->


        <li class="page-item <?= ($pageCourante == $nombresPages) ? 'disabled': "" ?>">
            <a class="page-link text-dark" href="?page=<?= $pageCourante + 1 ?>" aria-label="Next">
                <span aria-hidden="true">suivante</span>
                <span class="sr-only">Next</span>
            </a>
        </li>
    </ul>
</nav>
<!-- PAGINATION FIN BOT TAB-->

<?php require_once('include/footer.php') ?>