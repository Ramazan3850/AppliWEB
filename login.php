<?php
session_start();
require_once("config.php");

// Vérifie si une requête POST a été envoyée
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize les données envoyées par le formulaire
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Vérifie que les champs ne sont pas vides
    if (!empty($email) && !empty($password)) {
        // Requête pour récupérer l'utilisateur avec cet email
        $sql = 'SELECT * FROM utilisateurs WHERE email = ?';
        $stmt = $pdo->prepare($sql); 
        $stmt->execute([$email]); 
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Vérifie si l'utilisateur existe et que le mot de passe est correct
        foreach ($users as $user) {
            if (password_verify($password, $user['password'])) {
                // Connexion réussie
                $_SESSION['logged_in'] = true;
                $_SESSION['email'] = $email;
                $_SESSION['id'] = $user["id"]; 
                $_SESSION['nom'] = $user["nom"]." ".$user["prenom"]; 
                $_SESSION['role'] = $user["role"]; 

                // Redirige en fonction du rôle
                if ($user['role'] === 'visiteur') {
                    header("Location: connexionvisiteur.php");
                } elseif ($user['role'] === 'admin') {
                    header("Location: connexionadmin.php");
                } elseif ($user['role'] === 'comptable') {
                    header("Location: connexioncomptable.php");
                } else {
                    // Rôle inconnu, sécurité renforcée
                    header("Location: index.html?error=role");
                }
                exit;
            }
        }
    }
    // Si l'authentification a échoué
    header("Location: connexion.php?error=1");
    exit;
}
?>
