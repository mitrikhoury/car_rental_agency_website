<?php
session_start();
require 'db.inc.php'; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $pdo = db_connect();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['user_id']; // Store user ID in session
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // Store user's role in session
        header("Location: Search_For_Car.php"); // Redirect to user's search page (main) or dashboard
        exit();
    } else {
        $error = "Invalid username or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="car_details_style.css">
</head>
<body>
<header>
        <div class="header-content">
            <figure>
                <img src="carsImages/carRental_logo.jpg" alt="Agency Logo" class="logo">
            </figure>
            <h1>Car Rental Agency</h1>
            <nav class="header-nav">
                <p>if you dont have account ,,</p>
                <a href="Registration_step_one.php">Register as Customer</a>
                <a href="Search_For_Car.php">Main Page </a>
            </nav>
        </div>
    </header>
<main>
        <div class="container">
            <h2>Login</h2>
            <form method="post" action="login.php" class="form_profile">
                <?php if (isset($error)) echo "<p>$error</p>"; ?>
                <label>Username: <input type="text" name="username" required></label><br>
                <label>Password: <input type="password" name="password" required></label><br>
                <button type="submit">Login</button>
            </form>
        </div>
    </main>  
    <footer>
        <img src="carsImages/carRental_logo.jpg" alt="Small Logo" class="small-logo">
        <p>© 2023 Car Rental Agency. All rights reserved.</p>
        <p>Address: Der Al Rom, Ramallah, Palestine</p>
        <p>Email: mitkhoury@gmail.com | Phone: 0597516680</p>
        <a href="contact_us.php">Contact Us</a>
    </footer>
</body>
</html>
