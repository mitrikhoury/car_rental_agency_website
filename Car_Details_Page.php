<?php
session_start();
require_once('db.inc.php'); // Adjust the path to your db connection script

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php?redirect=Car_Details_Page.php");
    exit();
}


if (isset($_GET['car_id'])) {
    $car_id = $_GET['car_id'];
    $rental_to = $_GET['rental_to'];
    $rental_from = $_GET['rental_from'];
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['username'];
    $_SESSION['car_info']['car_id']=$car_id;
    $_SESSION['car_info']['rental_to']=$rental_to;
    $_SESSION['car_info']['rental_from']=$rental_from;

    // Calculate the number of rental days
    $from_date = new DateTime($rental_from);
    $to_date = new DateTime($rental_to);
    $interval = $from_date->diff($to_date); //in PHP, the diff() method is used to calculate the difference between two DateTime objects 
    $rental_days = $interval->days;

    try {
        $pdo = db_connect();
        $stmt = $pdo->prepare("SELECT * FROM cars WHERE car_id = :car_id");
        $stmt->bindParam(':car_id', $car_id, PDO::PARAM_INT);
        $stmt->execute();
        $car = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($car) { 
            $total_price = $car['price_per_day'] * $rental_days;
            $_SESSION['car_info']['model']=$car['model'];
            $_SESSION['car_info']['description']=$car['description'];
            $_SESSION['car_info']['total_price']=$total_price;
            $_SESSION['car_info']['Pick_up_location']=$car['city'];
            $_SESSION['car_info']['return_location']=$car['return_location_name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Details</title>
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
                        <span>Username: <?= $user_name ?></span>
                    </div>
                    <a href="shopping_basket.html">Basket</a>
                    <a href="logout.php">Logout</a>
                    <a href="Search_For_Car.php">Main Page </a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <div class="container">
        <div class="car-details">
            <div class="car-photos">
                <img src="carsImages\<?= $car['image1'] ?>" alt="car photo one">
                <img src="carsImages\<?= $car['image2'] ?>" alt="car photo two">
                <img src="carsImages\<?= $car['image3'] ?>" alt="car photo">
            </div>
            <div class="car-description">
                <h2>Car Description</h2>
                <ul>
                    <li><strong>Car Reference Number:</strong> <?= $car['car_id'] ?></li>
                    <li><strong>Car Model:</strong> <?= $car['model'] ?></li>
                    <li><strong>Car Type:</strong> <?= $car['type'] ?></li>
                    <li><strong>Car Make:</strong> <?= $car['make'] ?></li>
                    <li><strong>Registration Year:</strong> <?= $car['registration_year'] ?></li>
                    <li><strong>Color:</strong> <?= $car['color'] ?></li>
                    <li><strong>Brief Description:</strong> <?= $car['description'] ?></li>
                    <li><strong>Price per Day:</strong> <?= $car['price_per_day'] . ' $' ?></li>
                    <li><strong>Capacity of People:</strong> <?= $car['capacity_people'] ?></li>
                    <li><strong>Capacity of Suitcases:</strong> <?= $car['capacity_suitcases'] . ' 🧳' ?></li>
                    <li><strong>Total Price for Renting Period:</strong> <?= $total_price . ' $' ?></li>
                    <li><strong>Fuel Type:</strong> <?= $car['fuel_type'] . ' ⛽' ?></li>
                    <li><strong>Average Consumption (per 100 km):</strong> <?= $car['avg_consumption'] . ' L' ?></li>
                    <li><strong>Horsepower:</strong> <?= $car['horsepower'] . ' 🐎' ?></li>
                    <li><strong>Length:</strong> <?= $car['length'] . ' M' ?></li>
                    <li><strong>Width:</strong> <?= $car['width'] . ' M' ?></li>
                    <li><strong>Gear Type:</strong> <?= $car['gear_type'] . ' ⚙' ?></li>
                    <li><strong>Conditions or Restrictions:</strong> <?= $car['conditions'] ?></li>
                </ul>
                <form action="Step_1_Rent_m.php" method="post">
                    <button type="submit" name="rent_button">Rent-a-Car</button>
                </form>
            </div>
        </div>
        <div class="additional-info">
            <h2>Marking Information</h2>
            <p>This car is enjoyable to drive with excellent handling and comfort.</p>
            <p>Discount available for long-term rentals.</p>
        </div>
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
<?php
        } else {
            echo "<p>Car details not found.</p>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "<p>Error: Car ID is required.</p>";
}
?>
