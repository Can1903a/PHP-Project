<?php
$servername = "localhost";
$username = "root";
$password = "12345";
$dbname = "Eticaret";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Bağlantı başarısız: " . $conn->connect_error);
}   
?>