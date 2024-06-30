<?php
session_start();
require 'db.inc.php';
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$pdo = db_connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {// handle the form submission
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $credit_card_number = $_POST['credit_card_number'];
    $credit_card_expiry = $_POST['credit_card_expiry'];
    $credit_card_holder = $_POST['credit_card_holder'];
    $credit_card_bank = $_POST['credit_card_bank'];

   
    $stmt = $pdo->prepare("UPDATE users SET name = :name, address = :address, dob = :dob, email = :email, telephone = :telephone , username = :username , password = :password,
    credit_card_number = :credit_card_number , credit_card_expiry = :credit_card_expiry , credit_card_holder = :credit_card_holder , credit_card_bank = :credit_card_bank WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
    $stmt->bindParam(':dob', $dob, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':credit_card_number', $credit_card_number, PDO::PARAM_INT);
    $stmt->bindParam(':credit_card_expiry', $credit_card_expiry);
    $stmt->bindParam(':credit_card_holder', $credit_card_holder, PDO::PARAM_STR);
    $stmt->bindParam(':credit_card_bank', $credit_card_bank, PDO::PARAM_STR);   // Update user information in the database
    if ($stmt->execute()) {
        
        header("Location: View_profile.php"); // after update the profile redirect to View_profile
        $_SESSION['username'] = $username; // store the new user name 
        exit();
    } else {
        echo "Failed to update profile. Please try again."; // message
    }
}
?>
