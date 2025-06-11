<?php
try {
    $pdo = new PDO(
        "mysql:host=sql308.infinityfree.com;dbname=if0_39202074;charset=utf8",
        "if0_39202074",
        "BlackopsColdWar"
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>


