<?php
session_start();

// Redirect to login if user is not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - SkyHigh Travels</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
        }
        .navbar {
            background-color: #0056b3;
            color: white;
            padding: 20px 30px;
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
            font-size: 26px;
        }
        .container {
            width: 80%;
            margin: 40px auto;
            text-align: center;
        }
        .about-us {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
        .about-us h1 {
            font-size: 40px;
            color: #333;
            margin-bottom: 20px;
        }
        .about-us p {
            font-size: 18px;
            color: #555;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 28px;
            color: #333;
            margin-bottom: 15px;
            text-align: center;
        }
        .section-content {
            font-size: 18px;
            color: #555;
            line-height: 1.6;
            margin-bottom: 40px;
        }
        .section-content ul {
            list-style: none;
            padding-left: 0;
        }
        .section-content ul li {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .section-content ul li:before {
            content: '✔️';
            margin-right: 10px;
            color: #0056b3;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        footer p {
            margin: 0;
        }
    </style>
</head>
<body>

  

    <div class="container">
        <div class="about-us">
            <h1>About SkyHigh Travels</h1>
            <p>At SkyHigh Travels, we believe in making travel an unforgettable experience. With a commitment to offering exceptional service, we curate personalized travel packages that cater to every type of traveler, whether you're seeking adventure, relaxation, or cultural exploration.</p>

            <div class="section-title">Our Mission</div>
            <div class="section-content">
                <p>Our mission is simple: to provide world-class travel experiences that inspire and delight. We aim to simplify travel planning, offering flexible, personalized packages that allow our customers to experience the world in a way that suits their interests and preferences.</p>
            </div>

            <div class="section-title">Why Choose SkyHigh Travels?</div>
            <div class="section-content">
                <ul>
                    <li><strong>Expertise & Experience:</strong> With years of experience in the travel industry, we have built strong partnerships with top hotels, transportation services, and local experts worldwide.</li>
                    <li><strong>Tailored Experiences:</strong> Every traveler is different, which is why we offer highly customizable packages to meet your specific needs and preferences.</li>
                    <li><strong>Competitive Pricing:</strong> Enjoy the best rates for flights, hotels, and tours without compromising quality. We offer excellent value for money.</li>
                    <li><strong>24/7 Support:</strong> Our dedicated customer support team is available around the clock to assist with your travel needs, ensuring peace of mind at every step of your journey.</li>
                </ul>
            </div>

            <div class="section-title">Our Core Values</div>
            <div class="section-content">
                <p>Our core values guide everything we do, ensuring that every interaction with our clients is rooted in trust, integrity, and excellence:</p>
                <ul>
                    <li><strong>Customer-Centric:</strong> Your satisfaction is our top priority. We listen to your needs and ensure that your journey is smooth and hassle-free.</li>
                    <li><strong>Innovation:</strong> We constantly evolve to offer new destinations, unique experiences, and cutting-edge travel technology.</li>
                    <li><strong>Social Responsibility:</strong> We are committed to sustainable tourism, respecting local cultures, and minimizing our environmental impact.</li>
                </ul>
            </div>

            <div class="section-title">Our Story</div>
            <div class="section-content">
                <p>SkyHigh Travels was founded with the vision of creating unforgettable travel experiences. From our humble beginnings, we have grown into a trusted name in the travel industry, bringing joy and excitement to thousands of travelers across the globe. Our passion for travel drives us to continually improve and offer exceptional service to all our clients.</p>
            </div>

            <div class="section-title">Join Us On Our Journey</div>
            <div class="section-content">
                <p>At SkyHigh Travels, we are more than just a travel agency – we are your partner in creating memories that last a lifetime. Whether you're planning a getaway, an adventure trip, or a romantic retreat, let us help you make your dreams come true.</p>
                <p>Explore the world with SkyHigh Travels. Your adventure begins here!</p>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 SkyHigh Travels. All rights reserved.</p>
    </footer>

</body>
</html>
