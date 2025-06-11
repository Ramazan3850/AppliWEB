<?php
include 'config.php';

$stmt = $pdo->query("SELECT fiches_frais.id, utilisateurs.nom, fiches_frais.date_fiche, fiches_frais.statut 
                     FROM fiches_frais
                     JOIN utilisateurs ON fiches_frais.utilisateur_id = utilisateurs.id 
                     WHERE fiches_frais.statut = 'soumis'");

$fiches = $stmt->fetchAll();
?>

<h2>Tableau de bord - Comptable</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Date</th>
        <th>Statut</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($fiches as $fiche): ?>
    <tr>
        <td><?= $fiche['id'] ?></td>
        <td><?= $fiche['nom'] ?></td>
        <td><?= $fiche['date_fiche'] ?></td>
        <td><?= $fiche['statut'] ?></td>
        <td>
            <a href="validerfichefrais.php?id=<?= $fiche['id'] ?>">Valider</a>
            <a href="refuserfichefrais.php?id=<?= $fiche['id'] ?>">Refuser</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
