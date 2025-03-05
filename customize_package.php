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

// Fetch hotels
$hotels = $conn->query("SELECT * FROM hotels WHERE city_id = $city_id");

// Fetch travel options
$travel_options = $conn->query("SELECT * FROM travel_options WHERE city_id = $city_id");

// Fetch activities
$activities = $conn->query("SELECT * FROM activities WHERE city_id = $city_id");

// Fetch meal plans
$meal_plans = $conn->query("SELECT * FROM meal_plans WHERE city_id = $city_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customize Your Package - <?php echo $city['name']; ?></title>
    <style>
       body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f0f8ff;
    text-align: center;
    margin: 0;
    padding: 0;
}

.container {
    width: 80%;
    max-width: 900px;
    margin: 30px auto;
    background: linear-gradient(to bottom, #ffffff, #e0f7fa);
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease;
}

.container:hover {
    transform: scale(1.02);
}

h1 {
    font-size: 28px;
    margin-bottom: 25px;
    color: #00796b;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
}

form {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

label {
    font-size: 18px;
    color: #004d40;
    margin-bottom: 10px;
    font-weight: bold;
    text-align: left;
}

select,
input[type="number"],
input[type="date"],
button {
    width: 100%;
    padding: 14px;
    font-size: 16px;
    border-radius: 8px;
    border: 2px solid #00796b;
    background-color: #ffffff;
    transition: border-color 0.3s ease, background-color 0.3s ease;
    box-sizing: border-box;
}

select:focus,
input[type="number"]:focus,
input[type="date"]:focus {
    border-color: #004d40;
    background-color: #e0f7fa;
    outline: none;
}

button {
    background-color: #00796b;
    color: white;
    font-weight: bold;
    border: none;
    cursor: pointer;
    border-radius: 8px;
    padding: 16px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

button:hover {
    background-color: #004d40;
    transform: translateY(-2px);
}

button:active {
    background-color: #00332e;
    transform: translateY(1px);
}

h2 {
    font-size: 22px;
    font-weight: bold;
    color: #00796b;
    margin-top: 20px;
}

input[type="checkbox"] {
    margin-right: 10px;
}

div {
    text-align: left;
    margin-bottom: 10px;
}

.container select,
.container input[type="number"],
.container input[type="date"] {
    box-sizing: border-box;
}

.container input[type="checkbox"] {
    margin-top: 5px;
}

.container input[type="checkbox"]:checked {
    background-color: #00796b;
}

@media (max-width: 768px) {
    .container {
        width: 95%;
        padding: 20px;
    }

    h1 {
        font-size: 24px;
    }

    label,
    button {
        font-size: 14px;
    }

    h2 {
        font-size: 18px;
    }
}


    </style>
    <script>
        function updateTotalPrice() {
            let total = 0;

            // Hotel cost
            let hotel = document.getElementById("hotel");
            let hotelPrice = parseFloat(hotel.options[hotel.selectedIndex]?.getAttribute("data-price")) || 0;
            let hotelId = hotel.value || "";
            document.getElementById("selected_hotel").value = hotelId;

            let nights = parseInt(document.getElementById("nights").value) || 1;
            total += hotelPrice * nights;

            // Travel cost
            let travel = document.getElementById("travel");
            let travelPrice = parseFloat(travel.options[travel.selectedIndex]?.getAttribute("data-price")) || 0;
            let travelId = travel.value || "";
            document.getElementById("selected_travel").value = travelId;

            total += travelPrice;

            // Activities cost
            let activityCheckboxes = document.querySelectorAll('input[name="activities[]"]:checked');
            let selectedActivities = [];
            activityCheckboxes.forEach(activity => {
                total += parseFloat(activity.getAttribute("data-price")) || 0;
                selectedActivities.push(activity.value);
            });
            document.getElementById("selected_activities").value = selectedActivities.join(",");





            // Meal plan cost
            let mealPlan = document.getElementById("meal_plan");
            let mealPrice = parseFloat(mealPlan.options[mealPlan.selectedIndex]?.getAttribute("data-price")) || 0;
            let mealId = mealPlan.value || "";
            document.getElementById("selected_meal").value = mealId;

            total += mealPrice * nights;

            // Update total price
            document.getElementById("total_price").innerText = "Total Price: ₹" + total.toFixed(2);
            document.getElementById("total_price_input").value = total;
        }
    </script>
</head>
<body>

    <div class="container">
        <h1>Customize Your Package for <?php echo $city['name']; ?></h1>

        <form action="confirm_booking.php" method="POST">
            <input type="hidden" name="city_id" value="<?php echo $city_id; ?>">
            <input type="hidden" name="user_id" value="<?php echo $_SESSION["user_id"]; ?>">
            <input type="hidden" id="selected_hotel" name="selected_hotel">
            <input type="hidden" id="selected_travel" name="selected_travel">
            <input type="hidden" id="selected_meal" name="selected_meal">
            <input type="hidden" id="selected_activities" name="selected_activities">
            <input type="hidden" id="total_price_input" name="total_price">
            


            <!-- Hotel Selection -->
            <label for="hotel">Select Hotel:</label>
            <select id="hotel" name="hotel" onchange="updateTotalPrice()" required>
                <option value="">-- Choose Hotel --</option>
                <?php while ($hotel = $hotels->fetch_assoc()) { ?>
                    <option value="<?php echo $hotel['hotel_id']; ?>" data-price="<?php echo $hotel['price_per_night']; ?>">
                        <?php echo $hotel['name']; ?> - ₹<?php echo number_format($hotel['price_per_night'], 2); ?> per night
                    </option>
                <?php } ?>
            </select>



            <!-- Number of Nights -->
            <label for="nights">Number of Nights:</label>
            <input type="number" id="nights" name="nights" min="1" value="1" onchange="updateTotalPrice()" required>

            <!-- Travel Options -->
            <label for="travel">Select Travel Option:</label>
            <select id="travel" name="travel" onchange="updateTotalPrice()" required>
                <option value="">-- Choose Travel --</option>
                <?php while ($travel = $travel_options->fetch_assoc()) { ?>
                    <option value="<?php echo $travel['travel_id']; ?>" data-price="<?php echo $travel['price']; ?>">
                        <?php echo $travel['company_name']; ?> - ₹<?php echo number_format($travel['price'], 2); ?>
                    </option>
                <?php } ?>
            </select>

            <!-- Activities -->
            <label>Choose Activities:</label>
            <?php while ($activity = $activities->fetch_assoc()) { ?>
                <div>
                    <input type="checkbox" name="activities[]" value="<?php echo $activity['activity_id']; ?>"
                           data-price="<?php echo $activity['price']; ?>" onchange="updateTotalPrice()">
                    <?php echo $activity['name']; ?> - ₹<?php echo number_format($activity['price'], 2); ?>
                </div>
            <?php } ?>

            <!-- Meal Plans -->
            <label for="meal_plan">Select Meal Plan:</label>
            <select id="meal_plan" name="meal_plan" onchange="updateTotalPrice()" required>
                <option value="">-- Choose Meal Plan --</option>
                <?php while ($meal = $meal_plans->fetch_assoc()) { ?>
                    <option value="<?php echo $meal['meal_id']; ?>" data-price="<?php echo $meal['price_per_night']; ?>">
                        <?php echo $meal['name']; ?> - ₹<?php echo number_format($meal['price_per_night'], 2); ?> per night
                    </option>
                <?php } ?>
            </select>

            <!-- Total Price -->
            <h2 id="total_price">Total Price: ₹0.00</h2>

            <button type="submit">Confirm Booking</button>
        </form>
    </div>

</body>
</html>

<?php
$conn->close();
?>
