<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "travel_booking";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle adding city
if (isset($_POST['add_city'])) {
    $city_name = trim($_POST['city_name']);
    $description = trim($_POST['description']);

    if (!empty($city_name) && !empty($description)) {
        $stmt = $conn->prepare("INSERT INTO cities (name, description) VALUES (?, ?)");
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("ss", $city_name, $description);
        $stmt->execute();
        $stmt->close();
        $city_message = "City added successfully!";
    } else {
        $city_message = "All fields are required!";
    }
}

// Handle adding hotel
if (isset($_POST['add_hotel'])) {
    $hotel_name = trim($_POST['hotel_name']);
    $city_id = $_POST['city_id'];
    $star_rating = $_POST['star_rating'];
    $amenities = trim($_POST['amenities']);
    $price_per_night = $_POST['price_per_night'];

    if (!empty($hotel_name) && !empty($city_id)) {
        $stmt = $conn->prepare("INSERT INTO hotels (city_id, name, star_rating, amenities, price_per_night) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("isssd", $city_id, $hotel_name, $star_rating, $amenities, $price_per_night);
        $stmt->execute();
        $stmt->close();
        $hotel_message = "Hotel added successfully!";
    } else {
        $hotel_message = "All fields are required!";
    }
}

// Handle adding travel option
if (isset($_POST['add_travel'])) {
    $city_id = $_POST['city_id'];
    $travel_type = $_POST['travel_type'];
    $departure = trim($_POST['departure']);
    $arrival = trim($_POST['arrival']);
    $price = $_POST['price'];

    if (!empty($city_id) && !empty($travel_type) && !empty($departure) && !empty($arrival) && !empty($price)) {
        $stmt = $conn->prepare("INSERT INTO travel_options (city_id, type, departure, arrival, price) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("isssd", $city_id, $travel_type, $departure, $arrival, $price);
        $stmt->execute();
        $stmt->close();
        $travel_message = "Travel option added successfully!";
    } else {
        $travel_message = "All fields are required!";
    }
}

// Fetch cities for dropdown
$city_query = "SELECT id, name FROM cities";
$city_result = $conn->query($city_query);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_meal'])) {
    $meal_name = $_POST['meal_name'];
    $meal_price = $_POST['meal_price'];
    $city_id = $_POST['city_id']; // Get selected city

    if (!empty($meal_name) && !empty($meal_price) && !empty($city_id)) {
        // Insert meal plan into the database
        $stmt = $conn->prepare("INSERT INTO meal_plans (name, price_per_night, city_id) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sdi", $meal_name, $meal_price, $city_id);
            if ($stmt->execute()) {
                echo "<script>alert('Meal Plan added successfully!');</script>";
            } else {
                echo "<script>alert('Error adding meal: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Database error: Unable to prepare statement.');</script>";
        }
    } else {
        echo "<script>alert('Please fill all fields.');</script>";
    }
}


// Fetch cities for dropdown
$cities_result = $conn->query("SELECT city_id, name FROM cities");
$cities = [];
while ($row = $cities_result->fetch_assoc()) {
    $cities[] = $row;
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Add Data</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: url('images/img-5.jpg') no-repeat center center fixed; 
            background-size: cover; 
            margin: 0; 
            padding: 20px; 
        }

        .container { width: 50%; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px #ccc; margin-bottom: 20px; }
        h2 { text-align: center; color: #333; }
        form { display: flex; flex-direction: column; }
        label { font-weight: bold; margin: 10px 0 5px; }
        input, select, textarea { padding: 8px; width: 100%; border: 1px solid #ccc; border-radius: 5px; }
        button { background: #28a745; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; margin-top: 10px; }
        button:hover { background: #218838; }
        .message { margin-top: 10px; padding: 10px; text-align: center; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<div class="container">
    <h2>Add City</h2>
    <form method="post">
        <label>City Name:</label>
        <input type="text" name="city_name" required>
        <label>Description:</label>
        <textarea name="description" rows="3" required></textarea>
        <button type="submit" name="add_city">Add City</button>
    </form>
</div>

<div class="container">
    <h2>Add Hotel</h2>
    <form method="post">
        <label>Hotel Name:</label>
        <input type="text" name="hotel_name" required>
        <label>City:</label>
        <select name="city_id" required>
            <option value="">Select a City</option>
            <?php foreach ($cities as $city): ?>
                <option value="<?= $city['city_id']; ?>"><?= $city['name']; ?></option>
            <?php endforeach; ?>
        </select>
        <label>Star Rating:</label>
        <select name="star_rating" required>
            <option value="3-Star">3-Star</option>
            <option value="4-Star">4-Star</option>
            <option value="5-Star">5-Star</option>
        </select>
        <label>Amenities:</label>
        <textarea name="amenities" rows="3" required></textarea>
        <label>Price per Night:</label>
        <input type="number" step="0.01" name="price_per_night" required>
        <button type="submit" name="add_hotel">Add Hotel</button>
    </form>
</div>

<div class="container">
    <h2>Add Travel Option</h2>
    <form method="post">
        <label>City:</label>
        <select name="city_id" required>
            <option value="">Select a City</option>
            <?php foreach ($cities as $city): ?>
                <option value="<?= $city['city_id']; ?>"><?= $city['name']; ?></option>
            <?php endforeach; ?>
        </select>
        <label>Travel Type:</label>
        <select name="travel_type" required>
            <option value="Bus">Bus</option>
            <option value="Train">Train</option>
        </select>
        <label>Departure Location:</label>
        <input type="text" name="departure" required>
        <label>Arrival Location:</label>
        <input type="text" name="arrival" required>
        <label>Price:</label>
        <input type="number" step="0.01" name="price" required>
        <button type="submit" name="add_travel">Add Travel Option</button>
    </form>
</div>

<div class="container">
<h2>Add Meal Plan</h2>
    <form method="POST">
        <label>Meal Plan Name:</label>
        <input type="text" name="meal_name" required>

        <label>Price Per Day:</label>
        <input type="number" step="0.01" name="meal_price" required>

        <label>Select City:</label>
        <select name="city_id" required>
            <option value="">-- Select City --</option>
            <?php foreach ($cities as $city): ?>
                <option value="<?= $city['city_id']; ?>"><?= $city['name']; ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit" name="add_meal">Add Meal Plan</button>
    </form>
</div>

</body>
</html>
