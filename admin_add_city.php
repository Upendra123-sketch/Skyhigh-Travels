<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "travel_booking";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
$message = ""; // To store success/error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $city_name = trim($_POST['city_name']);
    $description = trim($_POST['description']);

    if (!empty($city_name) && !empty($description)) {
        // Insert using prepared statements
        $stmt = $conn->prepare("INSERT INTO cities (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $city_name, $description);
        
        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>City added successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }

        $stmt->close();
    } else {
        $message = "<div class='alert alert-warning'>Please fill all fields.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add City - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Add a New City</h2>
    <?= $message ?> <!-- Display success/error messages -->

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">City Name:</label>
            <input type="text" class="form-control" name="city_name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description:</label>
            <textarea class="form-control" name="description" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Add City</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
