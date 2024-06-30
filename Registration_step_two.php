<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (strlen($username) >= 6 && strlen($username) <= 13 && strlen($password) >= 8 && strlen($password) <= 12 && $password === $confirm_password) {
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        header("Location: Registration_step_three.php");
        exit();
    } else {
        $error = "Invalid username or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Step 2</title>
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
                <a href="logout.php">Logout</a>
                <a href="Search_For_Car.php">Main Page </a>
            </nav>
        </div>
    </header>
    <main>
    <div class="container">
    <form method="post" action="Registration_step_two.php">
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
        <label>Username: <input type="text" name="username" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <label>Confirm Password: <input type="password" name="confirm_password" required></label><br>
        <button type="submit">Next</button>
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
