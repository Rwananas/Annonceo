<?php

require_once('include/init.php');

// Récupération de l'ID de l'annonce
$id_annonce = $_GET['id'];

// Récupération des données de l'annonce
$stmt = $pdo->prepare("SELECT * FROM annonce WHERE id_annonce = ?");
$stmt->execute([$id_annonce]);
$annonce = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérification que l'annonce appartient bien au membre connecté
if ($annonce['membre_id'] != $_SESSION['membre']['id_membre']) {
    die("Vous n'êtes pas autorisé à modifier cette annonce.");
}

// Traitement du formulaire de modification
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    if ($_GET['action'] == 'update') {
        $tousAnnonces = $pdo->query("SELECT * FROM Annonce WHERE id_annonce = '{$_GET['id_annonce']}' ");
        $currentAnnonce = $tousAnnonces->fetch(PDO::FETCH_ASSOC);
    }

    $id_annonce = (isset($currentAnnonce['id_annonce'])) ? $currentAnnonce['id_annonce'] : "";
    $titre = (isset($currentAnnonce['titre'])) ? $currentAnnonce['titre'] : "";
    $description_courte = (isset($currentAnnonce['description_courte'])) ? $currentAnnonce['description_courte'] : "";
    $description_longue = (isset($currentAnnonce['description_longue'])) ? $currentAnnonce['description_longue'] : "";
    $prix = (isset($currentAnnonce['prix'])) ? $currentAnnonce['prix'] : "";
    $pays = (isset($currentAnnonce['pays'])) ? $currentAnnonce['pays'] : "";
    $ville = (isset($currentAnnonce['ville'])) ? $currentAnnonce['ville'] : "";
    $code_postal = (isset($currentAnnonce['code_postal'])) ? $currentAnnonce['code_postal'] : "";
    $adresse = (isset($currentAnnonce['adresse'])) ? $currentAnnonce['adresse'] : "";
    $photo = (isset($currentAnnonce['photo'])) ? $currentAnnonce['photo'] : "";

    // TRAITEMENT POUR PHOTO
    $photo_bdd = "";
    // Condition pour modifier une photo
    if ($_GET['action'] == 'update') {
        // A mettre en relation avec la nouvelle photo que l'on veut insérer en BDD pour remplacer la photo
        $photo_bdd = $_POST['photoActuelle'];
    }

    if (!empty($_FILES['photo']['name'])) {            
        $photo_nom = $_POST['titre'] . '_' . $_FILES['photo']['name'];            
        $photo_bdd = "$photo_nom";            
        $photo_dossier = RACINE_SITE . "img/$photo_nom";           
        copy($_FILES['photo']['tmp_name'], $photo_dossier);
    }

    // FIN TRAITEMENT PHOTO

    // Validation des données
    if (empty($titre) || empty($description_courte) || empty($description_longue)) {
        $erreur = "Tous les champs sont obligatoires.";
    } else {
        // Mise à jour de l'annonce dans la base de données
        $stmt = $pdo->prepare("UPDATE annonce SET titre = :titre, description_courte = :description_courte, description_longue = :description_longue, prix = :prix, pays = :pays, ville = :ville, code_postal = :code_postal, adresse = :adresse, photo = :photo WHERE id_annonce = :id_annonce");
        $stmt->execute([
            'titre' => $titre,
            'description_courte' => $description_courte,
            'description_longue' => $description_longue,
            'prix' => $prix,
            'pays' => $pays,
            'ville' => $ville,
            'code_postal' => $code_postal,
            'adresse' => $adresse,
            'photo' => $photo,
            'id_annonce' => $id_annonce
        ]);

        // Redirection vers la page d'accueil
        header("Location: " . URL . 'profil.php');
        exit();
    }
}
require_once('include/header.php');
?>

<div class="row justify-content-center py-5">
    <div class="col-md-8">
        <h2 class="mb-4">Modifier votre annonce</h2>
        <form method="post" action="update_annonce.php" enctype="multipart/form-data">
            <input type="hidden" name="id_annonce" value="<?= $annonce['id_annonce'] ?>">
            <div class="form-group">
                <label for="titre">Titre de l'annonce :</label>
                <input type="text" name="titre" id="titre" class="form-control" value="<?= $annonce['titre'] ?>">
            </div>
            <div class="form-group">
                <label for="description_courte">Description courte :</label>
                <textarea name="description_courte" id="description_courte" class="form-control"><?= $annonce['description_courte'] ?></textarea>
            </div>
            <div class="form-group">
                <label for="description_longue">Description longue :</label>
                <textarea name="description_longue" id="description_longue" class="form-control"><?= $annonce['description_longue'] ?></textarea>
            </div>
            <div class="form-group">
                <label for="photo">Photo :</label>
                <input type="file" name="photo" id="photo" class="form-control-file">
            </div>
            <div class="form-group">
                <label for="prix">Prix :</label>
                <input type="number" name="prix" id="prix" class="form-control" value="<?= $annonce['prix'] ?>">
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        </form>
    </div>
</div>

<?php require_once('include/footer.php') ?>