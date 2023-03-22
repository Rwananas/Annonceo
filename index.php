<?php
require_once('include/init.php');

// PAGINATION GESTION ANNONCES


// Si un indice page existe dans l'URL et qu'on trouve une valeur dedans 
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $pageCourante = (int) strip_tags($_GET['page']);
} else {
    $pageCourante = 1;
}
// $queryAnnonces = $pdo->query(" SELECT COUNT(id_annonce) AS nombreAnnonces FROM annonce ");
// $resultatAnnonces = $queryAnnonces->fetch();
// $nombreAnnonces = (int) $resultatAnnonces['nombreAnnonces'];
// $parPage = 6;
// $nombresPages = ceil($nombreAnnonces / $parPage);
// $premierAnnonce = ($pageCourante - 1) * $parPage;

// FIN PAGINATION GESTION ANNONCES

require_once('include/affichage.php');
require_once('include/header.php');
?>

</div>
<div class="container-fluid">

    <div class="text-center my-5">



        <!-- --------------------------- -->
        <!-- pour afficher les Annonces par catégories -->


        <div class="text-center my-5">
            <img class='img-fluid' src="./img/bandAnnonceo.svg" alt="Bandeau de La Boutique" loading="lazy">
        </div>


        <div class="container my-5">
            <div class="row justify-content-center">
            
                <div class="col-md-8 text-center">
                    <h1 class="display-4">Bienvenue sur Annonceo !</h1>
                    <p class="lead">Nous sommes ravis de vous accueillir dans notre boutique en ligne où vous trouverez une large sélection de produits de qualité à des prix compétitifs. Que vous cherchiez des vêtements tendance, des accessoires de mode, des produits de beauté, des articles de maison ou des cadeaux pour vos proches, nous avons ce qu'il vous faut.</p>
                    <p>Notre équipe travaille dur pour vous offrir une expérience de shopping en ligne simple et agréable, avec des livraisons rapides et fiables, un service clientèle exceptionnel et des offres spéciales tout au long de l'année. N'hésitez pas à explorer notre site et à nous contacter si vous avez des questions. Merci de votre visite et à bientôt sur Annonceo !</p>
                </div>
            </div>
        </div>




        <?php require_once('include/footer.php') ?>