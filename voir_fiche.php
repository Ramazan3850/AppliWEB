<?php
session_start();

// Inclut le bon fichier de config
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    require_once 'config_local.php';
} else {
    require_once 'config.php';
}

// Vérifie que l’utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: connexionvisiteur.php");
    exit;
}

// Récupère l’ID de la fiche
$id_fiche = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Requête pour la fiche
$sql = "SELECT f.*, u.nom, u.prenom
        FROM fiches_frais f
        JOIN utilisateurs u ON f.utilisateur_id = u.id
        WHERE f.id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id_fiche]);
$fiche = $stmt->fetch(PDO::FETCH_ASSOC);

// Requête pour les détails
$sql_details = "SELECT type_frais, quantite, montant FROM ligne_frais_forfait WHERE fiche_frais_id = :id";
$stmt_details = $pdo->prepare($sql_details);
$stmt_details->execute(['id' => $id_fiche]);
$details = $stmt_details->fetchAll(PDO::FETCH_ASSOC);

if (!$fiche) {
    echo "Fiche introuvable.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Détails de la fiche</title>
<style>
body { font-family: Arial, sans-serif; background: #f4f4f9; padding: 20px; }
h1 { color: #007bff; }
p { margin: 10px 0; }
.retour { display: inline-block; margin-top: 20px; color: #007bff; text-decoration: none; }
.retour:hover { text-decoration: underline; }
</style>
</head>
<body>
<h1>Détails de la fiche #<?= htmlspecialchars($fiche['id']) ?></h1>
<p><strong>Date :</strong> <?= htmlspecialchars($fiche['date_fiche']) ?></p>
<p><strong>Montant :</strong> <?= number_format((float) $fiche['montant'], 2) ?> €</p>
<p><strong>Commentaire :</strong> <?= htmlspecialchars(isset($fiche['commentaire']) ? $fiche['commentaire'] : '—') ?></p>
<p><strong>Statut :</strong>
<?php
$statut = isset($fiche['statut']) ? $fiche['statut'] : '';
switch ($statut) {
    case 'brouillon':
    case 'en attente':
    case 'soumise':
        echo '<span style="color:gray;">En attente</span>';
        break;
    case 'valide':
        echo '<span style="color:green; font-weight:bold;">Validée</span>';
        break;
    case 'refuse':
        echo '<span style="color:red; font-weight:bold;">Refusée</span>';
        break;
    default:
        echo 'Non renseigné';
}
?>
</p>

<h2>Détails des Frais (Types de frais disponibles)</h2>
<ul>
    <?php foreach ($details as $detail): ?>
        <li><strong><?= htmlspecialchars($detail['type_frais']) ?> :</strong> <?= number_format((float)$detail['montant'], 2) ?> € (Quantité : <?= htmlspecialchars($detail['quantite']) ?>)</li>
    <?php endforeach; ?>
</ul>

<a href="afficherfichefrais.php" class="retour">← Retour pour Visiteur</a>
<a href="fichesavalider.php" class="retour">← Retour pour Comptable</a>
</body>
</html>
