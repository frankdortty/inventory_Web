<?php
$host = 'localhost';
$db = 'accounting';
$user = 'root';
$pass = '';

// Create MySQLi connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
