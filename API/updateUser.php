<?php

require_once "auth.php";
require_once "../config_local.php";

// 1) On n’accepte que la méthode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status'=>'error','message'=>'Méthode non autorisée']);
    exit;
}

// 2) On récupère et on valide l’ID de l’utilisateur
$user_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($user_id <= 0) {
    echo json_encode(['status'=>'error','message'=>'ID utilisateur manquant ou invalide']);
    exit;
}

// 3) On récupère les autres champs (on peut choisir de les rendre tous obligatoires)
$nom      = isset($_POST['nom'])      ? trim($_POST['nom'])      : '';
$prenom   = isset($_POST['prenom'])   ? trim($_POST['prenom'])   : '';
$email    = isset($_POST['email'])    ? trim($_POST['email'])    : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$role     = isset($_POST['role'])     ? trim($_POST['role'])     : '';

if ($nom==='' || $prenom==='' || $email==='' || $password==='' || $role==='') {
    echo json_encode(['status'=>'error','message'=>'Paramètres manquants ou invalides']);
    exit;
}

try {
    // 4) Préparer et exécuter la mise à jour
    $stmt = $pdo->prepare("
        UPDATE utilisateurs
        SET nom      = ?,
            prenom   = ?,
            email    = ?,
            password = ?,
            role     = ?
        WHERE id = ?
    ");
    $stmt->execute([$nom, $prenom, $email, $password, $role, $user_id]);

    // 5) Réponse JSON
    echo json_encode(['status'=>'ok']);
} catch (Exception $e) {
    echo json_encode([
        'status'=>'error',
        'message'=>'Erreur serveur : '.$e->getMessage()
    ]);
}
