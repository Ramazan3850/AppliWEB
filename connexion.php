<?php
session_start();
require_once 'config.php'; // Connexion à la BDD (via le bon config)

// Vérifie si un formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prépare la requête pour chercher l'utilisateur
    $sql = "SELECT * FROM utilisateurs WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['email'] = $user['email'];
        $_SESSION['profil'] = $user['role']; // ex : admin, visiteur, rh

        // Redirige selon le rôle
        switch ($user['role']) {
            case 'visiteur':
                header('Location: accueilvisiteur.php');
                break;
            case 'admin':
                header('Location: accueiladmin.php');
                break;
            case 'rh':
            case 'comptable':
                header('Location: accueilcomptable.php');
                break;
            default:
                echo "Profil inconnu.";
        }
        exit;
    } else {
        $error = "Identifiants incorrects.";
    }
}
?>
