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

// Fetch booking ID from URL
$booking_id = $_GET['booking_id'];

// Get the booking details from the database
$stmt = $conn->prepare("SELECT * FROM bookings WHERE booking_id = ?");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

if (!$booking) {
    echo "Booking not found.";
    exit();
}

// Fetch city, hotel, and package details
$city_id = $booking['city_id'];
$hotel_id = $booking['hotel_id'];
$package_id = $booking['package_id'];

// Get city details
$stmt = $conn->prepare("SELECT * FROM cities WHERE city_id = ?");
$stmt->bind_param("i", $city_id);
$stmt->execute();
$city = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Get hotel details
$stmt = $conn->prepare("SELECT * FROM hotels WHERE hotel_id = ?");
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$hotel = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Get package details
$stmt = $conn->prepare("SELECT * FROM fixed_packages WHERE package_id = ?");
$stmt->bind_param("i", $package_id);
$stmt->execute();
$package = $stmt->get_result()->fetch_assoc();
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style>
     body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 20px;
    background-color: #f9f9f9;
}

.container {
    max-width: 900px;
    margin: 0 auto;
    background-color: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #4CAF50;
    font-size: 2em;
    margin-bottom: 30px;
}

.booking-details, .hotel-details, .package-details {
    margin-bottom: 20px;
    padding: 20px;
    background-color: #f5f5f5;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.details-title {
    font-size: 1.2em;
    color: #333;
    font-weight: 600;
    margin-bottom: 8px;
}

.details-content {
    font-size: 1em;
    color: #555;
    margin-left: 10px;
    margin-bottom: 12px;
}

.total-price {
    font-size: 1.5em;
    color: #D32F2F;
    font-weight: 700;
    text-align: center;
    margin-top: 30px;
}

.confirmation-message {
    text-align: center;
    font-size: 1.2em;
    color: #4CAF50;
    margin-top: 40px;
}

.button {
    display: inline-block;
    width: 200px;
    padding: 12px;
    background-color: #4CAF50;
    color: #fff;
    text-align: center;
    text-decoration: none;
    border-radius: 5px;
    margin: 30px auto;
    font-size: 1.1em;
    transition: background-color 0.3s;
}

.button:hover {
    background-color: #45a049;
}

.booking-details, .hotel-details, .package-details {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.details-title {
    margin-bottom: 6px;
}

.details-content {
    margin-left: 15px;
    font-size: 1em;
    line-height: 1.6;
}

.booking-details .details-content,
.hotel-details .details-content,
.package-details .details-content {
    width: 100%;
}

.container h1 {
    font-size: 2rem;
    color: #333;
    font-weight: bold;
    margin-bottom: 40px;
}

.booking-details div,
.hotel-details div,
.package-details div {
    margin-bottom: 12px;
}

.booking-details, .hotel-details, .package-details {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 15px;
}

.details-title {
    font-weight: bold;
    color: #333;
    text-align: right;
}

.details-content {
    color: #555;
    text-align: left;
    font-size: 1.1em;
}

.booking-details, .hotel-details, .package-details {
    align-items: center;
}

.total-price {
    font-size: 1.5em;
    color: #D32F2F;
    font-weight: 700;
    text-align: center;
    margin-top: 30px;
}

.confirmation-message {
    font-size: 1.2em;
    color: #4CAF50;
    margin-top: 20px;
}

@media (max-width: 768px) {
    .booking-details, .hotel-details, .package-details {
        display: block;
    }

    .details-title, .details-content {
        text-align: left;
        margin-left: 0;
    }
}


    </style>
</head>
<body>

<div class="container">
    <h1>Booking Confirmation</h1>

    <div class="booking-details">
        <div class="details-title">Booking ID:</div>
        <div class="details-content"><?php echo $booking['booking_id']; ?></div>
        
        <div class="details-title">Full Name:</div>
        <div class="details-content"><?php echo $booking['full_name']; ?></div>
        
        <div class="details-title">Email:</div>
        <div class="details-content"><?php echo $booking['email']; ?></div>
        
        <div class="details-title">Phone:</div>
        <div class="details-content"><?php echo $booking['phone']; ?></div>
        
        <div class="details-title">Travel Date:</div>
        <div class="details-content"><?php echo $booking['travel_date']; ?></div>
        
        <div class="details-title">Return Date:</div>
        <div class="details-content"><?php echo $booking['return_date']; ?></div>
    </div>

    <div class="hotel-details">
        <div class="details-title">Hotel:</div>
        <div class="details-content"><?php echo $hotel['name']; ?></div>
        <div class="details-content"><?php echo $hotel['amenities']; ?></div>
        <div class="details-content">Price per Night: ₹<?php echo $hotel['price_per_night']; ?></div>
    </div>

    <div class="package-details">
        <div class="details-title">Package:</div>
        <div class="details-content"><?php echo $package['package_name']; ?></div>
        <div class="details-content"><?php echo $package['description']; ?></div>
        <div class="details-content">Duration: <?php echo $package['duration']; ?> days</div>
    </div>

    <div class="total-price">
        Total Price: ₹<?php echo $booking['total_price']; ?>
    </div>

    <div class="confirmation-message">
        Your booking is confirmed! We look forward to welcoming you!
    </div>

    <a href="home.php" class="button">Back to Homepage</a>
</div>

</body>
</html>

<?php
$conn->close();
?>
