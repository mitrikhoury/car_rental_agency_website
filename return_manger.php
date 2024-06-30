<?php
session_start();
require_once('db.inc.php');

try {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    $pdo = db_connect();
    // Check if user is a manager (assuming 'manager' role based on your previous data)
    if ($_SESSION['role'] !== 'manager') {
        echo "Access denied. You are not authorized to view this page.";
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['car_id']) && isset($_POST['new_status'])) {
        $car_id = $_POST['car_id'];
        $new_status = $_POST['new_status'];

      
        // Update car status
        $stmt = $pdo->prepare("UPDATE cars SET status = :new_status WHERE car_id = :car_id");
        $stmt->bindParam(':new_status', $new_status, PDO::PARAM_STR);
        $stmt->bindParam(':car_id', $car_id, PDO::PARAM_INT);
        $stmt->execute();

       
    } 

    

    // Fetch returning cars with customer names
    $stmt = $pdo->prepare("
        SELECT c.car_id, c.model, c.make, c.type, u.name AS customer_name FROM cars c
        INNER JOIN rentals r ON c.car_id = r.car_id
        INNER JOIN users u ON r.user_id = u.user_id
        WHERE c.status = 'returning'
    ");
    $stmt->execute();
    $returning_cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
    exit(); // Stop execution in case of error
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manager View Returning Cars</title>
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
    <h2>Returning Cars List</h2>
    <table class="return_manger_table">
        <thead>
            <tr>
                <th>Car ID</th>
                <th>Model</th>
                <th>Make</th>
                <th>Type</th>
                <th>Customer Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($returning_cars as $car): ?>
                <tr>
                    <td><?php echo $car['car_id']; ?></td>
                    <td><?php echo $car['model']; ?></td>
                    <td><?php echo $car['make']; ?></td>
                    <td><?php echo $car['type']; ?></td>
                    <td><?php echo $car['customer_name']; ?></td>
                    <td class="action-column">
                        <form action="return_manger.php" method="post" class="form_table">
                            <input type="hidden" name="car_id" value="<?php echo $car['car_id']; ?>">
                            <input type="hidden" name="new_status" value="available">
                            <button type="submit">Change Status to Available</button>
                        </form>
                    </td>
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
