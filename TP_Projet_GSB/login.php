<?php
session_start();
require_once("config.php"); 

$sql = 'SELECT * FROM utilisateurs WHERE email = ?';
$result = $pdo->prepare($sql); 
$result->execute([$_POST['email']]); 
$users = $result->fetchall(PDO::FETCH_ASSOC);

var_dump($users); 


// $users = [
//     ["email" => "ramazanilkhan38@gmail.com", "password" => password_hash("kitdesoin", PASSWORD_DEFAULT)],
//     ["email" => "user2@example.com", "password" => password_hash("password2", PASSWORD_DEFAULT)]
// ];

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
    echo "error"; 
    //header("Location: login.html?error=1");
    exit;
}
?>
