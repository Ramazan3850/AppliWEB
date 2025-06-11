<?php

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $montant = htmlspecialchars($_POST['montant']);
    $date = htmlspecialchars($_POST['date']);
    $description = htmlspecialchars($_POST['description']);
    $valide_ou_refuse = htmlspecialchars($_POST['valide_ou_refuse']); 

    $sql = "INSERT INTO fiches_frais (montant, date, description, valide_ou_refuse)
            VALUES (:montant, :date, :description, :valide_ou_refuse)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':montant', $montant);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':valide_ou_refuse', $valide_ou_refuse);
    
    if ($stmt->execute()) {
        echo "Fiche de frais ajoutée avec succès !";
    } else {
        echo "Erreur lors de l'ajout.";
    }
}
