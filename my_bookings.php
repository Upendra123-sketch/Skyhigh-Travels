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

// Get user ID
$user_id = $_SESSION["user_id"];

// Fetch bookings
$query = "SELECT b.booking_id, c.name AS city, h.name AS hotel, 
                 t.type AS travel_mode, t.departure, t.arrival, 
                 m.name AS meal_plan, b.total_price, b.booking_date,b.status
          FROM bookings b
          JOIN cities c ON b.city_id = c.city_id
          JOIN hotels h ON b.hotel_id = h.hotel_id
          JOIN travel_options t ON b.travel_id = t.travel_id
          JOIN meal_plans m ON b.meal_id = m.meal_id
          WHERE b.user_id = $user_id
          ORDER BY b.booking_date DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - TravelScapes</title>
    <style>
       <style>
       body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('images/img-6.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
        }

    .container {
        width: 90%;
        max-width: 1200px;
        margin: 30px auto;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #007bff;
        font-size: 2rem;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 15px;
        text-align: center;
        font-size: 1rem;
        border: 1px solid #dee2e6;
    }

    th {
        background-color: #007bff;
        color: white;
        font-size: 1.1rem;
        letter-spacing: 0.5px;
    }

    tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    tr:hover {
        background-color: #e2e6ea;
    }

    .btn {
        display: inline-block;
        padding: 8px 16px;
        margin-top: 10px;
        background-color: #28a745;
        color: white;
        font-size: 1rem;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .btn:hover {
        background-color: #218838;
    }

    .btn:active {
        background-color: #1e7e34;
    }

    .btn:focus {
        outline: none;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .footer {
        text-align: center;
        margin-top: 40px;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .footer a {
        text-decoration: none;
        color: #007bff;
    }

    .footer a:hover {
        text-decoration: underline;
    }
    .status {
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 500;
        text-transform: uppercase;
        display: inline-block;
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
</style>

    </style>
</head>
<body>

    <div class="container">
        <h2>My Bookings</h2>
        <table>
            <tr>
                <th>Booking ID</th>
                <th>City</th>
                <th>Hotel</th>
                <th>Travel Mode</th>
                <th>Departure</th>
                <th>Arrival</th>
                <th>Meal Plan</th>
                <th>Total Price</th>
                <th>Booking Date</th>
                <th>Status Date</th>
                <th>Itinerary</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row["booking_id"]; ?></td>
                    <td><?php echo $row["city"]; ?></td>
                    <td><?php echo $row["hotel"]; ?></td>
                    <td><?php echo $row["travel_mode"]; ?></td>
                    <td><?php echo $row["departure"]; ?></td>
                    <td><?php echo $row["arrival"]; ?></td>
                    <td><?php echo $row["meal_plan"]; ?></td>
                    <td>₹<?php echo number_format($row["total_price"], 2); ?></td>
                    <td><?php echo $row["booking_date"]; ?></td>
                    <td>
                        <span class="status 
                            <?php 
                                if ($row['status'] == 'Pending') echo 'status-pending'; 
                                elseif ($row['status'] == 'Confirmed') echo 'status-confirmed'; 
                                else echo 'status-cancelled'; 
                            ?>">
                            <?php echo $row["status"]; ?>
                        </span>
                    </td>
                    <td>
                        <a href="view_itinerary.php?booking_id=<?php echo $row['booking_id']; ?>" class="btn">View Itinerary</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

</body>
</html>

<?php
$conn->close();
?>
