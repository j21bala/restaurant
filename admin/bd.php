<?php
$host = "localhost";
$dbname = "restaurante";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
  // echo "Conexión exitosa"; 
} catch (PDOException $error) {
    echo $error->getMessage();
}
?>