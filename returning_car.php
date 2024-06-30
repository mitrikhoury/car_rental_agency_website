<?php
session_start();
require_once('db.inc.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }

        $car_id = $_POST['car_id'];
        $user_id = $_SESSION['user_id'];
        $pdo = db_connect();
        
         $status='returning';
         $stmt_update_car = $pdo->prepare("UPDATE cars SET status = :status WHERE car_id = :car_id");  // to set the status of the car to rented
         $stmt_update_car->bindParam(':status',$status,PDO::PARAM_STR);
         $stmt_update_car->bindParam(':car_id', $car_id, PDO::PARAM_INT);
        $stmt_update_car->execute();
      
        header("Location: return_car_customer.php");
        exit();

    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
