<?php

require_once "auth.php";
require_once "../config_local.php";

// 1) N’accepte que la méthode GET
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    echo json_encode(["status"=>"error","message"=>"Méthode non autorisée"]);
    exit;
}

// 2) Récupère et valide l’ID utilisateur
$user_id = isset($_GET["user_id"]) ? (int)$_GET["user_id"] : 0;
if ($user_id <= 0) {
    echo json_encode(["status"=>"error","message"=>"ID utilisateur manquant ou invalide"]);
    exit;
}

try {
    // 3) Compter le nombre de fiches par statut
    $stmt = $pdo->prepare("
        SELECT statut, COUNT(*) AS total
        FROM fiches_frais
        WHERE utilisateur_id = ?
        GROUP BY statut
    ");
    $stmt->execute([$user_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4) Transformer en tableau associatif [statut => total]
    $stats = [];
    foreach ($rows as $r) {
        $stats[$r['statut']] = (int)$r['total'];
    }

    // 5) Renvoi du JSON
    echo json_encode([
        "status" => "ok",
        "stats"  => $stats
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status"  => "error",
        "message" => "Erreur serveur : " . $e->getMessage()
    ]);
}
