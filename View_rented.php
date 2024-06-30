<?php
session_start();
require 'db.inc.php'; 
$user_id = $_SESSION['user_id']; 
$pdo = db_connect();
try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
    $stmt = $pdo->prepare("
        SELECT 
            rentals.rental_id, rentals.invoice_date, rentals.rental_date, rentals.return_date,
            cars.type, cars.model, cars.city AS pickup_location, cars.return_location_name AS return_location
        FROM 
            rentals, cars 
        WHERE 
            rentals.user_id = :user_id AND rentals.car_id = cars.car_id
        ORDER BY 
            rentals.rental_date ASC
    ");    // prepare the statment get all the user car rented 
    
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);  //  bind paremeter ,, excute ,,, and fetch
    
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}

function theStatusofCar($rental_date, $return_date) {
    $current_date = date('Y-m-d');
    if ($current_date < $rental_date) {
        return 'future';
    } elseif ($current_date >= $rental_date && $current_date <= $return_date) {
        return 'current';
    } else {
        return 'past';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="car_details_style.css">
    <title>View Rented Cars</title>
</head>
<body>
<header>
        <div class="header-content">
            <figure>
                <img src="carsImages/carRental_logo.jpg" alt="Agency Logo" class="logo">
            </figure>
            <h1>Car Rental Agency</h1>
            <nav class="header-nav">
                <a href="About_us.php">About Us</a>
                <?php if (isset($user_id)) : ?>
                    <div class="user-profile">
                        <span>Username: <?=  $_SESSION['username']; ?></span>
                    </div>
                    <a href="shopping_basket.html">Basket</a>
                    <a href="logout.php">Logout</a>
                    <a href="Search_For_Car.php">Main Page</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
    <h1>View Rented Cars</h1>
    <h2>Infomatiom Stataus<h2>
    <table class="status-info-table">
        <thead>
            <tr>
                <th>Status</th>
                <th>Color</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Future</td>
                <td><div class="status-color future-color"></div></td>
                <td>The car has been rented but not picked up yet.</td>
            </tr>
            <tr>
                <td>Current</td>
                <td><div class="status-color current-color"></div></td>
                <td>The car has been picked up but not returned yet.</td>
            </tr>
            <tr>
                <td>Past</td>
                <td><div class="status-color past-color"></div></td>
                <td>The car was picked up and has been returned.</td>
            </tr>
        </tbody>
    </table>
    <table class="show_car_rented">
        <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Invoice Date</th>
                <th>Car Type</th>
                <th>Car Model</th>
                <th>Pick-up Date</th>
                <th>Pick-up Location</th>
                <th>Return Date</th>
                <th>Return Location</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rentals as $rental): ?>
                <tr class="<?= theStatusofCar($rental['rental_date'], $rental['return_date']); ?>">
                    <td><?= $rental['rental_id']; ?></td>
                    <td><?= $rental['invoice_date']; ?></td>
                    <td><?= $rental['type']; ?></td>
                    <td><?= $rental['model']; ?></td>
                    <td><?= $rental['rental_date']; ?></td>
                    <td><?= $rental['pickup_location']; ?></td>
                    <td><?= $rental['return_date']; ?></td>
                    <td><?= $rental['return_location']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
  </div>
 </main>
    <footer>
        <img src="carsImages/carRental_logo.jpg" alt="Small Logo" class="small-logo">
        <p>© 2023 Car Rental Agency. All rights reserved.</p>
        <p>Address: der al rom , Ramallah, palestine </p>
        <p>Email: mitkhoury@gmail.com | Phone: 0597516680</p>
        <a href="contact_us.php">Contact Us</a>
    </footer>
</body>
</html>
