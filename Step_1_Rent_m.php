<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php?redirect=Step_1_Rent_m.php");
    exit();
}


$car_id = $_SESSION['car_info']['car_id'] ?? '';
$model = $_SESSION['car_info']['model'] ?? '';
$description = $_SESSION['car_info']['description'] ?? '';
$rental_to = $_SESSION['car_info']['rental_to'] ?? '';
$rental_from = $_SESSION['car_info']['rental_from'] ?? '';
$total_price = $_SESSION['car_info']['total_price'] ?? '';
$pickup_location = $_SESSION['car_info']['Pick_up_location'] ?? ''; // Retrieve car details from session


$from_date = new DateTime($rental_from);
$to_date = new DateTime($rental_to);
$rental_period = $from_date->format('d-m-Y') . ' to ' . $to_date->format('d-m-Y');// Calculate rental period

$errors = []; // Array to store validation errors


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rent_form_submit'])) {// Handle form submission only if form is submitted
    // Retrieve special requirements and insurance from form submission
    $special_requirements = $_POST['special_requirements'] ?? [];
    $insurance = isset($_POST['insurance']) ? $_POST['insurance'] : '';

    $_SESSION['rental_info']['special_requirements'] = $special_requirements;
    $_SESSION['rental_info']['return_location'] = in_array('Return to a Different Location', $special_requirements) ? 'yes' : 'no';
    $_SESSION['rental_info']['insurance'] = $insurance;// Store in session for Step 2

   
    header("Location: Step_2_Rent.php");  // Redirect to Step 2 
    exit();
}

$temp_special_requirements = $_SESSION['rental_info']['special_requirements'] ?? [];
$temp_insurance = $_SESSION['rental_info']['insurance'] ?? '';    // Retrieve previously selected options

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent a Car</title>
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
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <div class="user-profile">
                        <span>Username: <?= htmlspecialchars($_SESSION['username']) ?></span>
                    </div>
                    <a href="shopping_basket.html">Basket</a>
                    <a href="logout.php">Logout</a>
                    <a href="Search_For_Car.php">Main Page</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="renting-form">
            <h2>Rent a Car</h2>
            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                <input type="hidden" name="rent_form_submit" value="1"> 
                <h3>Car Details</h3>
                <p><strong>Car Reference Number:</strong> <?= htmlspecialchars($car_id) ?></p>
                <p><strong>Car Model:</strong> <?= htmlspecialchars($model) ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($description) ?></p>
                <p><strong>Rental Period:</strong> <?= htmlspecialchars($rental_period) ?></p>
                <p><strong>Pickup Location:</strong> <?= htmlspecialchars($pickup_location) ?></p>
                <p><strong>Total Rent Amount:</strong> <?= htmlspecialchars($total_price) ?></p>

                <h3>Additional Options</h3>
                <label><input type="checkbox" name="special_requirements[]" value="Return to a Different Location" <?= in_array('Return to a Different Location', $temp_special_requirements) ? 'checked' : '' ?>> Return to a Different Location</label><br>
                <label><input type="checkbox" name="special_requirements[]" value="Baby Seats" <?= in_array('Baby Seats', $temp_special_requirements) ? 'checked' : '' ?>> Baby Seats</label><br>
                <label for="insurance">Insurance:</label>
                <input type="checkbox" id="insurance" name="insurance" value="insurance" <?= $temp_insurance ? 'checked' : '' ?>><br><br>

                <button type="submit">Next Step</button>
                <p>Note: Return to a Different Location extra cost 30$, Baby Seats extra cost 15$, insurance extra cost 50$</p>
            </form>
        </div>
    </div>

    <footer>
        <img src="carsImages/carRental_logo.jpg" alt="Small Logo" class="small-logo">
        <p>© 2023 Car Rental Agency. All rights reserved.</p>
        <p>Address: der al rom , Ramallah, palestine </p>
        <p>Email: mitkhoury@gmail.com | Phone: 0597516680</p>
        <a href="contact_us.php">Contact Us</a>
    </footer>
</body>

</html>
