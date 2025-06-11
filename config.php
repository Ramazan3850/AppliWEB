<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'gsb_database');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

try {
    $pdo = new PDO("mysql:host=sql308.infinityfree.com;dbname=if0_39202074;charset=utf8", "epiz_12345678_gsbdb", "BlackopsColdWar");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
