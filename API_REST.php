<?php 
header("Content-Type: application/json"); 
 
$host = "localhost"; 
$dbname = "gsb_database"; 
$username = "root"; 
$password = ""; 
$bdd = "mysql:host=$host;dbname=$dbname;charset=utf8mb4"; 
$options = [ 
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
    PDO::ATTR_EMULATE_PREPARES => false, 
]; 
try { 
    $pdo = new PDO($bdd, $username, $password, $options); 
} catch (PDOException $e) { 
    throw new PDOException($e->getMessage(), (int)$e->getCode()); 
} 
 
$email = $_POST["email"]; 
$password = $_POST["password"]; 
 
$sql = $pdo->prepare("SELECT * FROM utilisateurs WHERE email=:email"); 
$sql->execute(['email' => $email]); 

$user = $sql->fetch();
 
if(($sql->rowCount() > 0) && password_verify($password, $user["password"])) { 

    
    $json = array("status" => 200,'message' => "Success"); 
} else { 
    $json = array("status" => 400,'message' => "Error"); 
} 
 
echo json_encode($json);