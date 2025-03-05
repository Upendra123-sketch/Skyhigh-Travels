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

// Get POST data from the form
$package_id = $_POST['package_id'];
$city_id = $_POST['city_id'];
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$travel_date = $_POST['travel_date'];
$return_date = $_POST['return_date'];

// Fetch package details
$stmt = $conn->prepare("SELECT * FROM fixed_packages WHERE package_id = ?");
$stmt->bind_param("i", $package_id);
$stmt->execute();
$result = $stmt->get_result();
$package = $result->fetch_assoc();
$stmt->close();

// Fetch user data from session
$user_id = $_SESSION["user_id"];

// Ensure numeric values are passed correctly
$total_price = $package['total_price'];
$hotel_id = $package['hotel_id'];
$travel_id = $package['travel_id'];  // Correctly fetch travel_id
$duration = $package['duration'];

// Prepare to insert booking data
$stmt = $conn->prepare("INSERT INTO bookings (user_id, city_id, hotel_id, travel_option_id, travel_id, total_price, status, booking_date, full_name, email, phone, meal_id, travel_date, return_date, nights, package_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
if ($stmt === false) {
    die('Error preparing statement: ' . $conn->error);
}

// Bind the parameters correctly by passing variables by reference
$travel_option_id = 0;  // Assuming no specific travel option for fixed packages
$status = 'Pending';
$booking_date = date('Y-m-d H:i:s');  // Current timestamp
$meal_id = 1;  // Default meal option ID (you can change this if necessary)

$stmt->bind_param(
    "iiiiidsssssiisii", 
    $user_id, 
    $city_id, 
    $hotel_id, 
    $travel_option_id, 
    $travel_id, 
    $total_price, 
    $status, 
    $booking_date, 
    $full_name, 
    $email, 
    $phone, 
    $meal_id, 
    $travel_date, 
    $return_date, 
    $duration, 
    $package_id
);

// Execute the query
if ($stmt->execute()) {
    echo "Booking confirmed successfully!";
    // Redirect to a confirmation page (optional)
    header("Location: confirmation.php?booking_id=" . $stmt->insert_id);
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
