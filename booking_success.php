<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "travel_booking");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve booking ID from URL
if (!isset($_GET['booking_id'])) {
    die("Invalid booking request.");
}
$booking_id = intval($_GET['booking_id']);

// Fetch booking details
$booking_query = $conn->query("SELECT * FROM bookings WHERE booking_id = $booking_id");
$booking = $booking_query->fetch_assoc() ?? die("Booking not found.");

// Fetch city details
$city_query = $conn->query("SELECT * FROM cities WHERE city_id = {$booking['city_id']}");
$city = $city_query->fetch_assoc();

// Fetch hotel details
$hotel_query = $conn->query("SELECT * FROM hotels WHERE hotel_id = {$booking['hotel_id']}");
$hotel = $hotel_query->fetch_assoc();

// Fetch travel details
$travel_query = $conn->query("SELECT * FROM travel_options WHERE travel_id = {$booking['travel_id']}");
$travel = $travel_query->fetch_assoc();

// Fetch meal plan details
$meal_query = $conn->query("SELECT * FROM meal_plans WHERE meal_id = {$booking['meal_id']}");
$meal = $meal_query->fetch_assoc();

// Fetch selected activities
$activity_list = [];
if (!empty($booking['activities'])) {
    $activity_ids = $booking['activities'];
    $activity_query = $conn->query("SELECT * FROM activities WHERE activity_id IN ($activity_ids)");
    while ($row = $activity_query->fetch_assoc()) {
        $activity_list[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed - TravelScapes</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; text-align: center; }
        .container { width: 60%; margin: auto; background: white; padding: 20px; box-shadow: 0px 0px 10px gray; border-radius: 8px; margin-top: 20px; text-align: left; }
        h2 { color: #28a745; }
        img { width: 100%; height: auto; border-radius: 5px; }
        .section { padding: 10px; border-bottom: 1px solid #ddd; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; font-size: 16px; border-radius: 5px; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>

    <div class="container">
        <h1>Booking Confirmed! 🎉</h1>
        <h2>Your Booking ID: <?php echo $booking_id; ?></h2>

        <div class="section">
            <h2>Destination: <?php echo $city['name']; ?></h2>
            <p><?php echo $city['description']; ?></p>
            <img src="images/<?php echo strtolower($city['name']); ?>.jpg" alt="<?php echo $city['name']; ?>">
        </div>

        <div class="section">
            <h2>Hotel Details</h2>
            <p><strong><?php echo $hotel['name']; ?></strong></p>
            <p>Category: <?php echo $hotel['category']; ?>-Star</p>
            <p>Price per Night: ₹<?php echo number_format($hotel['price_per_night'], 2); ?></p>
        </div>

        <div class="section">
            <h2>Travel Details</h2>
            <p>Mode: <?php echo $travel['mode']; ?></p>
            <p>Departure: <?php echo $travel['departure']; ?></p>
            <p>Arrival: <?php echo $travel['arrival']; ?></p>
            <p>Price: ₹<?php echo number_format($travel['price'], 2); ?></p>
        </div>

        <div class="section">
            <h2>Meal Plan</h2>
            <p><?php echo $meal['name']; ?> - ₹<?php echo number_format($meal['price_per_night'], 2); ?> per night</p>
        </div>

        <div class="section">
            <h2>Activities</h2>
            <?php if (!empty($activity_list)) { ?>
                <ul>
                    <?php foreach ($activity_list as $activity) { ?>
                        <li><?php echo $activity['name']; ?> - ₹<?php echo number_format($activity['price'], 2); ?></li>
                    <?php } ?>
                </ul>
            <?php } else { ?>
                <p>No activities selected.</p>
            <?php } ?>
        </div>

        <h2>Total Price: ₹<?php echo number_format($booking['total_price'], 2); ?></h2>
        <p><strong>Payment Mode: Cash on Arrival</strong></p>

        <button onclick="window.location.href='index.php'">Go to Homepage</button>
    </div>

</body>
</html>

<?php
$conn->close();
?>
