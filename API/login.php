<?php

require_once "auth.php";
require_once "../config_local.php";

// Vérifier que la requête est bien un POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Méthode non autorisée"]);
    exit;
}

// Récupérer les paramètres envoyés par l’application mobile
$login = $_POST["login"] ?? "";
$password = $_POST["mdp"] ?? "";

// Vérifier les champs
if (empty($login) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Champs manquants"]);
    exit;
}

try {
    // Préparer la requête pour vérifier les identifiants
    $stmt = $pdo->prepare("SELECT id, nom, prenom, role FROM utilisateurs WHERE email = ? AND password = ?");
    $stmt->execute([$login, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Générer un token aléatoire et le stocker
        $token = bin2hex(random_bytes(32));
        $upd = $pdo->prepare("UPDATE utilisateurs SET api_token = ? WHERE id = ?");
        $upd->execute([$token, $user['id']]);

        // Succès : renvoyer les informations de l’utilisateur + token
        echo json_encode([
            "status" => "ok",
            "token"  => $token,
            "user"   => [
                "id"     => $user["id"],
                "nom"    => $user["nom"],
                "prenom" => $user["prenom"],
                "role"   => $user["role"]
            ]
        ]);
    } else {
        // Échec : identifiants invalides
        echo json_encode(["status" => "error", "message" => "Identifiants invalides"]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Erreur serveur : " . $e->getMessage()]);
}
?>
