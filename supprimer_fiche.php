<?php
session_start();

// Inclut le bon fichier de config (local ou distant)
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    require_once 'config_local.php';
} else {
    require_once 'config.php';
}

// Vérifie que l’utilisateur est admin
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: connexionadmin.php");
    exit();
}

// Récupère l’ID de la fiche
$id_fiche = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_fiche > 0) {
    // Supprime la fiche de frais
    $sql = "DELETE FROM fiches_frais WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id_fiche]);
}

// Redirige vers le dashboard
header("Location: dashboardadmin.php");
exit();
?>
