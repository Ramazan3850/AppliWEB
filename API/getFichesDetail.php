<?php

require_once "auth.php";
require_once "../config_local.php";

// 1) On n’accepte que la méthode GET
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    echo json_encode(["status"=>"error","message"=>"Méthode non autorisée"]);
    exit;
}

// 2) On récupère l’ID de la fiche
$fiche_id = isset($_GET["fiche_id"]) ? (int)$_GET["fiche_id"] : 0;
if ($fiche_id <= 0) {
    echo json_encode(["status"=>"error","message"=>"ID de fiche manquant ou invalide"]);
    exit;
}

try {
    // 3) On récupère les infos de la fiche
    $stmt = $pdo->prepare("
        SELECT 
          id,
          date_fiche,
          montant,
          statut,
          description,
          commentaire,
          justificatif
        FROM fiches_frais
        WHERE id = ?
    ");
    $stmt->execute([$fiche_id]);
    $fiche = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$fiche) {
        echo json_encode(["status"=>"error","message"=>"Fiche introuvable"]);
        exit;
    }

    // 4) On récupère les lignes forfaitisées
    $stmt2 = $pdo->prepare("
        SELECT 
          id, type_frais AS type, quantite, montant
        FROM ligne_frais_forfait
        WHERE fiche_frais_id = ?
    ");
    $stmt2->execute([$fiche_id]);
    $forfaits = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    // 5) On récupère les lignes hors-forfait
    $stmt3 = $pdo->prepare("
        SELECT 
          id, date AS date_ligne, libelle, montant
        FROM ligne_frais_hors_forfait
        WHERE fiche_frais_id = ?
    ");
    $stmt3->execute([$fiche_id]);
    $hors_forfaits = $stmt3->fetchAll(PDO::FETCH_ASSOC);

    // 6) On renvoie le tout en JSON
    echo json_encode([
        "status"        => "ok",
        "fiche"         => $fiche,
        "forfaits"      => $forfaits,
        "hors_forfaits" => $hors_forfaits
    ]);

} catch (Exception $e) {
    echo json_encode(["status"=>"error","message"=>"Erreur serveur : ".$e->getMessage()]);
}
?>
