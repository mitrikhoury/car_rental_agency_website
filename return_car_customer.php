<?php
session_start();
require_once('db.inc.php');

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

try {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $pdo = db_connect();

    // Fetch active car rents
    $stmt = $pdo->prepare("
        SELECT rental_id, car_id, rental_date AS pickup_date, return_date
        FROM rentals
        WHERE user_id = :user_id 
    ");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $rents = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
    exit(); // Stop execution in case of error
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Return a Car</title>
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
                <?php if ($_SESSION['user_id']) : ?>
                    <div class="user-profile">
                        <span>Username: <?= $_SESSION['username'] ?></span>
                    </div>
                    <a href="logout.php">Logout</a>
                    <a href="Search_For_Car.php">Main Page </a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
    <h2>Return a Car</h2>
    <table class="return_customer_table">
        <tr>
            <th>Reference Number</th>
            <th>Make</th>
            <th>Type</th>
            <th>Model</th>
            <th>Pickup Date</th>
            <th>Return Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php if (isset($rents) && is_array($rents) && count($rents) > 0): ?>
            <?php foreach ($rents as $rent): ?>
                <?php
                $car_id = $rent['car_id'];
                $car_stmt = $pdo->prepare("SELECT car_id, make, type, model FROM cars WHERE car_id = :car_id AND status = 'rented'");
                $car_stmt->bindParam(':car_id', $car_id, PDO::PARAM_INT);
                $car_stmt->execute();
                $car = $car_stmt->fetch(PDO::FETCH_ASSOC);
                if ($car) {
                    $status = theStatusofCar($rent['pickup_date'], $rent['return_date']);
                } else {
                    continue; // Skip this iteration if no car found
                }
                ?>
                <?php if ($status == 'current' || $status == 'past'): ?>
                    <tr class="<?php echo $status; ?>">
                        <td><?php echo $car['car_id']; ?></td>
                        <td><?php echo $car['make']; ?></td>
                        <td><?php echo $car['type']; ?></td>
                        <td><?php echo $car['model']; ?></td>
                        <td><?php echo $rent['pickup_date']; ?></td>
                        <td><?php echo $rent['return_date']; ?></td>
                        <td><?php echo $status; ?></td>
                        <td>
                            <form action="returning_car.php" method="post">
                                <input type="hidden" name="car_id" value="<?php echo $car['car_id']; ?>">
                                <button type="submit" class="return-button">Return</button>
                            </form>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">No active car rents found.</td>
            </tr>
        <?php endif; ?>
    </table>
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
