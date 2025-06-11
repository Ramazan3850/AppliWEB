<?php

require_once "auth.php";
require_once "../config_local.php";

// Afficher les erreurs en dev
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1) N’accepte que la méthode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status'=>'error','message'=>'Méthode non autorisée']);
    exit;
}

// 2) Récupère et valide les paramètres
$nom      = isset($_POST['nom'])      ? trim($_POST['nom'])      : '';
$prenom   = isset($_POST['prenom'])   ? trim($_POST['prenom'])   : '';
$email    = isset($_POST['email'])    ? trim($_POST['email'])    : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$role     = isset($_POST['role'])     ? trim($_POST['role'])     : '';

if ($nom === '' || $prenom === '' || $email === '' || $password === '' || $role === '') {
    echo json_encode(['status'=>'error','message'=>'Paramètres manquants ou invalides']);
    exit;
}

try {
    // 3) Insère le nouvel utilisateur
    $stmt = $pdo->prepare("
        INSERT INTO utilisateurs
            (nom, prenom, email, password, role)
        VALUES
            (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$nom, $prenom, $email, $password, $role]);
    
    // 4) Récupère l’ID généré
    $newId = $pdo->lastInsertId();
    
    // 5) Répondre au client
    echo json_encode([
        'status'   => 'ok',
        'user_id'  => $newId
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Erreur serveur : ' . $e->getMessage()
    ]);
}
