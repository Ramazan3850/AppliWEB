<?php

require_once "auth.php";
require_once "../config_local.php";

// Afficher les erreurs en dev
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1) N’accepte que la méthode POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status"=>"error","message"=>"Méthode non autorisée"]);
    exit;
}

// 2) Récupère et valide l’ID de la fiche
$fiche_id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;
if ($fiche_id <= 0) {
    echo json_encode(["status"=>"error","message"=>"ID de fiche manquant ou invalide"]);
    exit;
}

try {
    // 3) Supprime les lignes associées en transaction
    $pdo->beginTransaction();

    // Supprime d’abord les lignes forfait (si la table existe)
    $pdo->prepare("DELETE FROM ligne_frais_forfait WHERE fiche_frais_id = ?")
        ->execute([$fiche_id]);

    // Supprime les lignes hors-forfait
    $pdo->prepare("DELETE FROM ligne_frais_hors_forfait WHERE fiche_frais_id = ?")
        ->execute([$fiche_id]);

    // Puis supprime la fiche elle-même
    $stmt = $pdo->prepare("DELETE FROM fiches_frais WHERE id = ?");
    $stmt->execute([$fiche_id]);

    $pdo->commit();

    // 4) Réponse JSON
    echo json_encode(["status"=>"ok"]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
        "status"  => "error",
        "message" => "Erreur serveur : " . $e->getMessage()
    ]);
}
