<?php
$servername = "localhost";
$username = "root"; 
$password = "Lana.1234"; 
$dbname = "Task";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>