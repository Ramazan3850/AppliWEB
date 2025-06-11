<?php
include 'config.php';

$utilisateur_id = $_SESSION['utilisateur_id'];
$stmt = $pdo->prepare("SELECT * FROM fiches_frais WHERE utilisateur_id = ?");
$stmt->execute([$utilisateur_id]);

$fiches = $stmt->fetchAll();
?>

<h2>Mes Fiches de Frais</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Statut</th>
    </tr>
    <?php foreach ($fiches as $fiche): ?>
    <tr>
        <td><?= $fiche['id'] ?></td>
        <td><?= $fiche['date_fiche'] ?></td>
        <td><?= $fiche['statut'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
