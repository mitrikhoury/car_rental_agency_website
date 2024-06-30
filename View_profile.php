<?php
session_start();
require 'db.inc.php';
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$pdo = db_connect();
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View / Update Profile</title>
    <link rel="stylesheet" href="car_details_style.css">
</head>
<body>
<header>
        <div class="header-content">
            <figure>
                <img src="carsImages\carRental_logo.jpg" alt="Agency Logo" class="logo">
            </figure>
            <h1>Car Rental Agency</h1>
            <nav class="header-nav">
                <a href="About_us.php">About Us</a>
                <?php if ($user_id) : ?>
                    <div class="user-profile">
                        <span>Username: <?= $user['username'] ?></span>
                    </div>
                    <a href="shopping_basket.html">Basket</a>
                    <a href="logout.php">Logout</a>
                    <a href="Search_For_Car.php">Main Page </a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
<main>
     <div class="container">
    <h2>View / Update Profile</h2>
    <form action="Update_profile.php" method="post" class="form_profile">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= $user['name'] ?>" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?= $user['address'] ?>" required>

        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" value="<?= $user['dob'] ?>" required>

        <label for="user_id">ID Number:</label>
        <input type="text" id="user_id" name="user_id" value="<?= $user['user_id'] ?>" readonly>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= $user['email'] ?>" required>

        <label for="telephone">Telephone:</label>
        <input type="tel" id="telephone" name="telephone" value="<?= $user['telephone'] ?>" required>

        <label for="username">User Name:</label>
        <input type="text" id="username" name="username" value="<?= $user['username'] ?>" required>

        <label for="password">Password:</label>
        <input type="text" id="password" name="password" value="<?= $user['password'] ?>" required>
        
        <label for="credit_card_number">credit card number:</label>
        <input type="text" id="credit_card_number" name="credit_card_number" value="<?= $user['credit_card_number'] ?>" required>
        
        <label for="credit_card_expiry">credit card expiry:</label>
        <input type="date" id="credit_card_expiry" name="credit_card_expiry" value="<?= $user['credit_card_expiry'] ?>" required>
        
        <label for="credit_card_holder">credit card holder:</label>
        <input type="text" id="credit_card_holder" name="credit_card_holder" value="<?= $user['credit_card_holder'] ?>" required>
        
        <label for="credit_card_bank">credit card bank:</label>
        <input type="text" id="credit_card_bank" name="credit_card_bank" value="<?= $user['credit_card_bank'] ?>" required>
        <input type="submit" value="Update">
     </form>
    </div>
</main>
    <footer>
        <img src="carsImages\carRental_logo.jpg" alt="Small Logo" class="small-logo">
        <p>© 2023 Car Rental Agency. All rights reserved.</p>
        <p>Address: der al rom , Ramallah, palestine </p>
        <p>Email: mitkhoury@gmail.com | Phone: 0597516680</p>
        <a href="contact_us.php">Contact Us</a>
    </footer>
</body>
</html>
