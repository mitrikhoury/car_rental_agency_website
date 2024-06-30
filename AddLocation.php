<?php
session_start();
require_once('db.inc.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postal_code = $_POST['postal_code'];
    $country = $_POST['country'];
    $telephone = $_POST['telephone'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $return_location_name = $_POST['return_location_name'];
    $pdo = db_connect(); // Connecting to the database
    try {
        $stmt = $pdo->prepare("INSERT INTO locations (address, city, postal_code, country, name, telephone, return_location_name) 
                               VALUES (:address, :city, :postal_code, :country, :name, :telephone, :return_location_name)");

        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':postal_code', $postal_code);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':return_location_name', $return_location_name);

        $stmt->execute();

        echo "Location added successfully.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Location</title>
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
                <a href="About_us.php">About Us</a>
                <?php if ($_SESSION['user_id']): ?>
                    <div class="user-profile">
                        <span>Username: <?= $_SESSION['username'] ?></span>
                    </div>
                    <a href="View_profile.php">View Profile</a>
                    <a href="shopping_basket.html">Basket</a>
                    <a href="logout.php">Logout</a>
                    <a href="Search_For_Car.php">Main Page </a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="Registration_step_one.php">Register as Customer</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
    <h2>Add New Location</h2>
    <form action="AddLocation.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required>
        <br>
        <label for="city">City:</label>
        <input type="text" id="city" name="city" required>
        <br>
        <label for="postal_code">Postal Code:</label>
        <input type="text" id="postal_code" name="postal_code" required>
        <br>
        <label for="country">Country:</label>
        <input type="text" id="country" name="country" required>
        <br>
        <label for="telephone">Telephone:</label>
        <input type="text" id="telephone" name="telephone" required>
        <br>
        <label for="return_location_name">Return Location Name:</label>
        <input type="text" id="return_location_name" name="return_location_name" required>
        <br>
        <button type="submit">Add New Location</button>
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
