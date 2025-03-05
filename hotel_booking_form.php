<?php
session_start();
require 'db_connect.php';

if (!isset($_GET['hotel_id']) || !isset($_GET['city_id'])) {
    header("Location: home.php");
    exit();
}

$hotel_id = $_GET['hotel_id'];
$city_id = $_GET['city_id'];

// Fetch hotel details
$hotelQuery = $conn->prepare("SELECT hotel_name FROM hotels WHERE hotel_id = ?");
$hotelQuery->bind_param("i", $hotel_id);
$hotelQuery->execute();
$hotelResult = $hotelQuery->get_result();

if ($hotelResult->num_rows == 0) {
    echo "<p>Hotel not found.</p>";
    exit();
}

$hotel = $hotelResult->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Hotel - <?php echo htmlspecialchars($hotel['hotel_name']); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Book <?php echo htmlspecialchars($hotel['hotel_name']); ?></h1>
        <form action="process_hotel_booking.php" method="POST">
            <input type="hidden" name="hotel_id" value="<?php echo $hotel_id; ?>">
            <input type="hidden" name="city_id" value="<?php echo $city_id; ?>">
            
            <label for="name">Full Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone" required>

            <label for="checkin">Check-in Date:</label>
            <input type="date" name="checkin" id="checkin" required>

            <label for="checkout">Check-out Date:</label>
            <input type="date" name="checkout" id="checkout" required>

            <button type="submit" class="btn">Confirm Booking</button>
        </form>
    </div>
</body>
</html>
