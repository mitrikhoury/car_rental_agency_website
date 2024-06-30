<?php
session_start();
require 'db.inc.php';

// Function to generate a unique 10-digit customer ID
function generateUniqueCustomerId($pdo) {
    do {
        $user_id = random_int(1000000000, 9999999999); // genarte a random number 
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute(); 
        $count = $stmt->fetchColumn();  // excute a statment to check if the id is unique
    } while ($count > 0);  // if the count grather than 0 then the id is not unique and loop run again to get unique id
    
    return $user_id;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pdo = db_connect();
    $user_id = generateUniqueCustomerId($pdo);

    $stmt = $pdo->prepare("
        INSERT INTO users (
            user_id, name, address, dob, id_number, email, telephone, username, password, role, 
            credit_card_number, credit_card_expiry, credit_card_holder, credit_card_bank
        ) VALUES (
            :user_id, :name, :address, :dob, :id_number, :email, :telephone, :username, :password, 'customer', 
            :credit_card_number, :credit_card_expiry, :credit_card_holder, :credit_card_bank
        )
    ");

    // Bind parameters
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $_SESSION['name'], PDO::PARAM_STR);
    $stmt->bindParam(':address', $_SESSION['address'], PDO::PARAM_STR);
    $stmt->bindParam(':dob', $_SESSION['dob'], PDO::PARAM_STR);
    $stmt->bindParam(':id_number', $_SESSION['id_number'], PDO::PARAM_STR);
    $stmt->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
    $stmt->bindParam(':telephone', $_SESSION['telephone'], PDO::PARAM_STR);
    $stmt->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR);
    $stmt->bindParam(':password', $_SESSION['password'], PDO::PARAM_STR);  
    $stmt->bindParam(':credit_card_number', $_SESSION['credit_card_number'], PDO::PARAM_STR);
    $stmt->bindParam(':credit_card_expiry', $_SESSION['credit_card_expiry'], PDO::PARAM_STR);
    $stmt->bindParam(':credit_card_holder', $_SESSION['credit_card_holder'], PDO::PARAM_STR);
    $stmt->bindParam(':credit_card_bank', $_SESSION['credit_card_bank'], PDO::PARAM_STR);

    // Execute the prepared statement
    $stmt->execute();

    echo "Registration successful! Your customer ID is " . $user_id;
    echo "<br><a href='login.php'>Login</a>";

    session_destroy();  // Clear the session
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Step 3</title>
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
                <a href="logout.php">Logout</a>
                <a href="Search_For_Car.php">Main Page </a>
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
    <form method="post" action="Registration_step_three.php">
        <p>Name: <?php echo $_SESSION['name']; ?></p>
        <p>Address: <?php echo $_SESSION['address']; ?></p>
        <p>Date of Birth: <?php echo $_SESSION['dob']; ?></p>
        <p>ID Number: <?php echo $_SESSION['id_number']; ?></p>
        <p>Email: <?php echo $_SESSION['email']; ?></p>
        <p>Telephone: <?php echo $_SESSION['telephone']; ?></p>
        <p>Credit Card Number: <?php echo $_SESSION['credit_card_number']; ?></p>
        <p>Credit Card Expiry: <?php echo $_SESSION['credit_card_expiry']; ?></p>
        <p>Credit Card Holder: <?php echo $_SESSION['credit_card_holder']; ?></p>
        <p>Credit Card Bank: <?php echo $_SESSION['credit_card_bank']; ?></p>
        <p>Username: <?php echo $_SESSION['username']; ?></p>
        <button type="submit">Confirm</button>
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
