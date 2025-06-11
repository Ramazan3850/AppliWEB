<?php
session_start();
require_once("config.php"); 

$sql = 'SELECT * FROM utilisateurs WHERE email = ?';
$result = $pdo->prepare($sql); 
$result->execute([$_POST['email']]); 
$users = $result->fetchall(PDO::FETCH_ASSOC);

var_dump($users); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        foreach ($users as $user) {
            if ($user['email'] === $email && password_verify($password, $user['password'])) {
                var_dump($user);
                $_SESSION['logged_in'] = true;
                $_SESSION['email'] = $email;
                $_SESSION['id'] = $user["id"]; 
                $_SESSION['nom'] = $user["nom"]." ".$user["prenom"]; 
                $_SESSION['role'] = $user["role"]; 
                echo "connexion ok"; 
                header("Location: accueil.php");
                exit;
            }
        }
    }
}
if ($user && hash('$2y$10$HOCcB8.FIphDfBEki8pklOfYWIYWGYPpzHnx53wKAzkI4EyMrPkn6', $password) === $user['admin123']) {

    $_SESSION['utilisateur'] = [
        'id' => $user['id'],
        'nom' => $user['nom'],
        'role' => $user['role']
    ];

    if ($user['role'] === 'admin') {
        header("Location: accueil.php");
        exit();
    } elseif ($user['role'] === 'visiteur') {
        header("Location: accueil.php");
        exit();
    } elseif ($user['role'] === 'comptable') {
        header("Location: accueil.php");
        exit();
    } else {
        echo "Rôle inconnu.";
    }
} else {
    echo "Email ou mot de passe incorrect.";
}
