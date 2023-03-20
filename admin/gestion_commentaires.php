<?php
require_once('../include/init.php');

if (!internauteConnecteAdmin()) {
    header('location:' . URL . 'connexion.php');
    exit();
}


if (isset($_GET['action'])) {

    if ($_POST) {

        

        if (!isset($_POST['commentaire']) || iconv_strlen($_POST['commentaire']) < 3 || iconv_strlen($_POST['commentaire']) > 500) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format commentaire !</div>';
        }

        if (empty($erreur)) {
          
            if ($_GET['action'] == 'update') {
                $modifUser = $pdo->prepare(" UPDATE commentaire SET id_commentaire = :id_commentaire , membre_id = :membre_id, annonce_id = :annonce_id, commentaire = :commentaire WHERE id_commentaire = :id_commentaire ");
                $modifUser->bindValue(':id_commentaire', $_POST['id_commentaire'], PDO::PARAM_INT);
                $modifUser->bindValue(':membre_id', $_POST['membre_id'], PDO::PARAM_STR);
                $modifUser->bindValue(':annonce_id', $_POST['annonce_id'], PDO::PARAM_STR);
                $modifUser->bindValue(':commentaire', $_POST['commentaire'], PDO::PARAM_STR);
                $modifUser->execute();

                // pour personnaliser le message de réussite, je dois récupérer le pseudo de l'utilisateur modifié en BDD, pour personnaliser le message
                $queryUser = $pdo->query(" SELECT commentaire FROM commentaire WHERE id_commentaire = '$_GET[id_commentaire]' ");
                $commentaire = $queryUser->fetch(PDO::FETCH_ASSOC);

                $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                        <strong>Félicitations !</strong> Modification de l`\'utilisateur '. $commentaire['commentaire'] .' réussie !
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {
                // si on récupère autre chose que update (et donc add) on entame une procédure d'insertion en BDD
                $inscrireUser = $pdo->prepare(" INSERT INTO commentaire (id_commentaire, membre_id, annonce_id, commentaire,date_enregistrement) VALUES (:id_commentaire, :membre_id, :annonce_id, :commentaire, NOW()) ");
                $inscrireUser->bindValue(':commentaire', $_POST['commentaire'], PDO::PARAM_STR);
                $inscrireUser->bindValue(':id_commentaire', $_POST['id_commentaire'], PDO::PARAM_STR);
                $inscrireUser->bindValue(':membre_id', $_POST['membre_id'], PDO::PARAM_STR);
                $inscrireUser->bindValue(':annonce_id', $_POST['annonce_id'], PDO::PARAM_STR);
                $inscrireUser->bindValue(':commentaire', $_POST['commentaire'], PDO::PARAM_INT);
                $inscrireUser->execute();
            }
        }
    }

    // procédure de récupération des infos en BDD pour les afficher dans le formulaire lorsque on fait un update (plus pratique et plus sur)
    if ($_GET['action'] == 'update') {
        $tousCommentaire = $pdo->query("SELECT * FROM commentaire WHERE commentaire = '$_GET[commentaire]' ");
        $commentaireActuel = $tousCommentaire->fetch(PDO::FETCH_ASSOC);
    }

    $commentaire = (isset($commentaireActuel['commentaire'])) ? $commentaireActuel['commentaire'] : "";
   
    
    // syntaxe de condition classique équivalente à la ternaire juste au dessus
    /*if(isset($commentaireActuel['pseudo'])){
            $pseudo = $commentaireActuel['pseudo'];
        }else{
            $pseudo = "";
        }*/

    if($_GET['action'] == 'delete'){
       
        $pdo->query(" DELETE FROM commentaire WHERE id_commentaire = '$_GET[id_commentaire]' ");
    }
}

require_once('includeAdmin/header.php');
?>



<h1 class="text-center my-5">
    <div class="badge badge-warning text-wrap p-3">Gestion des commentaires</div>
</h1>

<?= $erreur ?>
<?= $content ?>



<?php if (isset($_GET['action'])) : ?>
    <h2 class="my-5">Formulaire <?= ($_GET['action'] == 'add') ? "de suppression" : "de modification" ?> des commentaires</h2>


<?php endif; ?>

<!-- requete SQL pour récupérer le nb des commentaires inscrits en BDD, nb que je pourrais afficher grace à rowCount deux lignes en dessous -->
<?php $nbCommentaires = $pdo->query("SELECT id_commentaire FROM commentaire"); ?>
<h2 class="py-5">Nombre de commentaires en base de données: <?= $nbCommentaires->rowCount() ?></h2>



<table class="table table-dark text-center table-responsive">
    <?php $afficheCommentaire = $pdo->query("SELECT * FROM commentaire ORDER BY date_enregistrement ASC "); ?>
    <thead>
        <tr>
            <?php for ($i = 0; $i < $afficheCommentaire->columnCount(); $i++) :
                $colonne = $afficheCommentaire->getColumnMeta(($i)) ?>
                    <th><?= $colonne['name'] ?></th>
            <?php endfor; ?>
            <th colspan=2>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($commentaire = $afficheCommentaire->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <?php foreach ($commentaire as $key => $value) : ?>
                        <td><?= $value ?></td>
                <?php endforeach; ?>
                
                <td><a data-href="?action=delete&id_commentaire=<?= $commentaire['id_commentaire'] ?>" data-toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger" style="font-size: 1.5rem;"></i></a></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<nav>
    <ul class="pagination justify-content-end">
        <li class="page-item ">
            <a class="page-link text-dark" href="" aria-label="Previous">
                <span aria-hidden="true">précédente</span>
                <span class="sr-only">Previous</span>
            </a>
        </li>

        <li class="mx-1 page-item">
            <a class="btn btn-outline-dark " href=""></a>
        </li>

        <li class="page-item ">
            <a class="page-link text-dark" href="" aria-label="Next">
                <span aria-hidden="true">suivante</span>
                <span class="sr-only">Next</span>
            </a>
        </li>
    </ul>
</nav>




<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Supprimer commentaire
            </div>
            <div class="modal-body">
                Etes-vous sur de vouloir retirer ce commentaire de votre base de données ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                <a class="btn btn-danger btn-ok">Supprimer</a>
            </div>
        </div>
    </div>
</div>

<!-- modal -->


<?php if (!isset($_GET['action']) && !isset($_GET['page'])) : ?>
    <!-- modal infos -->
    <div class="modal fade" id="myModalUsers" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-warning" id="exampleModalLabel">Gestion des commentaires</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Gérez ici votre base de données des commentaires</p>
                    <p>Vous pouvez supprimer un commentaire</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-warning text-dark" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    <!-- modal -->
<?php endif; ?>

<?php require_once('includeAdmin/footer.php'); ?>