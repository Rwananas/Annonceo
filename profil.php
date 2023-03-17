<?php
require_once('include/init.php');

$pageTitle = "Profil de " . $_SESSION['membre']['pseudo'];

// si le user nest PAS connecté, alors on lui interdit l'accés à la page profil (redirection vers la page connexion ou autre selon reflexion)
if (!internauteConnecte()) {
    header('location:' . URL . 'index.php');
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'validate') {
    $validate .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                    Félicitations <strong>' . $_SESSION['membre']['pseudo'] . '</strong>, vous etes connecté 😉 !
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
}

require_once('include/header.php');
?>


<h2 class="text-center my-5">
    <?= $erreur ?>
    <?= $content ?>



    <div class="badge badge-dark text-wrap p-3">Bonjour <?= (internauteConnecteAdmin()) ? $_SESSION['membre']['pseudo'] . ", vous etes admin du site" : $_SESSION['membre']['pseudo'] ?></div>

    <?php
    /* condition développée de la ternaire ligne 24
        if(internauteConnecteAdmin()){
            echo $_SESSION['membre']['pseudo'] . "vous etes admin du site";
        }else{
            echo $_SESSION['membre']['pseudo'];
        }*/
    ?>
</h2>

<?= $validate ?>



<!-- $validate .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                    Félicitations <strong>' . $_SESSION['membre']['pseudo'] .'</strong>, vous etes connecté 😉 !
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>'; -->



<div class="row justify-content-around py-5">
    <div class="col-md-3 text-center">
        <ul class="list-group">
            <li class="btn btn-outline-success text-dark my-3 shadow bg-white rounded"><?= $_SESSION['membre']['pseudo'] ?></li>
            <li class="btn btn-outline-success text-dark my-3 shadow bg-white rounded"><?= $_SESSION['membre']['prenom'] ?></li>
            <li class="btn btn-outline-success text-dark my-3 shadow bg-white rounded"><?= $_SESSION['membre']['nom'] ?></li>
            <li class="btn btn-outline-success text-dark my-3 shadow bg-white rounded"><?= $_SESSION['membre']['email'] ?></li>
            <li class="btn btn-outline-success text-dark my-3 shadow bg-white rounded"><?= $_SESSION['membre']['telephone'] ?></li>
        </ul>
    </div>
</div>

<div class="row justify-content-center py-5">
    <a href="<?= URL ?>depot_annonce.php">
        <button type="input" class="btn btn-sm btn-outline-warning text-dark shadow rounded">
            <i class="bi bi-plus-circle-fill"></i> Ajouter une annonce
        </button>
    </a>
    <a href="<?= URL ?>fiche_annonce.php">
        <button type="button" class="btn btn-sm btn-outline-warning text-dark shadow rounded">
            <i class="bi bi-plus-circle-fill"></i> Voir votre annonce
        </button>
    </a>
</div>

<?php require_once('include/footer.php') ?>