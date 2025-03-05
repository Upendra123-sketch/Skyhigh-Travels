<?php
$conn = new mysqli("localhost", "root", "", "travel_booking");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$hotel_id = $_GET['hotel_id'];
$stmt = $conn->prepare("SELECT * FROM hotels WHERE hotel_id = ?");
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$result = $stmt->get_result();
$hotel = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $hotel['name']; ?> Details</title>
</head>
<body>

    <h2><?php echo $hotel['name']; ?> (<?php echo $hotel['star_rating']; ?>)</h2>
    <p><strong>Price Per Night:</strong> ₹<?php echo $hotel['price_per_night']; ?></p>
    <p><strong>Amenities:</strong> <?php echo $hotel['amenities']; ?></p>
    <img src="images/hotels/<?php echo strtolower(str_replace(' ', '_', $hotel['name'])); ?>.jpg" alt="Hotel Image" width="300">
    
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
