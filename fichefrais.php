<?php
session_start();
require_once("config.php");

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'visiteur') {
    header("Location: connexionvisiteur.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_visiteur = $_SESSION['id'];
    $date_fiche = $_POST['date'];
    $commentaire = htmlspecialchars($_POST['commentaire'] ?? '');
    $statut = "brouillon";

    // Calcul du montant total
    $montant_total = 0;
    $types_frais = ['hebergement', 'repas', 'deplacement', 'horsfrais', 'train'];

    foreach ($types_frais as $type) {
        $montant = isset($_POST["montant_$type"]) && $_POST["montant_$type"] !== '' ? (float) $_POST["montant_$type"] : 0;
        $quantite = isset($_POST["quantite_$type"]) && $_POST["quantite_$type"] !== '' ? (int) $_POST["quantite_$type"] : 0;
        if ($montant > 0 && $quantite > 0) {
            $montant_total += $montant * $quantite;
        }
    }

    // Vérifie qu'il y a bien au moins un montant > 0
    if ($montant_total > 0) {
        // Insertion dans la table fiches_frais
        $sql = "INSERT INTO fiches_frais (date_fiche, montant, commentaire, statut, utilisateur_id)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$date_fiche, $montant_total, $commentaire, $statut, $id_visiteur]);

        header("Location: afficherfichefrais.php");
        exit;
    } else {
        echo "<p style='color: red; text-align: center;'>Erreur : Vous devez remplir au moins un type de frais avec un montant et une quantité valides.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ajouter Fiche de Frais</title>
<style>
body { font-family: Arial, sans-serif; background-color: #f0f4f8; margin: 0; padding: 0; color: #333; }
.container { max-width: 900px; margin: 50px auto; background: #fff; padding: 30px; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
h1 { text-align: center; color: #007BFF; margin-bottom: 30px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
th, td { padding: 15px; border-bottom: 1px solid #ddd; text-align: center; }
th { background-color: #007BFF; color: white; border-radius: 10px 10px 0 0; }
input[type="number"], input[type="file"], input[type="date"], textarea { width: 90%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; }
textarea { resize: vertical; height: 80px; }
.form-group { margin-bottom: 20px; }
label { display: block; margin-bottom: 8px; font-weight: bold; }
button { display: block; width: 100%; padding: 15px; background-color: #007BFF; color: white; font-size: 16px; border: none; border-radius: 8px; cursor: pointer; transition: background-color 0.3s ease; }
button:hover { background-color: #0056b3; }
.retour-accueil { display: inline-block; margin-bottom: 20px; text-decoration: none; background-color: #4CAF50; color: white; padding: 8px 16px; border-radius: 8px; transition: background-color 0.3s; }
.retour-accueil:hover { background-color: #45a049; }
@media (max-width: 768px) {
    table, thead, tbody, th, td, tr { display: block; }
    th { display: none; }
    td { margin-bottom: 15px; text-align: left; }
    td::before { content: attr(data-label); font-weight: bold; display: block; margin-bottom: 5px; }
}
</style>
</head>
<body>
<div class="container">
    <h1>Ajouter une fiche de frais</h1>
    <form method="POST" action="" enctype="multipart/form-data">
        <a href="accueilvisiteur.php" class="retour-accueil">← Retour à l'accueil</a>
        <table>
            <thead>
                <tr>
                    <th>Type de Frais</th>
                    <th>Montant (€)</th>
                    <th>Quantité</th>
                    <th>Justificatif</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (['hebergement', 'repas', 'deplacement', 'horsfrais', 'train'] as $type): ?>
                <tr>
                    <td><?= ucfirst($type) ?></td>
                    <td><input type="number" name="montant_<?= $type ?>" step="0.01" min="0"></td>
                    <td><input type="number" name="quantite_<?= $type ?>" min="0"></td>
                    <td><input type="file" name="justificatif_<?= $type ?>" accept="image/*,.pdf"></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="form-group">
            <label for="commentaire">Commentaire :</label>
            <textarea name="commentaire" id="commentaire"></textarea>
        </div>

        <div class="form-group">
            <label for="date">Date :</label>
            <input type="date" name="date" id="date" required>
        </div>

        <button type="submit">Envoyer la fiche de frais</button>
    </form>
</div>
</body>
</html>
