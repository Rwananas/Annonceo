<?php
// affichage des catégories dans la navigation latérale
$afficheMenuCategories = $pdo->query("SELECT * FROM  categorie ORDER BY titre ASC");
// fin de navigation laterale catégories

// tout l'affichage par categorie

if(isset($_GET['categorie'])){
    // pagination pour les categories
    if(isset($_GET['page']) && !empty($_GET['page'])){
        $pageCourante = (int) strip_tags($_GET['page']);
    }else{
        $pageCourante = 1;
    }
    $queryAnnonces = $pdo->query(" SELECT COUNT(id_annonce) AS nombreAnnonces FROM annonce ");
    $resultatAnnonces = $queryAnnonces->fetch();
    $nombreAnnonces = (int) $resultatAnnonces['nombreAnnonces'];
    $parPage = 10;
    $nombrePages = ceil($nombreAnnonces / $parPage);
    $premierAnnonce = ($pageCourante - 1) * $parPage;
    // fin pagination pour les categories

    // affichage de tous les annonces concernés par une categorie
    $afficheAnnonces = $pdo->query("SELECT * FROM annonce WHERE categorie_id = '$_GET[categorie]' ORDER BY prix");
    // fin affichage des annonces par categorie

    // affichage de la categorie dans le <h2>
    $afficheTitreCategorie = $pdo->query("SELECT titre FROM categorie WHERE id_categorie = '$_GET[categorie]' ");
    $titreCategorie = $afficheTitreCategorie->fetch(PDO::FETCH_ASSOC);
    // fin du h2 categorie

    // pour les onglets categories
    $pageTitle = "Nos modèles de  " . $_GET['categorie'];
    
    // fin onglets categories

}
// fin affichage par categorie

// -----------------------------------------------------------------------------------

// tout l'affichage par public
// si jamais il y dans l'url public donc applique ce code

// fin affichage par public

// ---------------------------------------------------------------------------------------
// Tout ce qui concerne la fiche annonce

// affichage d'un annonce
// GEt recuperer et appliquer ce qui va suivre avec le if
if(isset($_GET['id_annonce'])){
    $detailannonce = $pdo->query(" SELECT * FROM annonce WHERE id_annonce = '$_GET[id_annonce]' ");
     // pour se protéger de qlq'un qui tenterait de modifier l'id-annonce dans l'URL...si la valeur n'existe pas en BDD, on le redirige vers notre index (URL). Le <= 0 est fait dans le cas ou il injecte une valeur négative
    if($detailannonce->rowCount() <= 0){
        header('location:' . URL);
        exit;
    }
 // si on n'est pas rentré dans la condition, si le annonce existe, on fait le fetch, et le resultat de la requete sera affecté dans la variable/tableau $detail
    $detail = $detailannonce->fetch(PDO::FETCH_ASSOC);
}


// fin affichage d'un seul annonce


//  fin fiche annonce

// --------------------------------------------------------------------------------------------

?>