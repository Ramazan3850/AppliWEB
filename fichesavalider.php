<?php
session_start();

// Inclut le bon fichier de config (local ou distant)
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    require_once 'config_local.php';
} else {
    require_once 'config.php';
}

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'comptable') {
    header("Location: connexioncomptable.php");
    exit();
}

// Si le comptable valide ou refuse une fiche
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'], $_POST['id'])) {
    $action = $_POST['action'];
    $id_fiche = (int)$_POST['id'];
    $commentaire = trim($_POST['commentaire']);

    if ($action === 'valider') {
        $sql = "UPDATE fiches_frais SET statut = 'valide', commentaire = :commentaire WHERE id = :id";
    } elseif ($action === 'refuser') {
        $sql = "UPDATE fiches_frais SET statut = 'refuse', commentaire = :commentaire WHERE id = :id";
    }

    if (isset($sql)) {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'commentaire' => $commentaire,
            'id' => $id_fiche
        ]);
    }

    header("Location: fichesavalider.php");
    exit();
}

// Récupère les fiches à valider
$sql = "
    SELECT f.id, f.date_fiche, f.montant, f.commentaire, f.statut, u.nom, u.prenom
    FROM fiches_frais f
    JOIN utilisateurs u ON f.utilisateur_id = u.id
    WHERE f.statut IN ('brouillon', 'en attente')
    ORDER BY f.date_fiche DESC
";
$stmt = $pdo->query($sql);
$fiches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Fiches à Valider</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #f4f4f9; text-align: center; }
    h1 { margin: 20px 0; }
    table { margin: auto; border-collapse: collapse; width: 90%; background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    th, td { border: 1px solid #ddd; padding: 10px; }
    th { background: #007BFF; color: #fff; }
    tr:nth-child(even) { background: #f9f9f9; }
    tr:hover { background: #f1f1f1; }
    form { display: inline-block; }
    input[type="text"] { width: 150px; padding: 6px; border: 1px solid #ccc; border-radius: 4px; }

    /* Boutons plus beaux */
    .btn {
        display: inline-block;
        text-decoration: none;
        padding: 8px 15px;
        margin: 2px;
        border-radius: 4px;
        color: #fff;
        font-weight: bold;
        transition: background-color 0.3s;
        border: none;
        cursor: pointer;
    }

    .btn-valider {
        background-color: #28a745;
    }

    .btn-valider:hover {
        background-color: #218838;
    }

    .btn-refuser {
        background-color: #dc3545;
    }

    .btn-refuser:hover {
        background-color: #c82333;
    }

    .btn-detail {
        background-color: #007BFF;
    }

    .btn-detail:hover {
        background-color: #0056b3;
    }

    .retour-accueil {
  display: inline-block;
  margin-top: 20px;
}

</style>
</head>
<body>

    <h1>Fiches de Frais à Valider</h1>

    <table>
        <tr>
            <th>ID</th>
            <th>Visiteur</th>
            <th>Commentaire</th>
            <th>Montant</th>
            <th>Date</th>
            <th>Statut</th>
            <th>Commentaire</th>
            <th>Voir</th>
        </tr>

        <?php if (empty($fiches)): ?>
            <tr><td colspan="8">Aucune fiche à valider pour le moment.</td></tr>
        <?php else: ?>
            <?php foreach ($fiches as $fiche): ?>
            <tr>
                <td><?= htmlspecialchars($fiche['id'] ?? '') ?></td>
                <td><?= htmlspecialchars($fiche['nom'] ?? '') ?> <?= htmlspecialchars($fiche['prenom'] ?? '') ?></td>
                <td><?= htmlspecialchars($fiche['commentaire'] ?? '') ?></td>
                <td><?= htmlspecialchars($fiche['montant'] ?? '') ?> €</td>
                <td><?= htmlspecialchars($fiche['date_fiche'] ?? '') ?></td>
                <td>
                    <?php
                    $statut = $fiche['statut'] ?? '';
                    if ($statut === 'brouillon') {
                        echo '<span style="color: gray;">En attente</span>';
                    } elseif ($statut === 'valide') {
                        echo '<span style="color: green; font-weight: bold;">Validée</span>';
                    } elseif ($statut === 'refuse') {
                        echo '<span style="color: red; font-weight: bold;">Refusée</span>';
                    } else {
                        echo htmlspecialchars($statut);
                    }
                    ?>
                </td>
                <td>
                    <form action="" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $fiche['id'] ?>">
                        <input type="text" name="commentaire" placeholder="Commentaire">
                        <button type="submit" name="action" value="valider" class="btn btn-valider">Valider</button>
                        <button type="submit" name="action" value="refuser" class="btn btn-refuser">Refuser</button>
                    </form>
                </td>
                <td>
                    <a href="voir_fiche.php?id=<?= $fiche['id'] ?>" class="btn btn-detail">Voir la fiche</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>

    <br>
    <a href="accueilcomptable.php" class="retour-accueil">← Retour à l'accueil</a>
</body>

</html>
