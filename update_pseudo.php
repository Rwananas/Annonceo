<?php
session_start();
require_once('include/init.php');

$new_pseudo = $_POST['new_pseudo'];
$id_membre = $_SESSION['membre']['id_membre'];

$stmt = $pdo->prepare("UPDATE membre SET pseudo=:pseudo WHERE id_membre=:id_membre");
$stmt->bindValue(':pseudo', $new_pseudo, PDO::PARAM_STR);
$stmt->bindValue(':id_membre', $id_membre, PDO::PARAM_INT);
$stmt->execute();

$_SESSION['membre']['pseudo'] = $new_pseudo;

header('Location: profil.php');
exit();
?>
