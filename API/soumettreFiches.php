<?php

require_once "auth.php";
require_once "../config_local.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  echo json_encode(["status"=>"error","message"=>"Méthode non autorisée"]);
  exit;
}

$fiche_id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;
if ($fiche_id <= 0) {
  echo json_encode(["status"=>"error","message"=>"ID de fiche manquant ou invalide"]);
  exit;
}

try {
  $stmt = $pdo->prepare("
    UPDATE fiches_frais
    SET statut = 'en attente'
    WHERE id = ?
  ");
  $stmt->execute([$fiche_id]);
  echo json_encode(["status"=>"ok"]);
} catch (Exception $e) {
  echo json_encode(["status"=>"error","message"=>"Erreur serveur : ".$e->getMessage()]);
}
