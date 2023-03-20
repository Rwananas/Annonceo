<?php
require_once('include/init.php');

$pageTitle = "Profil de " . $_SESSION['membre']['pseudo'];

// si le user nest PAS connecté, alors on lui interdit l'accés à la page profil (redirection vers la page connexion ou autre selon reflexion)
if (!internauteConnecte()) {
    header('location:' . URL . 'index.php');
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'validate') {
    $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
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

<?= $content ?>


<!-- FORMULAIRE DE MODIFICATION DE PROFIL -->

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>Modifier mes informations personnelles</h4>
                </div>
                <div class="card-body">
                    <form action="update_compte.php" method="POST">
                        <div class="mb-3">
                            <label for="new_pseudo" class="form-label">Pseudo</label>
                            <input type="text" class="form-control" id="new_pseudo" name="new_pseudo" value="<?= $_SESSION['membre']['pseudo'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="new_nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="new_nom" name="new_nom" value="<?= $_SESSION['membre']['nom'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="new_prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="new_prenom" name="new_prenom" value="<?= $_SESSION['membre']['prenom'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="new_mail" class="form-label">Adresse email</label>
                            <input type="email" class="form-control" id="new_email" name="new_email" value="<?= $_SESSION['membre']['email'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="new_telephone" class="form-label">Numéro de téléphone</label>
                            <input type="tel" class="form-control" id="new_telephone" name="new_telephone" value="<?= $_SESSION['membre']['telephone'] ?>">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- LISTE/AFFICHAGE DES ANNONCES DANS LE PROFIL -->
<?php
// Récupération des annonces du membre
$id_membre = $_SESSION['membre']['id_membre'];
$stmt = $pdo->prepare("SELECT * FROM annonce WHERE membre_id = ?");
$stmt->execute([$id_membre]);
$annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row justify-content-center py-5">
    <div class="col-md-8">
        <h2 class="mb-4">Vos annonces</h2>
        <?php foreach ($annonces as $annonce) { ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= $annonce['titre'] ?></h5>
                    <?php if (!empty($annonce['photo'])) { ?>
                        <img src="<?= URL . 'img/' . $annonce['photo'] ?>" class="card-img-top mb-3" alt="Photo de l'annonce">
                    <?php } ?>
                    <p class="card-text"><?= $annonce['description_longue'] ?></p>
                    <p class="card-text"><small class="text-muted">Publiée le <?= $annonce['date_enregistrement'] ?></small></p>
                    <a href="<?= URL ?>annonceDuProfil.php?id=<?= $annonce['id_annonce'] ?>" class="btn btn-sm btn-primary">Modifier</a>
                    <a href="<?= URL ?>fiche_annonceModif.php?id=<?= $annonce['id_annonce'] ?>" class="btn btn-sm btn-primary">Voir l'annonce</a>
                    <a data-href="?action=delete&id_annonce=<?= $annonce['id_annonce'] ?>" class="btn btn-sm btn-danger">Supprimer</a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>



<?php require_once('include/footer.php') ?>