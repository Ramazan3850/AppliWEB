<?php

require_once "auth.php";
require_once "../config_local.php";

// 1) On n’accepte que la méthode POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status"=>"error","message"=>"Méthode non autorisée"]);
    exit;
}

// 2) On récupère et on valide les paramètres
$fiche_id    = isset($_POST["id"])           ? (int)   $_POST["id"]           : 0;
$date_fiche  = isset($_POST["date_fiche"])   ?         $_POST["date_fiche"]    : "";
$commentaire = isset($_POST["commentaire"])  ?         $_POST["commentaire"]   : "";

if ($fiche_id <= 0 || empty($date_fiche)) {
    echo json_encode(["status"=>"error","message"=>"Paramètres manquants ou invalides"]);
    exit;
}

// (Optionnel) debug rapide
// error_log("UPDATE FICHE → id:$fiche_id | date_fiche:$date_fiche | commentaire:$commentaire");

try {
    // 3) Mise à jour de la fiche
    $stmt = $pdo->prepare("
        UPDATE fiches_frais
        SET date_fiche  = ?,
            commentaire = ?
        WHERE id = ?
    ");
    $stmt->execute([$date_fiche, $commentaire, $fiche_id]);

    // 4) Réponse JSON
    echo json_encode(["status"=>"ok"]);
} catch (Exception $e) {
    echo json_encode([
        "status"  => "error",
        "message" => "Erreur serveur : " . $e->getMessage()
    ]);
}
