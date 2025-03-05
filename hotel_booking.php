<?php
session_start();
require 'db_connect.php'; // Ensure database connection

if (!isset($_GET['city_id'])) {
    header("Location: home.php"); // Redirect if no city is selected
    exit();
}

$city_id = $_GET['city_id'];

// Fetch city name
$cityQuery = $conn->prepare("SELECT city_name FROM cities WHERE city_id = ?");
$cityQuery->bind_param("i", $city_id);
$cityQuery->execute();
$cityResult = $cityQuery->get_result();

if ($cityResult->num_rows == 0) {
    echo "<p>City not found.</p>";
    exit();
}

$city = $cityResult->fetch_assoc();

// Fetch available hotels in the city
$hotelQuery = $conn->prepare("SELECT hotel_id, hotel_name, ratings, amenities FROM hotels WHERE city_id = ?");
$hotelQuery->bind_param("i", $city_id);
$hotelQuery->execute();
$hotelResult = $hotelQuery->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotels in <?php echo htmlspecialchars($city['city_name']); ?></title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your CSS file -->
</head>
<body>
    <div class="container">
        <h1>Hotels in <?php echo htmlspecialchars($city['city_name']); ?></h1>

        <?php if ($hotelResult->num_rows > 0) { ?>
            <div class="hotel-list">
                <?php while ($hotel = $hotelResult->fetch_assoc()) { ?>
                    <div class="hotel-card">
                        <h2><?php echo htmlspecialchars($hotel['hotel_name']); ?></h2>
                        <p><strong>Rating:</strong> <?php echo htmlspecialchars($hotel['ratings']); ?> stars</p>
                        <p><strong>Amenities:</strong> <?php echo htmlspecialchars($hotel['amenities']); ?></p>
                        <a href="hotel_booking_form.php?hotel_id=<?php echo $hotel['hotel_id']; ?>&city_id=<?php echo $city_id; ?>" class="btn">Book This Hotel</a>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <p>No hotels available for this destination.</p>
        <?php } ?>
    </div>
</body>
</html>
