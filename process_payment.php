<?php
// process_payment.php

$booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;
$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0.0;

// Validate payment success from the gateway
// Example: if Razorpay, you would validate signature here

// Update the booking status and payment method
$conn->query("UPDATE bookings SET payment_status = 'Completed', status = 'Confirmed' WHERE booking_id = $booking_id");

echo "Payment successful. Your booking is confirmed.";
?>
