<?php
session_start();
require_once "config.php";

// Vérifie que l’utilisateur est connecté (peu importe le rôle)
if (!isset($_SESSION['id'])) {
    header("Location: connexionvisiteur.php");
    exit();
}

// Récupère l’ID de la fiche
$id_fiche = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Requête sans restriction
$sql = "SELECT f.*, u.nom, u.prenom
        FROM fiches_frais f
        JOIN utilisateurs u ON f.utilisateur_id = u.id
        WHERE f.id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id_fiche]);
$fiche = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fiche) {
    echo "Fiche introuvable.";
    exit();
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
<p><strong>Commentaire :</strong> <?= htmlspecialchars($fiche['commentaire'] ?? '—') ?></p>
<p><strong>Statut :</strong>
<?php
$statut = $fiche['statut'] ?? '';
switch ($statut) {
    case 'brouillon': echo '<span style="color:gray;">Brouillon</span>'; break;
    case 'soumise': echo '<span style="color:orange;">En attente</span>'; break;
    case 'validée': echo '<span style="color:green;">Validée</span>'; break;
    case 'refusée': echo '<span style="color:red;">Refusée</span>'; break;
    default: echo 'Non renseigné';
}
?>
</p>

<a href="afficherfichefrais.php" class="retour">← Retour aux fiches</a>
<a href="fichesavalider.php" class="retour">← Retour à la page d'avant</a>
</body>
</html>
