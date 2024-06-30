<?php
session_start();
require_once('db.inc.php');
$cities = [];
$returnLocations = [];

$pdo = db_connect();
try {
    $stmt = $pdo->query("SELECT city FROM locations");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $cities[] = $row['city'];
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
try {
    // Fetch return locations
    $stmt = $pdo->query("SELECT return_location_name FROM locations");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $returnLocations[] = $row['return_location_name'];
    }
} catch (PDOException $e) {
    echo 'Error fetching return locations: ' . $e->getMessage();
}

// Initialize variables with default values or empty strings
$pickup_date = isset($_GET['pickup_date']) ? $_GET['pickup_date'] : date('Y-m-d');
$return_date = isset($_GET['return_date']) ? $_GET['return_date'] : date('Y-m-d', strtotime('+7 days'));
$pickup_location = isset($_GET['pickup_location']) ? $_GET['pickup_location'] : '';
$return_location_name = isset($_GET['return_location_name']) ? $_GET['return_location_name'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

$cars = []; // Initialize an empty array to store fetched cars

try {
    // Prepare base SQL query
    $sql = "SELECT car_id, type, model, description, image1, fuel_type, status FROM cars ";

    // Prepare conditions based on user inputs
    $conditions = [];
    $params = [];

    // Available for rent in a certain pick-up location
    if (!empty($pickup_location)) {
        $conditions[] = "city LIKE :pickup_location";
        $params[':pickup_location'] = '%' . $pickup_location . '%';
    }

    // Return to a certain location
    if (!empty($return_location_name)) {
        $conditions[] = "city LIKE :return_location_name";
        $params[':return_location_name'] = '%' . $return_location_name . '%';
    }

    // All cars in repair
    if ($status == 'in_repair') {
        $conditions[] = "status = 'in_repair'";
    }

    // All cars in damage
    if ($status == 'damaged') {
        $conditions[] = "status = 'damaged'";
    }

    // If searching for cars available for a specific period
    if (!empty($pickup_date) && !empty($return_date)) {
        $conditions[] = "(car_id NOT IN (
                            SELECT car_id FROM rentals 
                            WHERE rental_date <= :return_date AND return_date >= :pickup_date
                        ))";
        $params[':pickup_date'] = $pickup_date;
        $params[':return_date'] = $return_date;
    }

    // If no conditions are specified, default to selecting all cars for the next week
    if (empty($conditions)) {
        $sql .= "WHERE rental_date <= :return_date AND return_date >= :pickup_date";
        $params[':pickup_date'] = $pickup_date;
        $params[':return_date'] = $return_date;
    } else {
        // Combine conditions into the final query
        $sql .= " WHERE " . implode(' AND ', $conditions);
    }

    // Prepare and execute the SQL query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cars Inquiry</title>
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
    <h2>Cars Inquiry</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
        <label for="pickup_date">Pick-up Date:</label>
        <input type="date" id="pickup_date" name="pickup_date" value="<?php echo htmlspecialchars($pickup_date); ?>">
        <br>

        <label for="return_date">Return Date:</label>
        <input type="date" id="return_date" name="return_date" value="<?php echo htmlspecialchars($return_date); ?>">
        <br>

        <label for="pickup_location">Pick-up Location (City):</label>
        <select id="pickup_location" name="pickup_location" required>
            <?php foreach ($cities as $city): ?>
                <option value="<?= htmlspecialchars($city) ?>" <?php if ($pickup_location == $city) echo 'selected'; ?>>
                    <?= htmlspecialchars($city) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>

        <label for="return_location_name">Return Location:</label>
        <select id="return_location_name" name="return_location_name" required>
            <?php foreach ($returnLocations as $loc): ?>
                <option value="<?= htmlspecialchars($loc) ?>" <?php if ($return_location_name == $loc) echo 'selected'; ?>>
                    <?= htmlspecialchars($loc) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>

        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="available" <?php if ($status == 'available') echo 'selected'; ?>>Available</option>
            <option value="in_repair" <?php if ($status == 'in_repair') echo 'selected'; ?>>In Repair</option>
            <option value="damaged" <?php if ($status == 'damaged') echo 'selected'; ?>>Damaged</option>
        </select>
        <br>

        <button type="submit">Search</button>
    </form>

    <h2>Cars Inquiry Results</h2>
    <table class="car_inquire">
        <thead>
            <tr>
                <th>Car ID</th>
                <th>Type</th>
                <th>Model</th>
                <th>Description</th>
                <th>Photo</th>
                <th>Fuel Type</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cars as $car): ?>
                <tr>
                    <td><?php echo $car['car_id']; ?></td>
                    <td><?php echo $car['type']; ?></td>
                    <td><?php echo $car['model']; ?></td>
                    <td><?php echo $car['description']; ?></td>
                    <td><img src="<?php echo 'carsImages/'.$car['image1']; ?>" alt="Car Photo" height="100"></td>
                    <td><?php echo $car['fuel_type']; ?></td>
                    <td><?php echo ucfirst($car['status']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
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
