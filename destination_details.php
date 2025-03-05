<?php
session_start();
require 'db_connect.php'; // Database connection

if (!isset($_GET['city_id'])) {
    header("Location: home.php"); // Redirect if no city selected
    exit();
}

$city_id = $_GET['city_id'];

// Fetch city details and basic itinerary
$cityQuery = $conn->prepare("SELECT city_name, description, itinerary FROM cities WHERE city_id = ?");
$cityQuery->bind_param("i", $city_id);
$cityQuery->execute();
$cityResult = $cityQuery->get_result();
$city = $cityResult->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($city['city_name']); ?> - Destination Details</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your CSS file -->
</head>
<body>
    <div class="container">
        <h1>Explore <?php echo htmlspecialchars($city['city_name']); ?></h1>
        <p><?php echo htmlspecialchars($city['description']); ?></p>
        
        <h2>What to Explore</h2>
        <p><?php echo nl2br(htmlspecialchars($city['itinerary'])); ?></p>
        
        <div class="explore-section">
            <h2>Must-Visit Attractions</h2>
            <div class="attractions">
                <div class="attraction-card">
                    <img src="images/<?php echo strtolower($city['city_name']); ?>_monument.jpg" alt="Famous Monument">
                    <h3>Historic Sites</h3>
                    <p>Explore the rich history and heritage of <?php echo htmlspecialchars($city['city_name']); ?>.</p>
                </div>
                <div class="attraction-card">
                    <img src="images/<?php echo strtolower($city['city_name']); ?>_food.jpg" alt="Famous Food">
                    <h3>Local Cuisine</h3>
                    <p>Indulge in the famous local dishes and street food specialties.</p>
                </div>
                <div class="attraction-card">
                    <img src="images/<?php echo strtolower($city['city_name']); ?>_fun.jpg" alt="Fun Activities">
                    <h3>Adventure & Fun</h3>
                    <p>Enjoy thrilling activities, entertainment, and nightlife.</p>
                </div>
            </div>
        </div>
        
        <div class="book-now">
            <a href="hotel_booking.php?city_id=<?php echo $city_id; ?>" class="btn">Book Now</a>
        </div>
    </div>
</body>
</html>
