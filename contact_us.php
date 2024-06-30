
<?php 

session_start();
require 'db.inc.php';
$user_id = null;
$user_role = null;
$user_name = null;
$pdo = db_connect(); // Ensure you have this function defined in db.inc.php
// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch user details from the database based on session user_id
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $user_role = $user['role'];
        $user_name = $_SESSION['username'];
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us</title>
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
                <?php if ($user_id): ?>
                    <div class="user-profile">
                        <span>Username: <?= $user_name ?></span>
                    </div>
                    <a href="View_profile.php">View Profile</a>
                    <a href="shopping_basket.html">Basket</a>
                    <a href="logout.php">Logout</a>
                    <a href="Search_For_Car.php">Main Page</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="Registration_step_one.php">Register as Customer</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main> 
    <div class="container">
        <h2>Contact Form</h2>
        <form action="http://comp334.studentswebprojects.ritaj.ps/util/process.php" method="post">
            <label for="name">Sender Name:</label>
            <input type="text" id="name" name="name" required><br>
            <label for="email">Sender E-mail:</label>
            <input type="email" id="email" name="email" required><br>
            <label for="location">Sender Location (city):</label>
            <input type="text" id="location" name="location" required><br>
            <label for="subject">Message Subject:</label>
            <input type="text" id="subject" name="subject" required><br>
            <label for="message">Message Body:</label><br>
            <textarea id="message" name="message" rows="4" cols="50" required></textarea><br>
            <input type="submit" value="Send">
            <input type="reset" value="Reset">
        </form>
        </div>
        </main>
    <footer>
        <img src="carsImages\carRental_logo.jpg" alt="Small Logo" class="small-logo">
        <p>© 2023 Car Rental Agency. All rights reserved.</p>
        <p>Address: der al rom , Ramallah, palestine </p>
        <p>Email: mitkhoury@gmail.com | Phone: 0597516680</p>
    </footer>
</body>
</html>