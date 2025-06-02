<?php
$pdo = new PDO('mysql:host=localhost;dbname=gsb_database', 'root', '');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM fiches_frais WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $fiche = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$fiche) {
        echo "Fiche introuvable.";
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $montant = $_POST['montant'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $etat = $_POST['etat'];
    $commentaire = isset($_POST['commentaire']) ? $_POST['commentaire'] : '';

    $sql = "UPDATE fiches_frais 
            SET montant = :montant, date = :date, description = :description, 
                etat = :etat, commentaire = :commentaire 
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':montant', $montant);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':etat', $etat);
    $stmt->bindParam(':commentaire', $commentaire);

    if ($stmt->execute()) {
        echo "<div class='success'>Fiche mise à jour avec succès.</div>";
        $stmt = $pdo->prepare("SELECT * FROM fiches_frais WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $fiche = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "<div class='error'>Erreur lors de la mise à jour.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Fiche Frais</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        input[type="submit"] {
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            color: #721c24;
        }
    </style>
</head>
<body>
    <h1>Modifier la fiche de frais</h1>

    <form method="POST">
        <label for="montant">Montant :</label>
        <input type="text" id="montant" name="montant" value="<?= htmlspecialchars($fiche['montant']) ?>">

        <label for="date">Date :</label>
        <input type="date" id="date" name="date" value="<?= htmlspecialchars($fiche['date']) ?>">

        <label for="description">Description :</label>
        <input type="text" id="description" name="description" value="<?= htmlspecialchars($fiche['description']) ?>">

        <label for="etat">Etat :</label>
        <select id="etat" name="etat">
            <option value="valide" <?= $fiche['etat'] === 'valide' ? 'selected' : '' ?>>Validé</option>
            <option value="en attente" <?= $fiche['etat'] === 'valide' ? 'selected' : '' ?>>En attente</option>
            <option value="refuse" <?= $fiche['etat'] === 'refuse' ? 'selected' : '' ?>>Refusé</option>
        </select>

        <label for="commentaire">Commentaire :</label>
        <textarea id="commentaire" name="commentaire" rows="4"><?= isset($fiche['commentaire']) ? htmlspecialchars($fiche['commentaire']) : '' ?></textarea>

        <input type="submit" value="Enregistrer les modifications">
    </form>
</body>
</html>
