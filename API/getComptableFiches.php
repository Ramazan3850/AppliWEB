<?php

require_once "auth.php";
require_once "../config_local.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
  echo json_encode(["status"=>"error","message"=>"Méthode non autorisée"]);
  exit;
}

try {
  $stmt = $pdo->prepare("
    SELECT f.id, f.date_fiche, f.montant,
           u.nom AS visiteur_nom, u.prenom AS visiteur_prenom
    FROM fiches_frais f
    JOIN utilisateurs u ON f.utilisateur_id = u.id
    WHERE f.statut = 'en attente'
    ORDER BY f.date_fiche DESC
  ");
  $stmt->execute();
  $fiches = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode(["status"=>"ok","fiches"=>$fiches]);
} catch (Exception $e) {
  echo json_encode(["status"=>"error","message"=>"Erreur serveur : ".$e->getMessage()]);
}
