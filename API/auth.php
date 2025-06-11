<?php
// /API/auth.php
header("Content-Type: application/json");
// Inclut la config de la BDD
require_once "../config_local.php";

// 1) Récupérer le header Authorization
$hdr = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
if (!preg_match('/Bearer\s+(\S+)/', $hdr, $m)) {
    http_response_code(401);
    echo json_encode(['status'=>'error','message'=>'Token manquant']);
    exit;
}
$token = $m[1];

// 2) Vérifier le token en base
$stmt = $pdo->prepare("SELECT id, role FROM utilisateurs WHERE api_token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(401);
    echo json_encode(['status'=>'error','message'=>'Token invalide']);
    exit;
}

// 3) Exposer les infos utilisateur pour le reste du script
//    tu peux par exemple définir une variable globale :
$GLOBALS['currentUser'] = $user;
