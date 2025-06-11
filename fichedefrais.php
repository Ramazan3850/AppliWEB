<?php
$host = 'localhost';
$dbname = 'gsb_database';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $montant = floatval($_POST['montant']);
    $quantite = intval($_POST['quantite']);
    $date = htmlspecialchars($_POST['date']);
    $commentaire = htmlspecialchars($_POST['commentaire']);
    $etat = htmlspecialchars($_POST['etat']);

    if ($montant <= 0 || $quantite <= 0) {
        echo "❌ Montant ou quantité invalide.";
        exit;
    }

    $sql = "INSERT INTO fiches_frais (montant, quantite, date_frais, commentaire, etat)
            VALUES (:montant, :quantite, :date_frais, :commentaire, :etat)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':montant', $montant);
    $stmt->bindParam(':quantite', $quantite);
    $stmt->bindParam(':date_frais', $date);
    $stmt->bindParam(':commentaire', $commentaire);
    $stmt->bindParam(':etat', $etat);

    if ($stmt->execute()) {
        echo "✅ Fiche de frais ajoutée avec succès !";
    } else {
        echo "❌ Une erreur est survenue lors de l'ajout.";
    }
}
?>
