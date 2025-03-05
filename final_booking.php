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

$user_id = $_SESSION["user_id"];
$city_id = intval($_POST['city_id']);
$hotel_id = intval($_POST['hotel_id']);
$travel_id = intval($_POST['travel_id']);
$meal_id = intval($_POST['meal_id']);
$travel_date = $_POST['travel_date'];
$return_date = $_POST['return_date'];
$nights = intval($_POST['nights']);
$total_price = floatval($_POST['total_price']);
$activity_ids = isset($_POST['selected_activities']) ? $_POST['selected_activities'] : "";
$payment_method = $_POST["payment_method"]; // This is the new value you're capturing

// Validate input
if (empty($city_id) || empty($hotel_id) || empty($travel_id) || empty($meal_id) || empty($travel_date) || empty($return_date) || empty($nights) || empty($total_price)) {
    die("Error: Missing required booking details.");
}

// Retrieve user details
$user_query = $conn->query("SELECT name, email, phone FROM users WHERE user_id = $user_id");
$user = $user_query->fetch_assoc();
if (!$user) {
    die("Error: User details not found.");
}

// Insert booking into database
$stmt = $conn->prepare("INSERT INTO bookings (user_id, city_id, hotel_id, travel_id, meal_id, travel_date, return_date, nights, total_price, status, full_name, email, phone, payment_method) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', ?, ?, ?, ?)");
$stmt->bind_param("iiiiissidssss", $user_id, $city_id, $hotel_id, $travel_id, $meal_id, $travel_date, $return_date, $nights, $total_price, $user['name'], $user['email'], $user['phone'], $payment_method);

if ($stmt->execute()) {
    echo "<h2>Booking Confirmed!</h2>";
    echo "<p>Thank you, " . htmlspecialchars($user['name']) . ". Your booking has been successfully placed.</p>";
    echo "<p>Total Amount: ₹" . number_format($total_price, 2) . "</p>";
    echo "<p><strong>Payment Mode: " . htmlspecialchars($payment_method === 'pay_online' ? 'Pay Online' : 'Cash on Arrival') . "</strong></p>";
} else {
    echo "<p>Error: " . $stmt->error . "</p>";  // This will show any error with the query
}

$stmt->close();
$conn->close();
?>

<style>
    body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #e8f5e9;
    text-align: center;
    margin: 0;
    padding: 0;
}

.container {
    width: 70%;
    margin: 50px auto;
    background: linear-gradient(to bottom, #ffffff, #c8e6c9);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.15);
    text-align: left;
    transition: transform 0.3s ease;
}

.container:hover {
    transform: scale(1.02);
}

h1 {
    font-size: 32px;
    margin-bottom: 20px;
    color: #388e3c;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
}

h2 {
    font-size: 24px;
    font-weight: bold;
    color: #388e3c;
    margin-top: 20px;
}

p {
    font-size: 18px;
    color: #388e3c;
}

strong {
    font-size: 20px;
}

.section {
    margin-bottom: 25px;
    padding: 15px;
    border-bottom: 1px solid #ddd;
}

button {
    width: 100%;
    padding: 12px;
    background-color: #28a745;
    color: white;
    font-weight: bold;
    border: none;
    cursor: pointer;
    font-size: 18px;
    border-radius: 8px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

button:hover {
    background-color: #218838;
    transform: translateY(-2px);
}

button:active {
    background-color: #1e7e34;
    transform: translateY(1px);
}

a {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 20px;
    text-decoration: none;
    color: #fff;
    background-color: #00796b;
    font-size: 18px;
    border-radius: 8px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

a:hover {
    background-color: #004d40;
    transform: translateY(-2px);
}

a:active {
    background-color: #003d33;
    transform: translateY(1px);
}

input[type="hidden"] {
    display: none;
}

label {
    font-size: 18px;
    font-weight: bold;
    color: #388e3c;
    text-align: left;
    margin-bottom: 10px;
}

@media (max-width: 768px) {
    .container {
        width: 90%;
        padding: 20px;
    }

    h1 {
        font-size: 28px;
    }

    h2 {
        font-size: 20px;
    }

    p {
        font-size: 16px;
    }

    button {
        font-size: 16px;
    }

    a {
        font-size: 16px;
    }
}

</style>
