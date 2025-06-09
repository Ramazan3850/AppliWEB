<?php
session_start();
require_once("config.php");

// Vérifie que le visiteur est connecté
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'visiteur') {
    header("Location: connexionvisiteur.php");
    exit();
}

$id_visiteur = $_SESSION['id'];
$email = htmlspecialchars($_SESSION['email']);
$role = htmlspecialchars($_SESSION['role']);

// Récupère les fiches de ce visiteur
$sql = "SELECT * FROM fiches_frais WHERE utilisateur_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_visiteur]);
$fiches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Mes fiches de frais</title>
<style>
body {
  font-family: Arial, sans-serif;
  background-color: #f4f4f9;
  margin: 0;
  padding: 20px;
}

h1 {
  color: #007bff;
  text-align: center;
}

p {
  text-align: center;
}

table {
  width: 90%;
  margin: 20px auto;
  border-collapse: collapse;
  background: #fff;
}

th, td {
  border: 1px solid #ccc;
  padding: 8px;
  text-align: center;
}

th {
  background-color: #007bff;
  color: #fff;
}

tr:nth-child(even) {
  background-color: #f2f2f2;
}

a {
  color: #007bff;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}

.button {
  padding: 5px 10px;
  text-decoration: none;
  color: #fff;
  border-radius: 4px;
  margin: 0 3px;
}

.button.edit { background: #28a745; }
.button.delete { background: #dc3545; }
.button.submit { background: #17a2b8; }
.button.view { background: #6c757d; }

.button:hover {
  opacity: 0.9;
}
</style>
</head>
<body>
    <h1>Mes fiches de frais</h1>
    <p>Connecté en tant que <strong><?= $email ?></strong> (rôle : <strong><?= $role ?></strong>)</p>

    <?php if (count($fiches) > 0): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Montant</th>
            <th>Commentaire</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($fiches as $fiche): ?>
            <tr>
                <td><?= htmlspecialchars($fiche['id']) ?></td>
                <td><?= htmlspecialchars($fiche['date_fiche']) ?></td>
                <td><?= number_format((float) ($fiche['montant'] ?? 0), 2) ?> €</td>
                <td><?= htmlspecialchars($fiche['commentaire'] ?? '—') ?></td>
                <td>
                    <?php
                    $statut = $fiche['statut'] ?? '';
                    switch ($statut) {
                        case 'brouillon':
                            echo '<span style="color: gray;">Brouillon</span>';
                            break;
                        case 'soumise':
                            echo '<span style="color: orange;">En attente</span>';
                            break;
                        case 'validée':
                            echo '<span style="color: green;">Validée</span>';
                            break;
                        case 'refusée':
                            echo '<span style="color: red;">Refusée</span>';
                            break;
                        default:
                            echo htmlspecialchars($statut);
                    }
                    ?>
                </td>
                <td>
                    <a href="voir_fiche.php?id=<?= $fiche['id'] ?>" class="button view">Voir</a>
                    <?php if (($fiche['statut'] ?? '') === 'brouillon'): ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Aucune fiche de frais trouvée.</p>
<?php endif; ?>

<p><a href="fichefrais.php">Ajouter une nouvelle fiche</a></p>
<p><a href="accueilvisiteur.php">Retour à l'accueil</a> | <a href="logout.php">Déconnexion</a></p>
</body>
</html>
