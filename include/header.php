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
    <link rel="icon" type="image/png" href="./img/logoAnnonceo.png" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    
    <!-- links pour les icon bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="/admin/vendor/bootstrap/css/bootstrap.min.css">
  <script src="https://kit.fontawesome.com/896637ab26.js" crossorigin="anonymous"></script>
  
    <title><?= (isset($pageTitle) ? $pageTitle : "Annonceo") ?></title>

    <!-- Template Main CSS File -->
  <!-- <link href="assets/css/style.css" rel="stylesheet"> -->
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
      <li class="nav-item mt-2">
        <a class="nav-link" href="<?= URL ?>index.php">Accueil</a>
      </li>
      <li class="nav-item mt-2 mx-2">
        <a class="nav-link" href="<?= URL ?>QSN.php">Qui Sommes Nous</a>
      </li>
      <li class="nav-item mt-2">
        <a class="nav-link" href="<?= URL ?>contact.php">Contact</a>
      </li>
      
    </ul>
    <ul class="navbar-nav ml-auto">
      
    <?php if(internauteConnecte()): ?>
      <!-- si l'internaute est connecté il aura accés aux pages profil et un bouton de deconnexion  (mais pas aux autres) -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <button type="button" class="btn btn-outline-info">Espace <strong><?= $_SESSION['membre']['pseudo'] ?></strong></button>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?= URL ?>profil.php">Profil <?= $_SESSION['membre']['pseudo'] ?></a>
          
          <a class="dropdown-item" href="<?= URL ?>index.php?action=deconnexion">Déconnexion</a>
        </div>
      </li>
    <?php else: ?>
      
      <!-- si il n'est pas connecté, il aura droit aux pages inscription et connexion -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle mr-5" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <button type="button" class="btn btn-outline-info">Espace Membre</button>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?= URL ?>inscription.php"><button class="btn btn-outline-info">Inscription</button></a>
          <a class="dropdown-item"><button class="btn btn-outline-info" data-toggle="modal" data-target="#connexionModal">Connexion</button></a>
          
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
<h2 class="text-center pb-5">Numéro #1 des sites d'achat en ligne</h2>
<P><?= $erreur ?></p>