<?php
require_once('include/init.php');

$pageTitle = "Annonce";


// RECUPERATION ANNONCE
$listeCategories = $pdo->query("SELECT * FROM categorie ORDER BY titre");


$id_annonce = (isset($_POST['id_annonce'])) ? $_POST['id_annonce'] : "";
$titre = (isset($_POST['titre'])) ? $_POST['titre'] : "";
$description_courte = (isset($_POST['description_courte'])) ? $_POST['description_courte'] : "";
$description_longue = (isset($_POST['description_longue'])) ? $_POST['description_longue'] : "";
$prix = (isset($_POST['prix'])) ? $_POST['prix'] : "";
$pays = (isset($_POST['pays'])) ? $_POST['pays'] : "";
$ville = (isset($_POST['ville'])) ? $_POST['ville'] : "";
$code_postal = (isset($_POST['code_postal'])) ? $_POST['code_postal'] : "";
$adresse = (isset($_POST['adresse'])) ? $_POST['adresse'] : "";
$photo = (isset($_POST['photo'])) ? $_POST['photo'] : "";

if ($_POST) {
    // TITRE
    if (!isset($_POST['titre']) || iconv_strlen($_POST['titre']) < 3 || iconv_strlen($_POST['titre']) > 200) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format titre !</div>';
    }
    // DESCRIPTION COURTE
    if (!isset($_POST['description_courte']) || iconv_strlen($_POST['description_courte']) < 3 || iconv_strlen($_POST['description_courte']) > 20) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format description_courte !</div>';
    }
    // DESCRIPTION LONGUE
    if (!isset($_POST['description_longue']) || iconv_strlen($_POST['description_longue']) < 3 || iconv_strlen($_POST['description_longue']) > 120) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format description_longue !</div>';
    }
    // PRIX
    if (!isset($_POST['prix']) || !preg_match('#^[0-9]{1,4}$#', $_POST['prix'])) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format prix !</div>';
    }
    // PAYS
    if (!isset($_POST['pays']) || strlen($_POST['pays']) < 4 || strlen($_POST['pays']) > 30) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format pays !</div>';
    }
    // VILLE 
    if (!isset($_POST['ville']) || strlen($_POST['ville']) < 2 || strlen($_POST['ville']) > 30) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format ville !</div>';
    }
    // ADRESSE
    if (!isset($_POST['adresse']) || strlen($_POST['adresse']) < 5 || strlen($_POST['adresse']) > 50) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format adresse !</div>';
    }
    // CODE POSTALE
    if (!isset($_POST['code_postal']) || !preg_match('#^[0-9]{5}$#', $_POST['code_postal'])) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format code postal !</div>';
    }

    if (!isset($_POST['categorie_id'])) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format categorie !</div>';
    }

    // TRAITEMENT POUR PHOTO
    // initialisation de la variable à vide
    $photo_bdd = "";

    // Condition pour modifier une photo


    if (!empty($_FILES['photo']['name'])) {

        $photo_nom = uniqid() . '_' . $_FILES['photo']['name'];

        $photo_bdd = "$photo_nom";

        $photo_dossier = RACINE_SITE . "img/$photo_nom";

        copy($_FILES['photo']['tmp_name'], $photo_dossier);
    }

    // fin traitement pour la photo

    if (empty($erreur)) {


        // Pour personnaliser le message de réussite, je dois récupérer le TITRE de l'ANNONCE modifiée en BDD, pour perso le message
        // $selectAnnonce = $pdo->query(" SELECT titre FROM Annonce WHERE id_annonce = '$_GET[id_annonce]' ");
        // $Annonce = $selectAnnonce->fetch(PDO::FETCH_ASSOC);
        // $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
        //         <strong>Félicitations !</strong> Modification du Annonce ' . $Annonce['titre'] . ' réussie !
        //         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        //             <span aria-hidden="true">&times;</span>
        //         </button>
        //     </div>';


        $addAnnonce = $pdo->prepare(" INSERT INTO Annonce (membre_id, categorie_id, titre, description_courte, description_longue, prix, pays, ville, code_postal, adresse, photo, date_enregistrement) VALUES (:membre_id, :categorie_id, :titre, :description_courte, :description_longue, :prix, :pays, :ville, :code_postal, :adresse, :photo, NOW()) ");

        $addAnnonce->bindValue(':membre_id', $_SESSION['membre']['id_membre'], PDO::PARAM_STR);
        $addAnnonce->bindValue(':categorie_id', $_POST['categorie_id'], PDO::PARAM_STR);
        $addAnnonce->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
        $addAnnonce->bindValue(':description_courte', $_POST['description_courte'], PDO::PARAM_STR);
        $addAnnonce->bindValue(':description_longue', $_POST['description_longue'], PDO::PARAM_STR);
        $addAnnonce->bindValue(':prix', $_POST['prix'], PDO::PARAM_STR);
        $addAnnonce->bindValue(':pays', $_POST['pays'], PDO::PARAM_STR);
        $addAnnonce->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
        $addAnnonce->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_STR);
        $addAnnonce->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
        $addAnnonce->bindValue(':photo', $photo_bdd, PDO::PARAM_STR);
        // NOW() POUR DATE D'ENREGISTREMENT

        $addAnnonce->execute();
    }
}

require_once('include/header.php');
echo '<pre>';
var_dump($_POST);
echo '</pre>';
?>
<div class="container">
    <h2 class="text-center my-5">Déposez une annonce</h2>


    <form id="monForm" class="my-5" method="POST" action="" enctype="multipart/form-data">

        <input type="hidden" name="id_annonce" value="<?= $id_annonce ?>">

        <div class="row mt-5">
            <div class="col-md-4">
                <label class="form-label" for="titre">
                    <div class="badge badge-dark text-wrap">Titre</div>
                </label>
                <input class="form-control" type="text" name="titre" id="titre" placeholder="titre" value="<?= $titre ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label" for="description_courte">
                    <div class="badge badge-dark text-wrap">Description courte</div>
                </label>
                <input class="form-control" type="text" name="description_courte" id="description_courte" placeholder="Description courte" value="<?= $description_courte ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="categorie_id">
                    <div class="badge badge-dark text-wrap">Categorie</div>
                </label>
                <select class="form-control" name="categorie_id" id="categorie_id">
                    <?php
                    while ($categorie = $listeCategories->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="' . $categorie['id_categorie'] . '">' . $categorie['titre'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-6">
                <label class="form-label" for="description_longue">
                    <div class="badge badge-dark text-wrap">Description longue</div>
                </label>
                <textarea class="form-control" name="description_longue" id="description_longue" placeholder="Description détaillée" rows="5"><?=$description_longue?></textarea>
            </div>
        </div>


        <div class="row mt-5">
            <div class="col-md-4">
                <label class="form-label" for="pays">
                    <div class="badge badge-dark text-wrap">Pays</div>
                </label>
                <input class="form-control" type="text" name="pays" id="pays" placeholder="pays" value="<?= $pays ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="ville">
                    <div class="badge badge-dark text-wrap">Ville</div>
                </label>
                <input class="form-control" type="text" name="ville" id="ville" placeholder="ville" value="<?= $ville ?>">
            </div>

        </div>
        <div class="row mt-5">
            <div class="col-md-4">
                <label class="form-label" for="adresse">
                    <div class="badge badge-dark text-wrap">Adresse</div>
                </label>
                <input class="form-control" type="text" name="adresse" id="adresse" placeholder="adresse" value="<?= $adresse ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="code_postal">
                    <div class="badge badge-dark text-wrap">Code Postal</div>
                </label>
                <input class="form-control" type="number" name="code_postal" id="code_postal" placeholder="Code postal" value="<?= $code_postal ?>">
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-4">
                <label class="form-label" for="photo">
                    <div class="badge badge-dark text-wrap">Photo Principale</div>
                </label>
                <input class="form-control" type="file" name="photo" id="photo" placeholder="Photo">
            </div>
            <!-- ----------------- -->
            <!-- Si la variable $photo a trouvé une information en BDD, on affiche ce  qui suit dans les accolades  -->
            <?php if (!empty($photo)) : ?>
                <div class="mt-4">
                    <p>Vous pouvez changer d'image
                        <img src="<?= URL . 'img/' . $photo ?>" width="50px">
                    </p>
                </div>
            <?php endif; ?>
            <!-- Pour modifier la photo existante par une nouvelle (voir ligne 62)  -->
            <input type="hidden" name="photoActuelle" value="<?= $photo ?>">
            <!-- -------------------- -->
            <div class="col-md-4">
                <label class="form-label" for="prix">
                    <div class="badge badge-dark text-wrap">Prix</div>
                </label>
                <input class="form-control" type="number" name="prix" id="prix" placeholder="Prix" value="<?= $prix ?>">
            </div>
        </div>

        <div class="col-md-1 mt-5">
            <button type="submit" class="btn btn-outline-dark btn-warning">Valider</button>
        </div>

    </form>


    <div class="col-12">
        <p>Veuillez vous connecter pour déposer une annonce</p>
    </div>
</div>

<?php require_once('include/footer.php'); ?>