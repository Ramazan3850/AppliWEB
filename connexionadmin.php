<?php
session_start();
require_once "config.php"; // pour la connexion à la BDD

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Requête préparée pour récupérer l'utilisateur par email
    $sql = "SELECT id, email, motdepasse FROM administrateurs WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['motdepasse'])) {
        // Connexion réussie
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = 'admin';
        $_SESSION['id'] = $user['id'];
        header("Location: accueiladmin.php");
        exit;
    } else {
        $error_message = "Identifiants incorrects, veuillez réessayer.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
        }

        h1 {
            text-align: center;
            font-size: 2em;
            margin-bottom: 20px;
        }

        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        label, input, button {
            display: block;
            width: 100%;
            margin: 10px 0;
        }

        input, button {
            padding: 12px;
            font-size: 1em;
            border-radius: 4px;
        }

        input {
            border: 1px solid #ccc;
        }

        button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        p.error {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
     <form action="connexionadmin.php" method="POST">
        <h1>Connexion Administrateur</h1>
        <img src="gsblogo.png" alt="Image de connexion" class="login-image">

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Se connecter</button>

        <?php if (isset($error_message)): ?>
            <p style="color: red; font-weight: bold;"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
    </form>
</body>
</html>
