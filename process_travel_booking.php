<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];  // Get the logged-in user ID
$city_id = $_POST['city_id'];
$travel_mode = $_POST['travel_mode'];
$travel_date = $_POST['travel_date'];
$travel_class = $_POST['travel_class'];
$meal_preference = $_POST['meal_preference'];
$extra_luggage = $_POST['extra_luggage'];

// Check if the selected city exists in the cities table
$cityQuery = $conn->prepare("SELECT city_id FROM cities WHERE city_id = ?");
$cityQuery->bind_param("i", $city_id);
$cityQuery->execute();
$cityResult = $cityQuery->get_result();

if ($cityResult->num_rows == 0) {
    echo "City ID does not exist. Please select a valid city.";
    exit();
}

// Insert travel booking data into the travel_bookings table
$query = "INSERT INTO travel_bookings (user_id, city_id, travel_mode, travel_date, travel_class, meal_preference, extra_luggage) 
          VALUES (?, ?, ?, ?, ?, ?, ?)";

// Prepare the statement
$stmt = $conn->prepare($query);

if ($stmt === false) {
    echo "Error preparing the statement: " . $conn->error;
    exit();
}

// Bind the parameters
$stmt->bind_param("issssss", $user_id, $city_id, $travel_mode, $travel_date, $travel_class, $meal_preference, $extra_luggage);

// Execute the query
if ($stmt->execute()) {
    // Redirect to the confirmation page
    header("Location: confirmation.php");
    exit();
} else {
    echo "Error executing the query: " . $stmt->error;
}

$stmt->close();
?>
