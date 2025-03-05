<?php
$conn = new mysqli("localhost", "root", "", "travel_booking");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$travel_id = $_GET['travel_id'];
$stmt = $conn->prepare("SELECT * FROM travel_options WHERE travel_id = ?");
$stmt->bind_param("i", $travel_id);
$stmt->execute();
$result = $stmt->get_result();
$travel = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $travel['company_name']; ?> Details</title>
</head>
<body>

    <h2><?php echo $travel['company_name']; ?> (<?php echo $travel['type']; ?>)</h2>
    <p><strong>Price:</strong> ₹<?php echo $travel['price']; ?></p>
    <p><strong>Departure:</strong> <?php echo $travel['departure']; ?></p>
    <p><strong>Arrival:</strong> <?php echo $travel['arrival']; ?></p>
    <p><strong>Facilities:</strong> <?php echo $travel['facilities']; ?></p>
    <img src="images/travel/<?php echo strtolower(str_replace(' ', '_', $travel['company_name'])); ?>.jpg" alt="Travel Image" width="300">
    
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
