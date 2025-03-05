<?php
// payment_gateway.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Simulating payment process as successful
    $payment_status = 'Completed'; // Simulated successful payment
    $total_price = $_POST['total_price'];

    // Decode the booking details from the form input (from $_POST)
    $booking_details = json_decode($_POST['booking_details'], true);

    // Debug: Check if booking details are received and correctly formatted
    if (empty($booking_details)) {
        echo "<pre>";
        print_r($_POST['booking_details']);  // Show the raw booking details to debug
        echo "</pre>";
        die("Booking details are missing or malformed.");
    }

    // User card details (optional in your case as we are simulating)
    $card_number = $_POST['card_number'];
    $expiry_date = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

    if ($payment_status == 'Completed') {
        // On successful payment, store booking details in the database
        $conn = new mysqli("localhost", "root", "", "travel_booking");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Extracting necessary details from booking_details
        $city_id = isset($booking_details['city_id']) ? $booking_details['city_id'] : 0;
        $hotel_id = isset($booking_details['hotel_id']) ? $booking_details['hotel_id'] : 0;
        $travel_id = isset($booking_details['travel_id']) ? $booking_details['travel_id'] : 0;
        $meal_id = isset($booking_details['meal_id']) ? $booking_details['meal_id'] : 0;
        $nights = isset($booking_details['nights']) ? $booking_details['nights'] : 0;
        $travel_date = isset($booking_details['travel_date']) ? $booking_details['travel_date'] : null;
        $return_date = isset($booking_details['return_date']) ? $booking_details['return_date'] : null;

        // Insert booking into the bookings table
        $user_id = 1; // Replace with actual user ID (from session or other logic)
        $query = "INSERT INTO bookings 
                  (user_id, city_id, hotel_id, travel_option_id, travel_id, total_price, status, 
                  booking_date, payment_status, payment_method, meal_id, travel_date, return_date, nights)
                  VALUES 
                  (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($query);

        if ($stmt) {
            // Ensure that all values passed to bind_param are variables
            $stmt->bind_param("iiiiisssssss", $user_id, $city_id, $hotel_id, 0, $travel_id, $total_price, 
                              'Confirmed', $payment_status, 'Credit Card', $meal_id, $travel_date, $return_date, $nights);

            if ($stmt->execute()) {
                // Success: Redirect to home page with a success alert
                echo "<script>alert('Booking Successful!'); window.location.href = 'index.php';</script>";
            } else {
                // Failure: Show failure message
                echo "<h1>Booking Failed!</h1>";
                echo "<p>There was an issue with your booking. Please try again.</p>";
            }

            $stmt->close();
        } else {
            // If prepared statement fails
            echo "Error preparing the statement: " . $conn->error;
        }

        $conn->close();
    } else {
        // If payment fails
        echo "<h1>Payment Failed!</h1>";
        echo "<p>There was an issue with your payment. Please try again.</p>";
    }
} else {
    // Simulate the payment form (credit card entry)
    $total_price = $_GET['total_price'];

    // Debugging: Display the booking details passed from the previous page
    if (!isset($_GET['booking_details'])) {
        echo "<h1>Error: Booking details missing from the URL.</h1>";
        die();
    }

    // Decode booking details from the URL parameter
    $booking_details = json_decode(urldecode($_GET['booking_details']), true);

    // Debugging: Display booking details for validation
    echo "<pre>";
    print_r($booking_details);  // Show the decoded booking details
    echo "</pre>";

    if (empty($booking_details)) {
        echo "<h1>Error: Booking details are malformed or empty.</h1>";
        die();
    }
?>

<h1>Bank Payment Gateway</h1>
<p><strong>Amount to Pay: ₹<?php echo number_format($total_price, 2); ?></strong></p>

<div id="payment-form">
    <form method="POST" action="payment_gateway.php">
        <label for="card_number">Card Number:</label><br>
        <input type="text" id="card_number" name="card_number" placeholder="Enter Card Number" required><br><br>

        <label for="expiry_date">Expiry Date:</label><br>
        <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" required><br><br>

        <label for="cvv">CVV:</label><br>
        <input type="text" id="cvv" name="cvv" placeholder="Enter CVV" required><br><br>

        <!-- Hidden fields to pass total price and booking details -->
        <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
        <input type="hidden" name="booking_details" value="<?php echo urlencode(json_encode($booking_details)); ?>">

        <button type="submit">Submit Payment</button>
    </form>
</div>

<?php } ?>
