<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: connexion.php"); 
    $role = isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role']) : 'non défini';
    exit;
}

$hour = date('H');
if ($hour < 12) {
    $greeting = 'Bonjour';
} elseif ($hour < 18) {
    $greeting = 'Bon après-midi';
} else {
    $greeting = 'Bonsoir';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil Visiteur - GSB</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f4f8;
            color: #333;
        }

        nav {
            background-color: #005cbf;
            padding: 20px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            margin: 0;
            padding: 0;
            gap: 30px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 18px;
            padding: 12px 20px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        nav ul li a:hover {
            background-color: #003f8a;
        }

        .container {
            max-width: 1000px;
            margin: 60px auto;
            padding: 50px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
        }

        h1 {
            color: #005cbf;
            margin-bottom: 20px;
            font-size: 36px;
        }

        p {
            font-size: 20px;
            margin: 20px 0;
        }

        .btn-logout {
            display: inline-block;
            background-color: #ff5733;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 8px;
            margin-top: 30px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn-logout:hover {
            background-color: #e14c25;
        }
    </style>
</head>
<body>

<nav>
    <ul>
        <li><a href="www.ramazan-ilkhan.fr">Mon profil</a></li>
        <li><a href="afficherfichefrais.php">Mes fiches de frais</a></li>
        <li><a href="fichefrais.html">Ajouter une fiche de frais</a></li>
    </ul>
</nav>

<div class="container">
    <h1>Bienvenue sur l'application GSB</h1>
    <p><?= $greeting ?>, <strong><?= htmlspecialchars($_SESSION['email']) ?></strong> !</p>
    <p>Votre rôle : <?php echo htmlspecialchars($_SESSION['role']); ?></p>
    <a class="btn-logout" href="logout.php">Déconnexion</a>
</div>

</body>
</html>
