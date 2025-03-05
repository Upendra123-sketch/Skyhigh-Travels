<?php
session_start();
require 'db_connect.php'; // Make sure this file exists and contains database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    // Basic Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION["error"] = "All fields are required!";
        header("Location: signup.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["error"] = "Invalid email format!";
        header("Location: signup.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION["error"] = "Passwords do not match!";
        header("Location: signup.php");
        exit();
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $_SESSION["error"] = "Username or Email already exists!";
        $stmt->close();
        header("Location: signup.php");
        exit();
    }
    $stmt->close();

    // Hash the password
    $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    if (!$stmt) {
        $_SESSION["error"] = "Database error: " . $conn->error;
        header("Location: signup.php");
        exit();
    }

    $stmt->bind_param("sss", $username, $email, $hashedPwd);

    if ($stmt->execute()) {
        $_SESSION["success"] = "Signup successful! You can now log in.";
        header("Location: login.php"); // Redirect to login page
        exit();
    } else {
        $_SESSION["error"] = "Something went wrong! Try again.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - TravelScapes</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your CSS file -->
</head>
<body>

<div class="signup-container">
    <h2>Signup</h2>
    <?php
        if (isset($_SESSION["error"])) {
            echo "<p style='color: red;'>" . $_SESSION["error"] . "</p>";
            unset($_SESSION["error"]);
        }
        if (isset($_SESSION["success"])) {
            echo "<p style='color: green;'>" . $_SESSION["success"] . "</p>";
            unset($_SESSION["success"]);
        }
    ?>

    <form action="signup.php" method="POST">
        <label>Username:</label>
        <input type="text" name="username" required>
        
        <label>Email:</label>
        <input type="email" name="email" required>
        
        <label>Password:</label>
        <input type="password" name="password" required>
        
        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">Sign Up</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

</body>
</html>
