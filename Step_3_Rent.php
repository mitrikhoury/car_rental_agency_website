<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php?redirect=confirm_rent.php");
    exit();
}

// Retrieve invoice ID from session
$invoice_id = $_SESSION['invoice_id'] ?? '';
$new_location=$_SESSION['location_new'] ?? 'mitri';
$location_new_city=$_SESSION['location_new_city'];
echo 'new location' . $new_location;
echo 'new location' . $location_new_city;
// Clear session data after confirmation
unset($_SESSION['car_details']);
unset($_SESSION['rental_options']);
unset($_SESSION['invoice_id']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent Confirmation</title>
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
                <div class="user-profile">
                    <span>Username: <?= $_SESSION['username'] ?></span>
                </div>
                <a href="shopping_basket.html">Basket</a>
                <a href="logout.php">Logout</a>
                <a href="Search_For_Car.php">Main Page</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="confirmation">
            <h2>Rent Confirmation</h2>
            <p>Thank you for renting a car with us!</p>
            <?php if (!empty($invoice_id)) : ?>
                <p>Your rental has been successfully confirmed.</p>
                <p>Invoice ID: <?= htmlspecialchars($invoice_id) ?></p>
                <p>We will contact you shortly with further details.</p>
            <?php else : ?>
                <p>Oops! Something went wrong. Please try again.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <img src="carsImages\carRental_logo.jpg" alt="Small Logo" class="small-logo">
        <p>© 2023 Car Rental Agency. All rights reserved.</p>
        <p>Address: der al rom , Ramallah, palestine </p>
        <p>Email: mitkhoury@gmail.com | Phone: 0597516680</p>
        <a href="contact_us.php">Contact Us</a>
    </footer>
</body>
</html>
