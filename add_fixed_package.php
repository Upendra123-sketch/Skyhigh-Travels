<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "travel_booking");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch cities, hotels, travel options, and meals from the database
$cities = $conn->query("SELECT city_id, name FROM cities");
$hotels = $conn->query("SELECT hotel_id, name FROM hotels");
$travels = $conn->query("SELECT travel_id, company_name FROM travel_options");
$meals = $conn->query("SELECT meal_id, name FROM meal_plans");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $city_id = $_POST['city_id'];
    $hotel_id = $_POST['hotel_id'];
    $travel_id = $_POST['travel_id'];
    $meal_id = $_POST['meal_id'];
    $package_name = $_POST['package_name'];
    $description = $_POST['description'];
    $duration = $_POST['duration'];
    $total_price = $_POST['total_price'];

    $stmt = $conn->prepare("INSERT INTO fixed_packages (city_id, hotel_id, travel_id, meal_id, package_name, description, duration, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisssdi", $city_id, $hotel_id, $travel_id, $meal_id, $package_name, $description, $duration, $total_price);

    if ($stmt->execute()) {
        echo "<script>alert('Package added successfully!'); window.location='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error adding package.');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Fixed Package - Admin</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        /* Background Image */
        body {
            background: url('images/img-6.jpg') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Container with Transparency */
        .container {
            width: 100%;
            max-width: 600px;
            background: rgba(255, 255, 255, 0.9); /* Semi-transparent */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Heading */
        h1 {
            text-align: center;
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
        }

        /* Form Styles */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 14px;
            font-weight: 500;
            color: #333;
        }

        /* Inputs & Select */
        input, select, textarea {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f9f9f9;
            transition: all 0.3s ease-in-out;
        }

        input:focus, select:focus, textarea:focus {
            border: 1px solid #007bff;
            background: #ffffff;
            outline: none;
        }

        /* Textarea */
        textarea {
            resize: vertical;
            height: 80px;
        }

        /* Button */
        .button-container {
            text-align: center;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            font-size: 16px;
            font-weight: 500;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #0056b3;
            transform: scale(1.05);
        }

        /* Responsive */
        @media screen and (max-width: 768px) {
            .container {
                max-width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Add Fixed Package</h1>
    <form action="add_fixed_package.php" method="POST">
        <label for="city_id">City</label>
        <select name="city_id" required>
            <option value="">Select City</option>
            <?php while ($city = $cities->fetch_assoc()) { ?>
                <option value="<?php echo $city['city_id']; ?>"><?php echo $city['name']; ?></option>
            <?php } ?>
        </select>

        <label for="hotel_id">Hotel</label>
        <select name="hotel_id" required>
            <option value="">Select Hotel</option>
            <?php while ($hotel = $hotels->fetch_assoc()) { ?>
                <option value="<?php echo $hotel['hotel_id']; ?>"><?php echo $hotel['name']; ?></option>
            <?php } ?>
        </select>

        <label for="travel_id">Travel Option</label>
        <select name="travel_id" required>
            <option value="">Select Travel Option</option>
            <?php while ($travel = $travels->fetch_assoc()) { ?>
                <option value="<?php echo $travel['travel_id']; ?>"><?php echo $travel['company_name']; ?></option>
            <?php } ?>
        </select>

        <label for="meal_id">Meal Plan</label>
        <select name="meal_id" required>
            <option value="">Select Meal Plan</option>
            <?php while ($meal = $meals->fetch_assoc()) { ?>
                <option value="<?php echo $meal['meal_id']; ?>"><?php echo $meal['name']; ?></option>
            <?php } ?>
        </select>

        <label for="package_name">Package Name</label>
        <input type="text" name="package_name" required>

        <label for="description">Description</label>
        <textarea name="description" rows="4" required></textarea>

        <label for="duration">Duration (in days)</label>
        <input type="number" name="duration" min="1" required>

        <label for="total_price">Total Price (₹)</label>
        <input type="number" name="total_price" step="0.01" required>

        <div class="button-container">
            <button type="submit">Add Package</button>
        </div>
    </form>
</div>

</body>
</html>
