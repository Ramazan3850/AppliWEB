<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "gsb_database";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM fiches_frais";
    $stmt = $conn->query($sql);

    echo "<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        background-color: #f4f4f4;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
    }
    th {
        background-color: #005cbf;
        color: white;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    tr:hover {
        background-color: #ddd;
    }
    a {
        text-decoration: none;
        color: #005cbf;
        font-weight: bold;
    }
    a:hover {
        color: #005cbf;
    }
    h1 {
        color: #333;
    }
  </style>";

    echo "<table border='1' style='border-collapse: collapse; width: 100%; text-align: left;'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Valide ou Refuse</th>
                </tr>
            </thead>
            <tbody>";
    
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['montant']}</td>
                        <td>{$row['date']}</td>
                        <td>{$row['description']}</td>
                        <td>{$row['valide_ou_refuse']}</td>
                        <td><a href='modifierfichefrais.php?id={$row['id']}'>Modifier</a></td>
                      </tr>";
            }

    echo "</tbody></table>";

} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();

}
?>
