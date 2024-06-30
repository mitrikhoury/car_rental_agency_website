<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['address'] = $_POST['address'];
    $_SESSION['dob'] = $_POST['dob'];
    $_SESSION['id_number'] = $_POST['id_number'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['telephone'] = $_POST['telephone'];
    $_SESSION['credit_card_number'] = $_POST['credit_card_number'];
    $_SESSION['credit_card_expiry'] = $_POST['credit_card_expiry'];
    $_SESSION['credit_card_holder'] = $_POST['credit_card_holder'];
    $_SESSION['credit_card_bank'] = $_POST['credit_card_bank'];

    header("Location: Registration_step_two.php");  // this header used to redirect the user to a different page after a form submission
    exit();  // termenat the scrpit after sending the data and user to dtep two script 
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Step 1</title>
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
    <form method="post" action="Registration_step_one.php">
        <label>Name: <input type="text" name="name" required></label><br>
        <label>Address: <input type="text" name="address" required></label><br>
        <label>Date of Birth: <input type="date" name="dob" required></label><br>
        <label>ID Number: <input type="text" name="id_number" required></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <label>Telephone: <input type="text" name="telephone" required></label><br>
        <label>Credit Card Number: <input type="text" name="credit_card_number" required></label><br>
        <label>Credit Card Expiry: <input type="date" name="credit_card_expiry" required></label><br>
        <label>Credit Card Holder: <input type="text" name="credit_card_holder" required></label><br>
        <label>Credit Card Bank: <input type="text" name="credit_card_bank" required></label><br>
        <button type="submit">Next</button>
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
