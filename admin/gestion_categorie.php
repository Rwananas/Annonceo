<?php
require_once('../include/init.php');

if (!internauteConnecteAdmin()) {
    header('location:' . URL . 'index.php');
    exit();
}

// PAGINATION GESTION CATEGORIES

if (isset($_GET['page']) && !empty($_GET['page'])) {
    
    $pageCourante = (int) strip_tags($_GET['page']);
} else {    
    $pageCourante = 10;
}
$queryCategories = $pdo->query(" SELECT COUNT(id_categorie) AS nombreCategories FROM categorie ");
$resultatCategories = $queryCategories->fetch();
$nombreCategories = (int) $resultatCategories['nombreCategories'];
$parPage = 10;
$nombresPages = ceil($nombreCategories / $parPage);
$premierCategorie = ($pageCourante - 1) * $parPage;

// FIN PAGINATION

if (isset($_GET['action'])) {
    if ($_POST) {
        // CATEGORIE
        if (!isset($_POST['categorie']) || iconv_strlen($_POST['categorie']) < 3 || iconv_strlen($_POST['categorie']) > 20) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format categorie !</div>';
        }
        // TITRE
        if (!isset($_POST['titre']) || iconv_strlen($_POST['titre']) < 3 || iconv_strlen($_POST['titre']) > 20) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format titre !</div>';
        }
        // MOTS CLES
        if (!isset($_POST['motscles']) || iconv_strlen($_POST['motscles']) < 3 || iconv_strlen($_POST['motscles']) > 300) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format motscles !</div>';
        }

        if (empty($erreur)) {
            if ($_GET['action'] == 'update') {
                $modifCategorie =  $pdo->prepare(" UPDATE categorie SET id_categorie = :id_categorie, titre = :titre, motscles = :motscles WHERE id_categorie = :id_categorie");
                $modifCategorie->bindValue(':id_categorie', $_POST['id_categorie'], PDO::PARAM_STR);
                $modifCategorie->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
                $modifCategorie->bindValue(':motscles', $_POST['motscles'], PDO::PARAM_STR);
                
                $modifCategorie->execute();

            } 
        }
    }

    // RECUPERATION DES INFOS EN BDD POUR LES AFFICHER DANS LE FORMULAIRE LORS D'UN UPDATE
    if ($_GET['action'] == 'update') {
        $tousCategories = $pdo->query("SELECT * FROM categorie WHERE id_categorie = '$_GET[id_categorie]' ");
        $currentCategorie = $tousCategories->fetch(PDO::FETCH_ASSOC);
    }

    $id_categorie = (isset($currentCategorie['id_categorie'])) ? $currentCategorie['id_categorie'] : "";
    $titre = (isset($currentCategorie['titre'])) ? $currentCategorie['titre'] : "";
    $motscles = (isset($currentCategorie['motscles'])) ? $currentCategorie['motscles'] : "";


    // SUPPRESSION CATEGORIE
    if ($_GET['action'] == 'delete') {
        $pdo->query(" DELETE FROM categorie WHERE id_categorie = '$_GET[id_categorie]' ");
        $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                <strong>Suppression categorie effectuée !</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
    }
}

require_once('includeAdmin/header.php');
?>


<h1 class="text-center my-5">
    <div class="badge badge-warning text-wrap p-3">Gestion des categories</div>
</h1>

<?= $erreur ?>
<?= $content ?>


<?php if (!isset($_GET['action']) && !isset($_GET['page'])) : ?>
    <div class="blockquote alert alert-dismissible fade show mt-5 shadow border border-warning rounded" role="alert">
        <p>Gérez ici votre base de données des Categories</p>
        <p>Vous pouvez modifier leurs données, ajouter ou supprimer une categorie</p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<!-- AFFICHAGE DU FORMULAIRE ET DU TITRE A LA DEMANDE -->
<?php if (isset($_GET['action'])) : ?>
    <h2 class="pt-5">Formulaire <?= ($_GET['action'] == 'add') ? "d'ajout" : "de modification" ?> des categories</h2>

    <!-- FORMULAIRE -->
    <form id="monForm" class="my-5" method="POST" action="">

        <input type="hidden" name="id_categorie" value="<?= $id_categorie ?>">

        <div class="row mt-5">
            <div class="col-md-4">
                <label class="form-label" for="categorie">
                    <div class="badge badge-dark text-wrap">Categorie</div>
                </label>
                <input class="form-control" type="text" name="categorie" id="categorie" placeholder="categorie" value="<?= $id_categorie ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="titre">
                    <div class="badge badge-dark text-wrap">Titre</div>
                </label>
                <input class="form-control" type="text" name="titre" id="titre" placeholder="titre" value="<?= $titre ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="motscles">
                    <div class="badge badge-dark text-wrap">Mots clés</div>
                </label>
                <input class="form-control" type="text" name="motscles" id="motscles" placeholder="mots clés" value="<?= $motscles ?>">
            </div>
        </div>
        
        <div class="col-md-1 mt-5">
            <button type="submit" class="btn btn-outline-dark btn-warning">Valider</button>
        </div>

    </form>
    <!-- FIN FORMULAIRE -->
<?php endif; ?>

<!-- REQUETE SQL POUR RECUPERER LE NOMBRE DE CATEGORIES INSCRITS EN BDD -->
<?php $queryCategories = $pdo->query("SELECT id_categorie FROM categorie"); ?>
<h2 class="py-5">Nombre de categories en base de données: <?= $queryCategories->rowCount() ?> </h2>

<div class="row justify-content-center py-5">
    <a href='?action=add'>
        <button type="button" class="btn btn-sm btn-outline-dark shadow rounded">
            <i class="bi bi-plus-circle-fill"></i> Modification categorie
        </button>
    </a>
</div>


<!-- PAGINATION TOP TAB -->
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
<!-- PAGINATION FIN TOP TAB -->


<!-- TABLEAU -->
<table class="table table-dark text-center table-responsive">
    <!-- Requête complétée pour n'afficher que 10 categories dans le tableau, le OFFSET détermine quel categorie sera affiché en premier dans la nouvelle page -->
    <?php $afficheCategories = $pdo->query("SELECT * FROM categorie"); ?>
    <thead>
        <tr>
            <?php for ($i = 0; $i < $afficheCategories->columnCount(); $i++) : $colonne = $afficheCategories->getColumnMeta(($i)) ?>
                <th><?= $colonne['name'] ?></th>
            <?php endfor; ?>
            <th colspan=2>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($categorie = $afficheCategories->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <?php foreach ($categorie as $key => $value) : ?>
                    
                        <td><?= $value ?></td>
                    
                <?php endforeach; ?>
                <td><a href='?action=update&id_categorie=<?= $categorie['id_categorie'] ?>'><i class="bi bi-pen-fill text-warning"></i></a></td>
                <td><a data-href='?action=delete&id_categorie=<?= $categorie['id_categorie'] ?>' data-toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger" style="font-size: 1.5rem;"></i></a></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<!-- FIN TABLEAU -->

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


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Supprimer categorie
            </div>
            <div class="modal-body">
                Etes-vous sur de vouloir retirer cette catégorie ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                <a class="btn btn-danger btn-ok">Supprimer</a>
            </div>
        </div>
    </div>
</div>

<!-- MODAL CONFIRMATION DE MODIFICATION -->

<?php require_once('includeAdmin/footer.php'); ?>