<?php
session_start();

// Redirect to login if user is not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "travel_booking");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if booking_id is provided
if (!isset($_GET['booking_id'])) {
    echo "Invalid request!";
    exit();
}

$booking_id = $_GET['booking_id'];

// Fetch booking details
$query = "SELECT b.booking_id, c.name AS city, c.description AS city_desc, h.name AS hotel, h.amenities,
                 t.type AS travel_mode, t.company_name, t.departure, t.arrival, 
                 m.name AS meal_plan, b.total_price, b.booking_date 
          FROM bookings b
          JOIN cities c ON b.city_id = c.city_id
          JOIN hotels h ON b.hotel_id = h.hotel_id
          JOIN travel_options t ON b.travel_id = t.travel_id
          JOIN meal_plans m ON b.meal_id = m.meal_id
          WHERE b.booking_id = $booking_id";

$result = $conn->query($query);

if ($result->num_rows == 0) {
    echo "Booking not found!";
    exit();
}

$booking = $result->fetch_assoc();

// Set city name for background image
$city_image = "images/cities/" . strtolower(str_replace(" ", "_", $booking["city"])) . ".jpg";

// Check if image exists; if not, use a default background
if (!file_exists($city_image)) {
    $city_image = "images/cities/default.jpg";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itinerary - TravelScapes</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: url('images/img-3.jpg') no-repeat center center fixed;
        background-size: cover;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 80%;
        max-width: 1000px;
        margin: 40px auto;
        padding: 30px;
        background-color: rgba(255, 255, 255, 0.9); /* Slight transparency for readability */
        border-radius: 8px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    h2 {
        font-size: 2rem;
        color: #007bff;
        margin-bottom: 20px;
        text-align: center;
    }

    .info-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-top: 30px;
    }

    .info-item {
        padding: 15px;
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .info-item strong {
        color: #007bff;
        font-weight: bold;
    }

    .info-title {
        font-size: 1.1rem;
        font-weight: bold;
        color: #007bff;
        margin-bottom: 10px;
    }

    .back-btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        font-size: 1rem;
        border-radius: 5px;
        margin-top: 20px;
        text-align: center;
        transition: background-color 0.3s ease;
    }

    .back-btn:hover {
        background-color: #0056b3;
    }

    .back-btn:focus {
        outline: none;
    }
</style>

</head>
<body>
<div class="container">
    <h2>Itinerary for Booking ID: <?php echo $booking["booking_id"]; ?></h2>

    <div class="info-container">
        <!-- Left Column -->
        <div class="info-item">
            <div class="info-title">City</div>
            <p><strong>City:</strong> <?php echo $booking["city"]; ?></p>
            <p><strong>Description:</strong> <?php echo $booking["city_desc"]; ?></p>
        </div>

        <div class="info-item">
            <div class="info-title">Hotel Details</div>
            <p><strong>Hotel:</strong> <?php echo $booking["hotel"]; ?></p>
            <p><strong>Amenities:</strong> <?php echo $booking["amenities"]; ?></p>
        </div>

        <!-- Right Column -->
        <div class="info-item">
            <div class="info-title">Travel Details</div>
            <p><strong>Travel Mode:</strong> <?php echo $booking["travel_mode"]; ?> - <?php echo $booking["company_name"]; ?></p>
            <p><strong>Departure:</strong> <?php echo $booking["departure"]; ?></p>
            <p><strong>Arrival:</strong> <?php echo $booking["arrival"]; ?></p>
        </div>

        <div class="info-item">
            <div class="info-title">Meal Plan</div>
            <p><strong>Meal Plan:</strong> <?php echo $booking["meal_plan"]; ?></p>
        </div>
    </div>

    <div class="info-container">
        <div class="info-item">
            <div class="info-title">Price Details</div>
            <p><strong>Total Price:</strong> ₹<?php echo number_format($booking["total_price"], 2); ?></p>
            <p><strong>Booking Date:</strong> <?php echo $booking["booking_date"]; ?></p>
        </div>
    </div>

    <a href="my_bookings.php" class="back-btn">Back to My Bookings</a>
</div>

</body>
</html>

<?php
$conn->close();
?>
