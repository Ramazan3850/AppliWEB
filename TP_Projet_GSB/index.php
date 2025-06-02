
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        h1 {
            text-align: center;
        }
        form {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        label, input, button {
            display: block;
            width: 100%;
            margin: 10px 0;
        }
        input, button {
            padding: 8px;
            font-size: 1em;
        }
        button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        button:hover {
            background-color: #0056b3;
        }
        p.error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <form action="login.php" method="POST">
        <h1>Connexion</h1>
        <img src="gsblogo.png" alt="Image de connexion" class="login-image">

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Se connecter</button>
        

        <?php if (isset($_GET['error'])): ?>
            <p class="error">Identifiants incorrects.</p>
        <?php endif; ?>
    </form>
</body>
</html>
