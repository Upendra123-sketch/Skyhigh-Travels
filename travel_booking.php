<?php
session_start();
require 'db_connect.php';

if (!isset($_GET['city_id'])) {
    header("Location: home.php");
    exit();
}

$city_id = $_GET['city_id'];

// Fetch city details
$cityQuery = $conn->prepare("SELECT city_name FROM cities WHERE city_id = ?");
$cityQuery->bind_param("i", $city_id);
$cityQuery->execute();
$cityResult = $cityQuery->get_result();

if ($cityResult->num_rows == 0) {
    echo "<p>City not found.</p>";
    exit();
}

$city = $cityResult->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Travel Mode for <?php echo htmlspecialchars($city['city_name']); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Select Travel Mode for <?php echo htmlspecialchars($city['city_name']); ?></h1>
        <form action="process_travel_booking.php" method="POST">
            <input type="hidden" name="city_id" value="<?php echo $city_id; ?>">

            <label for="travel_mode">Select Travel Mode:</label>
            <select name="travel_mode" id="travel_mode" required>
                <option value="Train">Train</option>
                <option value="Flight">Flight</option>
                <option value="Bus">Bus</option>
            </select>

            <label for="travel_date">Select Travel Date:</label>
            <input type="date" name="travel_date" id="travel_date" required>

            <label for="travel_class">Select Travel Class:</label>
            <select name="travel_class" id="travel_class">
                <option value="Economy">Economy</option>
                <option value="Business">Business</option>
                <option value="First Class">First Class</option>
            </select>

            <label for="meal_preference">Select Meal Preference:</label>
            <select name="meal_preference" id="meal_preference">
                <option value="Vegetarian">Vegetarian</option>
                <option value="Non-Vegetarian">Non-Vegetarian</option>
                <option value="Vegan">Vegan</option>
            </select>

            <label for="extra_luggage">Extra Luggage:</label>
            <select name="extra_luggage" id="extra_luggage">
                <option value="No">No</option>
                <option value="Yes">Yes</option>
            </select>

            <button type="submit" class="btn">Continue</button>
        </form>
    </div>
</body>
</html>
