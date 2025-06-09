<?php
session_start();
require_once "config.php";

// Vérifie que l’utilisateur est admin
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: connexionadmin.php");
    exit();
}

// Récupère la liste des utilisateurs
$sql = "SELECT id, nom, prenom, email, role FROM utilisateurs";
$stmt = $pdo->query($sql);
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Gestion des Utilisateurs</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #f4f4f9; text-align: center; }
    h1 { margin: 20px 0; }
    table { margin: auto; border-collapse: collapse; width: 90%; background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    th, td { border: 1px solid #ddd; padding: 10px; }
    th { background: #007BFF; color: #fff; }
    tr:nth-child(even) { background: #f9f9f9; }
    tr:hover { background: #f1f1f1; }
    a { text-decoration: none; padding: 6px 12px; background: #007BFF; color: #fff; border-radius: 4px; }
    a:hover { background: #0056b3; }
    .btn { margin: 5px; display: inline-block; }
    .retour { margin-top: 20px; display: inline-block; }
</style>
</head>
<body>

    <h1>Gestion des Utilisateurs</h1>

    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($utilisateurs as $u): ?>
        <tr>
            <td><?= htmlspecialchars($u['id']) ?></td>
            <td><?= htmlspecialchars($u['nom']) ?></td>
            <td><?= htmlspecialchars($u['prenom']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['role']) ?></td>
            <td>
                <a href="edit_utilisateur.php?id=<?= $u['id'] ?>" class="btn">Éditer</a>
                <a href="supprimer_utilisateur.php?id=<?= $u['id'] ?>" onclick="return confirm('Supprimer cet utilisateur ?');" class="btn">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="dashboardadmin.php" class="retour">← Retour au Dashboard</a>

</body>
</html>
