<?php
session_start();

// Inclut le bon fichier de config
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    require_once 'config_local.php';
} else {
    require_once 'config.php';
}

// Vérifie que c’est bien un admin connecté
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: connexionadmin.php");
    exit();
}

$sql = "
    SELECT f.id AS fiche_id, u.nom, u.prenom, u.email, f.montant, f.date_fiche, f.statut
    FROM fiches_frais f
    JOIN utilisateurs u ON f.utilisateur_id = u.id
    ORDER BY f.date_fiche DESC
";
$stmt = $pdo->query($sql);
$fiches = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupère quelques stats
$totalUtilisateurs = $pdo->query("SELECT COUNT(*) FROM utilisateurs")->fetchColumn();
$totalfiches_frais = $pdo->query("SELECT COUNT(*) FROM fiches_frais")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 20px;
        text-align: center;
    }

    h1 {
        color: #333;
        margin-bottom: 20px;
    }

    table {
        width: 90%;
        margin: auto;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    th, td {
        padding: 12px 15px;
        border: 1px solid #ddd;
    }

    th {
        background-color: #007BFF;
        color: #fff;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    a {
        text-decoration: none;
        padding: 6px 12px;
        background-color: #007BFF;
        color: #fff;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    a:hover {
        background-color: #0056b3;
    }

    a.retour-accueil {
    margin-top: 20px;
    display: inline-block;
}

</style>
</head>
<body>

    <h1>Dashboard Admin : Fiches de Frais par Utilisateur</h1>

    <table>
        <tr>
            <th>ID Fiche</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Montant</th>
            <th>Date</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($fiches as $fiche): ?>
        <tr>
            <td><?= htmlspecialchars($fiche['fiche_id']) ?></td>
            <td><?= htmlspecialchars($fiche['nom']) ?></td>
            <td><?= htmlspecialchars($fiche['prenom']) ?></td>
            <td><?= htmlspecialchars($fiche['email']) ?></td>
            <td><?= htmlspecialchars($fiche['montant']) ?> €</td>
            <td><?= htmlspecialchars($fiche['date_fiche']) ?></td>
            <td><?= htmlspecialchars($fiche['statut']) ?></td>
            <td>
                <a href="edit_fiche.php?id=<?= $fiche['fiche_id'] ?>">Éditer</a> |
                <a href="supprimer_fiche.php?id=<?= $fiche['fiche_id'] ?>" onclick="return confirm('Supprimer cette fiche ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="accueiladmin.php" class="retour-accueil">← Retour à l'accueil</a>
</body>
</html>
