-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 21, 2025 at 05:00 PM
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
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$q9937Qc2oXCV9ZUIsBU6PutFmUjfCWql1WHRfpWPG9v50nF6ViRpi', '2025-12-21 15:13:56', '2025-12-21 15:13:56');

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(500) NOT NULL,
  `seats` int(11) DEFAULT NULL,
  `bags` int(11) DEFAULT NULL,
  `gear` varchar(50) DEFAULT NULL,
  `fuel` varchar(50) DEFAULT NULL,
  `price_day` decimal(10,2) DEFAULT NULL,
  `price_week` decimal(10,2) DEFAULT NULL,
  `price_month` decimal(10,2) DEFAULT NULL,
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

INSERT INTO `cars` (`id`, `name`, `image`, `seats`, `bags`, `gear`, `fuel`, `price_day`, `price_week`, `price_month`, `price`, `discount`, `description`, `availability`, `created_at`, `updated_at`, `specifications`) VALUES
(36, 'Mercedes-Benz A-Class', 'Mercedes-Benz A-Class.jpg', 5, 2, 'Automatic', 'Diesel', 900.00, 6300.00, 25500.00, 680.00, 0.00, NULL, 'available', '2025-12-17 13:47:53', '2025-12-17 14:07:46', NULL),
(39, 'Audi Q8 S-Line', 'Audi Q8 S-Line.jpg', 5, 4, 'Automatic', 'Petrol', 2300.00, 13800.00, 46000.00, 1100.00, 0.00, NULL, 'available', '2025-12-17 13:47:53', '2025-12-17 14:07:46', NULL),
(44, 'Porsche Cayenne Coupé', 'Porsche Cayenne Coup.jpg', 5, 4, 'Automatic', 'Petrol', 3800.00, 22800.00, 76000.00, 1400.00, 0.00, NULL, 'available', '2025-12-17 13:47:53', '2025-12-17 14:07:46', NULL),
(45, 'Range Rover Sport', 'Range Rover Sport.jpg', 5, 4, 'Automatic', 'Petrol', 4000.00, 24000.00, 80000.00, 1200.00, 0.00, NULL, 'available', '2025-12-17 13:47:53', '2025-12-17 14:07:46', NULL),
(46, 'Range Rover Vogue', 'Range Rover Vogue.jpg', 5, 5, 'Automatic', 'Petrol', 5300.00, 31800.00, 106000.00, 1300.00, 0.00, NULL, 'available', '2025-12-17 13:47:53', '2025-12-17 14:07:46', NULL),
(48, 'Mercedes-Benz G63 AMG', 'Mercedes-Benz G63 AMG.jpg', 5, 4, 'Automatic', 'Petrol', 13000.00, 78000.00, 260000.00, 1500.00, 0.00, NULL, 'available', '2025-12-17 13:47:53', '2025-12-17 14:07:46', NULL),
(52, 'Dacia logan  2025', 'Dacia logan 2025 (1).jpeg', 5, 4, 'Manual', 'Diesel', 300.00, 1900.00, 7500.00, 300.00, 5.00, NULL, 'available', '2025-12-17 13:47:53', '2025-12-17 14:07:46', NULL),
(53, 'AUDI A3 S-line sportback a2025/2024', 'AUDI A3 S-line sportback a20252024.jpeg', 5, 3, 'Automatic', 'Petrol', 1100.00, 6400.00, 25500.00, 480.00, 8.00, NULL, 'available', '2025-12-17 13:47:53', '2025-12-17 14:07:46', NULL),
(54, 'Clio 5 2025/2024', 'Clio 5 20252024.jpeg', 5, 3, 'Manual', 'Diesel', 310.00, 1900.00, 7500.00, 450.00, 5.00, NULL, 'available', '2025-12-17 13:47:53', '2025-12-17 14:07:46', NULL);

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
(1, 'mad_to_eur', '0.092', '2025-12-21 15:27:42'),
(2, 'mad_to_usd', '0.1', '2025-12-21 15:27:42'),
(3, 'default_currency', 'MAD', '2025-12-21 15:27:42'),
(4, 'whatsapp_number', '1234567890', '2025-12-21 15:27:42'),
(5, 'logo_path', 'assets/images/RENTAL-CARS.png', '2025-12-03 18:45:32'),
(14, 'facebook_url', '', '2025-12-21 15:27:42'),
(15, 'twitter_url', '', '2025-12-21 15:27:42'),
(16, 'instagram_url', 'https://www.instagram.com/rentalcars.ettaajluxury/', '2025-12-21 15:27:42'),
(17, 'linkedin_url', '', '2025-12-21 15:27:42'),
(18, 'youtube_url', '', '2025-12-21 15:27:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

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
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
