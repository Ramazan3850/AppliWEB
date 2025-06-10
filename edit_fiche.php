<?php
session_start();
require_once "config.php";

// Vérifie que l’utilisateur est admin
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: connexionadmin.php");
    exit();
}

// Récupère l’ID de la fiche
$id_fiche = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Traitement de la mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $montant = $_POST['montant'];
    $date_fiche = $_POST['date_fiche'];
    $statut = $_POST['statut'];

    $sql = "UPDATE fiches_frais SET montant = :montant, date_fiche = :date_fiche, statut = :statut WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'montant' => $montant,
        'date_fiche' => $date_fiche,
        'statut' => $statut,
        'id' => $id_fiche
    ]);

    header("Location: dashboardadmin.php");
    exit();
}

// Récupère les infos actuelles de la fiche pour pré-remplir le formulaire
$sql = "SELECT * FROM fiches_frais WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id_fiche]);
$fiche = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fiche) {
    echo "Fiche non trouvée.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Éditer Fiche</title>
<style>
    body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f9; }
    form { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 400px; margin: auto; }
    label { display: block; margin-top: 10px; }
    input, select { width: 100%; padding: 8px; margin-top: 5px; }
    button { margin-top: 15px; padding: 10px; background: #007BFF; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
    button:hover { background: #0056b3; }
</style>
</head>
<body>

    <h1>Éditer Fiche</h1>
    <form action="" method="POST">
        <label>Montant :</label>
        <input type="number" name="montant" value="<?= htmlspecialchars($fiche['montant']) ?>" required>

        <label>Date :</label>
        <input type="date" name="date_fiche" value="<?= htmlspecialchars($fiche['date_fiche']) ?>" required>

        <label>Statut :</label>
        <select name="statut">
            <option value="en attente" <?= $fiche['statut'] === 'en attente' ? 'selected' : '' ?>>En attente</option>
            <option value="valide" <?= $fiche['statut'] === 'valide' ? 'selected' : '' ?>>Valide</option>
            <option value="refuse" <?= $fiche['statut'] === 'refuse' ? 'selected' : '' ?>>Refusé</option>
        </select>

        <button type="submit">Enregistrer</button>
    </form>

</body>
</html>
