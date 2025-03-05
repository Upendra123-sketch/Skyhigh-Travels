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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Package - <?php echo $city['name']; ?></title>
    <style>
        /* Background Styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('images/img-3.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Container Styling */
        .container {
            width: 80%;
            max-width: 800px;
            background: rgba(255, 255, 255, 0.9); /* Slight transparency */
            padding: 40px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            text-align: center;
            position: relative;
        }

        .container:before {
            content: "";
            position: absolute;
            top: -10px;
            left: 50%;
            width: 80%;
            height: 6px;
            background: linear-gradient(to right, #ff6f61, #ff3e30);
            border-radius: 10px;
            transform: translateX(-50%);
        }

        /* Title Styling */
        h1 {
            font-family: 'Montserrat', sans-serif;
            color: #007bff;
            font-size: 26px;
        }

        /* Options Container */
        .options-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        /* Option Card Styling */
        .option-card {
            background: linear-gradient(135deg, #ffffff, #f2f7fa);
            padding: 20px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            text-align: center;
        }

        .option-card:hover {
            transform: scale(1.05);
            box-shadow: 0px 10px 40px rgba(0, 0, 0, 0.15);
        }

        /* Card Titles */
        .option-card h2 {
            font-size: 22px;
            font-weight: 600;
            color: #007bff;
        }

        /* Card Text */
        .option-card p {
            font-size: 16px;
            color: #555;
            margin: 10px 0;
        }

        /* Buttons */
        .option-btn {
            background: #007bff;
            color: white;
            padding: 12px 24px;
            border: none;
            cursor: pointer;
            border-radius: 25px;
            text-decoration: none;
            font-size: 16px;
            margin-top: 20px;
            transition: background 0.3s ease-in-out;
            display: inline-block;
        }

        .option-btn:hover {
            background: #0056b3;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 30px;
            }
            .option-btn {
                width: 100%;
                padding: 15px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Choose Your Package for <?php echo $city['name']; ?></h1>
        <p>Select between a fixed itinerary or customize your own package. Both options are designed to offer you the best travel experience.</p>

        <div class="options-container">
            <div class="option-card">
                <h2>Customize Your Package</h2>
                <p>Choose your preferred hotels, travel options, and activities. Tailor your trip to match your dream vacation.</p>
                <a href="customize_package.php?city_id=<?php echo $city_id; ?>" class="option-btn">Customize Package</a>
            </div>

            <div class="option-card">
                <h2>Fixed Package</h2>
                <p>Book a predefined itinerary for a hassle-free experience. Perfect for those who want a set plan.</p>
                <a href="fixed_package.php?city_id=<?php echo $city_id; ?>" class="option-btn">Book Fixed Package</a>
            </div>
        </div>
    </div>

</body>
</html>
