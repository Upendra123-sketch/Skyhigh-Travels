-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2025 at 01:15 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `travel_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `activity_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`activity_id`, `city_id`, `name`, `description`, `duration`, `price`) VALUES
(1, 1, 'Gateway of India Tour', 'Explore the historic monument with a guide', '2 hours', 500.00),
(2, 1, 'Elephanta Caves Visit', 'Boat ride to ancient rock-cut caves', 'Half day', 1200.00),
(3, 1, 'Bollywood Studio Tour', 'Visit famous Bollywood sets and studios', '4 hours', 1500.00),
(4, 1, 'Marine Drive Walk', 'Scenic evening walk along the Queen’s Necklace', '1 hour', 0.00),
(5, 2, 'Solang Valley Adventure', 'Paragliding and adventure sports', '3 hours', 2000.00),
(6, 2, 'Rohtang Pass Snow Tour', 'Experience snow activities at Rohtang Pass', 'Full day', 2500.00),
(7, 2, 'Hadimba Temple Visit', 'Explore the ancient wooden temple', '1 hour', 300.00),
(8, 2, 'River Rafting in Beas', 'Thrilling river rafting experience', '3 hours', 1800.00),
(9, 3, 'Ooty Lake Boating', 'Enjoy a peaceful boat ride in Ooty Lake', '1 hour', 500.00),
(10, 3, 'Doddabetta Peak Trek', 'Trek to the highest peak in Ooty', '3 hours', 700.00),
(11, 3, 'Botanical Gardens Tour', 'Guided tour of the famous botanical gardens', '2 hours', 400.00),
(12, 3, 'Tea Factory Visit', 'Learn tea-making process with tasting', '1 hour', 300.00),
(13, 4, 'Kaziranga Safari', 'Explore the wildlife reserve (one-horned rhinos)', 'Full day', 3000.00),
(14, 4, 'Brahmaputra River Cruise', 'Evening cruise with cultural performances', '2 hours', 1200.00),
(15, 4, 'Kamakhya Temple Visit', 'Visit the famous Hindu pilgrimage site', '1 hour', 200.00),
(16, 4, 'Majuli Island Tour', 'Explore the world’s largest river island', 'Half day', 1500.00),
(17, 5, 'Golden Temple Visit', 'Spiritual visit to Sikhism’s holiest shrine', '2 hours', 0.00),
(18, 5, 'Wagah Border Ceremony', 'Watch the India-Pakistan border ceremony', 'Half day', 1000.00),
(19, 5, 'Jallianwala Bagh Memorial', 'Historical site of the 1919 massacre', '1 hour', 100.00),
(20, 5, 'Punjabi Village Tour', 'Experience authentic Punjabi rural life', '3 hours', 1200.00);

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `travel_option_id` int(11) DEFAULT NULL,
  `travel_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('Pending','Confirmed','Canceled') DEFAULT 'Pending',
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `meal_id` int(11) NOT NULL,
  `travel_date` date NOT NULL,
  `return_date` date NOT NULL,
  `nights` int(11) NOT NULL,
  `package_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `city_id`, `hotel_id`, `travel_option_id`, `travel_id`, `total_price`, `status`, `booking_date`, `full_name`, `email`, `phone`, `meal_id`, `travel_date`, `return_date`, `nights`, `package_id`) VALUES
(19, 2, 2, 11, NULL, 7, 38050.00, 'Confirmed', '2025-02-10 10:42:36', 'Demo Demo', 'demo@gmail.com', '8899889988', 5, '2025-02-11', '2025-02-14', 3, NULL),
(20, 2, 2, 3, 0, 4, 18000.00, 'Pending', '2025-02-10 06:14:59', 'Upendra Saw', 'demo@gmail.com', '987987979', 1, '0000-00-00', '2025-02-13', 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `booking_activities`
--

CREATE TABLE `booking_activities` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `city_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`city_id`, `name`, `description`) VALUES
(1, 'Mumbai', 'Mumbai, the financial capital of India, is known for its vibrant lifestyle, Bollywood, and historic landmarks.'),
(2, 'Manali', 'Manali is a beautiful hill station in Himachal Pradesh, known for its scenic mountains and adventure activities.'),
(3, 'Ooty', 'Ooty, the Queen of Hill Stations, is famous for its tea gardens and pleasant climate.'),
(4, 'Assam', 'Assam, in Northeast India, is known for its tea plantations, Kaziranga National Park, and vibrant culture.'),
(5, 'Amritsar', 'Amritsar, home to the Golden Temple, is a major cultural and religious center in Punjab.'),
(7, 'Kolkata', 'City in East');

-- --------------------------------------------------------

--
-- Table structure for table `city_images`
--

CREATE TABLE `city_images` (
  `image_id` int(11) NOT NULL,
  `city_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fixed_packages`
--

CREATE TABLE `fixed_packages` (
  `package_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `travel_id` int(11) NOT NULL,
  `meal_id` int(11) NOT NULL,
  `package_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `duration` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fixed_packages`
--

INSERT INTO `fixed_packages` (`package_id`, `city_id`, `hotel_id`, `travel_id`, `meal_id`, `package_name`, `description`, `duration`, `total_price`) VALUES
(1, 1, 1, 1, 1, 'Mumbai City Tour', 'Explore Mumbai with a guided tour and premium hotel stay.', 3, 15000.00),
(2, 2, 3, 4, 5, 'Manali Adventure', 'Enjoy trekking and skiing in Manali with adventure activities.', 4, 18000.00),
(3, 3, 5, 7, 9, 'Ooty Getaway', 'Relax in the scenic beauty of Ooty with top-class services.', 5, 20000.00),
(4, 4, 7, 10, 13, 'Assam Tea & Culture', 'Discover the rich culture of Assam and its famous tea gardens.', 4, 17000.00),
(5, 5, 9, 12, 15, 'Golden Temple Tour', 'Visit the holy Golden Temple and experience Amritsar’s heritage.', 3, 16000.00),
(6, 1, 1, 2, 3, 'Dus Ka Dum', 'Mumabi at cheapest rate , have alook at inida\'s finanace capital without get finiancil load at your pocket', 3, 10000.00),
(7, 5, 43, 15, 12, 'Hadippa', 'Waheguru ji da khalsa , waheguru ji di fateh', 3, 12999.00);

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `hotel_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `star_rating` enum('3-Star','4-Star','5-Star') NOT NULL,
  `amenities` text NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`hotel_id`, `city_id`, `name`, `star_rating`, `amenities`, `price_per_night`) VALUES
(1, 1, 'Taj Mahal Palace', '5-Star', 'Free WiFi, Pool, Spa, Gym', 12000.00),
(2, 1, 'The Oberoi Mumbai', '5-Star', 'Free Breakfast, Ocean View, Gym', 10000.00),
(3, 1, 'ITC Grand Central', '4-Star', 'Restaurant, Free Parking, Lounge', 8000.00),
(4, 1, 'Trident Nariman Point', '4-Star', 'Free WiFi, Pool, Airport Shuttle', 7500.00),
(5, 1, 'Hotel Marine Plaza', '3-Star', 'Basic Amenities, City View, Parking', 5000.00),
(6, 1, 'Novotel Mumbai', '3-Star', 'Fitness Center, Restaurant, Bar', 4500.00),
(7, 1, 'Residency Hotel Fort', '3-Star', 'AC Rooms, Free WiFi, Breakfast', 4000.00),
(8, 1, 'The Shalimar Hotel', '4-Star', 'Spa, Conference Room, City View', 7000.00),
(9, 1, 'Sahara Star', '5-Star', 'Luxury Suites, Indoor Pool, Rooftop Dining', 11000.00),
(10, 1, 'Hotel Suba Palace', '3-Star', 'Free WiFi, Budget Friendly, Parking', 3500.00),
(11, 2, 'The Himalayan', '5-Star', 'Mountain View, Heated Pool, Spa', 9000.00),
(12, 2, 'Span Resort & Spa', '5-Star', 'Riverfront, Free Breakfast, Hot Tub', 8500.00),
(13, 2, 'Solang Valley Resort', '4-Star', 'Skiing Access, Bonfire, Spa', 7000.00),
(14, 2, 'Manu Allaya Resort', '4-Star', 'Himalayan View, Fitness Center', 6500.00),
(15, 2, 'Snow Valley Resorts', '3-Star', 'City Center, Complimentary Breakfast', 5000.00),
(16, 2, 'Johnson Lodge', '3-Star', 'Free WiFi, Pet Friendly, Lounge', 4500.00),
(17, 2, 'Whispering Valley Resort', '3-Star', 'Near Mall Road, Cozy Rooms', 4200.00),
(18, 2, 'Hotel Mountain Top', '4-Star', 'Luxury Rooms, Mountain View', 7200.00),
(19, 2, 'Sterling Manali', '4-Star', 'Banquet Hall, Conference Facilities', 6800.00),
(20, 2, 'Apple Country Resort', '5-Star', 'Spa, Vegetarian Only, Hilltop Location', 9400.00),
(21, 3, 'Savoy - IHCL', '5-Star', 'Colonial Style, Garden View, Fine Dining', 8800.00),
(22, 3, 'Club Mahindra Derby Green', '4-Star', 'Near Race Course, Spa, Lawn', 7200.00),
(23, 3, 'Sterling Ooty Elk Hill', '4-Star', 'Terrace Dining, Cozy Rooms', 6900.00),
(24, 3, 'Gem Park Ooty', '4-Star', 'Hill View, Multi-Cuisine Restaurant', 7100.00),
(25, 3, 'Fortune Resort Sullivan Court', '3-Star', 'Kids Play Area, Lawn, Free Breakfast', 4800.00),
(26, 3, 'Hotel Lake View', '3-Star', 'Cottage Style Stay, Lake View', 4500.00),
(27, 3, 'WelcomHeritage Fernhills', '5-Star', 'Heritage Property, Library, Spa', 9700.00),
(28, 3, 'Hotel Preethi Palace', '3-Star', 'Budget Friendly, Central Location', 3500.00),
(29, 3, 'Hotel Darshan', '3-Star', 'Near Ooty Lake, Good View', 4000.00),
(30, 3, 'Deccan Park Resort', '4-Star', 'Eco-Friendly, Spacious Rooms', 7200.00),
(31, 4, 'Radisson Blu Guwahati', '5-Star', 'Free WiFi, Pool, Gym, Spa', 9500.00),
(32, 4, 'Novotel Guwahati', '5-Star', 'Luxury Rooms, Lounge, Airport Transfer', 9000.00),
(33, 4, 'Hotel Dynasty', '4-Star', 'Multi-Cuisine Restaurant, Business Center', 7500.00),
(34, 4, 'Vivanta Assam', '4-Star', 'River View, Spa, Free Breakfast', 7200.00),
(35, 4, 'Landmark Hotel', '3-Star', 'Budget-Friendly, Free Parking, City View', 5000.00),
(36, 4, 'Hotel Atithi', '3-Star', 'Centrally Located, Free WiFi, Family Rooms', 4800.00),
(37, 4, 'Hotel Gateway Grandeur', '3-Star', 'Business Hotel, Complimentary Breakfast', 4500.00),
(38, 4, 'Hotel Kiranshree Grand', '4-Star', 'Rooftop Dining, Bar, Lounge', 7800.00),
(39, 4, 'The Lily Hotel', '5-Star', 'Luxury Suites, Banquet Hall, Spa', 9600.00),
(40, 4, 'Hotel Prag Continental', '3-Star', 'Good Location, Affordable, Parking Available', 4300.00),
(41, 5, 'Hyatt Regency Amritsar', '5-Star', 'Luxury Rooms, Free Breakfast, Pool', 10000.00),
(42, 5, 'Taj Swarna', '5-Star', 'Spa, City View, Rooftop Restaurant', 9800.00),
(43, 5, 'Ramada Amritsar', '4-Star', 'Historic Location, Free WiFi, Family-Friendly', 7500.00),
(44, 5, 'Holiday Inn Amritsar', '4-Star', 'Near Golden Temple, Complimentary Breakfast', 7300.00),
(45, 5, 'Golden Sarovar Portico', '4-Star', 'Modern Amenities, Bar, Business Lounge', 7200.00),
(46, 5, 'Hotel Hong Kong Inn', '3-Star', 'Affordable, Near Market, Free WiFi', 4500.00),
(47, 5, 'Hotel Abode', '3-Star', 'City View, Free Breakfast, AC Rooms', 4800.00),
(48, 5, 'Hotel City Park', '3-Star', 'Budget-Friendly, Free Parking, Good Location', 4200.00),
(49, 5, 'Regenta Central Amritsar', '4-Star', 'Premium Stay, Banquet Hall, Lounge', 7400.00),
(50, 5, 'Hotel Shiraz Regency', '3-Star', 'Classic Interiors, Good Connectivity', 4000.00),
(51, 7, 'Hotel Bengal', '4-Star', 'Gym,Banquet Hall, Swimming Pool', 1500.00);

-- --------------------------------------------------------

--
-- Table structure for table `itinerary`
--

CREATE TABLE `itinerary` (
  `itinerary_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `details` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meal_plans`
--

CREATE TABLE `meal_plans` (
  `meal_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `type` enum('Veg','Non-Veg','Mixed') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meal_plans`
--

INSERT INTO `meal_plans` (`meal_id`, `city_id`, `name`, `description`, `price_per_night`, `type`) VALUES
(1, 1, 'Breakfast Only', 'Includes breakfast buffet', 300.00, 'Veg'),
(2, 1, 'Half Board', 'Breakfast and Dinner included', 600.00, 'Mixed'),
(3, 1, 'Full Board', 'All meals included (Breakfast, Lunch, Dinner)', 900.00, 'Non-Veg'),
(4, 2, 'Breakfast Only', 'Simple breakfast with tea/coffee', 250.00, 'Veg'),
(5, 2, 'Half Board', 'Includes breakfast and dinner', 550.00, 'Mixed'),
(6, 2, 'Full Board', 'All meals included with local delicacies', 850.00, 'Non-Veg'),
(7, 3, 'Breakfast Only', 'Healthy vegetarian breakfast', 280.00, 'Veg'),
(8, 3, 'Half Board', 'Includes breakfast and dinner', 580.00, 'Mixed'),
(9, 3, 'Full Board', 'Complete meal package', 880.00, 'Non-Veg'),
(10, 4, 'Breakfast Only', 'Includes Assamese breakfast', 270.00, 'Veg'),
(11, 4, 'Half Board', 'Breakfast and Dinner with regional dishes', 570.00, 'Mixed'),
(12, 4, 'Full Board', 'Complete local cuisine experience', 870.00, 'Non-Veg'),
(13, 5, 'Breakfast Only', 'North Indian breakfast buffet', 320.00, 'Veg'),
(14, 5, 'Half Board', 'Breakfast and Dinner (Punjabi cuisine)', 620.00, 'Mixed'),
(15, 5, 'Full Board', 'All meals included with Punjabi specialties', 920.00, 'Non-Veg'),
(17, 7, '3 time meal', NULL, 999.00, 'Veg');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `payment_method` enum('Credit Card','UPI','PayPal') NOT NULL,
  `payment_status` enum('Success','Failed','Pending') DEFAULT 'Pending',
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `travel_options`
--

CREATE TABLE `travel_options` (
  `travel_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `type` enum('Bus','Train') NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `departure` varchar(255) NOT NULL,
  `arrival` varchar(255) NOT NULL,
  `route_details` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `facilities` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `travel_options`
--

INSERT INTO `travel_options` (`travel_id`, `city_id`, `type`, `company_name`, `departure`, `arrival`, `route_details`, `price`, `facilities`) VALUES
(1, 1, 'Bus', 'Neeta Travels', 'Mumbai', 'Manali', 'Mumbai -> Delhi -> Manali', 2500.00, 'Luxury AC, Recliner, WiFi'),
(2, 1, 'Train', 'Indian Railways', 'Mumbai CST', 'Manali', NULL, 2800.00, 'AC 3-Tier, Bedding, Meals'),
(3, 1, 'Bus', 'VRL Travels', 'Mumbai', 'Ooty', 'Mumbai -> Bangalore -> Ooty', 3000.00, 'Luxury AC Sleeper, WiFi'),
(4, 1, 'Train', 'Indian Railways', 'Mumbai CST', 'Ooty', NULL, 3200.00, 'AC 2-Tier, Lounge Access'),
(5, 2, 'Bus', 'Himachal Travels', 'Manali', 'Delhi', 'Manali -> Chandigarh -> Delhi', 1200.00, 'Volvo AC, WiFi, Charging'),
(6, 2, 'Train', 'Indian Railways', 'Manali Station', 'Delhi', NULL, 1500.00, 'Sleeper Class, Food, AC Fans'),
(7, 2, 'Bus', 'Snow Line Express', 'Manali', 'Mumbai', 'Manali -> Delhi -> Mumbai', 2800.00, 'Luxury AC Sleeper'),
(8, 2, 'Train', 'Indian Railways', 'Manali Station', 'Mumbai', NULL, 3500.00, 'AC 3-Tier, Meals'),
(9, 3, 'Bus', 'KPN Travels', 'Ooty', 'Bangalore', 'Ooty -> Mysore -> Bangalore', 1000.00, 'Luxury AC, Semi-Sleeper'),
(10, 3, 'Train', 'Indian Railways', 'Ooty Station', 'Bangalore', NULL, 1200.00, 'AC Chair Car, Free Snacks'),
(11, 3, 'Bus', 'SRS Travels', 'Ooty', 'Mumbai', 'Ooty -> Bangalore -> Mumbai', 3100.00, 'AC Sleeper, WiFi'),
(12, 3, 'Train', 'Indian Railways', 'Ooty Station', 'Mumbai', NULL, 3500.00, 'AC 2-Tier, Bedding'),
(13, 4, 'Bus', 'Assam Express', 'Assam', 'Kolkata', 'Assam -> Guwahati -> Kolkata', 2200.00, 'AC Sleeper, Meals'),
(14, 4, 'Train', 'Indian Railways', 'Assam Station', 'Kolkata', NULL, 2700.00, 'AC 3-Tier, Lounge Access'),
(15, 5, 'Bus', 'Punjab Travels', 'Amritsar', 'Delhi', 'Amritsar -> Chandigarh -> Delhi', 1300.00, 'AC Recliner, Snacks'),
(16, 5, 'Train', 'Indian Railways', 'Amritsar Station', 'Delhi', NULL, 1600.00, 'Sleeper Class, Meals'),
(17, 5, 'Bus', 'Indo Express', 'Amritsar', 'Mumbai', 'Amritsar -> Jaipur -> Mumbai', 2800.00, 'Luxury AC Sleeper'),
(18, 5, 'Train', 'Indian Railways', 'Amritsar Station', 'Mumbai', NULL, 3200.00, 'AC 2-Tier, Bedding, Meals'),
(19, 7, 'Train', '', 'Mumbai Central', 'Kolkata', NULL, 2564.00, '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(10) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `phone`, `created_at`, `role`) VALUES
(1, 'Rahul Pandey', 'rahul@gmail.com', '$2y$10$Rx0PrhcjSlYLI9v5ZeWsU.BTSH9RIFKcdKNwW9teGh4RS6GHkRLZK', '987654321', '2025-02-08 07:35:52', 'user'),
(2, 'Demo Demo', 'demo@gmail.com', '$2y$10$eB/YqR..ON1SWSweTnieReD3ZYyoOBFuggzSF10EkPNquSdJ1VwK.', '8899889988', '2025-02-09 10:17:57', 'user'),
(3, 'test test', 'test@gmail.com', '$2y$10$caI10pn5a/9FmcILepFy4OG/dFuoYFBUrpTdge3TvoMSwKi1VaBXy', '8899889988', '2025-02-09 10:23:37', 'user'),
(4, 'Admin', 'admin@gmail.com', '$2b$12$pdICcB2GQ4APWKeZCtqspumDb1jWUhvBz1EEmB8BdRLyFWfdxGpsu', '', '2025-02-09 11:23:41', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`activity_id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `hotel_id` (`hotel_id`),
  ADD KEY `travel_id` (`travel_id`);

--
-- Indexes for table `booking_activities`
--
ALTER TABLE `booking_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`city_id`);

--
-- Indexes for table `city_images`
--
ALTER TABLE `city_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `fixed_packages`
--
ALTER TABLE `fixed_packages`
  ADD PRIMARY KEY (`package_id`),
  ADD KEY `fk_fixed_city` (`city_id`),
  ADD KEY `fk_fixed_hotel` (`hotel_id`),
  ADD KEY `fk_fixed_travel` (`travel_id`),
  ADD KEY `fk_fixed_meal` (`meal_id`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`hotel_id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `itinerary`
--
ALTER TABLE `itinerary`
  ADD PRIMARY KEY (`itinerary_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `meal_plans`
--
ALTER TABLE `meal_plans`
  ADD PRIMARY KEY (`meal_id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `travel_options`
--
ALTER TABLE `travel_options`
  ADD PRIMARY KEY (`travel_id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `booking_activities`
--
ALTER TABLE `booking_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `city_images`
--
ALTER TABLE `city_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fixed_packages`
--
ALTER TABLE `fixed_packages`
  MODIFY `package_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `hotel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `itinerary`
--
ALTER TABLE `itinerary`
  MODIFY `itinerary_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `meal_plans`
--
ALTER TABLE `meal_plans`
  MODIFY `meal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `travel_options`
--
ALTER TABLE `travel_options`
  MODIFY `travel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`city_id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`city_id`) REFERENCES `cities` (`city_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_4` FOREIGN KEY (`travel_id`) REFERENCES `travel_options` (`travel_id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_activities`
--
ALTER TABLE `booking_activities`
  ADD CONSTRAINT `booking_activities_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_activities_ibfk_2` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`activity_id`) ON DELETE CASCADE;

--
-- Constraints for table `city_images`
--
ALTER TABLE `city_images`
  ADD CONSTRAINT `city_images_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`city_id`);

--
-- Constraints for table `fixed_packages`
--
ALTER TABLE `fixed_packages`
  ADD CONSTRAINT `fk_fixed_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`city_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fixed_hotel` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fixed_meal` FOREIGN KEY (`meal_id`) REFERENCES `meal_plans` (`meal_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fixed_travel` FOREIGN KEY (`travel_id`) REFERENCES `travel_options` (`travel_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hotels`
--
ALTER TABLE `hotels`
  ADD CONSTRAINT `hotels_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`city_id`) ON DELETE CASCADE;

--
-- Constraints for table `itinerary`
--
ALTER TABLE `itinerary`
  ADD CONSTRAINT `itinerary_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `meal_plans`
--
ALTER TABLE `meal_plans`
  ADD CONSTRAINT `meal_plans_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`city_id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `travel_options`
--
ALTER TABLE `travel_options`
  ADD CONSTRAINT `travel_options_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`city_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
