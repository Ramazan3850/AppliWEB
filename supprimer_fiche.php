<?php
session_start();
require_once "config.php";

// Vérifie la session
if (!isset($_SESSION['visiteur_id'])) {
    header("Location: connexionvisiteur.php");
    exit();
}

// Vérifie l'ID fourni
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_fiche = (int) $_GET['id'];

    // Supprime la fiche
    $sql = "DELETE FROM fichesfrais WHERE id = :id_fiche";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_fiche' => $id_fiche]);

    // Redirige vers l'affichage
    header("Location: afficherfichefrais.php");
    exit();
} else {
    // ID manquant ou invalide
    header("Location: afficherfichefrais.php");
    exit();
}
?>
