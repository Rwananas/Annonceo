<?php
require_once('../include/init.php');

if (!internauteConnecteAdmin()) {
    header('location:' . URL . 'index.php');
    exit();
}

// PAGINATION GESTION COMMENTAIRES


// Si un indice page existe dans l'URL et qu'on trouve une valeur dedans 
if (isset($_GET['page']) && !empty($_GET['page'])) {
    // Alors on déclare une variable $pageCourante à laquelle on va affecter la valeur véhiculée par l'indice page dans l'URL
    // Protection de ce qui sera véhiculé dans l'URL avec strip_tags ou htmlspecialchars, plus on force le typage de l'information dans l'URL avec (int) pour indiquer qu'on ne veut pas recevoir autre chose qu'un nombre entier
    $pageCourante = (int) strip_tags($_GET['page']);
} else {
    // Dans le cas ou aucune information n'a transité dans l'URL, $pageCourante prendra la valeur par défaut de 1 (première page)
    $pageCourante = 1;
}

// Je dois connaitre le nombre de Commentaires en BDD pour établir mon système de pagination
// Je connais déjà ce nombre (voir en haut) avec rowCount. La syntaxe qui va suivre est plus longue et compliqué mais elle sera plus rapide à l'éxécution que rowCount
$queryCommentaires = $pdo->query(" SELECT COUNT(id_commentaire) AS nombreCommentaires FROM Commentaire ");
// Le fetch après le query pour récupérer le nombre (pas besoin de fetch_assoc, je ne vais cibler aucune colonne, je veux récupérer un nombre total)
$resultatCommentaires = $queryCommentaires->fetch();
$nombreCommentaires = (int) $resultatCommentaires['nombreCommentaires'];
// echo debug($nombreCommentaires);

// Je veux que sur chaque page, ne s'affiche dans le tableau que 10 Commentaires
$parPage = 10;
// Calcul pour savoir combien de pages devront être générées ( nombre évolutif)
// Utilisation de ceil(), fonction prédéfinie qui arrondi à l'unité supérieur si le résultat de la division est un chiffre à virgule
$nombresPages = ceil($nombreCommentaires / $parPage);
// Définir le 1er Commentaire qui va s'afficher à chaque nouvelle pagge ( on va le cibler grace à l'indice qu'il occupe dans le tableau)
$premierCommentaire = ($pageCourante - 1) * $parPage;


// Fin pagination

if (isset($_GET['action'])) {
    if ($_POST) {
        // TITRE
        if (!isset($_POST['commentaire']) || iconv_strlen($_POST['commentaire']) < 3 || iconv_strlen($_POST['commentaire']) > 20) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format commentaire !</div>';
        }
        
        

        if (empty($erreur)) {
            if ($_GET['action'] == 'update') {
                $modifCommentaire =  $pdo->prepare(" UPDATE Commentaire SET id_commentaire = :id_commentaire, commentaire = :commentaire WHERE id_commentaire = :id_commentaire");
                $modifCommentaire->bindValue(':id_commentaire', $_POST['id_commentaire'], PDO::PARAM_INT);
                $modifCommentaire->bindValue(':commentaire', $_POST['commentaire'], PDO::PARAM_STR);
                
                $modifCommentaire->execute();

                // Pour personnaliser le message de réussite, je dois récupérer le pseudo de l'utilisateur modifiée en BDD, pour perso le message
                // $selectCommentaire = $pdo->query(" SELECT titre FROM Commentaire WHERE id_commentaire = '$_GET[id_commentaire]' ");
                // $Commentaire = $selectCommentaire->fetch(PDO::FETCH_ASSOC);
                // $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                //     <strong>Félicitations !</strong> Modification du Commentaire ' . $Commentaire['titre'] . ' réussie !
                //     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                //         <span aria-hidden="true">&times;</span>
                //     </button>
                // </div>';
            } 
        }
    }

    if ($_GET['action'] == 'update') {
        $tousCommentaires = $pdo->query("SELECT * FROM Commentaire WHERE id_commentaire = '$_GET[id_commentaire]' ");
        $currentCommentaire = $tousCommentaires->fetch(PDO::FETCH_ASSOC);
    }

    $id_commentaire = (isset($currentCommentaire['id_commentaire'])) ? $currentCommentaire['id_commentaire'] : "";
    $commentaire = (isset($currentCommentaire['commentaire'])) ? $currentCommentaire['commentaire'] : "";
    


    // SUPPRESSION Commentaire
    if ($_GET['action'] == 'delete') {
        $pdo->query(" DELETE FROM Commentaire WHERE id_commentaire = '$_GET[id_commentaire]' ");
        $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                <strong>Suppression Commentaire effectuée !</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
    }
}

require_once('includeAdmin/header.php');
?>


<h1 class="text-center my-5">
    <div class="badge badge-warning text-wrap p-3">Gestion des Commentaires</div>
</h1>

<?= $erreur ?>
<?= $content ?>


<?php if (!isset($_GET['action']) && !isset($_GET['page'])) : ?>
    <div class="blockquote alert alert-dismissible fade show mt-5 shadow border border-warning rounded" role="alert">
        <p>Gérez ici votre base de données des Commentaires</p>
        <p>Vous pouvez modifier leurs données, ajouter ou supprimer une Commentaire</p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<!-- AFFICHAGE DU FORMULAIRE ET DU TITRE A LA DEMANDE -->
<?php if (isset($_GET['action'])) : ?>
    <h2 class="pt-5">Formulaire <?= ($_GET['action'] == 'add') ? "d'ajout" : "de modification" ?> des Commentaires</h2>

    <!-- FORMULAIRE -->
    <form id="monForm" class="my-5" method="POST" action="">

        <input type="hidden" name="id_commentaire" value="<?= $id_commentaire ?>">

        <div class="col-md-6">
                <label class="form-label" for="commentaire">
                    <div class="badge badge-dark text-wrap">Commentaire</div>
                </label>
                <textarea class="form-control" name="commentaire" id="commentaire" placeholder="commentaire" value="<?= $commentaire ?>" rows="5"></textarea>
            </div>
        
        <div class="col-md-1 mt-5">
            <button type="submit" class="btn btn-outline-dark btn-warning">Valider</button>
        </div>

    </form>
    <!-- FIN FORMULAIRE -->
<?php endif; ?>

<!-- REQUETE SQL POUR RECUPERER LE NOMBRE DE COMMENTAIRES INSCRITS EN BDD -->
<?php $queryCommentaires = $pdo->query("SELECT id_commentaire FROM Commentaire"); ?>
<h2 class="py-5">Nombre de Commentaires en base de données: <?= $queryCommentaires->rowCount() ?> </h2>

<div class="row justify-content-center py-5">
    <a href='?action=add'>
        <button type="button" class="btn btn-sm btn-outline-dark shadow rounded">
            <i class="bi bi-plus-circle-fill"></i> Modification Commentaire
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
    <!-- Requête complétée pour n'afficher que 10 Commentaires dans le tableau, le OFFSET détermine quel Commentaire sera affiché en premier dans la nouvelle page -->
    <?php $afficheCommentaires = $pdo->query("SELECT * FROM Commentaire ORDER BY membre_id ASC LIMIT $parPage OFFSET $premierCommentaire "); ?>
    <thead>
        <tr>
            <?php for ($i = 0; $i < $afficheCommentaires->columnCount(); $i++) : $colonne = $afficheCommentaires->getColumnMeta(($i)) ?>
                <th><?= $colonne['name'] ?></th>
            <?php endfor; ?>
            <th colspan=2>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($Commentaire = $afficheCommentaires->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <?php foreach ($Commentaire as $key => $value) : ?>
                    <?php if ($key == 'commentaire') : ?>
                        <td><?= $value ?> €</td>
                    <?php endif; ?>
                <?php endforeach; ?>
                <td><a href='?action=update&id_commentaire=<?= $Commentaire['id_commentaire'] ?>'><i class="bi bi-pen-fill text-warning"></i></a></td>
                <td><a data-href='?action=delete&id_commentaire=<?= $Commentaire['id_commentaire'] ?>' data-toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger" style="font-size: 1.5rem;"></i></a></td>
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
<!-- <img class="img-fluid" src="" width="50"> -->

<!-- <td><a href=''><i class="bi bi-pen-fill text-warning"></i></a></td>-->
<!-- <td><a data-href="" data-toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger" style="font-size: 1.5rem;"></i></a></td> -->

<!-- modal suppression codepen https://codepen.io/lowpez/pen/rvXbJq -->

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Supprimer commentaire
            </div>
            <div class="modal-body">
                Etes-vous sur de vouloir retirer ce commentaire ?
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