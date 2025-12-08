-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 08 déc. 2025 à 14:16
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `car_rental`
--

-- --------------------------------------------------------

--
-- Structure de la table `cars`
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
-- Déchargement des données de la table `cars`
--

INSERT INTO `cars` (`id`, `name`, `image`, `price`, `discount`, `description`, `availability`, `created_at`, `updated_at`, `specifications`) VALUES
(8, 'Toyota Camry', 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=800', 50.00, 10.00, 'Comfortable sedan perfect for city driving and long trips.', 'available', '2025-12-03 18:45:32', '2025-12-03 19:19:32', '{\"gear_box\":\"Automat\",\"fuel\":\"Hybrid\",\"doors\":\"4\",\"air_conditioner\":\"Yes\",\"seats\":\"5\",\"distance\":\"500 km\"}'),
(9, 'Honda Civic', 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800', 45.00, 0.00, 'Reliable and fuel-efficient compact car.', 'available', '2025-12-03 18:45:32', '2025-12-03 19:09:54', '{\"gear_box\": \"Automat\", \"fuel\": \"Hybrid\", \"doors\": \"4\", \"air_conditioner\": \"Yes\", \"seats\": \"5\", \"distance\": \"600 km\"}'),
(10, 'BMW 3 Series', 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800', 120.00, 15.00, 'Luxury sedan with premium features and performance.', 'available', '2025-12-03 18:45:32', '2025-12-03 19:09:54', '{\"gear_box\": \"Automat\", \"fuel\": \"Petrol\", \"doors\": \"4\", \"air_conditioner\": \"Yes\", \"seats\": \"5\", \"distance\": \"550 km\"}'),
(11, 'Mercedes-Benz C-Class', 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800', 130.00, 20.00, 'Elegant luxury car with advanced technology.', 'available', '2025-12-03 18:45:32', '2025-12-03 19:09:54', '{\"gear_box\": \"Automat\", \"fuel\": \"Diesel\", \"doors\": \"4\", \"air_conditioner\": \"Yes\", \"seats\": \"5\", \"distance\": \"700 km\"}'),
(12, 'Ford Mustang', 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=800', 100.00, 5.00, 'Iconic sports car with powerful engine.', 'available', '2025-12-03 18:45:32', '2025-12-03 19:09:54', '{\"gear_box\": \"Manual\", \"fuel\": \"Petrol\", \"doors\": \"2\", \"air_conditioner\": \"Yes\", \"seats\": \"4\", \"distance\": \"450 km\"}'),
(13, 'Tesla Model 3', 'https://images.unsplash.com/photo-1560958089-b8a1929cea89?w=800', 150.00, 0.00, 'Electric vehicle with autopilot features.', 'available', '2025-12-03 18:45:32', '2025-12-03 19:09:54', '{\"gear_box\": \"Automat\", \"fuel\": \"Hybrid\", \"doors\": \"4\", \"air_conditioner\": \"Yes\", \"seats\": \"5\", \"distance\": \"800 km\"}'),
(14, 'RENTAL CARS', 'assets/images/rental-cars-1764790589.png', 100.00, 0.00, '', 'available', '2025-12-03 19:34:56', '2025-12-03 19:36:29', '{\"gear_box\":\"Automat\",\"fuel\":\"Petrol\",\"doors\":\"4\",\"air_conditioner\":\"Yes\",\"seats\":\"5\",\"distance\":\"500 km\"}');

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `updated_at`) VALUES
(1, 'mad_to_eur', '0.092', '2025-12-08 13:10:45'),
(2, 'mad_to_usd', '0.1', '2025-12-08 13:10:45'),
(3, 'default_currency', 'MAD', '2025-12-08 13:10:45'),
(4, 'whatsapp_number', '212769323828', '2025-12-08 13:10:45'),
(5, 'logo_path', 'assets/images/RENTAL-CARS.png', '2025-12-03 18:45:32'),
(14, 'facebook_url', '', '2025-12-08 13:10:45'),
(15, 'twitter_url', '', '2025-12-08 13:10:45'),
(16, 'instagram_url', 'https://www.instagram.com/rentalcars.ettaajluxury/', '2025-12-08 13:10:45'),
(17, 'linkedin_url', '', '2025-12-08 13:10:45'),
(18, 'youtube_url', '', '2025-12-08 13:10:45');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
