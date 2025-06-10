<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'gsb_database');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

try {
    $pdo = new PDO("mysql:host=localhost;dbname=gsb_database;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
