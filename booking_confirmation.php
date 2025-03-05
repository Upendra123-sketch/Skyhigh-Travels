<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "travel_booking");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve booking details using the booking ID
$booking_id = $_GET['booking_id'];

$stmt = $conn->prepare("SELECT * FROM bookings WHERE booking_id = ?");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

if (!$booking) {
    die("Booking not found.");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
</head>
<body>

<h1>Booking Confirmation</h1>

<p><strong>City:</strong> <?php echo $booking['city_id']; ?></p>
<p><strong>Hotel:</strong> <?php echo $booking['hotel_id']; ?></p>
<p><strong>Travel Type:</strong> <?php echo $booking['travel_option_id']; ?></p>
<p><strong>Meal Plan:</strong> <?php echo $booking['meal_id']; ?></p>
<p><strong>Total Price:</strong> ₹<?php echo $booking['total_price']; ?></p>
<p><strong>Booking Date:</strong> <?php echo $booking['booking_date']; ?></p>
<p><strong>Status:</strong> <?php echo $booking['status']; ?></p>
<p><strong>Travel Date:</strong> <?php echo $booking['travel_date']; ?></p>
<p><strong>Return Date:</strong> <?php echo $booking['return_date']; ?></p>
<p><strong>Nights:</strong> <?php echo $booking['nights']; ?></p>
<p><strong>Full Name:</strong> <?php echo $booking['full_name']; ?></p>
<p><strong>Email:</strong> <?php echo $booking['email']; ?></p>
<p><strong>Phone:</strong> <?php echo $booking['phone']; ?></p>

</body>
</html>
