<?php
// affichage des catégories dans la navigation latérale
$afficheMenuCategories = $pdo->query(" SELECT * FROM categorie ORDER BY titre ");
// fin de navigation laterale catégories

// tout l'affichage par categorie
if (isset($_GET['categorie'])) {

    // affichage de tous les Annonces concernés par une categorie
    $afficheAnnonces = $pdo->query(" SELECT * FROM Annonce WHERE categorie_id = '$_GET[categorie]' ORDER BY prix ASC ");
    // fin affichage des Annonces par categorie

    // affichage de la categorie dans le <h2>
    $afficheTitreCategorie = $pdo->query(" SELECT titre FROM categorie WHERE id_categorie = '$_GET[categorie]' ");
    $titreCategorie = $afficheTitreCategorie->fetch(PDO::FETCH_ASSOC);
    // fin du h2 categorie

    // pour les onglets categories
    $pageTitle = "Nos modèles de " . $_GET['categorie'];
    // fin onglets categories
}
// fin affichage par categorie

// -----------------------------------------------------------------------------------

// tout l'affichage par public
// if(isset($_GET['public'])){
//     // pagination Annonces par public

//     // fin pagination Annonces par public

//     // affichage des Annonces par public
//     // requete qui va cibler tous les Annonces qui ont en commun le public récupéré dans l'URL
//     $afficheAnnonces = $pdo->query(" SELECT * FROM Annonce WHERE public = '$_GET[public]' ORDER BY prix ASC ");
//     // fin affichage des Annonces par public

//     // affichage du public dans le <h2>
//     $afficheTitrePublic = $pdo->query(" SELECT public FROM Annonce WHERE public = '$_GET[public]' ");
//     $titrePublic = $afficheTitrePublic->fetch(PDO::FETCH_ASSOC);
//     // fin du </h2> pour le public

//     // pour les onglets publics
//     $pageTitle = "Nos vetements " . ucfirst($_GET['public']) . 's'; 
//     // fin onglets publics
// }
// fin affichage par public

// ---------------------------------------------------------------------------------------
// Tout ce qui concerne la fiche Annonce

// affichage d'un Annonce
if (isset($_GET['id_Annonce'])) {
    $detailAnnonce = $pdo->query(" SELECT * FROM annonce WHERE id_annonce = '$_GET[id_annonce]' ");
    // pour se protéger de qlq'un qui tenterait de modifier l'id-Annonce dans l'URL...si la valeur n'existe pas en BDD, on le redirige vers notre index (URL). Le <= 0 est fait dans le cas ou il injecte une valeur négative
    if ($detailAnnonce->rowCount() <= 0) {
        header('location:' . URL);
        exit;
    }
    // si on n'est pas rentré dans la condition, si le Annonce existe, on fait le fetch, et le resultat de la requete sera affecté dans la variable/tableau $detail
    $detail = $detailAnnonce->fetch(PDO::FETCH_ASSOC);
}
// fin affichage d'un seul Annonce


//  fin fiche Annonce

// --------------------------------------------------------------------------------------------