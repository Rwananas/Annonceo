<?php
require_once('include/connexion.php');

if(isset($_GET['action'])  && $_GET['action'] == 'deconnexion'){
  unset($_SESSION['membre']);
  // une fois deconnecté, on redirige, par exemple vers la page de connexion, si par erreur la personne s'est déconnectée
  header('location:' . URL . 'index.php');
  // onn'oublie pas le exit après toute redirection, cela permet de neutraliser le code qui suit, en cas d'acte de malveillance
  exit();
}



// POUR AFFICHER LES CATEGORIES DANS LA NAV
$afficheMenuPublics = $pdo->query(" SELECT DISTINCT titre FROM categorie ORDER BY titre ASC ");


?>

<!-- $erreur .= '<div class="alert alert-danger" role="alert">Erreur pseudo inconnu !</div>'; -->

<!-- $validate .= '<div class="alert alert-info alert-dismissible fade show mt-5" role="alert">
                  Félicitations <strong>' . $_SESSION['membre']['pseudo'] .'</strong>, vous etes connecté(e) 😉 !
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>'; -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- favicon -->
    <link rel="icon" type="image/png" href="logo.png" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

         <!-- links pour les icon bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">

  <!-- code pour récupérer le nom de chaque page de manière dynamique on declare pour chaque fichier, une valeur à pageTitle
  Dans le cas de la page d'accueil/index, impossible d'avoir une valeur si on a cliqué sur rien, donc on ne peut pas déclarer dans index.php une valeur unique. Cela empecherait d'avoir un onglet dynamiqu si on veut afficher les manteaux, ou les vestes etc...
  Pour résoudre ce problème, on dit que si pageTitle existe (dans un fichier), on affiche sa valeur, si elle n'existe pas, on affiche La Boutique -->
    <title><?= (isset($pageTitle) ? $pageTitle : "Annonceo") ?></title>
</head>
<body>

<header>

<!-- ------------------- -->

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="<?= URL ?>"><img src="<?= URL ?>img/logoAnnonceo.svg"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item mt-2 mx-2">
        <a class="nav-link" href="<?= URL ?>">Qui Sommes Nous</a>
      </li>
      <li class="nav-item mt-2">
        <a class="nav-link" href="<?= URL ?>">Contact</a>
      </li>
      <li class="nav-item mt-2">
        <a class="nav-link" href="<?= URL ?>annonce.php">Annonces</a>
      </li>
      
      <!-- -----------POUR AFFICHER LES CATEGORIE DANS LA NAV -->
      <!-- <?php while($menuPublic = $afficheMenuPublics->fetch(PDO::FETCH_ASSOC)): ?>
      <li class="nav-item">
        <a class="nav-link" href="<?= URL ?>?public=<?= $menuPublic['titre'] ?>"><button type="button" class="btn btn-outline-info"><?= ucfirst($menuPublic['titre']) ?></button></a>
      </li>
      <?php endwhile; ?> -->
      <!-- ---------- -->
    </ul>
    <ul class="navbar-nav ml-auto">
      <!-- -------------------------- -->
    <?php if(internauteConnecte()): ?>
      <!-- si l'internaute est connecté il aura accés aux pages profil, panier et un bouton de deconnexion  (mais pas aux autres) -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <button type="button" class="btn btn-outline-info">Espace <strong><?= $_SESSION['membre']['pseudo'] ?></strong></button>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?= URL ?>profil.php">Profil <?= $_SESSION['membre']['pseudo'] ?></a>
          <a class="dropdown-item" href="<?= URL ?>panier.php">Panier <?= $_SESSION['membre']['pseudo'] ?></a>
          <a class="dropdown-item" href="<?= URL ?>annonce.php">Vos Annonces <?= $_SESSION['membre']['pseudo'] ?></a>
          <a class="dropdown-item" href="<?= URL ?>index.php?action=deconnexion">Déconnexion</a>
        </div>
      </li>
    <?php else: ?>
      <!-- ---------------------------- -->
      <!-- si il n'est pas connecté, il aura droit aux pages inscription, connexion et panier (mais pas aux autres)-->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle mr-5" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <button type="button" class="btn btn-outline-info">Espace Membre</button>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?= URL ?>inscription.php"><button class="btn btn-outline-info">Inscription</button></a>
          <a class="dropdown-item"><button class="btn btn-outline-info" data-toggle="modal" data-target="#connexionModal">
            Connexion
          </button></a>
          <a class="dropdown-item" href="<?= URL ?>panier.php"><button class="btn btn-outline-info px-4">Panier</button></a>
        </div>
      </li>
      <?php endif; ?>
    
     <!-- ------------------------------------ -->
     <!-- le bouton admin n'apparaitra que pour un utilisateur qui a les droits d'admin -->
    <?php if(internauteConnecteAdmin()): ?>
      <li class="nav-item mr-5">
          <a class="nav-link" href="admin/index.php"><button type="button" class="btn btn-outline-info">Admin</button></a>
      </li>
    <?php endif; ?>
      <!-- ------------------------------------ -->
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Recherche" aria-label="Search">
      <button class="btn btn-outline-info my-2 my-sm-0" type="submit">Recherche</button>
    </form>
  </div>
</nav>

</header>

<div class="container">

          <!-- Modal -->
          <div class="modal fade" id="connexionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel"><img src="<?= URL ?>img/logoAnnonceo.svg">Annonceo</h3>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body text-center">
                
                <form name="connexion" method="POST" action="">
                    <div class="row justify-content-around">
                      <div class="col-md-4 mt-4">
                      <label class="form-label" for="pseudo"><div class="badge badge-dark text-wrap">Pseudo</div></label>
                      <input class="form-control btn btn-outline-info" type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo">
                      </div>
                    </div>

                    <div class="row justify-content-around">
                      <div class="col-md-6 mt-4">
                      <label class="form-label" for="mdp"><div class="badge badge-dark text-wrap">Mot de passe</div></label>
                      <input class="form-control btn btn-outline-info" type="password" name="mdp" id="mdp" placeholder="Votre mot de passe">
                      </div>
                    </div>
                    
                    <div class="row justify-content-center">
                      <button type="submit" name="connexion" class="btn btn-lg btn-outline-info mt-3">Connexion</button>
                    </div>
                    <p><a href="inscription/index.php"> Pas encore de compte ? Inscrivez-vous ici</a></p>

                </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                </div>
              </div>
            </div>
          </div>
          <!-- ------------- -->

<h1 class="text-center mt-5"><div class="badge badge-dark text-wrap p-3">ANNONCEO</div></h1>
<h2 class="text-center pb-5">Notre Catalogue. Nos Produits !</h2>
<P><?= $erreur ?></p>