<?php

require_once('include/init.php');

if (isset($_GET['action'])  && $_GET['action'] == 'deconnexion') {
    unset($_SESSION['membre']);
    // Utilisateur DECONNECTE, redirection vers la page de INDEX
    header('location:' . URL . 'index.php');
    exit();
}



// CREER UNE ERREUR 'localhost vous a redirigé à de trop nombreuses reprises.'
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Utilisateur CONNECTE, redirection vers la page PROFIL
// if(internauteConnecte()){
//     header('location:' . URL . 'profil.php');
//     exit();
// }

// AUTRE MANIERE TESTEE
// if(internauteConnecte()){
//     header('location' . URL . 'profil.php?action=validate');
//     exit();
// }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// VOIR EN BAS DE PAGE



// Condition à mettre obligatoirement pour éviter un undefined key $action (si la personne veut se connecter sans passer par la phase inscription)
if(isset($_GET['action']) && $_GET['action'] == 'validate'){
    $validate .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
<strong>Félicitations !</strong> Votre inscription est réussie 😉, vous pouvez vous connecter !
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
</div>';
}

if ($_POST && !isset($_POST['inscription']) && isset($_POST['pseudo']) && isset($_POST['mdp'])) {

    // requete qui va comparer le pseudo entré dans le champs du form avec les infos en BDD (Ce pseudo existe t-il ?)
    $verifPseudo = $pdo->prepare(" SELECT * FROM membre WHERE pseudo = :pseudo ");
    $verifPseudo->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
    $verifPseudo->execute();

    // si un même pseudo existe en BDD (rowCount == 1), alors on continue la procédure d'authentification
    if ($verifPseudo->rowCount() == 1) {
        // on fait un fetch pour récupérer toutes les valeurs de cette entrée en BDD qui à le même pseudo
        $user = $verifPseudo->fetch(PDO::FETCH_ASSOC);
        // si le mot de passe correspond, on authentifie
        // password_verify est une fonction prédéfinie qui permet de comparer le mdp en BDD hashé, avec le vrai mdp du user (elle va déhasher le mdp en BDD)
        if (password_verify($_POST['mdp'], $user['mdp'])) {
            // les deux mots de passe correspondent, on crée une session utilisateur qui va enregistrer toutes les infos le concernant, il en aura besoin sur le site
            foreach ($user as $key => $value) {
                // on récupère toutes les infos en BDD sauf son mot de passe, dangeureux et inutile
                if ($key != 'mdp') {
                    // boucle qui permet de ne pas taper toutes les lignes en dessous
                    $_SESSION['membre'][$key] = $value;
                }
            }
        } else {
            // si le mot de passe ne correspond pas, message d'erreur
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur ce mot de passe ne correspond pas !</div>';
        }
    } else {
        // si le pseudo n'est pas référencé en BDD, on en avertit l'utilisateur
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur ce pseudo n\'existe pas, vérifiez !<br> Etes vous inscrit ?</div>';
    }

    $validate .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
    <strong>Félicitations !</strong> Votre inscription est réussie 😉, vous pouvez vous connecter !
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>';



    // TENTATIVE DE REDIRECTION VERS PAGE PROFIL
    // header('location' . URL . 'profil.php?action=validate');
}
