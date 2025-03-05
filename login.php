<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "travel_booking");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if email exists
    $stmt = $conn->prepare("SELECT user_id, name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $name, $hashed_password, $role);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        if (password_verify($password, $hashed_password)) {
            // Store user details in session
            $_SESSION["user_id"] = $user_id;
            $_SESSION["name"] = $name;
            $_SESSION["role"] = $role;

            // Check if the user is an admin or normal user
            if ($role == 'admin') {
                // Redirect to admin dashboard if user is admin
                echo "<script>alert('Login Successful!'); window.location='admin_dashboard.php';</script>";
            } else {
                // Redirect to home page for normal users
                echo "<script>alert('Login Successful!'); window.location='home.php';</script>";
            }
        } else {
            echo "<script>alert('Incorrect password! Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Email not found! Please register first.');</script>";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TravelScapes</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            animation: fadeIn 1.5s ease-in-out;
        }

        .container {
            background: white;
            padding: 30px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            width: 320px;
            text-align: center;
            animation: slideUp 0.7s ease-out;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
        }

        input {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: 0.3s ease-in-out;
        }

        input:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(72, 255, 84, 0.8);
            outline: none;
        }

        button {
            background: #4CAF50;
            color: white;
            padding: 12px;
            border: none;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
            font-size: 18px;
            transition: 0.3s ease-in-out;
        }

        button:hover {
            background: #45a049;
            transform: translateY(-3px);
        }

        button:active {
            background: #388e3c;
            transform: translateY(1px);
        }

        p {
            font-size: 14px;
            color: #555;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Animations */
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        @keyframes slideUp {
            0% { transform: translateY(50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Signup</a></p>
    </div>
</body>
</html>
