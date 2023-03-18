
<!-- INDEX LIGNE 33 -->
<!-- POUR AFFICHER LES CATEGORIES AVEC UNE LISTE DE BOUTONS -->

<div class="list-group text-center">
                <?php while ($menuCategorie = $afficheMenuCategories->fetch(PDO::FETCH_ASSOC)) : ?>
                    <a class="btn btn-outline-info my-2" href="<?= URL ?>?categorie=<?= $menuCategorie['id_categorie'] ?>"><?= $menuCategorie['titre'] ?></a>
                <?php endwhile; ?>
            </div>