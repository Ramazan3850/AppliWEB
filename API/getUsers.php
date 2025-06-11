<?php

require_once "auth.php";
require_once "../config_local.php";

// 1) N’accepte que la méthode GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée']);
    exit;
}

try {
    // 2) Récupérer tous les utilisateurs
    $stmt = $pdo->query("
        SELECT id, nom, prenom, email, role
        FROM utilisateurs
        ORDER BY nom, prenom
    ");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3) Répondre en JSON
    echo json_encode([
        'status' => 'ok',
        'users'  => $users
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Erreur serveur : ' . $e->getMessage()
    ]);
}
