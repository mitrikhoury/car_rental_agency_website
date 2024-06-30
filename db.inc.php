<?php
// Database connection details
$db_host = 'localhost';
$db_name = 'db_car_rental';
$db_user = 'root';
$db_pass = '';

function db_connect(){
    global $db_host,$db_name,$db_user,$db_pass;
    
    try {
        // Create a new PDO instance
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;s
    } catch (PDOException $e) {
        // Handle connection error
        echo 'Connection failed: ' . $e->getMessage();
        exit;
    }
}

?>