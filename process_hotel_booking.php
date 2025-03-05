<?php
session_start();
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hotel_id = $_POST['hotel_id'];
    $city_id = $_POST['city_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];

    // Insert booking into the database
    $query = $conn->prepare("INSERT INTO bookings (hotel_id, city_id, name, email, phone, checkin_date, checkout_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $query->bind_param("iisssss", $hotel_id, $city_id, $name, $email, $phone, $checkin, $checkout);

    if ($query->execute()) {
        // Get the last inserted booking ID
        $booking_id = $conn->insert_id;
        
        // Redirect to travel booking page with booking_id
        header("Location: travel_booking.php?booking_id=" . $booking_id);
        exit();
    } else {
        echo "<p>Booking failed. Please try again.</p>";
    }
} else {
    header("Location: home.php");
    exit();
}
?>
