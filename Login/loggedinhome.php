<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelScapes - Explore the World</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .city-card {
            transition: transform 0.3s;
        }
        .city-card:hover {
            transform: scale(1.05);
        }
        footer a {
            color: lightblue;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <?php
        // Database Connection
        $conn = new mysqli("localhost", "root", "", "travelscapes");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Fetch Cities
        $sql = "SELECT * FROM cities";
        $result = $conn->query($sql);
    ?>

    <!-- Hero Section -->
    <header class="bg-dark text-white text-center py-5">
        <h1>Welcome to TravelScapes</h1>
        <p>Your perfect travel partner for unforgettable experiences!</p>
    </header>
    
    <!-- About Section -->
    <section class="container my-5 text-center">
        <h2>About TravelScapes</h2>
        <p>TravelScapes offers the best tour packages, covering breathtaking destinations, comfortable hotels, and seamless travel experiences. Explore, book, and embark on your dream journey with us.</p>
    </section>
    
    <!-- Cities Section -->
    <section class="container my-5">
        <h2 class="text-center mb-4">Explore Our Destinations</h2>
        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card city-card">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['city_name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                            <a href="book.php?cityid=<?php echo $row['city_id']; ?>" class="btn btn-primary">View Packages</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4">
        <p>&copy; 2025 TravelScapes. All Rights Reserved.</p>
        <p>Contact: support@travelscapes.com | Follow us on <a href="#">Instagram</a>, <a href="#">Facebook</a></p>
    </footer>

    <?php $conn->close(); ?>
</body>
</html>
