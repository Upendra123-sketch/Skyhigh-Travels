<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "travel_booking");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get Booking ID
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Update the booking status to "Confirmed"
    $stmt = $conn->prepare("UPDATE bookings SET status = 'Confirmed' WHERE booking_id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $stmt->close();
}

// Redirect back to Admin Dashboard
header("Location: admin_dashboard.php");
exit();
?>
