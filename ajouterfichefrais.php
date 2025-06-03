<?php
session_start();
require_once("config.php");

// Vérifie la connexion
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'visiteur') {
    header("Location: connexionvisiteur.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_visiteur = $_SESSION['id'];
    $date_fiche = $_POST['date'];
    $commentaire = htmlspecialchars($_POST['commentaire'] ?? '');
    $statut = "brouillon";

    // Calcul du montant total
    $montant_total = 0;
    $types_frais = ['hebergement', 'repas', 'deplacement', 'horsfrais', 'train'];

    foreach ($types_frais as $type) {
        $montant = isset($_POST["montant_$type"]) && $_POST["montant_$type"] !== '' ? (float) $_POST["montant_$type"] : 0;
        $quantite = isset($_POST["quantite_$type"]) && $_POST["quantite_$type"] !== '' ? (int) $_POST["quantite_$type"] : 0;
        if ($montant > 0 && $quantite > 0) {
            $montant_total += $montant * $quantite;
        }
    }

    f ($montant_total > 0) {
    // Insertion dans la table fiches_frais
    $sql = "INSERT INTO fiches_frais (date_fiche, montant, commentaire, statut, utilisateur_id)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$date_fiche, $montant_total, $commentaire, $statut, $id_visiteur]);

    header("Location: afficherfichefrais.php");
    exit;
} else {
    echo "Erreur : Vous devez remplir au moins un type de frais.";
    exit;
}
}
?>
