<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: connexionadmin.php");
    exit();
}

$id_utilisateur = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $sql = "UPDATE utilisateurs SET nom = :nom, prenom = :prenom, email = :email, role = :role WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'role' => $role,
        'id' => $id_utilisateur
    ]);

    header("Location: gestionutilisateurs.php");
    exit();
}

// Récupère les infos de l’utilisateur
$sql = "SELECT * FROM utilisateurs WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id_utilisateur]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    echo "Utilisateur non trouvé.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Éditer Utilisateur</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 20px; }
    form { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 400px; margin: auto; }
    label { display: block; margin-top: 10px; }
    input, select { width: 100%; padding: 8px; margin-top: 5px; }
    button { margin-top: 15px; padding: 10px; background: #007BFF; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
    button:hover { background: #0056b3; }
</style>
</head>
<body>

    <h1>Éditer Utilisateur</h1>
    <form action="" method="POST">
        <label>Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($utilisateur['nom']) ?>" required>

        <label>Prénom :</label>
        <input type="text" name="prenom" value="<?= htmlspecialchars($utilisateur['prenom']) ?>" required>

        <label>Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($utilisateur['email']) ?>" required>

        <label>Rôle :</label>
        <select name="role" required>
            <option value="visiteur" <?= $utilisateur['role'] === 'visiteur' ? 'selected' : '' ?>>Visiteur</option>
            <option value="admin" <?= $utilisateur['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="comptable" <?= $utilisateur['role'] === 'comptable' ? 'selected' : '' ?>>Comptable</option>
        </select>

        <button type="submit">Enregistrer</button>
    </form>

</body>
</html>
