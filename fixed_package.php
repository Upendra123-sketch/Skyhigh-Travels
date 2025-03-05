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

$city_id = $_GET['city_id'];

// Fetch city details
$stmt = $conn->prepare("SELECT * FROM cities WHERE city_id = ?");
$stmt->bind_param("i", $city_id);
$stmt->execute();
$result = $stmt->get_result();
$city = $result->fetch_assoc();
$stmt->close();

// Fetch fixed packages for this city
$stmt = $conn->prepare("SELECT * FROM fixed_packages WHERE city_id = ?");
$stmt->bind_param("i", $city_id);
$stmt->execute();
$packages = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fixed Packages - <?php echo $city['name']; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('images/img-8.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            color: #333;
          
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
    max-width: 1200px;
    margin: 50px auto;
    background: rgba(255, 255, 255, 0.1); /* Transparent White */
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0px 10px 50px rgba(0, 0, 0, 0.2);
    text-align: center;
    backdrop-filter: blur(1px); /* Blurred Background Effect */
}


h1 {
    font-size: 40px;
    color: #2e7d32;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

h2 {
    font-size: 28px;
    font-weight: bold;
    color: #1b5e20;
    margin-bottom: 15px;
}

p {
    font-size: 18px;
    color: #444;
    line-height: 1.6;
}

div.package {
    background-color: #ffffff;
    margin: 20px 0;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0px 5px 30px rgba(0, 0, 0, 0.1);
    text-align: left;
}

.package:hover {
    box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.15);
    transform: translateY(-5px);
    transition: all 0.3s ease-in-out;
}

.package h2 {
    font-size: 26px;
    color: #388e3c;
}

.package p {
    font-size: 16px;
    color: #555;
    margin-bottom: 15px;
}

.package form {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 15px;
}

input[type="text"], input[type="email"], input[type="date"] {
    width: 80%;
    padding: 14px;
    margin: 8px 0;
    border-radius: 8px;
    border: 2px solid #4caf50;
    font-size: 16px;
    outline: none;
    transition: 0.3s;
}

input[type="text"]:focus, input[type="email"]:focus, input[type="date"]:focus {
    border-color: #388e3c;
    box-shadow: 0px 0px 8px rgba(56, 142, 108, 0.7);
}

button {
    padding: 15px 20px;
    background-color: #388e3c;
    color: white;
    font-size: 18px;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    width: 80%;
    margin-top: 15px;
    transition: all 0.3s ease-in-out;
}

button:hover {
    background-color: #2e7d32;
    transform: translateY(-3px);
}

button:active {
    background-color: #1b5e20;
    transform: translateY(2px);
}

.back-home {
    font-size: 18px;
    color: #388e3c;
    text-decoration: none;
    margin-top: 20px;
    display: inline-block;
    font-weight: bold;
    transition: 0.3s;
}

.back-home:hover {
    color: #2e7d32;
    text-decoration: underline;
}

@media (max-width: 768px) {
    .container {
        width: 90%;
        padding: 25px;
    }

    h1 {
        font-size: 30px;
    }

    h2 {
        font-size: 22px;
    }

    p {
        font-size: 14px;
    }

    input[type="text"], input[type="email"], input[type="date"], button {
        width: 90%;
        font-size: 14px;
    }
}

    </style>
<body>

<div class="container">
    <h1>Select a Fixed Package for <?php echo $city['name']; ?></h1>

    <?php while ($package = $packages->fetch_assoc()) { ?>
        <div class="package">
            <h2><?php echo $package['package_name']; ?></h2>
            <p><?php echo $package['description']; ?></p>
            <p>Duration: <?php echo $package['duration']; ?> days</p>
            <p>Total Price: ₹<?php echo $package['total_price']; ?></p>

            <form action="process_booking.php" method="POST">
                <input type="hidden" name="package_id" value="<?php echo $package['package_id']; ?>">
                <input type="hidden" name="city_id" value="<?php echo $city_id; ?>">

                <input type="text" name="full_name" placeholder="Full Name" required><br>
                <input type="email" name="email" placeholder="Email" required><br>
                <input type="text" name="phone" placeholder="Phone" required><br>

                <input type="date" name="travel_date" required><br>
                <input type="date" name="return_date" required><br>

                <button type="submit">Confirm Booking</button>
            </form>
        </div>
    <?php } ?>

    <a href="home.php" class="back-home">Back to Home</a>
</div>

</body>
</html>
