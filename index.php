<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Accueil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }

        header {
            background-color: #007BFF;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .menu {
            display: flex;
            justify-content: space-evenly;
            gap: 10px;
            margin-top: 50px;
            padding: 20px;
        }

        .menu a {
            margin: 0 20px;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .menu a:hover {
            background-color: #0056b3;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #fffcfc;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .profile-card {
            width: 250px;
            height: 300px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-10px);
        }

        .profile-card h2 {
            margin-bottom: 10px;
            font-size: 20px;
            color: #0056b3;
        }

        .profile-card p {
            color: #555;
            font-size: 16px;
            font-weight: 400;
            line-height: 1.6; 
            font-family: 'Roboto', sans-serif; 
            text-align: center; 
            margin-top: 10px;
        }

        .profile-card a {
            text-decoration: none;
            color: inherit;
        }

        .profile-card img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        .footer-image {
            position: fixed;
            bottom: 0px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
}

        .footer-image img {
            width: 155px;
            height: auto;
}


    </style>
</head>
<body>

<header>
    <h1>Bienvenue sur le site GSB</h1>
    <h2>Connectez vous</h2>
</header>

<div class="footer-image">
    <img src="gsblogo.png" alt="GSB" />
</div>

<div class="menu">
    <a href="connexionvisiteur.php">
        <div class="profile-card">
            <h2>Profil Visiteur</h2>
            <p>Accédez aux fonctionnalités des visiteurs pour gérer vos données.</p>
        </div>
    </a>
    <a href="connexionadmin.php">
        <div class="profile-card">
            <h2>Profil Administrateur</h2>
            <p>Accédez aux fonctionnalités des administrateurs pour gérer l'application.</p>
        </div>
    </a>
    <a href="connexioncomptable.php">
        <div class="profile-card">
            <h2>Profil Comptable</h2>
            <p>Accédez aux fonctionnalités des comptables pour consulter les rapports et pour gérer vos fiches de frais.</p>
        </div>
    </a>
</div>

</body>
</html>
