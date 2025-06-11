<?php

require_once "auth.php";
require_once "../config_local.php";

// 1) Vérifier POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status"=>"error","message"=>"Méthode non autorisée"]);
    exit;
}

// 2) Lire le JSON brut
$body = file_get_contents('php://input');
$data = json_decode($body, true);


// 3) Récupérer l’ID
$fiche_id = isset($data['id']) ? (int)$data['id'] : 0;
if ($fiche_id <= 0) {
    echo json_encode(["status"=>"error","message"=>"ID de fiche manquant ou invalide"]);
    exit;
}

try {
    // 4) Mettre à jour le statut
    $stmt = $pdo->prepare("UPDATE fiches_frais SET statut = 'validée' WHERE id = ?");
    $stmt->execute([$fiche_id]);
    echo json_encode(["status"=>"ok"]);
} catch (Exception $e) {
    echo json_encode(["status"=>"error","message"=>"Erreur serveur : ".$e->getMessage()]);
}
