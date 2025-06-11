<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=gsb_database;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion locale : " . $e->getMessage());
}
?>


