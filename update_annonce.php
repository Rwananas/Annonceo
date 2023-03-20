
<?php
session_start();
require_once('include/init.php');

$id_membre = $_SESSION['membre']['id_membre'];

// Récupération des nouvelles valeurs
$new_pseudo = $_POST['new_pseudo'];
$new_prenom = $_POST['new_prenom'];
$new_nom = $_POST['new_nom'];
$new_email = $_POST['new_email'];
$new_telephone = $_POST['new_telephone'];

// Mise à jour des données dans la base de données
$stmt = $pdo->prepare("UPDATE membre SET pseudo=:pseudo, prenom=:prenom, nom=:nom, email=:email, telephone=:telephone WHERE id_membre=:id_membre");
$stmt->bindValue(':pseudo', $new_pseudo, PDO::PARAM_STR);
$stmt->bindValue(':prenom', $new_prenom, PDO::PARAM_STR);
$stmt->bindValue(':nom', $new_nom, PDO::PARAM_STR);
$stmt->bindValue(':email', $new_email, PDO::PARAM_STR);
$stmt->bindValue(':telephone', $new_telephone, PDO::PARAM_STR);
$stmt->bindValue(':id_membre', $id_membre, PDO::PARAM_INT);
$stmt->execute();

// Mise à jour des données dans la session PHP de l'utilisateur
$_SESSION['membre']['pseudo'] = $new_pseudo;
$_SESSION['membre']['prenom'] = $new_prenom;
$_SESSION['membre']['nom'] = $new_nom;
$_SESSION['membre']['email'] = $new_email;
$_SESSION['membre']['telephone'] = $new_telephone;

// Redirection vers la page de profil
header('Location: profil.php');
exit();
?>
