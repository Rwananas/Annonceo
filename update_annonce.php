
<?php

require_once('include/init.php');

// vérification de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // récupération des données du formulaire
    $id_annonce = $_POST['id_annonce'];
    $titre = $_POST['titre'];
    $description_courte = $_POST['description_courte'];
    $description_longue = $_POST['description_longue'];
    $prix = $_POST['prix'];

    // traitement de l'image si elle a été téléchargée
    if (!empty($_FILES['photo']['name'])) {
        $photo = $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], 'chemin_vers_dossier/' . $photo);
    } else {
        $photo = null;
    }

    // requête SQL pour mettre à jour l'annonce dans la base de données
    $sql = "UPDATE annonce SET titre = :titre, description_courte = :description_courte, description_longue = :description_longue, photo = :photo, prix = :prix WHERE id_annonce = :id_annonce";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':titre', $titre);
    $stmt->bindValue(':description_courte', $description_courte);
    $stmt->bindValue(':description_longue', $description_longue);
    $stmt->bindValue(':photo', $photo);
    $stmt->bindValue(':prix', $prix);
    $stmt->bindValue(':id_annonce', $id_annonce);
    $stmt->execute();

    // redirection vers la page de détails de l'annonce mise à jour
    header('Location: ' . URL . 'fiche_annonceModif.php?id=' . $id_annonce);
    exit();
} else {
    // récupération de l'annonce à mettre à jour
    $id_annonce = $_GET[''];
    $sql = "SELECT * FROM annonce WHERE id_annonce = :id_annonce";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_annonce', $id_annonce);
    $stmt->execute();
    $annonce = $stmt->fetch(PDO::FETCH_ASSOC);

    // vérification que l'annonce existe
    if (!$annonce) {
        die('Annonce non trouvée');
    }

}
// redirection vers la page de détails de l'annonce mise à jour
header('Location: ' . URL . 'fiche_annonceModif.php?id=' . $id_annonce);
exit();
?>
