-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2025 at 08:13 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `car_rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(500) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(5,2) DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `availability` enum('available','unavailable') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `specifications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specifications`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `name`, `image`, `price`, `discount`, `description`, `availability`, `created_at`, `updated_at`, `specifications`) VALUES
(1, 'Toyota Camry', 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=800', 50.00, 10.00, 'Comfortable sedan perfect for city driving and long trips.', 'available', '2025-12-03 16:35:38', '2025-12-03 19:09:54', '{\"gear_box\": \"Automat\", \"fuel\": \"Petrol\", \"doors\": \"4\", \"air_conditioner\": \"Yes\", \"seats\": \"5\", \"distance\": \"500 km\"}'),
(2, 'Honda Civic', 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800', 45.00, 50.00, 'Reliable and fuel-efficient compact car.', 'available', '2025-12-03 16:35:38', '2025-12-03 19:09:54', '{\"gear_box\": \"Automat\", \"fuel\": \"Hybrid\", \"doors\": \"4\", \"air_conditioner\": \"Yes\", \"seats\": \"5\", \"distance\": \"600 km\"}'),
(3, 'BMW 3 Series', 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800', 120.00, 15.00, 'Luxury sedan with premium features and performance.', 'available', '2025-12-03 16:35:38', '2025-12-03 19:09:54', '{\"gear_box\": \"Automat\", \"fuel\": \"Petrol\", \"doors\": \"4\", \"air_conditioner\": \"Yes\", \"seats\": \"5\", \"distance\": \"550 km\"}'),
(4, 'Mercedes-Benz C-Class', 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800', 130.00, 20.00, 'Elegant luxury car with advanced technology.', 'available', '2025-12-03 16:35:38', '2025-12-03 19:09:54', '{\"gear_box\": \"Automat\", \"fuel\": \"Diesel\", \"doors\": \"4\", \"air_conditioner\": \"Yes\", \"seats\": \"5\", \"distance\": \"700 km\"}'),
(5, 'Ford Mustang', 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=800', 100.00, 5.00, 'Iconic sports car with powerful engine.', 'available', '2025-12-03 16:35:38', '2025-12-03 19:09:54', '{\"gear_box\": \"Manual\", \"fuel\": \"Petrol\", \"doors\": \"2\", \"air_conditioner\": \"Yes\", \"seats\": \"4\", \"distance\": \"450 km\"}'),
(7, 'Rental cars', 'assets/images/car_1764788358_6930888643656.png', 300.00, 0.00, 'rental cars', 'available', '2025-12-03 17:53:39', '2025-12-03 19:09:54', '{\"gear_box\": \"Automat\", \"fuel\": \"Petrol\", \"doors\": \"4\", \"air_conditioner\": \"Yes\", \"seats\": \"5\", \"distance\": \"500 km\"}'),
(8, 'Toyota Camry', 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=800', 50.00, 10.00, 'Comfortable sedan perfect for city driving and long trips.', 'available', '2025-12-03 18:45:32', '2025-12-03 19:09:54', '{\"gear_box\": \"Automat\", \"fuel\": \"Petrol\", \"doors\": \"4\", \"air_conditioner\": \"Yes\", \"seats\": \"5\", \"distance\": \"500 km\"}'),
(9, 'Honda Civic', 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800', 45.00, 0.00, 'Reliable and fuel-efficient compact car.', 'available', '2025-12-03 18:45:32', '2025-12-03 19:09:54', '{\"gear_box\": \"Automat\", \"fuel\": \"Hybrid\", \"doors\": \"4\", \"air_conditioner\": \"Yes\", \"seats\": \"5\", \"distance\": \"600 km\"}'),
(10, 'BMW 3 Series', 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800', 120.00, 15.00, 'Luxury sedan with premium features and performance.', 'available', '2025-12-03 18:45:32', '2025-12-03 19:09:54', '{\"gear_box\": \"Automat\", \"fuel\": \"Petrol\", \"doors\": \"4\", \"air_conditioner\": \"Yes\", \"seats\": \"5\", \"distance\": \"550 km\"}'),
(11, 'Mercedes-Benz C-Class', 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800', 130.00, 20.00, 'Elegant luxury car with advanced technology.', 'available', '2025-12-03 18:45:32', '2025-12-03 19:09:54', '{\"gear_box\": \"Automat\", \"fuel\": \"Diesel\", \"doors\": \"4\", \"air_conditioner\": \"Yes\", \"seats\": \"5\", \"distance\": \"700 km\"}'),
(12, 'Ford Mustang', 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=800', 100.00, 5.00, 'Iconic sports car with powerful engine.', 'available', '2025-12-03 18:45:32', '2025-12-03 19:09:54', '{\"gear_box\": \"Manual\", \"fuel\": \"Petrol\", \"doors\": \"2\", \"air_conditioner\": \"Yes\", \"seats\": \"4\", \"distance\": \"450 km\"}'),
(13, 'Tesla Model 3', 'https://images.unsplash.com/photo-1560958089-b8a1929cea89?w=800', 150.00, 0.00, 'Electric vehicle with autopilot features.', 'available', '2025-12-03 18:45:32', '2025-12-03 19:09:54', '{\"gear_box\": \"Automat\", \"fuel\": \"Hybrid\", \"doors\": \"4\", \"air_conditioner\": \"Yes\", \"seats\": \"5\", \"distance\": \"800 km\"}');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `updated_at`) VALUES
(1, 'mad_to_eur', '0.092', '2025-12-03 18:57:49'),
(2, 'mad_to_usd', '0.1', '2025-12-03 18:57:49'),
(3, 'default_currency', 'MAD', '2025-12-03 18:57:49'),
(4, 'whatsapp_number', '212653330752', '2025-12-03 18:57:49'),
(5, 'logo_path', 'assets/images/RENTAL-CARS.png', '2025-12-03 18:45:32'),
(14, 'facebook_url', '', '2025-12-03 18:57:49'),
(15, 'twitter_url', '', '2025-12-03 18:57:49'),
(16, 'instagram_url', 'https://www.instagram.com/rentalcars.ettaajluxury/', '2025-12-03 18:57:49'),
(17, 'linkedin_url', '', '2025-12-03 18:57:49'),
(18, 'youtube_url', '', '2025-12-03 18:57:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
