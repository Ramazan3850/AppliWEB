<?php
session_start();

$comptes = [
    'visiteur@example.com' => ['password' => 'visiteur123', 'profil' => 'visiteur'],
    'admin@example.com' => ['password' => 'admin123', 'profil' => 'admin'],
    'rh@example.com' => ['password' => 'rh123', 'profil' => 'rh']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mdp = $_POST['password'];

    if (isset($comptes[$email]) && $comptes[$email]['password'] === $mdp) {
        $_SESSION['email'] = $email;
        $_SESSION['profil'] = $comptes[$email]['profil'];

        switch ($_SESSION['profil']) {
            case 'visiteur':
                header('Location: accueilvisiteur.php');
                break;
            case 'admin':
                header('Location: accueiladmin.php');
                break;
            case 'rh':
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
