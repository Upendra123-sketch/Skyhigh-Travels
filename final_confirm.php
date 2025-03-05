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

// Fetch booking details
$user_id = $_SESSION["user_id"];
$booking_query = $conn->query("SELECT * FROM bookings WHERE user_id = '$user_id' ORDER BY booking_id DESC LIMIT 1");
$booking = $booking_query->fetch_assoc();

if (!$booking) {
    die("No booking found.");
}

$city_id = $booking['city_id'];
$hotel_id = $booking['hotel_id'];
$travel_id = $booking['travel_id'];
$meal_id = $booking['meal_id'];
$total_price = $booking['total_price'];

// Fetch related details
$city_query = $conn->query("SELECT * FROM cities WHERE city_id = $city_id");
$city = $city_query->fetch_assoc();
$hotel_query = $conn->query("SELECT * FROM hotels WHERE hotel_id = $hotel_id");
$hotel = $hotel_query->fetch_assoc();
$travel_query = $conn->query("SELECT * FROM travel_options WHERE travel_id = $travel_id");
$travel = $travel_query->fetch_assoc();
$meal_query = $conn->query("SELECT * FROM meal_plans WHERE meal_id = $meal_id");
$meal = $meal_query->fetch_assoc();

// Fetch selected activities
$activities_query = $conn->query("SELECT * FROM activities WHERE activity_id IN (SELECT activity_id FROM booking_activities WHERE booking_id = '{$booking['booking_id']}')");
$activities = [];
while ($activity = $activities_query->fetch_assoc()) {
    $activities[] = $activity;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - TravelScapes</title>
    <style>
        /* Your CSS styles */
    </style>
</head>
<body>

    <div class="container">
        <h1>Your Booking Confirmation</h1>

        <div class="section">
            <h2>Booking Details</h2>
            <p><strong>Booking ID:</strong> <?php echo $booking['booking_id']; ?></p>
            <p><strong>Payment Method:</strong> <?php echo $booking['payment_method'] == 'online' ? 'Online Payment' : 'Cash on Arrival'; ?></p>
        </div>

        <div class="section">
            <h2>Destination: <?php echo $city['name']; ?></h2>
            <p><?php echo $city['description']; ?></p>
            <img src="images/<?php echo strtolower($city['name']); ?>.jpg" alt="<?php echo $city['name']; ?>">
        </div>

        <div class="section">
            <h2>Hotel Details</h2>
            <p><strong><?php echo $hotel['name']; ?></strong></p>
            <p>Category: <?php echo $hotel['star_rating']; ?> Star</p>
            <p>Amenities: <?php echo $hotel['amenities']; ?></p>
            <p>Price per Night: ₹<?php echo number_format($hotel['price_per_night'], 2); ?></p>
        </div>

        <div class="section">
            <h2>Travel Details</h2>
            <p>Mode: <?php echo $travel['company_name']; ?></p>
            <p>Type: <?php echo $travel['type']; ?></p>
            <p>Departure: <?php echo $travel['departure']; ?></p>
            <p>Arrival: <?php echo $travel['arrival']; ?></p>
            <p>Price: ₹<?php echo number_format($travel['price'], 2); ?></p>
        </div>

        <div class="section">
            <h2>Meal Plan</h2>
            <p><?php echo $meal['name']; ?> - ₹<?php echo number_format($meal['price_per_night'], 2); ?> per night</p>
        </div>

        <div class="section">
            <h2>Activities Selected</h2>
            <?php if (!empty($activities)) { ?>
                <ul>
                    <?php foreach ($activities as $activity) { ?>
                        <li><?php echo $activity['name']; ?> - ₹<?php echo number_format($activity['price'], 2); ?></li>
                    <?php } ?>
                </ul>
            <?php } else { ?>
                <p>No activities selected</p>
            <?php } ?>
        </div>

        <div class="section">
            <h2>Total Price: ₹<?php echo number_format($total_price, 2); ?></h2>
        </div>

        <div class="section">
            <p>Thank you for booking with TravelScapes! We hope you have a wonderful trip!</p>
        </div>

    </div>

</body>
</html>

<?php
$conn->close();
?>
