<?php
$host = 'localhost';
$dbname = 'gsb_database';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if (isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $montant = htmlspecialchars($_POST['montant']);
        $date = htmlspecialchars($_POST['date']);
        $description = htmlspecialchars($_POST['description']);
        $valide_ou_refuse = htmlspecialchars($_POST['valide_ou_refuse']);

        $sql = "UPDATE fiches_frais 
                SET montant = :montant, date = :date, description = :description, valide_ou_refuse = :valide_ou_refuse
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':montant', $montant);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':valide_ou_refuse', $valide_ou_refuse);

        if ($stmt->execute()) {
            echo "Fiche de frais mise à jour avec succès !";
            echo "<a href='afficherfichefrais.php'>Retour au tableau</a>";
        } else {
            echo "Erreur lors de la mise à jour.";
        }
    } else {
        $sql = "SELECT * FROM fiches_frais WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $fiche = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} else {
    die("ID de fiche non spécifié.");
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            background: white;
            margin: 40px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input, textarea, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            margin-top: 15px;
            background: #005cbf;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #005cbf;
        }
        .success {
            color: green;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        .button {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .button:hover {
            background: #0056b3;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Fiche de Frais</title>
</head>
<body>
    <h1>Modifier une fiche de frais</h1>
    <form method="POST">
        <label for="montant">Montant :</label>
        <input type="number" id="montant" name="montant" value="<?= $fiche['montant'] ?>" step="0.01" required>

        <label for="date">Date :</label>
        <input type="date" id="date" name="date" value="<?= $fiche['date'] ?>" required>

        <label for="description">Description :</label>
        <textarea id="description" name="description" rows="3"><?= $fiche['description'] ?></textarea>

        <label for="valide_ou_refuse">Statut :</label>
        <select name="valide_ou_refuse" required>
            <option value="Valide" <?= $fiche['valide_ou_refuse'] == 'Valide' ? 'selected' : '' ?>>Valide</option>
            <option value="Refuse" <?= $fiche['valide_ou_refuse'] == 'Refuse' ? 'selected' : '' ?>>Refuse</option>
        </select><br>

        <button type="submit">Mettre à jour</button>
    </form>
</body>
</html>