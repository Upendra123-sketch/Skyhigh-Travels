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

// Retrieve and validate user selections
$city_id = isset($_POST['city_id']) ? intval($_POST['city_id']) : 0;
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
$hotel_id = isset($_POST['selected_hotel']) ? intval($_POST['selected_hotel']) : 0;
$travel_id = isset($_POST['selected_travel']) ? intval($_POST['selected_travel']) : 0;
$meal_id = isset($_POST['selected_meal']) ? intval($_POST['selected_meal']) : 0;
$activities = isset($_POST['selected_activities']) ? explode(',', $_POST['selected_activities']) : [];
$total_price = isset($_POST['total_price']) ? floatval($_POST['total_price']) : 0.0;

// Fetch city details
$city_query = $conn->query("SELECT * FROM cities WHERE city_id = $city_id");
$city = $city_query->fetch_assoc() ?? die("Error: City not found.");

// Fetch hotel details
$hotel_query = $conn->query("SELECT * FROM hotels WHERE hotel_id = $hotel_id");
$hotel = $hotel_query->fetch_assoc() ?? die("Error: Hotel not found.");

// Fetch travel details
$travel_query = $conn->query("SELECT * FROM travel_options WHERE travel_id = $travel_id");
$travel = $travel_query->fetch_assoc() ?? die("Error: Travel option not found.");

// Fetch meal plan details
$meal_query = $conn->query("SELECT * FROM meal_plans WHERE meal_id = $meal_id");
$meal = $meal_query->fetch_assoc() ?? die("Error: Meal plan not found.");

// Fetch selected activities
$activity_list = [];
if (!empty($activities)) {
    $activity_ids = implode(',', array_map('intval', $activities));
    $activity_query = $conn->query("SELECT * FROM activities WHERE activity_id IN ($activity_ids)");
    while ($row = $activity_query->fetch_assoc()) {
        $activity_list[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Your Booking - TravelScapes</title>
    <style>
        /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    color: #333;
    line-height: 1.6;
}

/* Container */
.container {
    max-width: 1000px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h1 {
    text-align: center;
    color: #4CAF50;
    font-size: 2rem;
    margin-bottom: 20px;
}

/* Section Styles */
.section {
    margin-bottom: 20px;
}

.section h2 {
    font-size: 1.5rem;
    color: #333;
    margin-bottom: 10px;
    border-bottom: 2px solid #4CAF50;
    padding-bottom: 5px;
}

.section p {
    font-size: 1rem;
    margin: 5px 0;
}

.section img {
    width: 100%;
    max-height: 300px;
    object-fit: cover;
    border-radius: 8px;
}

ul {
    list-style: none;
    padding-left: 0;
}

ul li {
    font-size: 1rem;
    margin-bottom: 10px;
}

input[type="date"], input[type="text"], input[type="radio"], button {
    font-size: 1rem;
    padding: 10px;
    margin-top: 5px;
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Button Style */
button {
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
    padding: 15px;
    font-size: 1rem;
    width: 100%;
    margin-top: 20px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #45a049;
}

/* Radio Buttons Styling */
input[type="radio"] {
    width: auto;
    margin-right: 10px;
}

/* Payment Modal */
#paymentModal {
    display: none;
    position: fixed;
    top: 10%;
    left: 50%;
    transform: translateX(-50%);
    background-color: white;
    padding: 20px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    width: 90%;
    max-width: 400px;
    z-index: 1001;  /* Ensure modal content is above the overlay */
    border-radius: 8px;
}

#paymentModal h2 {
    font-size: 1.25rem;
    color: #4CAF50;
    margin-bottom: 15px;
}

#paymentDetailsForm label {
    display: block;
    margin-top: 10px;
}

#paymentDetailsForm input {
    width: calc(100% - 20px);
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Modal Overlay */
#paymentModalOverlay {
    content: "";
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;  /* Ensure the overlay is below the modal */
}

/* Payment Details Form Button */
#paymentDetailsForm button {
    background-color: #4CAF50;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 5px;
    width: 100%;
    cursor: pointer;
    font-size: 1rem;
}

#paymentDetailsForm button:hover {
    background-color: #45a049;
}


/* Responsive Styles */
@media (max-width: 768px) {
    .container {
        padding: 15px;
        margin: 10px;
    }

    .section img {
        max-height: 200px;
    }

    button {
        font-size: 0.9rem;
    }
}

    </style>
</head>

<body>
    <div class="container">
        <h1>Confirm Your Booking</h1>

        <div class="section">
            <h2>Destination: <?php echo $city['name']; ?></h2>
            <p><?php echo $city['description']; ?></p>
            <img src="images/<?php echo strtolower($city['name']); ?>.jpg" alt="<?php echo $city['name']; ?>">
        </div>

        <div class="section">
            <h2>Hotel Details</h2>
            <p><strong><?php echo $hotel['name']; ?></strong></p>
            <p>Category: <?php echo $hotel['star_rating']; ?></p>
            <p>Amenities: <?php echo $hotel['amenities']; ?>-Star</p>
            <p>Price per Night: ₹<?php echo number_format($hotel['price_per_night'], 2); ?></p>
        </div>

        <div class="section">
            <h2>Travel Details</h2>
            <p>Mode: <?php echo $travel['company_name']; ?></p>
            <p>Type: <?php echo $travel['type']; ?></p>
            <p>Departure: <?php echo $travel['departure']; ?></p>
            <p>Arrival: <?php echo $travel['arrival']; ?></p>
            <p>Price: ₹<?php echo number_format($travel['price'], 2); ?></p>
        </div>

        <div class="section">
            <h2>Meal Plan</h2>
            <p><?php echo $meal['name']; ?> - ₹<?php echo number_format($meal['price_per_night'], 2); ?> per night</p>
        </div>

        <div class="section">
            <h2>Activities Selected</h2>
            <?php if (!empty($activity_list)) { ?>
                <ul>
                    <?php foreach ($activity_list as $activity) { ?>
                        <li><?php echo $activity['name']; ?> - ₹<?php echo number_format($activity['price'], 2); ?></li>
                    <?php } ?>
                </ul>
            <?php } else { ?>
                <p>No activities selected</p>
            <?php } ?>
        </div>

        <div class="section">
            <h2>Total Price: ₹<?php echo number_format($total_price, 2); ?></h2>
            <p><strong>Payment Mode: </strong>
                <input type="radio" name="payment_method" value="cash_on_arrival" checked> Cash on Arrival
                <input type="radio" name="payment_method" value="pay_online"> Pay Online
            </p>
        </div>

        <form action="final_booking.php" method="POST" onsubmit="return validateForm()" id="paymentForm">
            <input type="hidden" name="city_id" value="<?php echo $city_id; ?>">
            <input type="hidden" name="hotel_id" value="<?php echo $hotel_id; ?>">
            <input type="hidden" name="travel_id" value="<?php echo $travel_id; ?>">
            <input type="hidden" name="meal_id" value="<?php echo $meal_id; ?>">
            <input type="hidden" name="selected_activities" value="<?php echo implode(',', $activities); ?>">

            <label>Travel Date:</label>
            <input type="date" name="travel_date" id="travel_date" required>

            <label>Return Date:</label>
            <input type="date" name="return_date" id="return_date" required>

            <input type="hidden" name="nights" id="nights">
            <input type="hidden" name="total_price" id="total_price">
            <input type="hidden" name="payment_method" id="payment_method">

            <button type="submit">Confirm Booking</button>
        </form>

        <!-- Payment Modal for Pay Online -->
        <div id="paymentModal" style="display:none;">
            <div>
                <h2>Payment Details</h2>
                <form id="paymentDetailsForm">
                    <label>Card Number:</label>
                    <input type="text" id="card_number" required>
                    <label>Card Expiry Date:</label>
                    <input type="text" id="expiry_date" required>
                    <label>CVV:</label>
                    <input type="text" id="cvv" required>
                    <button type="button" onclick="confirmPayment()">Confirm Payment</button>
                </form>
            </div>
        </div>

    </div>
<script>
   
   function validateForm() {
    let travelDate = new Date(document.getElementById("travel_date").value);
    let returnDate = new Date(document.getElementById("return_date").value);

    if (returnDate <= travelDate) {
        alert("Return date must be after travel date.");
        return false;
    }

    let nights = Math.ceil((returnDate - travelDate) / (1000 * 60 * 60 * 24));
    document.getElementById("nights").value = nights;

    let totalPrice = <?php echo $total_price; ?>;
    document.getElementById("total_price").value = totalPrice;

    // Update the payment method value based on the selected radio button
    let paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    document.getElementById("payment_method").value = paymentMethod;

    if (paymentMethod === 'pay_online') {
        // Create the modal overlay dynamically
        let overlay = document.createElement("div");
        overlay.id = "paymentModalOverlay";
        document.body.appendChild(overlay);

        // Open payment modal for online payment
        document.getElementById("paymentModal").style.display = "block";
        
        return false; // Prevent form submission for now
    }

    // Proceed directly if cash on arrival is selected
    return true;
}

function confirmPayment() {
    // Handle payment confirmation for online payment (e.g., integrate with payment gateway)
    alert("Payment confirmed. Your booking is being processed.");
    document.getElementById("paymentModal").style.display = "none";

    // Remove the overlay when modal is closed
    document.getElementById("paymentModalOverlay").remove();

    // Submit the form after payment confirmation
    document.getElementById("paymentForm").submit();
}
</script>

</body>
</html>
