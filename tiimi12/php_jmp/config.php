<?php

$host = "localhost";  // HAMK server
$dbname = "trtkp24_12";    // Your database name
$username = "trtkp24_12";  // Your MySQL username
$password = "kri6aSjc";  // Your MySQL password
try {
    // Create a new PDO instance (database connection)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;  // Stop further execution if database connection fails
}
?>


