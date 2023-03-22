<?php
require_once('../include/init.php');

if (!internauteConnecteAdmin()) {
    header('location:' . URL . 'index.php');
    exit();
}

// PAGINATION GESTION MEMBRES


// Si un indice page existe dans l'URL et qu'on trouve une valeur dedans 
if (isset($_GET['page']) && !empty($_GET['page'])) {
    // Alors on déclare une variable $pageCourante à laquelle on va affecter la valeur véhiculée par l'indice page dans l'URL
    // Protection de ce qui sera véhiculé dans l'URL avec strip_tags ou htmlspecialchars, plus on force le typage de l'information dans l'URL avec (int) pour indiquer qu'on ne veut pas recevoir autre chose qu'un nombre entier
    $pageCourante = (int) strip_tags($_GET['page']);
} else {
    // Dans le cas ou aucune information n'a transité dans l'URL, $pageCourante prendra la valeur par défaut de 1 (première page)
    $pageCourante = 1;
}

// Je dois connaitre le nombre de membres en BDD pour établir mon système de pagination
// Je connais déjà ce nombre (voir en haut) avec rowCount. La syntaxe qui va suivre est plus longue et compliqué mais elle sera plus rapide à l'éxécution que rowCount
$queryMembres = $pdo->query(" SELECT COUNT(id_membre) AS nombreMembres FROM membre ");
// Le fetch après le query pour récupérer le nombre (pas besoin de fetch_assoc, je ne vais cibler aucune colonne, je veux récupérer un nombre total)
$resultatMembres = $queryMembres->fetch();
$nombreMembres = (int) $resultatMembres['nombreMembres'];
// echo debug($nombremembres);

// Je veux que sur chaque page, ne s'affiche dans le tableau que 10 membres
$parPage = 10;
// Calcul pour savoir combien de pages devront être générées ( nombre évolutif)
// Utilisation de ceil(), fonction prédéfinie qui arrondi à l'unité supérieur si le résultat de la division est un chiffre à virgule
$nombresPages = ceil($nombreMembres / $parPage);
// Définir le 1er membre qui va s'afficher à chaque nouvelle pagge ( on va le cibler grace à l'indice qu'il occupe dans le tableau)
$premierMembre = ($pageCourante - 1) * $parPage;


// Fin pagination

// au préalable, pour introduire le formulaire, je vérifie que j'ai reçu dans l'URL un indice action. Ca permettra de ne pas répéter plusieurs fois cette vérification dans tout le traitement du formulaire qui va suivre
if (isset($_GET['action'])) {

    if ($_POST) {
        // PSEUDO
        if (!isset($_POST['pseudo']) || !preg_match('#^[a-zA-Z0-9-_.]{3,20}$#', $_POST['pseudo'])) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format pseudo !</div>';
        }

        if ($_GET['action'] == 'add') {
            $verifPseudo = $pdo->prepare("SELECT pseudo FROM membre WHERE pseudo = :pseudo ");
            $verifPseudo->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
            $verifPseudo->execute();

            if ($verifPseudo->rowCount() == 1) {

                $erreur .= '<div class="alert alert-danger" role="alert">Erreur, ce pseudo existe déjà, vous devez en choisir un autre !</div>';
            }

            if (!isset($_POST['mdp']) || strlen($_POST['mdp']) < 3 || strlen($_POST['mdp']) > 20) {
                $erreur .= '<div class="alert alert-danger" role="alert">Erreur format mdp !</div>';
            }

            $_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
        }
        // NOM
        if (!isset($_POST['nom']) || iconv_strlen($_POST['nom']) < 3 || iconv_strlen($_POST['nom']) > 20) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format nom !</div>';
        }
        // PRENOM
        if (!isset($_POST['prenom']) || iconv_strlen($_POST['prenom']) < 3 || iconv_strlen($_POST['prenom']) > 20) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format prénom !</div>';
        }
        // EMAIL
        if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format email !</div>';
        }
        // CIVILITE
        if (!isset($_POST['civilite']) || $_POST['civilite'] != 'f' && $_POST['civilite'] != 'm') {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format civilité !</div>';
        }
        // TELEPHONE
        if (!isset($_POST['telephone']) || !preg_match('#^[0-9]{10}$#', $_POST['telephone'])) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format téléphone !</div>';
        }


        if (empty($erreur)) {
            // si dans l'URL action == update, on entame une procédure de modification
            if ($_GET['action'] == 'update') {
                $modifUser = $pdo->prepare(" UPDATE membre SET id_membre = :id_membre , pseudo = :pseudo, nom = :nom, prenom = :prenom, telephone = :telephone, email = :email, civilite = :civilite WHERE id_membre = :id_membre ");
                $modifUser->bindValue(':id_membre', $_POST['id_membre'], PDO::PARAM_INT);
                $modifUser->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
                $modifUser->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
                $modifUser->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
                $modifUser->bindValue(':telephone', $_POST['telephone'], PDO::PARAM_STR);
                $modifUser->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
                $modifUser->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
                
                $modifUser->execute();

                // pour personnaliser le message de réussite, je dois récupérer le pseudo de l'utilisateur modifié en BDD, pour personnaliser le message
                $queryUser = $pdo->query(" SELECT pseudo FROM membre WHERE id_membre = '$_GET[id_membre]' ");
                $user = $queryUser->fetch(PDO::FETCH_ASSOC);

                $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                        <strong>Félicitations !</strong> Modification de l\'utilisateur ' . $user['pseudo'] . ' réussie !
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {
                // si on récupère autre chose que update (et donc add) on entame une procédure d'insertion en BDD
                $inscrireUser = $pdo->prepare(" INSERT INTO membre (pseudo, mdp, nom, prenom, telephone, email, civilite, date_enregistrement, statut) VALUES (:pseudo, :mdp, :nom, :prenom, :telephone, :email, :civilite, NOW(), 2) ");
                $inscrireUser->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
                $inscrireUser->bindValue(':mdp', $_POST['mdp'], PDO::PARAM_STR);
                $inscrireUser->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
                $inscrireUser->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
                $inscrireUser->bindValue(':telephone', $_POST['telephone'], PDO::PARAM_INT);
                $inscrireUser->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
                $inscrireUser->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
                // NOW() POUR DATE D'ENREGISTREMENT
                // 2 POUR LE STATUT EN BDD
                $inscrireUser->execute();

                $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                        <strong>Félicitations !</strong> Inscription de l\'utilisateur réussie !
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            }
        }
    }

    // RECUPERATION DES INFOS EN BDD POUR LES AFFICHER DANS LE FORMULAIRE LORS D'UN UPDATE
    if ($_GET['action'] == 'update') {
        $tousUsers = $pdo->query("SELECT * FROM membre WHERE id_membre = '$_GET[id_membre]' ");
        $userActuel = $tousUsers->fetch(PDO::FETCH_ASSOC);
    }

    $id_membre = (isset($userActuel['id_membre'])) ? $userActuel['id_membre'] : "";
    $pseudo = (isset($userActuel['pseudo'])) ? $userActuel['pseudo'] : "";
    $email = (isset($userActuel['email'])) ? $userActuel['email'] : "";
    $nom = (isset($userActuel['nom'])) ? $userActuel['nom'] : "";
    $prenom = (isset($userActuel['prenom'])) ? $userActuel['prenom'] : "";
    $civilite = (isset($userActuel['civilite'])) ? $userActuel['civilite'] : "";
    $telephone = (isset($userActuel['telephone'])) ? $userActuel['telephone'] : "";


    if ($_GET['action'] == 'delete') {
        // requete de suppression d'une entrée (pas besoin de stocker une valeur dans une variable que l'on declare, on travaille directement avec l'objet $pdo qui pointe sur la méthode query pour faire un DELETE)
        $pdo->query(" DELETE FROM membre WHERE id_membre = '$_GET[id_membre]' ");

        $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                        <strong> Suppression de l\'utilisateur réussie ! </strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    }
}

require_once('includeAdmin/header.php');
?>

<!-- $erreur .= '<div class="alert alert-danger" role="alert">Erreur format mot de passe !</div>'; -->

<!-- $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                        <strong>Félicitations !</strong> Insertion du produit réussie !
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>'; -->

<h1 class="text-center my-5">
    <div class="badge badge-warning text-wrap p-3">Gestion des utilisateurs</div>
</h1>

<?= $erreur ?>
<?= $content ?>


<!-- AFFICHAGE DU FORMULAIRE ET DU TITRE A LA DEMANDE -->
<?php if (isset($_GET['action'])) : ?>
    <h2 class="my-5">Formulaire <?= ($_GET['action'] == 'add') ? "d'ajout" : "de modification" ?> des utilisateurs</h2>

    <!-- FORMULAIRE -->
    <form class="my-5" method="POST" action="">

        <input type="hidden" name="id_membre" value="<?= $id_membre ?>">

        <div class="row">
            <div class="col-md-4 mt-5">
                <label class="form-label" for="pseudo">
                    <div class="badge badge-dark text-wrap">Pseudo</div>
                </label>
                <input class="form-control" type="text" name="pseudo" id="pseudo" placeholder="Pseudo" value="<?= $pseudo ?>">
            </div>

            <!-- PAS DE CHAMPS MOT DE PASSE POUR UNE MODIFICATION -->
            <?php if ($_GET['action'] == "add") : ?>

                <div class="col-md-4 mt-5">
                    <label class="form-label" for="mdp">
                        <div class="badge badge-dark text-wrap">Mot de passe</div>
                    </label>
                    <input class="form-control" type="password" name="mdp" id="mdp" placeholder="Mot de passe">
                </div>

            <?php endif; ?>

            <div class="col-md-4 mt-5">
                <label class="form-label" for="email">
                    <div class="badge badge-dark text-wrap">Email</div>
                </label>
                <input class="form-control" type="email" name="email" id="email" placeholder="Email" value="<?= $email ?>">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mt-5">
                <label class="form-label" for="nom">
                    <div class="badge badge-dark text-wrap">Nom</div>
                </label>
                <input class="form-control" type="text" name="nom" id="nom" placeholder="Nom" value="<?= $nom ?>">
            </div>

            <div class="col-md-4 mt-5">
                <label class="form-label" for="prenom">
                    <div class="badge badge-dark text-wrap">Prénom</div>
                </label>
                <input class="form-control" type="text" name="prenom" id="prenom" placeholder="Prénom" value="<?= $prenom ?>">
            </div>

            <div class="col-md-4 mt-4">
                <p>
                <div class="badge badge-dark text-wrap">Civilité</div>
                </p>

                <input type="radio" name="civilite" id="civilite1" value="f" <?= ($civilite == "femme") ? 'checked' : ""  ?>>
                <label class="mx-2" for="civilite1">Femme</label>

                <input type="radio" name="civilite" id="civilite2" value="m" <?= ($civilite == "homme") ? 'checked' : ""  ?>>
                <label class="mx-2" for="civilite2">Homme</label>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mt-5">
                <label class="form-label" for="telephone">
                    <div class="badge badge-dark text-wrap">Telephone</div>
                </label>
                <input class="form-control" type="tel" name="telephone" id="telephone" placeholder="telephone" value="<?= $telephone ?>">
            </div>
        </div>

        <div class="col-md-1 mt-5">
            <button type="submit" class="btn btn-outline-dark btn-warning">Valider</button>
        </div>

    </form>
    <!-- FIN FORMULAIRE -->
<?php endif; ?>


<!-- REQUETE SQL POUR RECUPERER LE NOMBRE DE D'UTILISATEURS INSCRITS EN BDD -->
<!-- nb que je pourrais afficher grace à rowCount deux lignes en dessous -->
<?php $queryMembres = $pdo->query("SELECT id_membre FROM membre"); ?>
<h2 class="py-5">Nombre d'utilisateurs en base de données: <?= $queryMembres->rowCount() ?></h2>

<div class="row justify-content-center py-5">
    <a href='?action=add'>
        <button type="button" class="btn btn-sm btn-outline-dark shadow rounded">
            <i class="bi bi-plus-circle-fill"></i> Ajouter un utilisateur
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
    <?php $afficheUsers = $pdo->query("SELECT * FROM membre ORDER BY pseudo ASC LIMIT $parPage OFFSET $premierMembre "); ?>
    <thead>
        <tr>
            <?php for ($i = 0; $i < $afficheUsers->columnCount(); $i++) :
                $colonne = $afficheUsers->getColumnMeta(($i)) ?>
                <?php if ($colonne['name'] != 'mdp') : ?>
                    <th><?= $colonne['name'] ?></th>
                <?php endif; ?>
            <?php endfor; ?>
            <th colspan=2>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($user = $afficheUsers->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <?php foreach ($user as $key => $value) : ?>
                    <?php if ($key != 'mdp') : ?>
                        <td><?= $value ?></td>
                    <?php endif; ?>
                <?php endforeach; ?>
                <td><a href='?action=update&id_membre=<?= $user['id_membre'] ?>'><i class="bi bi-pen-fill text-warning"></i></a></td>
                <td><a data-href="?action=delete&id_membre=<?= $user['id_membre'] ?>" data-toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger" style="font-size: 1.5rem;"></i></a></td>
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
                Supprimer Utilisateur
            </div>
            <div class="modal-body">
                Etes-vous sur de vouloir retirer cet utilisateur de votre base de données ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                <a class="btn btn-danger btn-ok">Supprimer</a>
            </div>
        </div>
    </div>
</div>

<!-- MODAL CONFIRMATION DE MODIFICATION -->




<!-- pour empecher la modale de s'ouvrir à chaque rafraichissement de page, le temps de terminer de coder cette page -->
<?php if (!isset($_GET['action']) && !isset($_GET['page'])) : ?>
    <!-- modal infos -->
    <div class="modal fade" id="myModalUsers" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-warning" id="exampleModalLabel">Gestion des utilisateurs</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Gérez ici votre base de données des utilisateurs</p>
                    <p>Vous pouvez modifier leurs données, ajouter ou supprimer un utilisateur</p>
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