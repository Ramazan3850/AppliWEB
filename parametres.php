<?php
session_start();
require_once "config.php";

// Vérifie que l’utilisateur est admin
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: connexionadmin.php");
    exit();
}

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST as $cle => $valeur) {
        $sql = "UPDATE parametres SET valeur = :valeur WHERE cle = :cle";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'valeur' => $valeur,
            'cle' => $cle
        ]);
    }

    header("Location: parametres.php");
    exit();
}

// Récupère les paramètres existants
$sql = "SELECT * FROM parametres";
$stmt = $pdo->query($sql);
$parametres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Paramètres</title>
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    text-align: center;
    margin: 0;
    padding: 20px;
}

h1 {
    margin-bottom: 20px;
    color: #333;
}

form {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    width: 400px;
    margin: auto;
}

label {
    display: block;
    margin-top: 10px;
    text-align: left;
    font-weight: bold;
}

input {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    margin-top: 15px;
    padding: 10px;
    background: #007BFF;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
}

button:hover {
    background: #0056b3;
}

.retour {
    margin-top: 20px;
    display: inline-block;
    text-decoration: none;
    background: #007BFF;
    color: #fff;
    padding: 10px 15px;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.retour:hover {
    background: #0056b3;
}

</style>
</head>
<body>

    <h1>Paramètres</h1>

    <form method="POST">
        <?php foreach ($parametres as $p): ?>
            <label><?= htmlspecialchars($p['cle']) ?> :</label>
            <input type="text" name="<?= htmlspecialchars($p['cle']) ?>" value="<?= htmlspecialchars($p['valeur']) ?>">
        <?php endforeach; ?>
        <button type="submit">Enregistrer</button>
    </form>

    <a href="accueiladmin.php" class="retour">← Retour au Dashboard</a>

</body>
</html>
