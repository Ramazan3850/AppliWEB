<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=gsb_database", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupération sécurisée des données
    $date = isset($_POST["date_frais"]) ? $_POST["date_frais"] : null;
    $commentaire = isset($_POST["commentaire"]) ? $_POST["commentaire"] : "";

    $types = ["hebergement", "repas", "deplacement", "horsforfait", "train"];

    foreach ($types as $type) {
        $montant = isset($_POST["montant_$type"]) ? $_POST["montant_$type"] : null;
        $quantite = isset($_POST["quantite_$type"]) ? $_POST["quantite_$type"] : null;
        $justificatif = isset($_FILES["justificatif_$type"]) ? $_FILES["justificatif_$type"] : null;

        if ($justificatif && $justificatif["error"] == UPLOAD_ERR_OK) {
            $dossier = "justificatifs/";
            if (!file_exists($dossier)) {
                mkdir($dossier, 0777, true);
            }

            $nom_fichier = basename($justificatif["name"]);
            $chemin_fichier = $dossier . time() . "_" . $nom_fichier;

            move_uploaded_file($justificatif["tmp_name"], $chemin_fichier);

            $sql = "INSERT INTO fiches_frais (type_frais, montant, quantite, justificatif, date_frais, commentaire)
                    VALUES (:type, :montant, :quantite, :justificatif, :date, :commentaire)";

            $stmt = $pdo->prepare($sql); // ici on corrige : $bdd devient $pdo
            $stmt->execute([
                ':type' => $type,
                ':montant' => $montant,
                ':quantite' => $quantite,
                ':justificatif' => $chemin_fichier,
                ':date' => $date,
                ':commentaire' => $commentaire
            ]);
        }
    }

    echo "<p>✅ Fiche de frais ajoutée avec succès !</p>";
    echo '<a href="accueilvisiteur.php">⬅️ Retour à l\'accueil</a>';
} else {
    echo "<p>Erreur : méthode non autorisée.</p>";
}
?>

