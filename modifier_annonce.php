<?php

require_once('include/init.php');
require_once('include/affichage.php');
require_once('include/header.php');

if (empty($erreur)) {
            if ($_GET['action'] == 'update') {
                $modifAnnonce =  $pdo->prepare(" UPDATE Annonce SET id_annonce = :id_annonce, titre = :titre, description_courte = :description_courte, description_longue = :description_longue, prix = :prix, pays = :pays, ville = :ville, code_postal = :code_postal, adresse = :adresse, photo = :photo  WHERE id_annonce = :id_annonce");
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
            }
        }