<?php
session_start();
require_once('db.inc.php');

// Fetch cities and return locations from locations table
$pdo = db_connect();
$cities = [];
$returnLocations = [];

try {
    // Fetch cities
    $stmt = $pdo->query("SELECT city FROM locations");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $cities[] = $row['city'];
    }
} catch (PDOException $e) {
    echo 'Error fetching cities: ' . $e->getMessage();
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

// Function to handle file uploads
function handleFileUpload($file, $uploadDir, $carID, $index) {
    $allowedTypes = array('jpg', 'jpeg', 'png');
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Validate file type
    if (!in_array($fileType, $allowedTypes)) {
        die("Error: Only JPG, JPEG, and PNG files are allowed.");
    }

    // Rename file
    $newFileName = 'car'.$carID.'img'.$index.'.'.$fileType;
    $destination = $uploadDir . $newFileName;

    // Upload file
    if (move_uploaded_file($fileTmpName, $destination)) {
        return $newFileName; // Return the new file name if upload is successful
    } else {
        die("Error uploading file."); // Handle upload failure gracefully
    }
}

// Initialize variables
$model = $make = $type = $registration_year = $description = $price_per_day = '';
$capacity_people = $capacity_suitcases = $color = $fuel_type = $avg_consumption = '';
$horsepower = $length = $width = $gear_type = $plate_number = $conditions = $city = '';
$return_location_name = $image1 = $image2 = $image3 = '';
$status = 'available'; // Default status

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validate and sanitize inputs where necessary (PDO will handle parameter types)
        $model = $_POST['model'];
        $make = $_POST['make'];
        $type = $_POST['type'];
        $registration_year = $_POST['registration_year'];
        $description = $_POST['description'];
        $price_per_day = $_POST['price_per_day'];
        $capacity_people = $_POST['capacity_people'];
        $capacity_suitcases = $_POST['capacity_suitcases'];
        $color = $_POST['color'];
        $fuel_type = $_POST['fuel_type'];
        $avg_consumption = $_POST['avg_consumption'];
        $horsepower = $_POST['horsepower'];
        $length = $_POST['length'];
        $width = $_POST['width'];
        $gear_type = $_POST['gear_type'];
        $plate_number = $_POST['plate_number'];
        $conditions = $_POST['conditions'];
        $city = $_POST['city'];
        $status = $_POST['status']; // Get status from form
        $return_location_name = $_POST['return_location_name'];

        // Handle image uploads
        $uploadDir = 'carsImages/';

        // Insert data into database using prepared statements to prevent SQL injection
        $stmt = $pdo->prepare("INSERT INTO cars (model, make, type, registration_year, description, price_per_day, 
                            capacity_people, capacity_suitcases, color, fuel_type, avg_consumption, horsepower, 
                            length, width, gear_type, plate_number, conditions, image1, image2, image3, city, return_location_name, status) 
                            VALUES (:model, :make, :type, :registration_year, :description, :price_per_day, 
                            :capacity_people, :capacity_suitcases, :color, :fuel_type, :avg_consumption, 
                            :horsepower, :length, :width, :gear_type, :plate_number, :conditions, 
                            :image1, :image2, :image3, :city, :return_location_name, :status)");

        $stmt->bindParam(':model', $model);
        $stmt->bindParam(':make', $make);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':registration_year', $registration_year);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price_per_day', $price_per_day);
        $stmt->bindParam(':capacity_people', $capacity_people);
        $stmt->bindParam(':capacity_suitcases', $capacity_suitcases);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':fuel_type', $fuel_type);
        $stmt->bindParam(':avg_consumption', $avg_consumption);
        $stmt->bindParam(':horsepower', $horsepower);
        $stmt->bindParam(':length', $length);
        $stmt->bindParam(':width', $width);
        $stmt->bindParam(':gear_type', $gear_type);
        $stmt->bindParam(':plate_number', $plate_number);
        $stmt->bindParam(':conditions', $conditions);
        $stmt->bindParam(':image1', $image1);
        $stmt->bindParam(':image2', $image2);
        $stmt->bindParam(':image3', $image3);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':return_location_name', $return_location_name);
        $stmt->bindParam(':status', $status); // Bind status parameter

        if ($stmt->execute()) {
            $carID = $pdo->lastInsertId(); // Retrieve car ID after successful insertion
            
            // Handle image uploads after $carID is available
            $image1 = handleFileUpload($_FILES['image1'], $uploadDir, $carID, 1);
            $image2 = handleFileUpload($_FILES['image2'], $uploadDir, $carID, 2);
            $image3 = handleFileUpload($_FILES['image3'], $uploadDir, $carID, 3);

            // Update the record with image filenames
            $stmt_update = $pdo->prepare("UPDATE cars 
                                          SET image1 = :image1, image2 = :image2, image3 = :image3 
                                          WHERE car_id = :carID");
            $stmt_update->bindParam(':image1', $image1);
            $stmt_update->bindParam(':image2', $image2);
            $stmt_update->bindParam(':image3', $image3);
            $stmt_update->bindParam(':carID', $carID);

            if ($stmt_update->execute()) {
                echo "Car successfully added. Car ID: " . $carID;
            } else {
                echo "Error updating image filenames.";
            }
        } else {
            echo "Error inserting data into database.";
        }

    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add A Car</title>
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
    <h2>Add A Car</h2>
    <form action="AddCar.php" method="post" enctype="multipart/form-data">
        <label for="model">Car Model:</label>
        <input type="text" id="model" name="model" required>
        <br>

        <label for="make">Car Make:</label>
        <select id="make" name="make" required>
            <option value="BMW">BMW</option>
            <option value="VW">VW</option>
            <option value="Volvo">Volvo</option>
            <option value="Toyota">Toyota</option>
            <option value="Honda">Honda</option>
            <option value="Mercedes-Benz">Mercedes-Benz</option>
            <option value="Audi">Audi</option>
            <option value="Skoda">Skoda</option>
            <option value="Tesla">Tesla</option>
        </select>
        <br>

        <label for="type">Car Type:</label>
        <select id="type" name="type" required>
            <option value="Van">Van</option>
            <option value="Min-Van">Min-Van</option>
            <option value="Sedan">Sedan</option>
            <option value="SUV">SUV</option>
            <option value="Hatchback">Hatchback</option>
        </select>
        <br>

        <label for="registration_year">Registration Year:</label>
        <input type="number" id="registration_year" name="registration_year" required>
        <br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        <br>

        <label for="price_per_day">Price Per Day:</label>
        <input type="number" id="price_per_day" name="price_per_day" step="0.01" required>
        <br>

        <label for="capacity_people">Capacity (People):</label>
        <input type="number" id="capacity_people" name="capacity_people" required>
        <br>

        <label for="capacity_suitcases">Capacity (Suitcases):</label>
        <input type="number" id="capacity_suitcases" name="capacity_suitcases" required>
        <br>

        <label for="color">Color:</label>
        <input type="text" id="color" name="color" required>
        <br>

        <label for="fuel_type">Fuel Type:</label>
        <select id="fuel_type" name="fuel_type" required>
            <option value="Petrol">Petrol</option>
            <option value="Diesel">Diesel</option>
            <option value="Electric">Electric</option>
        </select>
        <br>

        <label for="avg_consumption">Average Consumption (l/100km):</label>
        <input type="number" id="avg_consumption" name="avg_consumption" step="0.01" required>
        <br>

        <label for="horsepower">Horsepower:</label>
        <input type="number" id="horsepower" name="horsepower" required>
        <br>

        <label for="length">Length (cm):</label>
        <input type="number" id="length" name="length" required>
        <br>

        <label for="width">Width (cm):</label>
        <input type="number" id="width" name="width" required>
        <br>

        <label for="gear_type">Gear Type:</label>
        <select id="gear_type" name="gear_type" required>
            <option value="Automatic">Automatic</option>
            <option value="Manual">Manual</option>
        </select>
        <br>

        <label for="plate_number">Plate Number:</label>
        <input type="text" id="plate_number" name="plate_number" required>
        <br>

        <label for="conditions">Conditions:</label>
        <textarea id="conditions" name="conditions" required></textarea>
        <br>

        <label for="city">City:</label>
        <select id="city" name="city" required>
            <?php foreach ($cities as $city): ?>
                <option value="<?= htmlspecialchars($city) ?>"><?= htmlspecialchars($city) ?></option>
            <?php endforeach; ?>
        </select>
        <br>

        <label for="return_location_name">Return Location:</label>
        <select id="return_location_name" name="return_location_name" required>
            <?php foreach ($returnLocations as $locationOption): ?>
                <option value="<?php echo $locationOption; ?>"><?php echo $locationOption; ?></option>
            <?php endforeach; ?>
        </select>
        <br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="available">Available</option>
            <option value="unavailable">Unavailable</option>
        </select>
        <br>

        <label for="image1">Image 1:</label>
        <input type="file" id="image1" name="image1" required>
        <br>

        <label for="image2">Image 2:</label>
        <input type="file" id="image2" name="image2" required>
        <br>

        <label for="image3">Image 3:</label>
        <input type="file" id="image3" name="image3" required>
        <br>

        <input type="submit" value="Submit">
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
