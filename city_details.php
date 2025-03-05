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

$city_id = filter_input(INPUT_GET, 'city_id', FILTER_VALIDATE_INT);
if (!$city_id) {
    die("Invalid city ID.");
}

$stmt = $conn->prepare("SELECT * FROM cities WHERE city_id = ?");
$stmt->bind_param("i", $city_id);
$stmt->execute();
$result = $stmt->get_result();
$city = $result->fetch_assoc();

if (!$city) {
    die("<h2>City not found.</h2>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($city['name']); ?> - TravelScapes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
        }
        .container {
            width: 60%;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0px 0px 10px gray;
            border-radius: 8px;
            margin-top: 20px;
        }
        button {
            background: #007bff;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            font-size: 18px;
        }
        button:hover {
            background: #0056b3;
        }
        .map-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1><?php echo htmlspecialchars($city['name']); ?></h1>
        <p><?php echo htmlspecialchars($city['description']); ?></p>
        <img src="images/<?php echo strtolower($city['name']); ?>.jpg" 
             onerror="this.src='images/default.jpg';" 
             alt="<?php echo htmlspecialchars($city['name']); ?>" width="100%">
        
        <!-- Google Maps Link -->
        <div class="map-container">
            <p><strong>View on Google Maps:</strong></p>
            <a href="<?php echo htmlspecialchars($city['map_link']); ?>" target="_blank">
                <button>Open in Google Maps</button>
            </a>
        </div>

        <br>
        <a href="package_selection.php?city_id=<?php echo $city['city_id']; ?>">
            <button>Book Now</button>
        </a>
    </div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
