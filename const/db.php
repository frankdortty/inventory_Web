<?php
$host = 'localhost';
$db = 'accounting';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

try {
    // Create PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage()); // Handle connection errors
}
?>
