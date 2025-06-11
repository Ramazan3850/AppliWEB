<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - GSB</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            background-color: #005cbf;
            color: white;
            text-align: center;
            padding: 20px;
            margin: 0;
        }

        p {
            font-size: 18px;
            line-height: 1.6;
            margin: 15px 0;
            text-align: center;
        }

        a {
            display: inline-block;
            background-color: #ff5733;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
            margin: 20px auto;
        }

        a:hover {
            background-color: #e94e26;
        }

        nav {
            background-color: #ffffff;
            padding: 10px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        nav ul li {
            display: inline;
        }

        nav ul li a {
            text-decoration: none;
            color: #005cbf;
            font-size: 18px;
            padding: 10px 15px;
            border-radius: 5px;
            background-color: #f1f1f1;
            transition: background-color 0.3s ease;
        }

        nav ul li a:hover {
            background-color: #005cbf;
            color: white;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

    </style>
</head>
<body>
<?php
$hour = date('H');
if ($hour < 12) {
    $greeting = 'Bonjour';
} elseif ($hour < 18) {
    $greeting = 'Bon après-midi';
} else {
    $greeting = 'Bonsoir';
}
?>

<nav>
    <ul>
        <li><a href="Documentation Technique gsb - Copie.pdf">Tableau de bord</a></li>
        <li><a href="CV-parcours.pdf">Mon profil</a></li>
        <li><a href="settings.php">Paramètres</a></li>
        <li><a href="afficherfichefrais.php">Mes fiches de frais</a></li>
        <li><a href="fichefrais.html">Ajouter une fiche de frais</a></li>
    </ul>
</nav>

    <div class="container">
        <h1>Bienvenue sur l'application GSB</h1>
        <p><?php echo $greeting; ?>, <?php echo htmlspecialchars($_SESSION['nom']); ?> !</p>
        <p>Votre rôle : <?php echo htmlspecialchars($_SESSION['role']); ?></p>
        <a href="logout.php">Déconnexion</a>
    </div>
</body>
</html>
