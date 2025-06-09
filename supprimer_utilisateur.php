<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: connexionadmin.php");
    exit();
}

$id_utilisateur = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_utilisateur > 0) {
    $sql = "DELETE FROM utilisateurs WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id_utilisateur]);
}

header("Location: gestionutilisateurs.php");
exit();
?>
