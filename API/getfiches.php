<?php

require_once "auth.php";
require_once "../config_local.php";

// 1) Vérifier la méthode
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    echo json_encode(["status" => "error", "message" => "Méthode non autorisée"]);
    exit;
}

// 2) Récupérer l'ID utilisateur
$user_id = isset($_GET["user_id"]) ? (int) $_GET["user_id"] : 0;
if ($user_id <= 0) {
    echo json_encode(["status" => "error", "message" => "ID utilisateur manquant ou invalide"]);
    exit;
}

try {
    // 3) Filtrer sur la colonne utilisateur_id (et non id_utilisateur)
    $stmt = $pdo->prepare("
        SELECT 
            id,
            date_fiche,
            montant,
            statut
        FROM fiches_frais
        WHERE utilisateur_id = ?
        ORDER BY date_enregistrement DESC
    ");
    $stmt->execute([$user_id]);
    $fiches = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4) Retourner le résultat
    echo json_encode([
        "status" => "ok",
        "fiches" => $fiches
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Erreur serveur : " . $e->getMessage()
    ]);
}
