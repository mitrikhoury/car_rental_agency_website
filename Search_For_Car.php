<?php
session_start();
require 'db.inc.php'; 

// Initialize variables
$user_id = null;
$user_role = null;
$user_name = null;
$pdo = db_connect(); //connection

if (isset($_SESSION['user_id'])) {   // Check if user is logged in
    $user_id = $_SESSION['user_id'];

    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);  ///Fetch user details from the database based on session user_id

    if ($user) {
        $user_role = $user['role'];
        $user_name = $_SESSION['username'];
    }
}

// Set default values for car search
$rental_from = date('Y-m-d');
$rental_to = date('Y-m-d', strtotime('+3 days'));
$car_type = 'sedan';
$pickup_location = 'Birzeit';
$min_price = 200;
$max_price = 1000;
$sort_by = 'price_per_day';
$sort_order = 'ASC';
$shortlisted_cars = [];


if ($_SERVER["REQUEST_METHOD"] == "POST") {  // form handle data
    if (isset($_POST['shortlist'])) {
        if (!empty($_POST['car_ids'])) {   // handle the short list post 
            $car_ids = $_POST['car_ids'];
            $placeholders = str_repeat('?,', count($car_ids) - 1) . '?';
            $sql = "SELECT * FROM cars WHERE car_id IN ($placeholders)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($car_ids);
            $shortlisted_cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } else {    // Handle the search for car 
        $rental_from = $_POST['rental_from'];
        $rental_to = $_POST['rental_to'];
        $car_type = $_POST['car_type'];
        $pickup_location = $_POST['pickup_location'];
        $min_price = $_POST['min_price'];
        $max_price = $_POST['max_price'];
    }
}


if (isset($_GET['sort_by'])) {   // Process sorting and set in cookies
    $sort_by = $_GET['sort_by'];
    $sort_order = $_GET['sort_order'];
    setcookie('sort_by', $sort_by, time() + (86400 * 30), "/");
    setcookie('sort_order', $sort_order, time() + (86400 * 30), "/");
} else {
    if (isset($_COOKIE['sort_by'])) {   // the sort proccess in the cookies
        $sort_by = $_COOKIE['sort_by'];
        $sort_order = $_COOKIE['sort_order'];
    }
}


if (empty($shortlisted_cars)) {  // if the short list empty then the user search for car 
    $sql = "
        SELECT cars.*
        FROM cars
        WHERE cars.type = :car_type
        AND cars.price_per_day >= :min_price
        AND cars.price_per_day <= :max_price
        AND cars.city = :pickup_location
        AND cars.car_id NOT IN (
            SELECT r.car_id
            FROM rentals r
            WHERE r.rental_date <= :rental_to
            AND r.return_date >= :rental_from
        )
        ORDER BY $sort_by $sort_order
    ";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':car_type', $car_type, PDO::PARAM_STR);
    $stmt->bindParam(':min_price', $min_price, PDO::PARAM_INT);
    $stmt->bindParam(':max_price', $max_price, PDO::PARAM_INT);
    $stmt->bindParam(':pickup_location', $pickup_location, PDO::PARAM_STR);
    $stmt->bindParam(':rental_from', $rental_from, PDO::PARAM_STR);
    $stmt->bindParam(':rental_to', $rental_to, PDO::PARAM_STR);

    $stmt->execute();

    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);   //  prepeare the statemnet and bind value and excute it 
} else {  // the short list not empty then the user click short list action  and set the car selected in car variable
    $cars = $shortlisted_cars;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Search</title>
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
                <?php if ($user_id): ?>
                    <div class="user-profile">
                        <span>Username: <?= htmlspecialchars($user_name) ?></span>
                    </div>
                    <a href="View_profile.php">View Profile</a>
                    <a href="shopping_basket.html">Basket</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="Registration_step_one.php">Register as Customer</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <nav class="sidebar">  
        <ul>
            <?php if ($user_id && $user_role == 'customer'): ?>
                <li><a href="return_car_customer.php">Return a Car</a></li>
                <li><a href="View_rented.php">View Rented Cars</a></li>
                <li><a href="View_profile.php">View / Update Profile</a></li>
            <?php elseif ($user_id && $user_role == 'manager'): ?>
                <li><a href="AddCar.php">Add a Car</a></li>
                <li><a href="return_manger.php">Return a Car</a></li>
                <li><a href="cars_inquire.php">Cars Inquiry</a></li>
                <li><a href="AddLocation.php">Add a New Location</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <main>
        <div class="container_main">
            <form method="post" action="Search_For_Car.php">
                
                <label for="rental_from">Rental From:</label>
                <input type="date" id="rental_from" name="rental_from" value="<?= htmlspecialchars($rental_from) ?>" required>

                <label for="rental_to">Rental To:</label>
                <input type="date" id="rental_to" name="rental_to" value="<?= htmlspecialchars($rental_to) ?>" required>

                <label for="car_type">Car Type:</label>
                <select id="car_type" name="car_type">
                    <option value="sedan" <?= ($car_type == 'sedan') ? 'selected' : '' ?>>Sedan</option>
                    <option value="suv" <?= ($car_type == 'suv') ? 'selected' : '' ?>>SUV</option>
                    <option value="hatchback" <?= ($car_type == 'hatchback') ? 'selected' : '' ?>>Hatchback</option>
                    <option value="convertible" <?= ($car_type == 'convertible') ? 'selected' : '' ?>>Convertible</option>
                </select>

                <label for="pickup_location">Pickup Location:</label>
                <input type="text" id="pickup_location" name="pickup_location" value="<?= htmlspecialchars($pickup_location) ?>" required>

                <label for="min_price">Min Price:</label>
                <input type="number" id="min_price" name="min_price" value="<?= htmlspecialchars($min_price) ?>" min="0" required>

                <label for="max_price">Max Price:</label>
                <input type="number" id="max_price" name="max_price" value="<?= htmlspecialchars($max_price) ?>" min="0" required>

                <button type="submit">Search</button>
            </form>
        </div>

        <form method="post" action="Search_For_Car.php">
            <table id="carTable">
                <thead>
                    <tr>
                        <th><button type="submit" name="shortlist">Shortlist</button></th>
                        <th><a href="?sort_by=price_per_day&sort_order=<?= ($sort_by == 'price_per_day' && $sort_order == 'ASC') ? 'DESC' : 'ASC' ?>">Price per Day</a></th>
                        <th><a href="?sort_by=type&sort_order=<?= ($sort_by == 'type' && $sort_order == 'ASC') ? 'DESC' : 'ASC' ?>">Car Type</a></th>
                        <th><a href="?sort_by=fuel_type&sort_order=<?= ($sort_by == 'fuel_type' && $sort_order == 'ASC') ? 'DESC' : 'ASC' ?>">Fuel Type</a></th>
                        <th>Car Photo</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cars as $car): ?>
                    <tr>
                        <td><input type="checkbox" name="car_ids[]" value="<?= $car['car_id'] ?>"></td>
                        <td><?= htmlspecialchars($car['price_per_day']) ?></td>
                        <td><?= htmlspecialchars($car['type']) ?></td>
                        <td class="<?= strtolower(htmlspecialchars($car['fuel_type'])) ?>"><?= htmlspecialchars($car['fuel_type']) ?></td>
                        <td><img src="carsImages/<?= htmlspecialchars($car['image1']) ?>" alt="Car Photo" height="100"></td>
                        <td><a href="Car_Details_Page.php?car_id=<?= $car['car_id'] ?>&rental_from=<?= $rental_from ?>&rental_to=<?= $rental_to ?>">Rent</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </main>
    <footer>
        <img src="carsImages/carRental_logo.jpg" alt="Small Logo" class="small-logo">
        <p>© 2023 Car Rental Agency. All rights reserved.</p>
        <p>Address: der al rom, Ramallah, Palestine</p>
        <p>Email: mitkhoury@gmail.com | Phone: 0597516680</p>
        <a href="contact_us.php">Contact Us</a>
    </footer>
</body>
</html>
