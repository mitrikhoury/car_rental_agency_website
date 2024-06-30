<?php
require_once('db.inc.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php?redirect=rent_invoice.php");
    exit();
}

$returnLocations = [];
$pdo = db_connect();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

try {
   
    $stmt = $pdo->query("SELECT return_location_name FROM locations");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $returnLocations[] = $row['return_location_name'];
    }   // Fetch return locations
} catch (PDOException $e) {
    echo 'Error fetching return locations: ' . $e->getMessage();
}


$car_id = $_SESSION['car_info']['car_id'] ?? '';
$model = $_SESSION['car_info']['model'] ?? '';
$description = $_SESSION['car_info']['description'] ?? '';
$rental_to = $_SESSION['car_info']['rental_to'] ?? '';
$rental_from = $_SESSION['car_info']['rental_from'] ?? '';
$total_price = $_SESSION['car_info']['total_price'] ?? '';
$pickup_location = $_SESSION['car_info']['Pick_up_location'] ?? '';
$special_requirements = $_SESSION['rental_info']['special_requirements'] ?? [];
$insurance = $_SESSION['rental_info']['insurance'] ?? '';// Retrieve data from session

$from_date = new DateTime($rental_from);
$to_date = new DateTime($rental_to);
$rental_period = $from_date->format('d-m-Y') . ' to ' . $to_date->format('d-m-Y');

// Additional costs
$extra_costs = [
    'Return to a Different Location' => 30,
    'Baby Seats' => 15,
    'insurance' => 50
];


foreach ($special_requirements as $requirement) {
    if (isset($extra_costs[$requirement])) {
        $total_price += $extra_costs[$requirement];
    }
}

if ($insurance == 'insurance') {
    $total_price += $extra_costs['insurance'];
}
// Calculate additional costs


if ($_SERVER['REQUEST_METHOD'] == 'POST') {   // Process form submission
    // Validate Credit Card Information
    $credit_card_number = $_POST['credit_card_number'] ?? '';
    $expiration_date = $_POST['expiration_date'] ?? '';
    $credit_card_holder = $_POST['card_holder_name'] ?? '';
    $credit_card_bank = $_POST['bank_issued'] ?? '';
    $credit_card_type = $_POST['credit_card_type'] ?? '';
    $location_new = $_POST['return_location'] ?? '';    //  for check yes or no 
    $return_location_name1 = $_POST['return_location_name1'] ?? ''; // get the city select

    $_SESSION['location_new'] = $location_new;
    $_SESSION['location_new_city'] = $return_location_name1;

    
    $rental_id = mt_rand(1000000000, 9999999999); //generate invoice ID

    // Fetch user's stored credit card information
    $stmt = $pdo->prepare("SELECT credit_card_number, credit_card_expiry, credit_card_holder, credit_card_bank FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user_credit_card = $stmt->fetch(PDO::FETCH_ASSOC);

    // Validate credit card details
    if (
        $credit_card_number != $user_credit_card['credit_card_number'] ||
        $credit_card_holder != $user_credit_card['credit_card_holder'] ||
        $credit_card_bank != $user_credit_card['credit_card_bank']
    ) {
        echo "Credit card details do not match. Please check your information.";
        exit();
    }

    // Check if return location is different and update if needed
    if ($location_new == 'yes' && !empty($return_location_name1)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM locations WHERE return_location_name = ?");
        $stmt->execute([$return_location_name1]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {  
            try {
                $stmt1 = $pdo->prepare("UPDATE cars SET return_location_name = :return_location_name WHERE car_id = :car_id");// set the new return location (update)
                $stmt1->bindParam(':return_location_name', $return_location_name1, PDO::PARAM_STR); // bind paremeters
                $stmt1->bindParam(':car_id', $car_id, PDO::PARAM_INT); // execute the statement
                $stmt1->execute();

                $stmt2 = $pdo->prepare("UPDATE cars SET city = :return_location_name WHERE car_id = :car_id");  // set the new pick up location (update)
                $stmt2->bindParam(':return_location_name', $return_location_name1, PDO::PARAM_STR);
                $stmt2->bindParam(':car_id', $car_id, PDO::PARAM_INT);  // bind paremeters
                $stmt2->execute();   // execute the statement

                
                $_SESSION['car_info']['Pick_up_location'] = $return_location_name1;  // update the session pick up date 
                $pickup_location = $return_location_name1; // Update current variable too
            } catch (PDOException $e) {
                echo 'Error updating pickup location (city) in cars table: ' . $e->getMessage();
                exit();
            }
        } else {
            echo "Invalid return location specified: " . $return_location_name1;
            exit();
        }
    }


    $rental_date = date('Y-m-d', strtotime($rental_from));
    $return_date = date('Y-m-d', strtotime($rental_to));  // get the dates

    try {
        if (isset($rental_id)) {  // check if the system genarete the nubmer
            $_SESSION['invoice_id'] = $rental_id;   // store it in session to appear in step three 
    
            $stmt = $pdo->prepare("INSERT INTO rentals (rental_id, user_id, car_id, rental_date, return_date, invoice_date) VALUES (:rental_id, :user_id, :car_id, :rental_date, :return_date, :invoice_date)");
    
            $stmt->bindParam(':rental_id', $rental_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':car_id', $car_id, PDO::PARAM_INT);
            $stmt->bindParam(':rental_date', $rental_date);
            $stmt->bindParam(':return_date', $return_date);  // bind the parameters 
    
            
            $invoice_date = date('Y-m-d'); //  the data of the process
            $stmt->bindParam(':invoice_date', $invoice_date); // bind it 

            $stmt->execute();  // execute the statement 

            $status='rented';
            $stmt2 = $pdo->prepare("UPDATE cars SET status = :status WHERE car_id = :car_id");  // to set the status of the car to rented
            $stmt2->bindParam(':status',$status,PDO::PARAM_STR);
            $stmt2->bindParam(':car_id', $car_id, PDO::PARAM_INT);
            $stmt2->execute();
    
            echo 'Invoice ID: ' . $rental_id;
            echo 'Session Invoice ID: ' . $_SESSION['invoice_id'];
    
            header("Location: Step_3_Rent.php");  // redirect to the step three
            exit();  
        }
    } catch (PDOException $e) {
        echo 'Error inserting rental information: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent Invoice</title>
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
                <div class="user-profile">
                    <span>Username: <?= htmlspecialchars($_SESSION['username']) ?></span>
                </div>
                <a href="shopping_basket.html">Basket</a>
                <a href="logout.php">Logout</a>
                <a href="Search_For_Car.php">Main Page</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="invoice">
            <h2>Invoice</h2>
            <h3>Car Details</h3>
            <div>
                <p><strong>Model:</strong> <?= htmlspecialchars($model) ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($description) ?></p>
                <p><strong>Rental Period:</strong> <?= htmlspecialchars($rental_period) ?></p>
                <p><strong>Pickup Location:</strong> <?= htmlspecialchars($pickup_location) ?></p>
                <p><strong>Total Price:</strong> <?= htmlspecialchars($total_price) ?></p>
            </div>

            <h3>Special Requirements</h3>
            <div class="special-requirements">
                <?php if (empty($special_requirements)) : ?>
                    <p>No special requirements selected.</p>
                <?php else : ?>
                    <ul>
                        <?php foreach ($special_requirements as $requirement) : ?>
                            <li><?= htmlspecialchars($requirement) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <h3>Insurance</h3>
            <div class="insurance">
                <p><?= ($insurance == 'insurance') ? 'Included' : 'Not included' ?></p>
            </div>

            <h3>Credit Card Information</h3>
            <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
                <div class="credit-card-info">
                    <label for="credit_card_number">Credit Card Number:</label>
                    <input type="text" id="credit_card_number" name="credit_card_number" required><br><br>

                    <label for="expiration_date">Expiration Date:</label>
                    <input type="month" id="expiration_date" name="expiration_date" required><br><br>

                    <label for="card_holder_name">Card Holder Name:</label>
                    <input type="text" id="card_holder_name" name="card_holder_name" required><br><br>

                    <label for="bank_issued">Bank Issued:</label>
                    <input type="text" id="bank_issued" name="bank_issued" required><br><br>

                    <label for="credit_card_type">Card Type:</label>
                    <select id="credit_card_type" name="credit_card_type" required>
                        <option value="Visa">Visa</option>
                        <option value="MasterCard">MasterCard</option>
                        <option value="American Express">American Express</option>
                    </select><br><br>
                </div>

                <h3>Return Location</h3>
                <div class="return-location">
                    <label for="return_location">Return to a Different Location?</label>
                    <select id="return_location" name="return_location">
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select><br><br>

                    <label for="return_location_name1">New Return Location:</label>
                    <select id="return_location_name1" name="return_location_name1">
                        <?php foreach ($returnLocations as $location) : ?>
                            <option value="<?= htmlspecialchars($location) ?>"><?= htmlspecialchars($location) ?></option>
                        <?php endforeach; ?>
                    </select><br><br>
                </div>

                <div>
                    <input type="submit" value="Confirm Rental" name="confirm_rental">
                </div>
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
