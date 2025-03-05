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

// Fetch all bookings
$result = $conn->query("SELECT * FROM bookings");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TravelScapes</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
            margin: 0;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #2C3E50;
            color: white;
            padding: 15px 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .navbar h1 {
            font-size: 24px;
            margin: 0;
            font-weight: 600;
        }

        .navbar .nav-links {
            display: flex;
            gap: 25px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            transition: 0.3s;
            padding: 8px 12px;
            border-radius: 5px;
        }

        .navbar a:hover {
            background: #1ABC9C;
        }

        .logout {
            background: #E74C3C;
            padding: 8px 14px;
            border-radius: 5px;
        }

        .logout:hover {
            background: #C0392B;
        }

        /* Main Content */
        .dashboard-container {
            max-width: 90%;
            margin: 40px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            font-size: 22px;
            margin-bottom: 20px;
            font-weight: 600;
            color: #333;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th, table td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            font-size: 15px;
        }

        table th {
            background: #1ABC9C;
            color: white;
            font-weight: 600;
        }

        table tr:nth-child(even) {
            background: #f9f9f9;
        }

        table tr:hover {
            background: #ECF0F1;
            transition: 0.3s;
        }

        /* Status Labels */
        .status {
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-pending {
            background: orange;
            color: white;
        }

        .status-confirmed {
            background: green;
            color: white;
        }

        .status-cancelled {
            background: red;
            color: white;
        }

        /* View Itinerary Button */
        .view-button {
            padding: 8px 14px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            font-weight: 500;
        }

        .view-button:hover {
            background: #0056b3;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                text-align: center;
            }

            .navbar .nav-links {
                margin-top: 10px;
                gap: 15px;
            }

            .dashboard-container {
                width: 95%;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <h1>TravelScapes Admin</h1>
    <div class="nav-links">
        <a href="admin_dashboard.php">📊 Dashboard</a>
        <!-- <a href="admin_add_city.php">🏙 Add City</a> -->
        <a href="admin_add.php">🏨 Add Hotels</a>
        <a href="add_fixed_package.php">📦 Add Packages</a>
        <a href="logout.php" class="logout">🚪 Logout</a>
    </div>
</div>

<!-- Main Content -->
<div class="dashboard-container">
    <h2>All Bookings</h2>
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Travel Date</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($booking = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $booking['booking_id']; ?></td>
                    <td><?php echo $booking['full_name']; ?></td>
                    <td><?php echo $booking['email']; ?></td>
                    <td><?php echo $booking['phone']; ?></td>
                    <td><?php echo $booking['travel_date']; ?></td>
                    <td>₹<?php echo $booking['total_price']; ?></td>
                    <td>
                        <span class="status 
                            <?php 
                                if ($booking['status'] == 'Pending') echo 'status-pending'; 
                                elseif ($booking['status'] == 'Confirmed') echo 'status-confirmed'; 
                                else echo 'status-cancelled'; 
                            ?>
                        ">
                            <?php echo $booking['status']; ?>
                        </span>
                    </td>
                    <td>
                        <a href="view_user_itinerary.php?booking_id=<?php echo $booking['booking_id']; ?>" class="view-button">View Itinerary</a>
                    </td>
                    <td>
                    <?php if ($booking['status'] == 'Pending') { ?>
                            <a href="confirm.php?booking_id=<?php echo $booking['booking_id']; ?>" class="view-button confirm-btn">Confirm</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php
$conn->close();
?>
