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

// Fetch available travel options
$travelQuery = $conn->prepare("SELECT * FROM travel_options WHERE city_id = ?");
$travelQuery->bind_param("i", $city_id);
$travelQuery->execute();
$travelResult = $travelQuery->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($city['city_name']); ?> - Travel Details</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your CSS file -->
</head>
<body>
    <div class="container">
        <h1>Explore <?php echo htmlspecialchars($city['city_name']); ?></h1>
        <p><?php echo htmlspecialchars($city['description']); ?></p>
        
        <h2>Basic Itinerary</h2>
        <p><?php echo nl2br(htmlspecialchars($city['itinerary'])); ?></p>
        
        <h2>Available Travel Options</h2>
        <table>
            <tr>
                <th>Mode</th>
                <th>Operator</th>
                <th>Cost</th>
                <th>Duration</th>
                <th>Book Now</th>
            </tr>
            <?php while ($travel = $travelResult->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($travel['mode']); ?></td>
                    <td><?php echo htmlspecialchars($travel['operator']); ?></td>
                    <td><?php echo htmlspecialchars($travel['cost']); ?></td>
                    <td><?php echo htmlspecialchars($travel['duration']); ?></td>
                    <td><a href="book_travel.php?travel_id=<?php echo $travel['id']; ?>&city_id=<?php echo $city_id; ?>">Book</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
