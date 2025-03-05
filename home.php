<?php
session_start();

// Redirect to login if user is not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "travel_booking");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch cities from database
$result = $conn->query("SELECT * FROM cities");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - TravelScapes</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
        }
        .navbar {
            background-color: #333;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 16px;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .navbar h2 {
            margin: 0;
            font-size: 24px;
        }
        .container {
            width: 80%;
            margin: 40px auto;
            text-align: center;
        }
        .intro {
            position: relative;
            width: 100%;
            height: 600px; /* Adjust the height as needed */
            background: url('images/beach-sunset.jpg') center/cover no-repeat;
            display: flex;
            border-radius: 10px;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }

        .intro-overlay {
            background: rgba(0, 0, 0, 0.1); /* Semi-transparent dark background */
            padding: 30px;
            border-radius: 8px;
        }

        .intro h2 {
            font-size: 36px;
            margin: 0;
        }

        .intro p {
            font-size: 20px;
            margin-top: 10px;
        }

        .city-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr); /* This ensures 5 cards per line */
            gap: 30px;
            margin-top: 40px;
        }
        .city-card {
            background: white;
            padding: 20px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .city-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.2);
        }
        .city-card img {
            width: 100%;
            height: 200px;
            border-radius: 8px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .city-card img:hover {
            transform: scale(1.1);
        }
        .city-card h3 {
            font-size: 24px;
            color: #333;
            margin: 15px 0;
        }
        .city-card p {
            font-size: 16px;
            color: #777;
            line-height: 1.6;
        }
        .city-card button {
            background: #007bff;
            color: white;
            padding: 12px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 15px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .city-card button:hover {
            background: #0056b3;
        }
        .more-details {
            font-size: 14px;
            color: #007bff;
            cursor: pointer;
        }
        .more-details:hover {
            text-decoration: underline;
        }
        /* Footer Styling */
footer {
    background-color: #222;
    color: white;
    padding: 50px 0;
    font-family: 'Arial', sans-serif;
}

.footer-container {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    max-width: 1200px;
    margin: auto;
}

.footer-section {
    flex: 1;
    margin: 15px;
    min-width: 250px;
}

.footer-section h3 {
    font-size: 22px;
    margin-bottom: 15px;
    border-bottom: 3px solid #007bff;
    display: inline-block;
    padding-bottom: 5px;
}

.footer-section p,
.footer-section ul {
    font-size: 16px;
    line-height: 1.6;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin: 10px 0;
}

.footer-section ul li a {
    color: #bbb;
    text-decoration: none;
    transition: 0.3s ease;
}

.footer-section ul li a:hover {
    color: #007bff;
    text-decoration: underline;
}

/* Contact Icons */
.footer-section.contact p {
    display: flex;
    align-items: center;
}

.footer-section.contact i {
    margin-right: 10px;
    color: #007bff;
}

/* Social Icons */
.social-icons {
    display: flex;
    justify-content: flex-start;
    gap: 15px;
    margin-top: 10px;
}

.social-icons a {
    color: white;
    font-size: 24px;
    transition: transform 0.3s ease, color 0.3s ease;
}

.social-icons a:hover {
    transform: scale(1.2);
    color: #007bff;
}

/* Footer Bottom */
.footer-bottom {
    background-color: #111;
    padding: 15px 0;
    text-align: center;
    font-size: 14px;
}


    </style>
</head>
<body>

<div class="navbar">
    <h2>SkyHigh Travels</h2> <!-- Updated company name -->
    <div>
        <span>Welcome, <?php echo $_SESSION["name"]; ?> |</span>
        <a href="my_bookings.php">My Bookings</a>
        <a href="about.php">About Us</a> <!-- New link to the About Us page -->

        <a href="logout.php">Logout</a>
    </div>
</div>


    <div class="container">
    <div class="intro">
        <div class="intro-overlay">
                <h2>Welcome to SkyHigh Travels!</h2>
                <p>Your one-stop platform for booking hotels and travel packages across India. Choose your destination and let us handle the rest!</p>
            </div>
        </div>



        <h2>Explore Cities</h2>
        <div class="city-grid">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="city-card">
                    <img src="images/<?php echo strtolower($row['name']); ?>.jpg" alt="<?php echo $row['name']; ?>">
                    <h3><?php echo $row['name']; ?></h3>
                    <p><?php echo substr($row['description'], 0, 100); ?>...</p>
                    <span class="more-details" onclick="window.location.href='city_details.php?city_id=<?php echo $row['city_id']; ?>'">Read More</span>
                    <br>
                    <a href="city_details.php?city_id=<?php echo $row['city_id']; ?>">
                        <button>View Details</button>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>

</body>
<footer>
    <div class="footer-container">
        <div class="footer-section about">
            <h3>SkyHigh Travels</h3>
            <p>Explore the world with our seamless booking experience. Find the best hotels, travel options, and activities—all in one place!</p>
        </div>

        <div class="footer-section links">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="my_bookings.php">My Bookings</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="footer-section contact">
            <h3>Contact Us</h3>
            <p><i class="fas fa-envelope"></i> support@skyhightravels.com</p>
            <p><i class="fas fa-phone"></i> +91 98765 43210</p>
            <p><i class="fas fa-map-marker-alt"></i> 123, Travel Street, Mumbai, India</p>
        </div>

        <div class="footer-section social">
            <h3>Follow Us</h3>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2025 SkyHigh Travels | Designed with ❤️ for travel lovers.</p>
    </div>
</footer>



</html>

<?php
$conn->close();
?>
