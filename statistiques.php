<?php
session_start();
require_once "config.php";

// Vérifie que l’utilisateur est bien un comptable
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'comptable') {
    header("Location: connexioncomptable.php");
    exit();
}

// Récupère les statistiques des fiches
$sql = "
    SELECT statut, COUNT(*) as total
    FROM fiches_frais
    GROUP BY statut
";
$stmt = $pdo->query($sql);
$stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prépare les données pour le JS
$labels = [];
$totaux = [];
foreach ($stats as $row) {
    // Remplace 'brouillon' par 'en attente'
    $libelle = ($row['statut'] === 'brouillon') ? 'En attente' : ucfirst($row['statut']);
    $labels[] = $libelle;
    $totaux[] = $row['total'];
}

$labels_json = json_encode($labels);
$totaux_json = json_encode($totaux);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Statistiques des Fiches de Frais</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    body { font-family: Arial, sans-serif; background: #f4f4f9; text-align: center; padding: 20px; }
    h1 { color: #007bff; margin-bottom: 20px; }
    canvas { margin-top: 20px; }
    .retour { display: inline-block; margin-top: 20px; color: #007bff; text-decoration: none; }
    .retour:hover { text-decoration: underline; }

    #pieChart {
  display: block;
  margin: 20px auto;
  max-width: 350px; /* Taille maximale du camembert */
  max-height: 350px;
  width: 100%;
  height: auto;
}

</style>
</head>
<body>

<h1>Statistiques des Fiches de Frais</h1>

<canvas id="pieChart" width="350" height="350" style="max-width: 300px; max-height: 300px;"></canvas>

<a href="accueilcomptable.php" class="retour">← Retour à l'accueil comptable</a>

<script>
const ctx = document.getElementById('pieChart').getContext('2d');
const pieChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?= $labels_json ?>,
        datasets: [{
            data: <?= $totaux_json ?>,
            backgroundColor: ['#007BFF', '#28a745', '#dc3545', '#ffc107'],
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});
</script>

</body>
</html>
