<?php
session_start();
$conn = new mysqli("localhost", "root", "", "travel_booking");

// Check for database connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if booking_id is provided
if (!isset($_GET['booking_id']) || empty($_GET['booking_id'])) {
    die("Booking ID is required.");
}

$booking_id = $_GET['booking_id'];

// Fetch full booking details
$sql = "
    SELECT 
        b.booking_id, b.full_name, b.email, b.phone, b.travel_date, b.return_date, 
        b.total_price, b.nights, b.status, b.booking_date,
        c.city_id, c.name AS city_name, 
        h.hotel_id, h.name AS hotel_name, h.star_rating, h.amenities, h.price_per_night,
        t.travel_id, t.type AS travel_type, t.departure AS departure_time, t.arrival AS arrival_time, t.price AS travel_price,
        m.meal_id, m.name AS meal_name, m.price_per_night
    FROM bookings b
    LEFT JOIN cities c ON b.city_id = c.city_id
    LEFT JOIN hotels h ON b.hotel_id = h.hotel_id
    LEFT JOIN travel_options t ON b.travel_id = t.travel_id
    LEFT JOIN meal_plans m ON b.meal_id = m.meal_id
    WHERE b.booking_id = ?
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    die("Booking not found.");
}

// Image path for the city
$city_image = "images/" . strtolower($booking['city_name']) . ".jpg";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Itinerary - TravelScapes</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .city-banner {
            width: 100%;
            height: 500px;
            border-radius: 10px;
            background: url('<?php echo $city_image; ?>') no-repeat center center/cover;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .card h2 {
            color: #444;
            font-size: 18px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .details {
            font-size: 16px;
            line-height: 1.6;
        }

        .highlight {
            font-weight: bold;
            color: #2c3e50;
        }

        .total-section {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            color: #e74c3c;
            padding: 10px;
            margin-top: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .button {
            display: block;
            width: 200px;
            margin: 20px auto;
            text-align: center;
            padding: 10px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="city-banner"></div>

    <h1>Booking Itinerary</h1>

    <div class="details-grid">
        <!-- Booking Details -->
        <div class="card">
            <h2>Booking Details</h2>
            <p class="details"><span class="highlight">Booking ID:</span> <?php echo $booking['booking_id']; ?></p>
            <p class="details"><span class="highlight">Name:</span> <?php echo $booking['full_name']; ?></p>
            <p class="details"><span class="highlight">Email:</span> <?php echo $booking['email']; ?></p>
            <p class="details"><span class="highlight">Phone:</span> <?php echo $booking['phone']; ?></p>
            <p class="details"><span class="highlight">Booking Date:</span> <?php echo $booking['booking_date']; ?></p>
            <p class="details"><span class="highlight">Status:</span> <?php echo $booking['status']; ?></p>
        </div>

        <!-- Travel Details -->
        <div class="card">
            <h2>Travel Details</h2>
            <p class="details"><span class="highlight">City:</span> <?php echo $booking['city_name']; ?></p>
            <p class="details"><span class="highlight">Travel Date:</span> <?php echo $booking['travel_date']; ?></p>
            <p class="details"><span class="highlight">Return Date:</span> <?php echo $booking['return_date']; ?></p>
            <p class="details"><span class="highlight">Travel Type:</span> <?php echo $booking['travel_type']; ?></p>
            <p class="details"><span class="highlight">Departure:</span> <?php echo $booking['departure_time']; ?></p>
            <p class="details"><span class="highlight">Arrival:</span> <?php echo $booking['arrival_time']; ?></p>
            <p class="details"><span class="highlight">Cost:</span> ₹<?php echo $booking['travel_price']; ?></p>
        </div>

        <!-- Hotel Details -->
        <div class="card">
            <h2>Hotel Details</h2>
            <p class="details"><span class="highlight">Hotel:</span> <?php echo $booking['hotel_name']; ?></p>
            <p class="details"><span class="highlight">Rating:</span> <?php echo $booking['star_rating']; ?>⭐</p>
            <p class="details"><span class="highlight">Nights:</span> <?php echo $booking['nights']; ?></p>
            <p class="details"><span class="highlight">Cost:</span> ₹<?php echo $booking['price_per_night'] * $booking['nights']; ?></p>
        </div>

        <!-- Meal Plan -->
        <div class="card">
            <h2>Meal Plan</h2>
            <p class="details"><span class="highlight">Plan:</span> <?php echo $booking['meal_name']; ?></p>
            <p class="details"><span class="highlight">Cost per Day:</span> ₹<?php echo $booking['price_per_night']; ?></p>
        </div>
    </div>

    <div class="total-section">Grand Total: ₹<?php echo $booking['total_price']; ?></div>

    <a href="admin_dashboard.php" class="button">Back to Dashboard</a>
</div>

</body>
</html>
