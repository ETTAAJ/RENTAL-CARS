-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 21, 2025 at 06:03 PM
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
(36, 'Mercedes-Benz A-Class', 'assets/images/mercedes-benz-a-class.jpg', 5, 2, 'Automatic', 'Diesel', 900.00, 6300.00, 25500.00, 680.00, 0.00, '', 'available', '2025-12-17 13:47:53', '2025-12-21 16:07:48', '{\"gear_box\":\"Automat\",\"fuel\":\"Petrol\",\"doors\":\"4\",\"air_conditioner\":\"Yes\",\"seats\":\"5\",\"distance\":\"500 km\"}'),
(39, 'Audi Q8 S-Line', 'assets/images/audi-q8-s-line.jpg', 5, 4, 'Automatic', 'Petrol', 2300.00, 13800.00, 46000.00, 1100.00, 0.00, '', 'available', '2025-12-17 13:47:53', '2025-12-21 16:08:07', '{\"gear_box\":\"Automat\",\"fuel\":\"Petrol\",\"doors\":\"4\",\"air_conditioner\":\"Yes\",\"seats\":\"5\",\"distance\":\"500 km\"}'),
(44, 'Porsche Cayenne Coupé', 'assets/images/porsche-cayenne-coup.jpg', 5, 4, 'Automatic', 'Petrol', 3800.00, 22800.00, 76000.00, 1400.00, 0.00, '', 'available', '2025-12-17 13:47:53', '2025-12-21 16:08:36', '{\"gear_box\":\"Automat\",\"fuel\":\"Petrol\",\"doors\":\"4\",\"air_conditioner\":\"Yes\",\"seats\":\"5\",\"distance\":\"500 km\"}'),
(45, 'Range Rover Sport', 'assets/images/range-rover-sport.jpg', 5, 4, 'Automatic', 'Petrol', 4000.00, 24000.00, 80000.00, 1200.00, 0.00, '', 'available', '2025-12-17 13:47:53', '2025-12-21 16:09:22', '{\"gear_box\":\"Automat\",\"fuel\":\"Petrol\",\"doors\":\"4\",\"air_conditioner\":\"Yes\",\"seats\":\"5\",\"distance\":\"500 km\"}'),
(46, 'Range Rover Vogue', 'assets/images/range-rover-vogue.jpg', 5, 5, 'Automatic', 'Petrol', 5300.00, 31800.00, 106000.00, 1300.00, 0.00, '', 'available', '2025-12-17 13:47:53', '2025-12-21 16:09:41', '{\"gear_box\":\"Automat\",\"fuel\":\"Petrol\",\"doors\":\"4\",\"air_conditioner\":\"Yes\",\"seats\":\"5\",\"distance\":\"500 km\"}'),
(48, 'Mercedes-Benz G63 AMG', 'assets/images/mercedes-benz-g63-amg.jpg', 5, 4, 'Automatic', 'Petrol', 13000.00, 78000.00, 260000.00, 1500.00, 0.00, '', 'available', '2025-12-17 13:47:53', '2025-12-21 16:10:06', '{\"gear_box\":\"Automat\",\"fuel\":\"Petrol\",\"doors\":\"4\",\"air_conditioner\":\"Yes\",\"seats\":\"5\",\"distance\":\"500 km\"}'),
(52, 'Dacia logan  2025', 'assets/images/dacia-logan--2025.jpg', 5, 4, 'Manual', 'Diesel', 300.00, 1900.00, 7500.00, 300.00, 5.00, '', 'available', '2025-12-17 13:47:53', '2025-12-21 16:10:22', '{\"gear_box\":\"Automat\",\"fuel\":\"Petrol\",\"doors\":\"4\",\"air_conditioner\":\"Yes\",\"seats\":\"5\",\"distance\":\"500 km\"}'),
(54, 'Clio 5 2025/2024', 'assets/images/clio-5-20252024.jpg', 5, 3, 'Manual', 'Diesel', 310.00, 1900.00, 7500.00, 450.00, 5.00, '', 'available', '2025-12-17 13:47:53', '2025-12-21 16:11:15', '{\"gear_box\":\"Automat\",\"fuel\":\"Petrol\",\"doors\":\"4\",\"air_conditioner\":\"Yes\",\"seats\":\"5\",\"distance\":\"500 km\"}');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `symbol` varchar(10) DEFAULT '',
  `rate_to_base` decimal(10,6) DEFAULT 1.000000,
  `rate_to_mad` decimal(10,6) DEFAULT 1.000000,
  `is_active` tinyint(1) DEFAULT 1,
  `is_base` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `code`, `name`, `symbol`, `rate_to_base`, `rate_to_mad`, `is_active`, `is_base`, `created_at`, `updated_at`) VALUES
(2, 'EUR', 'Euro', '€', 1.000000, 0.092000, 1, 1, '2025-12-21 16:28:57', '2025-12-21 16:58:13'),
(3, 'USD', 'US Dollar', '$', 1.000000, 0.100000, 1, 0, '2025-12-21 16:28:57', '2025-12-21 16:58:13');

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
(1, 'mad_to_eur', '0.092', '2025-12-21 16:30:30'),
(2, 'mad_to_usd', '0.1', '2025-12-21 16:30:30'),
(3, 'default_currency', 'MAD', '2025-12-21 16:30:30'),
(4, 'whatsapp_number', '', '2025-12-21 16:30:30'),
(5, 'logo_path', 'assets/images/RENTAL-CARS.png', '2025-12-03 18:45:32'),
(14, 'facebook_url', '', '2025-12-21 16:30:30'),
(15, 'twitter_url', '', '2025-12-21 16:30:30'),
(16, 'instagram_url', '', '2025-12-21 16:30:30'),
(17, 'linkedin_url', '', '2025-12-21 16:30:30'),
(18, 'youtube_url', '', '2025-12-21 16:30:30');

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
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

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
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
