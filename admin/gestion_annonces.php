<?php
require_once('../include/init.php');

if (!internauteConnecteAdmin()) {
    header('location:' . URL . 'index.php');
    exit();
}

// PAGINATION Annonces





// Si un indice page existe dans l'URL et qu'on trouve une valeur dedans 
if (isset($_GET['page']) && !empty($_GET['page'])) {
    // Alors on déclare une variable $pageCourante à laquelle on va affecter la valuer véhiculée par l'indice page dans l'URL
    // Protection de ce qui sera véhiculé dans l'URL avec strip_tags ou htmlspecialchars, plus on force le typage de l'information dans l'URL avec (int) pour indiquer qu'on ne veut pas recevoir autre chose qu'un nombre entier
    $pageCourante = (int) strip_tags($_GET['page']);
} else {
    // Dans le cas ou aucune information n'a transité dans l'URL, $pageCourante prendra la valeur par défaut de 1 (première page)
    $pageCourante = 1;
}

// Je dois connaitre le nombre de Annonces en BDD pour établir mon système de pagination
// Je connais déjà ce nombre (voir en haut) avec rowCount. La syntaxe qui va suivre est plus longue et compliqué mais elle sera plus rapide à l'éxécution que rowCount
$queryAnnonces = $pdo->query(" SELECT COUNT(id_annonce) AS nombreAnnonces FROM Annonce ");
// Le fetch après le query pour récupérer le nombre (pas besoin de fetch_assoc, je ne vais cibler aucune colonne, je veux récupérer un nombre total)
$resultatAnnonces = $queryAnnonces->fetch();
$nombreAnnonces = (int) $resultatAnnonces['nombreAnnonces'];
// echo debug($nombreAnnonces);

// Je veux que sur chaque page, ne s'affiche dans le tableau que 10 Annonces
$parPage = 10;
// Calcul pour savoir combien de pages devront être générées ( nombre évolutif)
// Utilisation de ceil(), fonction prédéfinie qui arrondi à l'unité supérieur si le résultat de la division est un chiffre à virgule
$nombresPages = ceil($nombreAnnonces / $parPage);
// Définir le 1er Annonce qui va s'afficher à chaque nouvelle pagge ( on va le cibler grace à l'indice qu'il occupe dans le tableau)
$premierAnnonce = ($pageCourante - 1) * $parPage;


// Fin pagination

$listeCategories = $pdo->query("SELECT * FROM categorie ORDER BY titre");

if (isset($_GET['action'])) {
    if ($_POST) {
        // TITRE
        if (!isset($_POST['titre']) || iconv_strlen($_POST['titre']) < 3 || iconv_strlen($_POST['titre']) > 20) {
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
        // CATEGORIE
        if (!isset($_POST['categorie_id'])) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format categorie !</div>';
        }

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

        if (empty($erreur)) {
            if ($_GET['action'] == 'update') {
                $modifAnnonce =  $pdo->prepare(" UPDATE Annonce SET id_annonce = :id_annonce, titre = :titre, description_courte = :description_courte, description_longue = :description_longue, prix = :prix, pays = :pays, ville = :ville, adresse = :adresse, photo = :photo  WHERE id_annonce = :id_annonce");
                $modifAnnonce->bindValue(':id_annonce', $_POST['id_annonce'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':description_courte', $_POST['description_courte'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':description_longue', $_POST['description_longue'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':prix', $_POST['prix'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':pays', $_POST['pays'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':photo', $photo_bdd, PDO::PARAM_STR);

                $modifAnnonce->execute();

                // Pour personnaliser le message de réussite, je dois récupérer le TITRE de l'ANNONCE modifiée en BDD, pour perso le message
                $selectAnnonce = $pdo->query(" SELECT titre FROM Annonce WHERE id_annonce = '$_GET[id_annonce]' ");
                $Annonce = $selectAnnonce->fetch(PDO::FETCH_ASSOC);
                $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                    <strong>Félicitations !</strong> Modification du Annonce ' . $Annonce['titre'] . ' réussie !
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
            } else {
                // si on récupère autre chose que update (et donc add) on entame une procédure d'insertion en BDD
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
                // Pour le bindValue de la photo, on ne peut utiliser $_POST['photo'] pour le pointeur nommé :photo. On doit donner une chaine de caractères (affectée à photo_bdd, voir plus en haut)
                $addAnnonce->bindValue(':photo', $photo_bdd, PDO::PARAM_STR);
                // NOW() POUR DATE D'ENREGISTREMENT

                $addAnnonce->execute();
            }
        }
    }

    // RECUPERATION DES INFOS EN BDD POUR LES AFFICHER DANS LE FORMULAIRE LORS D'UN UPDATE
    if ($_GET['action'] == 'update') {
        $tousAnnonces = $pdo->query("SELECT * FROM Annonce WHERE id_annonce = '$_GET[id_annonce]' ");
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


    // SUPPRESSION ANNONCE
    if ($_GET['action'] == 'delete') {
        $pdo->query(" DELETE FROM Annonce WHERE id_annonce = '$_GET[id_annonce]' ");
        $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                <strong>Suppression Annonce effectuée !</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
    }
}

require_once('includeAdmin/header.php');
?>


<h1 class="text-center my-5">
    <div class="badge badge-warning text-wrap p-3">Gestion des Annonces</div>
</h1>

<?= $erreur ?>
<?= $content ?>


<?php if (!isset($_GET['action']) && !isset($_GET['page'])) : ?>
    <div class="blockquote alert alert-dismissible fade show mt-5 shadow border border-warning rounded" role="alert">
        <p>Gérez ici votre base de données des Annonces</p>
        <p>Vous pouvez modifier leurs données, ajouter ou supprimer une Annonce</p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<!-- AFFICHAGE DU FORMULAIRE ET DU TITRE A LA DEMANDE -->
<?php if (isset($_GET['action'])) : ?>
    <h2 class="pt-5">Formulaire <?= ($_GET['action'] == 'add') ? "d'ajout" : "de modification" ?> des Annonces</h2>

    <!-- L'attribut enctype de la balise form permet l'envoi de fichier en upload  -->
    <!-- FORMULAIRE -->
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
                <select class="form-control" name="categorie_id" id="categorie_id" >
                    <?php 
                        while($categorie = $listeCategories->fetch(PDO::FETCH_ASSOC)) {
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
                <textarea class="form-control" name="description_longue" id="description_longue" placeholder="Description détaillée" value="<?= $description_longue ?>" rows="5"></textarea>
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
        </div>

        <div class="row mt-5">
            <div class="col-md-4">
                <label class="form-label" for="photo">
                    <div class="badge badge-dark text-wrap">Photo</div>
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
            <!-- Pour modifier la photo existante par une nouvelle (voir ligne 56)  -->
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
    <!-- FIN FORMULAIRE -->
<?php endif; ?>

<!-- REQUETE SQL POUR RECUPERER LE NOMBRE D'ANNONCES INSCRITS EN BDD -->
<?php $queryAnnonces = $pdo->query("SELECT id_annonce FROM annonce"); ?>
<h2 class="py-5">Nombre d'Annonces en base de données: <?= $queryAnnonces->rowCount() ?> </h2>

<div class="row justify-content-center py-5">
    <a href='?action=add'>
        <button type="button" class="btn btn-sm btn-outline-dark shadow rounded">
            <i class="bi bi-plus-circle-fill"></i> Ajouter une Annonce
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
    <!-- Requête complétée pour n'afficher que 10 Annonces dans le tableau, le OFFSET détermine quel Annonce sera affiché en premier dans la nouvelle page -->
    <?php $afficheAnnonces = $pdo->query("SELECT * FROM annonce ORDER BY prix ASC LIMIT $parPage OFFSET $premierAnnonce "); ?>
    <thead>
        <tr>
            <?php for ($i = 0; $i < $afficheAnnonces->columnCount(); $i++) : $colonne = $afficheAnnonces->getColumnMeta(($i)) ?>
                <th><?= $colonne['name'] ?></th>
            <?php endfor; ?>
            <th colspan=2>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($Annonce = $afficheAnnonces->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <?php foreach ($Annonce as $key => $value) : ?>
                    <?php if ($key == 'prix') : ?>
                        <td><?= $value ?> €</td>
                    <?php elseif ($key == 'photo') : ?>
                        <td><img class="img-fluid" src=" <?= '../img/' . $value ?>" alt="" width="50" loading="lazy"></td>
                    <?php else : ?>
                        <td><?= $value ?></td>
                    <?php endif; ?>
                <?php endforeach; ?>
                <td><a href='?action=update&id_annonce=<?= $Annonce['id_annonce'] ?>'><i class="bi bi-pen-fill text-warning"></i></a></td>
                <td><a data-href='?action=delete&id_annonce=<?= $Annonce['id_annonce'] ?>' data-toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger" style="font-size: 1.5rem;"></i></a></td>
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


<!-- MODAL CONFIRMATION DE SUPPRESSION -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Supprimer annonce
            </div>
            <div class="modal-body">
                Etes-vous sur de vouloir retirer cette annonce ?
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