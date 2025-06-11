<?php

require_once "auth.php";
require_once "../config_local.php";

// DEBUG → affiche en JSON brut tout ce que reçoit PHP
echo json_encode([
  "reçu_raw" => $_POST
]);
exit;

// ini_set('display_errors', 1);
error_reporting(E_ALL);

// DEBUG
error_log("⚙️ addFiche POST payload → " . print_r($_POST, true));

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status"=>"error","message"=>"Méthode non autorisée"]);
    exit;
}

$user_id = $GLOBALS['currentUser']['id'];
$date_fiche  = isset($_POST["date_fiche"])  ? $_POST["date_fiche"]        : "";
$commentaire = isset($_POST["commentaire"])? $_POST["commentaire"]      : "";

// DEBUG
error_log("⚙️ Parsed → user_id={$user_id}, date_fiche={$date_fiche}, commentaire={$commentaire}");

if ($user_id || empty($date_fiche)) {
    echo json_encode(["status"=>"error","message"=>"Paramètres manquants ou invalides"]);
    exit;
}

try {
  $stmt = $pdo->prepare("
    INSERT INTO fiches_frais
      (utilisateur_id, date_fiche, montant, statut, commentaire)
    VALUES
      (?, ?, 0, 'brouillon', ?)
  ");
  $stmt->execute([$user_id,$date_fiche,$commentaire]);
  echo json_encode(["status"=>"ok","id_fiche"=>$pdo->lastInsertId()]);
} catch(Exception $e){
  echo json_encode(["status"=>"error","message"=>"Erreur serveur: ".$e->getMessage()]);
}
?>