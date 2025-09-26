-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 26, 2025 at 09:34 AM
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
-- Database: `tripmate_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `name`, `description`, `created_at`, `updated_at`, `status`, `image`) VALUES
(8, 'Hiking', 'Hiking is about discovering scenic trails, lush greenery, and panoramic views. Whether gentle walks or steep climbs, it offers both adventure and a connection to the island’s natural beauty.', '2025-08-04 18:09:09', '2025-09-17 09:31:30', 'active', 'images/0Y8MYgx63cnz6kGJTZRKRUn7IUTfZTf0gVJjNJMm.jpg'),
(9, 'Rafting', 'Rafting in Sri Lanka combines adventure with teamwork as you navigate fast-flowing rivers and rapids. It’s an exhilarating sport that balances challenge with fun in the water.', '2025-08-04 18:09:30', '2025-09-17 09:31:19', 'active', 'images/ISagVpxUSIN1g2oAnUMMnkNDFJqqPgi4G7DyOFie.webp'),
(11, 'Beach Side', 'The beachside atmosphere is a blend of golden sands, warm waters, and vibrant sunsets. It’s ideal for relaxation, swimming, or enjoying fresh seafood with the sound of waves in the background.', '2025-08-06 04:17:28', '2025-09-17 09:31:07', 'active', 'images/NmJrnzEgXTaq02U6pdRHzFha99BA32caOTYy4hkG.jpg'),
(12, 'Safari', 'A safari in Sri Lanka lets you explore rich biodiversity, from elephants and leopards to colorful birds. It’s a journey through natural habitats where you can experience wildlife in its pure form.', '2025-08-06 04:17:47', '2025-09-17 09:30:47', 'active', 'images/1mkfGqSVj5E67SfGYRwPE65uDLSv5e9dtAiqLYyD.jpg'),
(13, 'Sky Diving', 'Skydiving provides a thrilling aerial view of Sri Lanka’s diverse landscapes. The free fall followed by a gentle parachute glide gives an unmatched sense of freedom and adrenaline rush.', '2025-08-06 04:17:56', '2025-09-17 09:30:36', 'active', 'images/sKkNeXWcxUHTdcuCk2yuNtIfyyLkMXDbkZatREPk.jpg'),
(14, 'Camping', 'Camping in Sri Lanka is about immersing yourself in nature, whether in forests, open fields, or by riversides. It offers a chance to enjoy starry skies, bonfires, and the calm of the outdoors, away from busy city life.', '2025-08-06 04:18:01', '2025-09-17 09:30:27', 'active', 'images/j4dHuQTC4rN7yq2bfWstFGg02UxJ28FpeeHQgX3a.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `activity_location`
--

CREATE TABLE `activity_location` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_location`
--

INSERT INTO `activity_location` (`id`, `activity_id`, `location_id`, `created_at`, `updated_at`) VALUES
(5, 9, 2, '2025-08-09 12:31:03', '2025-08-09 12:31:03'),
(10, 8, 4, '2025-08-13 02:07:38', '2025-08-13 02:07:38'),
(13, 8, 7, '2025-08-21 21:06:11', '2025-08-21 21:06:11'),
(14, 11, 8, '2025-08-21 21:16:08', '2025-08-21 21:16:08'),
(15, 11, 9, '2025-08-21 21:19:12', '2025-08-21 21:19:12'),
(16, 13, 10, '2025-08-21 22:35:07', '2025-08-21 22:35:07'),
(17, 13, 11, '2025-08-21 22:38:03', '2025-08-21 22:38:03'),
(18, 12, 12, '2025-08-21 23:15:09', '2025-08-21 23:15:09'),
(19, 12, 13, '2025-08-21 23:18:59', '2025-08-21 23:18:59');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$12$pDle5dksc9zf9K2U9Rd5B.VRu//nt4yv88H6IzPqnlRonq6hIxdL.', NULL, '2025-09-11 08:15:05');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `check_in` date DEFAULT NULL,
  `check_out` date DEFAULT NULL,
  `booking_status` varchar(255) NOT NULL DEFAULT 'pending',
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payment_details`)),
  `booking_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`booking_details`)),
  `tourist_id` bigint(20) UNSIGNED DEFAULT NULL,
  `hotel_id` bigint(20) UNSIGNED DEFAULT NULL,
  `room_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `check_in_date` date DEFAULT NULL,
  `check_out_date` date DEFAULT NULL,
  `rooms_booked` int(11) NOT NULL DEFAULT 1,
  `guests_count` int(11) NOT NULL DEFAULT 1,
  `price_per_night` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
  `special_requests` text DEFAULT NULL,
  `booking_reference` varchar(255) DEFAULT NULL,
  `booking_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `created_at`, `updated_at`, `check_in`, `check_out`, `booking_status`, `payment_status`, `payment_method`, `payment_details`, `booking_details`, `tourist_id`, `hotel_id`, `room_type_id`, `check_in_date`, `check_out_date`, `rooms_booked`, `guests_count`, `price_per_night`, `total_amount`, `status`, `special_requests`, `booking_reference`, `booking_date`) VALUES
(1, '2025-09-09 14:57:53', '2025-09-11 10:08:50', '2025-09-09', '2025-09-10', 'cancelled', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"wedfdf\\\",\\\"processed_at\\\":\\\"2025-09-09T20:27:53.614384Z\\\"}\"', '\"{\\\"1\\\":{\\\"roomName\\\":\\\"Standard Room\\\",\\\"roomCount\\\":3,\\\"pricePerNight\\\":75,\\\"subtotal\\\":225,\\\"nights\\\":1}}\"', 43, 10, 1, '2025-09-09', '2025-09-10', 1, 2, 0.00, 225.00, 'cancelled', NULL, 'BK68C08DC995FCD', '2025-09-09 20:27:53'),
(4, '2025-09-09 15:58:17', '2025-09-09 16:57:43', '2025-09-09', '2025-09-10', 'completed', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"erfv\\\",\\\"processed_at\\\":\\\"2025-09-09T21:28:17.574114Z\\\"}\"', '\"{\\\"1\\\":{\\\"roomName\\\":\\\"Standard Room\\\",\\\"roomCount\\\":2,\\\"pricePerNight\\\":75,\\\"subtotal\\\":150,\\\"nights\\\":1},\\\"2\\\":{\\\"roomName\\\":\\\"Deluxe Room\\\",\\\"roomCount\\\":3,\\\"pricePerNight\\\":130,\\\"subtotal\\\":390,\\\"nights\\\":1}}\"', 43, 10, 1, '2025-09-09', '2025-09-10', 2, 2, 75.00, 540.00, 'completed', NULL, 'BK68C09BF18C273', '2025-09-09 21:28:17'),
(5, '2025-09-09 16:24:29', '2025-09-09 16:52:28', '2025-09-09', '2025-09-10', 'completed', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-09T21:54:29.483558Z\\\"}\"', '\"{\\\"2\\\":{\\\"roomName\\\":\\\"Deluxe Room\\\",\\\"roomCount\\\":5,\\\"pricePerNight\\\":130,\\\"subtotal\\\":650,\\\"nights\\\":1},\\\"4\\\":{\\\"roomName\\\":\\\"Family Room\\\",\\\"roomCount\\\":2,\\\"pricePerNight\\\":140,\\\"subtotal\\\":280,\\\"nights\\\":1}}\"', 43, 10, 2, '2025-09-09', '2025-09-10', 5, 2, 130.00, 930.00, 'completed', NULL, 'BK68C0A215760C7', '2025-09-09 21:54:29'),
(6, '2025-09-09 16:52:48', '2025-09-09 16:53:40', '2025-09-09', '2025-09-10', 'completed', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-09T22:22:48.135284Z\\\"}\"', '\"{\\\"1\\\":{\\\"roomName\\\":\\\"Standard Room\\\",\\\"roomCount\\\":1,\\\"pricePerNight\\\":75,\\\"subtotal\\\":75,\\\"nights\\\":1}}\"', 43, 10, 1, '2025-09-09', '2025-09-10', 1, 2, 75.00, 75.00, 'completed', NULL, 'BK68C0A8B821058', '2025-09-09 22:22:48'),
(7, '2025-09-09 23:41:33', '2025-09-09 23:42:26', '2025-09-10', '2025-09-11', 'confirmed', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"11111111111111111111111111111\\\",\\\"processed_at\\\":\\\"2025-09-09T23:41:33.356372Z\\\"}\"', '\"{\\\"1\\\":{\\\"roomName\\\":\\\"Standard Room\\\",\\\"roomCount\\\":3,\\\"pricePerNight\\\":75,\\\"subtotal\\\":225,\\\"nights\\\":1}}\"', 43, 10, 1, '2025-09-10', '2025-09-11', 3, 2, 75.00, 225.00, 'confirmed', NULL, 'BK68C0BB2D56FF4', '2025-09-09 23:41:33'),
(8, '2025-09-10 05:57:31', '2025-09-10 05:59:36', '2025-09-10', '2025-09-11', 'completed', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-10T05:57:31.366845Z\\\"}\"', '\"{\\\"1\\\":{\\\"roomName\\\":\\\"Standard Room\\\",\\\"roomCount\\\":2,\\\"pricePerNight\\\":75,\\\"subtotal\\\":150,\\\"nights\\\":1},\\\"2\\\":{\\\"roomName\\\":\\\"Deluxe Room\\\",\\\"roomCount\\\":6,\\\"pricePerNight\\\":130,\\\"subtotal\\\":780,\\\"nights\\\":1}}\"', 43, 10, 1, '2025-09-10', '2025-09-11', 2, 2, 75.00, 930.00, 'completed', NULL, 'BK68C1134B598BD', '2025-09-10 05:57:31'),
(9, '2025-09-10 06:47:34', '2025-09-10 06:48:44', '2025-09-11', '2025-09-12', 'completed', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"wed\\\",\\\"processed_at\\\":\\\"2025-09-10T06:47:34.614966Z\\\"}\"', '\"{\\\"3\\\":{\\\"roomName\\\":\\\"Twin Room\\\",\\\"roomCount\\\":2,\\\"pricePerNight\\\":90,\\\"subtotal\\\":180,\\\"nights\\\":1}}\"', 43, 10, 3, '2025-09-11', '2025-09-12', 2, 2, 90.00, 180.00, 'completed', NULL, 'BK68C11F0696219', '2025-09-10 06:47:34'),
(10, '2025-09-10 07:17:20', '2025-09-11 10:03:21', '2025-09-10', '2025-09-11', 'cancelled', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"111\\\",\\\"processed_at\\\":\\\"2025-09-10T07:17:20.279680Z\\\"}\"', '\"{\\\"4\\\":{\\\"roomName\\\":\\\"Family Room\\\",\\\"roomCount\\\":2,\\\"pricePerNight\\\":140,\\\"subtotal\\\":280,\\\"nights\\\":1}}\"', 43, 10, 4, '2025-09-10', '2025-09-11', 2, 2, 140.00, 280.00, 'cancelled', NULL, 'BK68C1260044454', '2025-09-10 07:17:20'),
(11, '2025-09-11 10:37:20', '2025-09-11 10:42:37', '2025-09-11', '2025-09-12', 'confirmed', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-11T10:37:20.343946Z\\\"}\"', '\"{\\\"1\\\":{\\\"roomName\\\":\\\"Standard Room\\\",\\\"roomCount\\\":1,\\\"pricePerNight\\\":75,\\\"subtotal\\\":75,\\\"nights\\\":1}}\"', 43, 10, 1, '2025-09-11', '2025-09-12', 1, 2, 75.00, 75.00, 'confirmed', NULL, 'BK68C2A66053F5C', '2025-09-11 10:37:20'),
(12, '2025-09-11 10:48:03', '2025-09-11 10:48:03', '2025-09-11', '2025-09-12', 'pending', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-11T10:48:03.594464Z\\\"}\"', '\"{\\\"1\\\":{\\\"roomName\\\":\\\"Standard Room\\\",\\\"roomCount\\\":1,\\\"pricePerNight\\\":75,\\\"subtotal\\\":75,\\\"nights\\\":1}}\"', 43, 10, 1, '2025-09-11', '2025-09-12', 1, 2, 75.00, 75.00, 'pending', NULL, 'BK68C2A8E391204', '2025-09-11 10:48:03'),
(13, '2025-09-11 12:06:03', '2025-09-11 12:06:03', '2025-09-11', '2025-09-12', 'pending', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-11T12:06:03.123362Z\\\"}\"', '\"{\\\"1\\\":{\\\"roomName\\\":\\\"Standard Room\\\",\\\"roomCount\\\":2,\\\"pricePerNight\\\":75,\\\"subtotal\\\":150,\\\"nights\\\":1}}\"', 43, 10, 1, '2025-09-11', '2025-09-12', 2, 2, 75.00, 150.00, 'pending', NULL, 'BK68C2BB2B1E1B3', '2025-09-11 12:06:03'),
(14, '2025-09-11 12:11:36', '2025-09-11 12:11:36', '2025-09-11', '2025-09-12', 'pending', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-11T12:11:36.782630Z\\\"}\"', '\"{\\\"2\\\":{\\\"roomName\\\":\\\"Deluxe Room\\\",\\\"roomCount\\\":2,\\\"pricePerNight\\\":130,\\\"subtotal\\\":260,\\\"nights\\\":1}}\"', 43, 10, 2, '2025-09-11', '2025-09-12', 2, 2, 130.00, 260.00, 'pending', NULL, 'BK68C2BC78BF106', '2025-09-11 12:11:36'),
(15, '2025-09-11 12:18:57', '2025-09-11 12:18:57', '2025-09-11', '2025-09-12', 'pending', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-11T12:18:57.331416Z\\\"}\"', '\"{\\\"2\\\":{\\\"roomName\\\":\\\"Deluxe Room\\\",\\\"roomCount\\\":2,\\\"pricePerNight\\\":130,\\\"subtotal\\\":260,\\\"nights\\\":1}}\"', 43, 10, 2, '2025-09-11', '2025-09-12', 2, 2, 130.00, 260.00, 'pending', NULL, 'BK68C2BE3150E70', '2025-09-11 12:18:57'),
(16, '2025-09-11 12:25:02', '2025-09-11 12:49:21', '2025-09-11', '2025-09-12', 'completed', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-11T12:25:02.104713Z\\\"}\"', '\"{\\\"2\\\":{\\\"roomName\\\":\\\"Deluxe Room\\\",\\\"roomCount\\\":2,\\\"pricePerNight\\\":130,\\\"subtotal\\\":260,\\\"nights\\\":1}}\"', 43, 10, 2, '2025-09-11', '2025-09-12', 2, 2, 130.00, 260.00, 'completed', NULL, 'BK68C2BF9E198ED', '2025-09-11 12:25:02'),
(17, '2025-09-11 12:33:04', '2025-09-11 12:43:58', '2025-09-11', '2025-09-12', 'completed', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-11T12:33:04.046758Z\\\"}\"', '\"{\\\"2\\\":{\\\"roomName\\\":\\\"Deluxe Room\\\",\\\"roomCount\\\":2,\\\"pricePerNight\\\":130,\\\"subtotal\\\":260,\\\"nights\\\":1}}\"', 43, 10, 2, '2025-09-11', '2025-09-12', 2, 2, 130.00, 260.00, 'completed', NULL, 'BK68C2C1800B687', '2025-09-11 12:33:04'),
(18, '2025-09-11 17:04:33', '2025-09-11 17:04:33', NULL, NULL, 'completed', 'paid', NULL, NULL, NULL, 25, 6, 1, '2025-09-04', '2025-09-06', 1, 1, NULL, 150.00, 'completed', NULL, 'BK202509115905', '2025-09-11 17:04:33'),
(19, '2025-09-11 17:05:06', '2025-09-11 17:05:06', NULL, NULL, 'completed', 'paid', NULL, NULL, NULL, 25, 6, 1, '2025-09-04', '2025-09-06', 1, 1, NULL, 150.00, 'completed', NULL, 'BK202509111804', '2025-09-11 17:05:06'),
(20, '2025-09-11 17:05:06', '2025-09-11 17:05:06', NULL, NULL, 'completed', 'paid', NULL, NULL, NULL, 25, 6, 1, '2025-09-03', '2025-09-05', 1, 1, NULL, 160.00, 'completed', NULL, 'BK202509119728', '2025-09-11 17:05:06'),
(21, '2025-09-11 17:05:06', '2025-09-11 17:05:06', NULL, NULL, 'completed', 'paid', NULL, NULL, NULL, 25, 6, 1, '2025-09-02', '2025-09-04', 1, 1, NULL, 170.00, 'completed', NULL, 'BK202509113262', '2025-09-11 17:05:06'),
(22, '2025-09-11 17:05:06', '2025-09-11 17:05:06', NULL, NULL, 'completed', 'paid', NULL, NULL, NULL, 25, 6, 1, '2025-09-01', '2025-09-03', 1, 1, NULL, 180.00, 'completed', NULL, 'BK202509111251', '2025-09-11 17:05:06'),
(23, '2025-09-11 17:05:06', '2025-09-11 17:05:06', NULL, NULL, 'completed', 'paid', NULL, NULL, NULL, 25, 6, 1, '2025-08-31', '2025-09-02', 1, 1, NULL, 190.00, 'completed', NULL, 'BK202509117774', '2025-09-11 17:05:06'),
(24, '2025-09-11 17:05:06', '2025-09-11 17:05:06', NULL, NULL, 'completed', 'paid', NULL, NULL, NULL, 25, 6, 1, '2025-08-30', '2025-09-01', 1, 1, NULL, 200.00, 'completed', NULL, 'BK202509114504', '2025-09-11 17:05:06'),
(25, '2025-09-11 17:20:08', '2025-09-11 17:20:08', '2025-09-11', '2025-09-12', 'pending', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-11T17:20:08.816095Z\\\"}\"', '\"{\\\"1\\\":{\\\"roomName\\\":\\\"Standard Room\\\",\\\"roomCount\\\":1,\\\"pricePerNight\\\":75,\\\"subtotal\\\":75,\\\"nights\\\":1}}\"', 43, 10, 1, '2025-09-11', '2025-09-12', 1, 2, 75.00, 75.00, 'pending', NULL, 'BK68C304C8C73AE', '2025-09-11 17:20:08'),
(26, '2025-09-11 17:52:13', '2025-09-11 17:52:13', '2025-09-11', '2025-09-12', 'pending', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-11T17:52:13.172132Z\\\"}\"', '\"{\\\"4\\\":{\\\"roomName\\\":\\\"Family Room\\\",\\\"roomCount\\\":2,\\\"pricePerNight\\\":140,\\\"subtotal\\\":280,\\\"nights\\\":1}}\"', 43, 10, 4, '2025-09-11', '2025-09-12', 2, 2, 140.00, 280.00, 'pending', NULL, 'BK68C30C4D2A041', '2025-09-11 17:52:13'),
(27, '2025-09-11 17:52:23', '2025-09-11 17:52:23', '2025-09-11', '2025-09-12', 'pending', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-11T17:52:23.862773Z\\\"}\"', '\"{\\\"4\\\":{\\\"roomName\\\":\\\"Family Room\\\",\\\"roomCount\\\":2,\\\"pricePerNight\\\":140,\\\"subtotal\\\":280,\\\"nights\\\":1}}\"', 43, 10, 4, '2025-09-11', '2025-09-12', 2, 2, 140.00, 280.00, 'pending', NULL, 'BK68C30C57D2A15', '2025-09-11 17:52:23'),
(28, '2025-09-11 17:58:48', '2025-09-11 17:58:48', '2025-09-11', '2025-09-12', 'pending', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-11T17:58:48.523052Z\\\"}\"', '\"{\\\"2\\\":{\\\"roomName\\\":\\\"Deluxe Room\\\",\\\"roomCount\\\":1,\\\"pricePerNight\\\":130,\\\"subtotal\\\":130,\\\"nights\\\":1}}\"', 43, 10, 2, '2025-09-11', '2025-09-12', 1, 2, 130.00, 130.00, 'pending', NULL, 'BK68C30DD87FAFD', '2025-09-11 17:58:48'),
(29, '2025-09-11 18:01:37', '2025-09-11 18:01:37', '2025-09-11', '2025-09-12', 'pending', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-11T18:01:37.793179Z\\\"}\"', '\"{\\\"2\\\":{\\\"roomName\\\":\\\"Deluxe Room\\\",\\\"roomCount\\\":1,\\\"pricePerNight\\\":130,\\\"subtotal\\\":130,\\\"nights\\\":1}}\"', 43, 10, 2, '2025-09-11', '2025-09-12', 1, 2, 130.00, 130.00, 'pending', NULL, 'BK68C30E81C1A39', '2025-09-11 18:01:37'),
(30, '2025-09-11 18:08:29', '2025-09-11 18:08:29', '2025-09-11', '2025-09-12', 'pending', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-11T18:08:29.448039Z\\\"}\"', '\"{\\\"3\\\":{\\\"roomName\\\":\\\"Twin Room\\\",\\\"roomCount\\\":1,\\\"pricePerNight\\\":90,\\\"subtotal\\\":90,\\\"nights\\\":1}}\"', 43, 10, 3, '2025-09-11', '2025-09-12', 1, 2, 90.00, 90.00, 'pending', NULL, 'BK68C3101D6D606', '2025-09-11 18:08:29'),
(31, '2025-09-11 19:38:14', '2025-09-16 17:40:30', '2025-09-12', '2025-09-13', 'confirmed', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"3\\\",\\\"processed_at\\\":\\\"2025-09-11T19:38:14.229097Z\\\"}\"', '\"{\\\"1\\\":{\\\"roomName\\\":\\\"Standard Room\\\",\\\"roomCount\\\":1,\\\"pricePerNight\\\":75,\\\"subtotal\\\":75,\\\"nights\\\":1}}\"', 43, 10, 1, '2025-09-12', '2025-09-13', 1, 2, 75.00, 75.00, 'confirmed', NULL, 'BK20250912EA729B', '2025-09-11 19:38:14'),
(32, '2025-09-11 19:43:44', '2025-09-12 01:58:18', '2025-09-12', '2025-09-13', 'confirmed', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-11T19:43:44.499848Z\\\"}\"', '\"{\\\"3\\\":{\\\"roomName\\\":\\\"Twin Room\\\",\\\"roomCount\\\":3,\\\"pricePerNight\\\":90,\\\"subtotal\\\":270,\\\"nights\\\":1}}\"', 43, 10, 3, '2025-09-12', '2025-09-13', 3, 2, 90.00, 270.00, 'confirmed', NULL, 'BK20250912955A0B', '2025-09-11 19:43:44'),
(33, '2025-09-11 20:07:31', '2025-09-12 01:51:12', '2025-09-12', '2025-09-13', 'completed', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1222\\\",\\\"card_name\\\":\\\"33\\\",\\\"processed_at\\\":\\\"2025-09-11T20:07:31.663238Z\\\"}\"', '\"{\\\"1\\\":{\\\"roomName\\\":\\\"Standard Room\\\",\\\"roomCount\\\":1,\\\"pricePerNight\\\":75,\\\"subtotal\\\":75,\\\"nights\\\":1}}\"', 43, 10, 1, '2025-09-12', '2025-09-13', 1, 2, 75.00, 75.00, 'completed', NULL, 'BK20250912A37569', '2025-09-11 20:07:31'),
(34, '2025-09-12 01:47:53', '2025-09-12 01:47:53', '2025-09-12', '2025-09-13', 'pending', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-12T01:47:53.808008Z\\\"}\"', '\"{\\\"1\\\":{\\\"roomName\\\":\\\"Standard Room\\\",\\\"roomCount\\\":2,\\\"pricePerNight\\\":90,\\\"subtotal\\\":180,\\\"nights\\\":1}}\"', 43, 6, 1, '2025-09-12', '2025-09-13', 2, 2, 90.00, 180.00, 'pending', NULL, 'BK20250912D8D6A5', '2025-09-12 01:47:53'),
(35, '2025-09-12 02:10:53', '2025-09-16 17:37:41', '2025-09-12', '2025-09-13', 'completed', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-12T02:10:53.848455Z\\\"}\"', '\"{\\\"1\\\":{\\\"roomName\\\":\\\"Standard Room\\\",\\\"roomCount\\\":1,\\\"pricePerNight\\\":75,\\\"subtotal\\\":75,\\\"nights\\\":1}}\"', 43, 10, 1, '2025-09-12', '2025-09-13', 1, 2, 75.00, 75.00, 'completed', NULL, 'BK202509128284B1', '2025-09-12 02:10:53'),
(36, '2025-09-14 09:16:37', '2025-09-16 17:24:19', '2025-09-14', '2025-09-15', 'completed', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"11\\\",\\\"processed_at\\\":\\\"2025-09-14T09:16:37.086448Z\\\"}\"', '\"{\\\"1\\\":{\\\"roomName\\\":\\\"Standard Room\\\",\\\"roomCount\\\":1,\\\"pricePerNight\\\":75,\\\"subtotal\\\":75,\\\"nights\\\":1}}\"', 43, 10, 1, '2025-09-14', '2025-09-15', 1, 2, 75.00, 75.00, 'completed', NULL, 'BK202509141002EE', '2025-09-14 09:16:37'),
(37, '2025-09-16 17:11:57', '2025-09-16 17:16:54', '2025-09-16', '2025-09-17', 'completed', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"111\\\",\\\"processed_at\\\":\\\"2025-09-16T17:11:57.666099Z\\\"}\"', '\"{\\\"1\\\":{\\\"roomName\\\":\\\"Standard Room\\\",\\\"roomCount\\\":1,\\\"pricePerNight\\\":75,\\\"subtotal\\\":75,\\\"nights\\\":1}}\"', 43, 10, 1, '2025-09-16', '2025-09-17', 1, 2, 75.00, 75.00, 'completed', NULL, 'BK2025091677AD60', '2025-09-16 17:11:57'),
(38, '2025-09-17 01:19:29', '2025-09-17 01:19:29', '2025-09-17', '2025-09-18', 'pending', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-17T01:19:29.023233Z\\\"}\"', '\"{\\\"1\\\":{\\\"roomName\\\":\\\"Standard Room\\\",\\\"roomCount\\\":1,\\\"pricePerNight\\\":90,\\\"subtotal\\\":90,\\\"nights\\\":1}}\"', 43, 6, 1, '2025-09-17', '2025-09-18', 1, 2, 90.00, 90.00, 'pending', NULL, 'BK20250917BA2DAA', '2025-09-17 01:19:29'),
(39, '2025-09-17 01:30:57', '2025-09-17 01:30:57', NULL, NULL, 'pending', 'pending', NULL, NULL, NULL, 25, 6, NULL, '2025-09-24', '2025-09-27', 1, 1, NULL, 150.00, 'pending', NULL, 'BK7575', '2025-09-17 01:30:57'),
(40, '2025-09-17 01:31:55', '2025-09-17 01:33:29', '2025-09-17', '2025-09-18', 'confirmed', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-17T01:31:55.678267Z\\\"}\"', '\"{\\\"1\\\":{\\\"roomName\\\":\\\"Standard Room\\\",\\\"roomCount\\\":1,\\\"pricePerNight\\\":75,\\\"subtotal\\\":75,\\\"nights\\\":1}}\"', 43, 10, 1, '2025-09-17', '2025-09-18', 1, 2, 75.00, 75.00, 'confirmed', NULL, 'BK202509179DAC46', '2025-09-17 01:31:55'),
(41, '2025-09-17 01:48:30', '2025-09-17 01:48:30', NULL, NULL, 'pending', 'pending', NULL, NULL, NULL, 25, 6, NULL, '2025-09-24', '2025-09-27', 1, 1, NULL, 150.00, 'pending', NULL, 'BK0688', '2025-09-17 01:48:30'),
(42, '2025-09-17 01:49:46', '2025-09-17 02:46:19', '2025-09-17', '2025-09-18', 'confirmed', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-17T01:49:46.818659Z\\\"}\"', '\"{\\\"1\\\":{\\\"roomName\\\":\\\"Standard Room\\\",\\\"roomCount\\\":1,\\\"pricePerNight\\\":75,\\\"subtotal\\\":75,\\\"nights\\\":1}}\"', 43, 10, 1, '2025-09-17', '2025-09-18', 1, 2, 75.00, 75.00, 'confirmed', NULL, 'BK202509172BEEA0', '2025-09-17 01:49:46'),
(43, '2025-09-17 02:19:58', '2025-09-17 02:46:11', '2025-09-17', '2025-09-18', 'completed', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-17T02:19:58.830563Z\\\"}\"', '\"{\\\"1\\\":{\\\"roomName\\\":\\\"Standard Room\\\",\\\"roomCount\\\":1,\\\"pricePerNight\\\":75,\\\"subtotal\\\":75,\\\"nights\\\":1}}\"', 43, 10, 1, '2025-09-17', '2025-09-18', 1, 2, 75.00, 75.00, 'completed', NULL, 'BK20250917B0791B', '2025-09-17 02:19:58'),
(44, '2025-09-17 07:34:41', '2025-09-17 07:34:41', '2025-09-17', '2025-09-18', 'pending', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-17T07:34:41.148341Z\\\"}\"', '\"{\\\"1\\\":{\\\"roomName\\\":\\\"Standard Room\\\",\\\"roomCount\\\":1,\\\"pricePerNight\\\":75,\\\"subtotal\\\":75,\\\"nights\\\":1}}\"', 43, 10, 1, '2025-09-17', '2025-09-18', 1, 2, 75.00, 75.00, 'pending', NULL, 'BK20250917B7D92D', '2025-09-17 07:34:41'),
(45, '2025-09-17 07:37:44', '2025-09-17 07:37:44', '2025-09-17', '2025-09-18', 'pending', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-17T07:37:44.861073Z\\\"}\"', '\"{\\\"1\\\":{\\\"roomName\\\":\\\"Standard Room\\\",\\\"roomCount\\\":1,\\\"pricePerNight\\\":75,\\\"subtotal\\\":75,\\\"nights\\\":1}}\"', 43, 10, 1, '2025-09-17', '2025-09-18', 1, 2, 75.00, 75.00, 'pending', NULL, 'BK202509179E4E6C', '2025-09-17 07:37:44'),
(46, '2025-09-17 10:11:16', '2025-09-17 10:26:50', '2025-09-17', '2025-09-18', 'completed', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"2222\\\",\\\"card_name\\\":\\\"wsedfg\\\",\\\"processed_at\\\":\\\"2025-09-17T10:11:16.018831Z\\\"}\"', '\"{\\\"2\\\":{\\\"roomName\\\":\\\"Deluxe Room\\\",\\\"roomCount\\\":2,\\\"pricePerNight\\\":130,\\\"subtotal\\\":260,\\\"nights\\\":1}}\"', 43, 10, 2, '2025-09-17', '2025-09-18', 2, 2, 130.00, 260.00, 'completed', NULL, 'BK2025091709ED6B', '2025-09-17 10:11:16'),
(47, '2025-09-20 10:39:22', '2025-09-20 10:39:22', '2025-09-20', '2025-09-21', 'pending', 'paid', 'card', '\"{\\\"card_last_four\\\":\\\"1111\\\",\\\"card_name\\\":\\\"1\\\",\\\"processed_at\\\":\\\"2025-09-20T10:39:22.559731Z\\\"}\"', '\"{\\\"1\\\":{\\\"roomName\\\":\\\"Standard Room\\\",\\\"roomCount\\\":2,\\\"pricePerNight\\\":75,\\\"subtotal\\\":150,\\\"nights\\\":1}}\"', 43, 10, 1, '2025-09-20', '2025-09-21', 2, 2, 75.00, 150.00, 'pending', NULL, 'BK202509207E8C2E', '2025-09-20 10:39:22');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-account_locked_gojivaj618@dotxan.com_127.0.0.1', 'b:1;', 1758795280),
('laravel-cache-account_locked_lkpcc500@gmail.com_127.0.0.1', 'b:1;', 1758853182),
('laravel-cache-failed_logins_127.0.0.1', 'i:5;', 1758853482);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emergency_services`
--

CREATE TABLE `emergency_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('hospital','police','fire_station','pharmacy','ambulance') NOT NULL,
  `description` text DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `emergency_phone` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `google_place_id` varchar(255) DEFAULT NULL,
  `is_24_7` tinyint(1) NOT NULL DEFAULT 0,
  `operating_hours` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`operating_hours`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `name`, `icon`, `category`, `created_at`, `updated_at`) VALUES
(1, 'Free WiFi', 'fas fa-wifi', 'connectivity', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(2, 'High-Speed Internet', 'fas fa-ethernet', 'connectivity', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(3, 'Swimming Pool', 'fas fa-swimming-pool', 'amenities', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(4, 'Fitness Center', 'fas fa-dumbbell', 'amenities', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(5, 'Spa Services', 'fas fa-spa', 'amenities', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(6, 'Restaurant', 'fas fa-utensils', 'amenities', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(7, 'Bar/Lounge', 'fas fa-cocktail', 'amenities', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(8, 'Room Service', 'fas fa-bell', 'amenities', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(9, 'Laundry Service', 'fas fa-tshirt', 'amenities', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(10, 'Concierge Service', 'fas fa-concierge-bell', 'amenities', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(11, 'Free Parking', 'fas fa-parking', 'transportation', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(12, 'Valet Parking', 'fas fa-car', 'transportation', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(13, 'Airport Shuttle', 'fas fa-shuttle-van', 'transportation', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(14, 'Business Center', 'fas fa-briefcase', 'business', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(15, 'Meeting Rooms', 'fas fa-users', 'business', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(16, 'Conference Facilities', 'fas fa-chalkboard', 'business', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(17, 'Pet Friendly', 'fas fa-paw', 'family', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(18, 'Kids Club', 'fas fa-child', 'family', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(19, 'Babysitting Service', 'fas fa-baby', 'family', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(20, '24/7 Security', 'fas fa-shield-alt', 'security', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(21, '24/7 Front Desk', 'fas fa-clock', 'security', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(22, 'Safe Deposit Box', 'fas fa-lock', 'security', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(23, 'Air Conditioning', 'fas fa-snowflake', 'comfort', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(24, 'Heating', 'fas fa-fire', 'comfort', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(25, 'Non-Smoking Rooms', 'fas fa-ban', 'comfort', '2025-09-09 03:35:42', '2025-09-09 03:35:42'),
(26, 'Balcony/Terrace', 'fas fa-tree', 'comfort', '2025-09-09 03:35:42', '2025-09-09 03:35:42');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_login_attempts`
--

CREATE TABLE `failed_login_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `attempt_count` int(11) NOT NULL DEFAULT 1,
  `locked_until` timestamp NULL DEFAULT NULL,
  `last_attempt_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `failed_login_attempts`
--

INSERT INTO `failed_login_attempts` (`id`, `email`, `ip_address`, `user_agent`, `attempt_count`, `locked_until`, `last_attempt_at`, `created_at`, `updated_at`) VALUES
(1, 'gojivaj618@dotxan.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 6, '2025-09-25 10:14:40', '2025-09-25 10:04:40', '2025-09-25 09:41:40', '2025-09-25 10:04:40'),
(2, 'lkpcc500@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 5, '2025-09-26 02:19:42', '2025-09-26 02:09:42', '2025-09-25 10:01:49', '2025-09-26 02:09:42');

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `location_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Active',
  `description` text DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `map_url` text DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `star_rating` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`id`, `name`, `email`, `username`, `password`, `created_at`, `updated_at`, `location_id`, `status`, `description`, `main_image`, `latitude`, `longitude`, `map_url`, `address`, `phone`, `website`, `star_rating`) VALUES
(6, 'Adventure Base Camp', 'cigonok736@mirarmax.com', 'hotel123', '$2y$12$RXGtCj5KTXF3jRZBtYFQyOPt3056SxyEeMLOxUnMOtXMqbMuGHM6e', NULL, '2025-09-17 09:52:08', 2, 'Active', 'Kithulgala in Sri Lanka is home to an exciting adventure base camp that boasts stunning views of the Kalani River. At this camp, you can indulge in thrilling activities like white water rafting, canyoning, and waterfall abseiling. Immerse yourself in the lush surroundings and enjoy delicious Sri Lankan cuisine on the scenic green river banks.', 'hotel/main-images/Otwx6eqZZHCjUBvMvWLWNa5yO9cIZImYRpi0UOEv.jpg', NULL, NULL, NULL, 'Adventure Base Camp, Yatiberiya, Kitulgala', '+94788170197', NULL, 3),
(8, 'New', 'lkpcc500@gmail.com', 'new', '$2y$12$a2mtRVqeyh9S0i8JI5FrCe.l/zqvF5Slvkaa96rSTQF3fcfPjn0EC', '2025-08-21 05:32:04', '2025-08-21 05:32:04', 4, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'The Tea Cottage Resort & Spa', 'kojape2990@ncien.com', 'K01', '$2y$12$uIwEA9s.ePYhPXWTsEXMweOOsdBWRLhuZEZPfxaVjoWRVcHWqBSGy', '2025-09-09 03:21:01', '2025-09-17 09:20:28', 2, 'Active', 'The Tea Cottage Resort & Spa is a boutique resort located on a working tea plantation in the highlands of Sri Lanka, offering a serene, tranquil experience with mountain and tea garden views. Accommodations vary from cottages to suites, featuring modern amenities and comfortable spaces with views of the surrounding nature. Guests can enjoy spa treatments, explore the tea plantation and local attractions like waterfalls, and dine on cuisines including local Sri Lankan specialties.', 'hotel/main-images/S4JvavzeluiENT39tdLdHLlKgzE3SWwe2Xyb6h0L.webp', 7.04206500, 80.55623290, 'https://maps.app.goo.gl/JazKc6AzRjnDsFQKA', 'Greenwood Estate, Nawalapitiya - Dimbula Rd, Nawalapitiya 20650', '+94768242606', NULL, 3),
(11, 'Hotel Test 1', 'digito9202@poesd.com', 'hotel-test-1', '$2y$12$0WwPmNyxsi/HbLSQ7ThpRO8E8C6.0uNM9Sl4ZJSvS20VnIx3gK5ji', '2025-09-17 10:16:01', '2025-09-17 10:17:53', 7, 'Active', 'New Hotel Test 1', 'hotel/main-images/h2aVdPT0nyLdRGfbtizzA9Ww7O893tqbqFbYTiZU.jpg', NULL, NULL, NULL, '123', '+94788818181', NULL, 4);

-- --------------------------------------------------------

--
-- Table structure for table `hotel_facilities`
--

CREATE TABLE `hotel_facilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hotel_id` bigint(20) UNSIGNED NOT NULL,
  `facility_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_facilities`
--

INSERT INTO `hotel_facilities` (`id`, `hotel_id`, `facility_id`, `created_at`, `updated_at`) VALUES
(1, 10, 7, NULL, NULL),
(2, 10, 6, NULL, NULL),
(3, 10, 8, NULL, NULL),
(4, 10, 5, NULL, NULL),
(5, 10, 23, NULL, NULL),
(6, 10, 26, NULL, NULL),
(7, 10, 25, NULL, NULL),
(8, 10, 1, NULL, NULL),
(9, 10, 18, NULL, NULL),
(10, 10, 21, NULL, NULL),
(11, 10, 20, NULL, NULL),
(12, 10, 11, NULL, NULL),
(13, 10, 12, NULL, NULL),
(14, 10, 24, NULL, NULL),
(15, 11, 7, NULL, NULL),
(16, 11, 9, NULL, NULL),
(17, 11, 16, NULL, NULL),
(18, 11, 26, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hotel_images`
--

CREATE TABLE `hotel_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hotel_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_images`
--

INSERT INTO `hotel_images` (`id`, `hotel_id`, `image_path`, `alt_text`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 10, 'hotel/additional-images/k7AqgE4MIO6uuUkG7NkOSDZATl1fiJXw9k3gkbk7.webp', 'The Tea Cottage Resort & Spa - Image 1', 0, '2025-09-09 03:51:46', '2025-09-09 03:51:46'),
(2, 10, 'hotel/additional-images/r8ZYmlx8qS6IQhKbj1bMdqKrmbLjG8CTArUkzjgu.webp', 'The Tea Cottage Resort & Spa - Image 2', 1, '2025-09-09 03:51:46', '2025-09-09 03:51:46'),
(3, 10, 'hotel/additional-images/zyGEBWkx4UNTK2G5W1iMMW38mfEQc0Q6FKxYUvD7.webp', 'The Tea Cottage Resort & Spa - Image 3', 2, '2025-09-09 03:51:46', '2025-09-09 03:51:46'),
(4, 10, 'hotel/additional-images/Kos2sG7PHzjTYy7XA2B6zZOWyW5a0wxPfRa6eDgR.webp', 'The Tea Cottage Resort & Spa - Image 4', 3, '2025-09-09 03:51:46', '2025-09-09 03:51:46'),
(5, 10, 'hotel/additional-images/WgsBQWcgN5UepHVcjyMccToByiKNBZA7BNSaRsiD.webp', 'The Tea Cottage Resort & Spa - Image 5', 4, '2025-09-09 03:51:46', '2025-09-09 03:51:46'),
(6, 10, 'hotel/additional-images/s9l436d2qdr07JKkrLyWmTpUDfTE4h8yWhZr9L44.webp', 'The Tea Cottage Resort & Spa - Image 6', 5, '2025-09-09 03:51:46', '2025-09-09 03:51:46'),
(7, 10, 'hotel/additional-images/2rycSaoGY90wjzq0yFKeGFNpM40HOAoC7IG88ICH.webp', 'The Tea Cottage Resort & Spa - Image 7', 6, '2025-09-09 03:51:46', '2025-09-09 03:51:46'),
(8, 10, 'hotel/additional-images/dHbtgeSQsiCsBgqIfmRksaEgoUDmyChcgtFX8w9c.webp', 'The Tea Cottage Resort & Spa - Image 8', 7, '2025-09-09 03:51:46', '2025-09-09 03:51:46'),
(9, 10, 'hotel/additional-images/6hdgXeu5fKrJi6KmhjYOqOnhdI9J6V1odzYVPsfC.webp', 'The Tea Cottage Resort & Spa - Image 9', 8, '2025-09-09 03:51:46', '2025-09-09 03:51:46'),
(10, 10, 'hotel/additional-images/rA09zWGc2zlK58QpPeyGBpUQm76eAiXB7xqm6IwX.webp', 'The Tea Cottage Resort & Spa - Image 10', 9, '2025-09-09 03:51:46', '2025-09-09 03:51:46'),
(11, 10, 'hotel/additional-images/nG0gNbwwZDjW8ght3jnTiXRTUgpzp7bHNqv6oPg4.webp', 'The Tea Cottage Resort & Spa - Image 11', 10, '2025-09-09 03:51:46', '2025-09-09 03:51:46');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_notifications`
--

CREATE TABLE `hotel_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hotel_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `action_url` varchar(255) DEFAULT NULL,
  `related_id` bigint(20) UNSIGNED DEFAULT NULL,
  `related_type` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_notifications`
--

INSERT INTO `hotel_notifications` (`id`, `hotel_id`, `type`, `title`, `message`, `action_url`, `related_id`, `related_type`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES
(6, 10, 'review', 'New Review Posted', '4-star review from Lakmina Welagedara: \"ede\"', 'http://127.0.0.1:8000/hotel/reviews', 19, 'review', 1, '2025-09-16 17:25:10', '2025-09-16 17:24:36', '2025-09-16 17:25:10'),
(7, 6, 'booking', 'New Booking Received', 'Booking #BK20250917BA2DAA from Lakmina Welagedara', 'http://127.0.0.1:8000/hotel/bookings', 38, 'booking', 0, NULL, '2025-09-17 01:19:29', '2025-09-17 01:19:29'),
(8, 6, 'booking', 'New Booking Received', 'Booking #BK7575 from Nandana Welagedara', 'http://127.0.0.1:8000/hotel/bookings', 39, 'booking', 0, NULL, '2025-09-17 01:30:58', '2025-09-17 01:30:58'),
(9, 10, 'booking', 'New Booking Received', 'Booking #BK202509179DAC46 from Lakmina Welagedara', 'http://127.0.0.1:8000/hotel/bookings', 40, 'booking', 1, '2025-09-17 01:33:25', '2025-09-17 01:31:55', '2025-09-17 01:33:25'),
(10, 6, 'booking', 'New Booking Received', 'Booking #BK0688 from Nandana Welagedara', 'http://127.0.0.1:8000/hotel/bookings', 41, 'booking', 0, NULL, '2025-09-17 01:48:30', '2025-09-17 01:48:30'),
(11, 10, 'booking', 'New Booking Received', 'Booking #BK202509172BEEA0 from Lakmina Welagedara', 'http://127.0.0.1:8000/hotel/bookings', 42, 'booking', 1, '2025-09-17 02:30:56', '2025-09-17 01:49:46', '2025-09-17 02:30:56'),
(12, 10, 'booking', 'New Booking Received', 'Booking #BK20250917B0791B from Lakmina Welagedara', 'http://127.0.0.1:8000/hotel/bookings', 43, 'booking', 1, '2025-09-17 02:30:56', '2025-09-17 02:19:58', '2025-09-17 02:30:56'),
(13, 10, 'booking', 'New Booking Received', 'Booking #BK20250917B7D92D from Lakmina Welagedara', 'http://127.0.0.1:8000/hotel/bookings', 44, 'booking', 1, '2025-09-17 09:11:44', '2025-09-17 07:34:41', '2025-09-17 09:11:44'),
(14, 10, 'booking', 'New Booking Received', 'Booking #BK202509179E4E6C from Lakmina Welagedara', 'http://127.0.0.1:8000/hotel/bookings', 45, 'booking', 1, '2025-09-17 09:11:44', '2025-09-17 07:37:44', '2025-09-17 09:11:44'),
(15, 10, 'booking', 'New Booking Received', 'Booking #BK2025091709ED6B from Lakmina Welagedara', 'http://127.0.0.1:8000/hotel/bookings', 46, 'booking', 1, '2025-09-17 10:12:51', '2025-09-17 10:11:16', '2025-09-17 10:12:51'),
(16, 10, 'booking', 'New Booking Received', 'Booking #BK202509207E8C2E from Lakmina Welagedara', 'http://127.0.0.1:8000/hotel/bookings', 47, 'booking', 0, NULL, '2025-09-20 10:39:22', '2025-09-20 10:39:22');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_rooms`
--

CREATE TABLE `hotel_rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hotel_id` bigint(20) UNSIGNED NOT NULL,
  `room_type_id` bigint(20) UNSIGNED NOT NULL,
  `room_count` int(11) NOT NULL DEFAULT 0,
  `price_per_night` decimal(10,2) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_rooms`
--

INSERT INTO `hotel_rooms` (`id`, `hotel_id`, `room_type_id`, `room_count`, `price_per_night`, `is_available`, `notes`, `created_at`, `updated_at`) VALUES
(1, 10, 1, 5, 75.00, 1, NULL, '2025-09-09 10:09:29', '2025-09-09 10:09:29'),
(2, 6, 1, 10, 90.00, 1, 'Added for testing availability system', '2025-09-09 12:48:27', '2025-09-09 12:48:27'),
(3, 6, 2, 3, 107.00, 1, 'Added for testing availability system', '2025-09-09 12:48:27', '2025-09-09 12:48:27'),
(4, 6, 3, 4, 100.00, 1, 'Added for testing availability system', '2025-09-09 12:48:27', '2025-09-09 12:48:27'),
(5, 10, 2, 10, 130.00, 1, NULL, '2025-09-09 13:02:16', '2025-09-09 13:02:16'),
(6, 10, 3, 6, 90.00, 1, NULL, '2025-09-09 13:02:16', '2025-09-09 13:02:16'),
(7, 10, 4, 4, 140.00, 1, NULL, '2025-09-09 13:31:11', '2025-09-09 13:31:11'),
(8, 11, 1, 5, 50.00, 1, NULL, '2025-09-17 10:21:12', '2025-09-17 10:21:12'),
(9, 11, 2, 4, 100.00, 1, NULL, '2025-09-17 10:21:12', '2025-09-17 10:21:12');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `google_place_id` varchar(255) DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `description`, `latitude`, `longitude`, `google_place_id`, `main_image`, `created_at`, `updated_at`, `status`) VALUES
(2, 'Kithulgala', 'Kitulgala is the most popular destination in Sri Lanka for adventure seekers. It is 2 hours due East via Avissawella-Hatton-Nuwaraeliya Hwy/Ginigathhara Rd/ A7 from Colombo. A small town in the wet zone rain forest, it is known as one of the wettest places in the county that gets two monsoons each year.\r\n\r\n\"Kitulgala is one of the wettest places on the island and remains lush throughout the year, vibrant with life\"\r\n(Sunday Observer, 2017).\r\n\r\nIt is not only a rated destination for adventure seekers, but a destination to sit back & relax away from the city heat.', NULL, NULL, NULL, 'locations/vrmB7WOEJLCWPEyvE4I7pPDfT7nmiT5q15xaQKWa.jpg', '2025-08-09 12:31:02', '2025-08-13 22:24:50', 'active'),
(4, 'Little Adam\'s Peak', 'The hike to Little Adam\'s Peak in Ella, Sri Lanka, is a short, easy trail through lush tea plantations leading to a summit with panoramic views of the Ella Gap, Ella Rock, and the surrounding highlands. The 1,141-meter peak offers a 360-degree vista, with the trail being well-marked, partially paved with steps, and suitable for all fitness levels. The trek typically takes 1-2 hours round trip, with the best times to visit being early morning for sunrise or late afternoon to enjoy the sunset.', NULL, NULL, NULL, 'locations/3OR1OhalMxCgtPJ2H717G1mT69GqWwH6ChoVNYel.jpg', '2025-08-13 02:07:38', '2025-09-17 07:48:48', 'active'),
(7, 'Ella Rock', 'The Ella Rock hike is a popular trail near Ella, Sri Lanka, offering breathtaking views of hills, valleys, and tea plantations. The moderately challenging round-trip hike takes about 4 hours, including time at the top, and involves walking along railway tracks and through lush vegetation. Hikers should be prepared with water, sunscreen, and a hat, and are advised to download a map for navigation.', NULL, NULL, NULL, 'locations/pOQwhY94NcKvXW53sNuOZfcgs0sFfsHKYtMXoLZ5.jpg', '2025-08-21 21:06:09', '2025-08-21 21:06:11', 'active'),
(8, 'Mirissa Beach', 'Mirissa is a gorgeous beach destination in Sri Lanka, and the perfect place to unwind in a hammock with a fresh coconut. The sparkling blue waters teem with marine life, from turtles to big blue whales. Secret Beach is small and secluded, and if you\'re lucky you might have it all to yourself.', NULL, NULL, NULL, 'locations/PiLiQv5XcJqoxKDP94RNVAeP9GW3m9etwSAv0a7d.jpg', '2025-08-21 21:16:08', '2025-08-21 21:16:08', 'active'),
(9, 'Jungle Beach', '\"Jungle Beach\" can refer to at least two different locations in Sri Lanka: a secluded eco-retreat near Trincomalee on the east coast known for its lush scrub jungle, thatched cabins, and untouched natural setting; and a popular beach near Unawatuna on the south coast, famed for its golden sand, calm waters, and proximity to the Rumassala Mountain and Peace Pagoda. The name evokes a natural, serene, and lush environment, with each location offering a distinct yet beautiful beach experience.', NULL, NULL, NULL, 'locations/HJzgjc3RFnh34qpZ6aEaTrorg4coi2olp8KnyqGr.jpg', '2025-08-21 21:19:12', '2025-08-21 21:19:12', 'active'),
(10, 'Skydive Sri Lanka', '\"Come skydiving in Sri Lanka: you will be jumping over the Pearl of the Indian Ocean with our professional team of international skydiving instructors. We are located just 1h South of Colombo, you will enjoy skydiving with stunning views of the ocean and landing on Bentota Beach. Full video of your experience with 360 views included with your booking.\"', NULL, NULL, NULL, 'locations/HQO1sSLJjjAhWcZkxkyv5EJUy93EXv1EasE2BQho.webp', '2025-08-21 22:35:07', '2025-08-21 22:35:07', 'active'),
(11, 'Eagles\' Skydive Sri Lanka', 'Eagles\' Skydive Sri Lanka, located at the SLAF Station Koggala, offers tandem and free-fall jumps with expert, USPA-affiliated instructors. Supported by the Sri Lanka Air Force, this drop zone provides an adventurous and scenic experience over Koggala\'s beaches and lakes, utilizing aircraft like the Y-12 and Cessna. The facility is known for its emphasis on safety and offers breathtaking views of Sri Lanka from high altitudes, making it a landmark for adventure tourism on the island.', NULL, NULL, NULL, 'locations/DvL76fxuo4WAh7DkO3Ps4a0nZ0TlViuKvH93HzIu.webp', '2025-08-21 22:38:03', '2025-08-21 22:38:03', 'active'),
(12, 'Udawalawa', 'An Udawalawe safari is a game drive through Udawalawe National Park, a premier wildlife destination in Sri Lanka known for its large elephant population, open grasslands, and diverse ecosystems. Visitors take guided tours in 4x4 jeeps to spot elephants, water buffalo, crocodiles, and numerous bird species. The best times to visit are early morning or late afternoon for peak animal activity.', NULL, NULL, NULL, 'locations/g9JVe5QL67Qa0ggUKPJaed20UkCIksRgo3Bm403W.webp', '2025-08-21 23:15:09', '2025-08-21 23:15:09', 'active'),
(13, 'Ridiyagama Safari Park', 'Ridiyagama Safari Park is Sri Lanka\'s first man-made safari park, allowing visitors to see animals in large, freely-roaming zones. It features separate zones for carnivores like lions and African big cats, and herbivores including elephants, zebras, antelopes, and camels. Visitors drive through the zones, similar to other safari parks, to get a close-up view of the animals in a naturalistic environment.', NULL, NULL, NULL, 'locations/4TeSzonaqo5b34VfGZhXhU0aNllRlRlchgAatxlB.webp', '2025-08-21 23:18:59', '2025-08-21 23:18:59', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `location_images`
--

CREATE TABLE `location_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `location_images`
--

INSERT INTO `location_images` (`id`, `location_id`, `path`, `caption`, `created_at`, `updated_at`) VALUES
(12, 2, 'locations/gallery/535zcDiZTtM5Jq4RXPxeSuOrs31HrUuqXB05pjnj.jpg', NULL, '2025-08-09 22:18:01', '2025-08-09 22:18:01'),
(15, 2, 'locations/gallery/2h1h84LJmnYxXCLrsXXZncFhcyztbco0mR5PQD1h.jpg', NULL, '2025-08-13 22:27:37', '2025-08-13 22:27:37'),
(16, 7, 'locations/gallery/TMI3H5IyeIs4aVwQ3KQoQIwcWjERBWaRlhMOROOv.jpg', NULL, '2025-08-21 21:06:11', '2025-08-21 21:06:11'),
(17, 7, 'locations/gallery/Cu1GSfviGQHHwQ9peCFeuqGvIeib7zJ7zrXrN8Va.webp', NULL, '2025-08-21 21:06:11', '2025-08-21 21:06:11'),
(18, 7, 'locations/gallery/waqUAJYQVydQxziq9mt4fhCi8c0Bm4IVTb2XBqE0.jpg', NULL, '2025-08-21 21:06:11', '2025-08-21 21:06:11'),
(20, 4, 'locations/gallery/ddYjEtVrH3PQZtA7IhDMQc7tQ3iKD7eeLmL4QEVl.jpg', NULL, '2025-08-21 21:10:38', '2025-08-21 21:10:38'),
(21, 4, 'locations/gallery/FCs8aqpgfIptD10tOG0dL4XcK77OROK7czfdaW5j.jpg', NULL, '2025-08-21 21:10:38', '2025-08-21 21:10:38'),
(22, 8, 'locations/gallery/KvLtKh3kRdw6MA0PoiMS7AayMI3mrhQN7fUGay5M.jpg', NULL, '2025-08-21 21:16:08', '2025-08-21 21:16:08'),
(23, 8, 'locations/gallery/h1iYz8UTHNHd5GX24fRt0mps1ej96zeRqQ2YhSj9.webp', NULL, '2025-08-21 21:16:08', '2025-08-21 21:16:08'),
(24, 8, 'locations/gallery/f9JaE5k0o9LP2h0vLZ4pFk7jm3isxwd4EMxjReyw.webp', NULL, '2025-08-21 21:16:08', '2025-08-21 21:16:08'),
(25, 9, 'locations/gallery/d7vFz4wLJTOlf6ht42sx3Y7EZkJ6hi1r9ZQrhkxT.jpg', NULL, '2025-08-21 21:19:12', '2025-08-21 21:19:12'),
(26, 9, 'locations/gallery/CRutPYrqKkKInDxZbhewtxUtOTzGg4cXLjeZ1RY5.jpg', NULL, '2025-08-21 21:19:12', '2025-08-21 21:19:12'),
(27, 9, 'locations/gallery/q5nUOwFGpldYKfQt0SUj9XOeg3ozc032md4BLW0f.jpg', NULL, '2025-08-21 21:19:12', '2025-08-21 21:19:12'),
(28, 10, 'locations/gallery/USx1OweWe1Jcp8Exaon6qgpPNqrNknX2yG9nayWV.webp', NULL, '2025-08-21 22:35:07', '2025-08-21 22:35:07'),
(29, 10, 'locations/gallery/NCJyp5OUxBy9olfQsgs3cYN7OGGJaj7mPTPyV7Yf.webp', NULL, '2025-08-21 22:35:07', '2025-08-21 22:35:07'),
(30, 10, 'locations/gallery/IisJy3AHYmIG3y84q7t4ol2bU4FT2PXDFE0K24HT.webp', NULL, '2025-08-21 22:35:07', '2025-08-21 22:35:07'),
(31, 11, 'locations/gallery/pOodMhRq2DGeZxcwqTYGOSzTNoMui2QBzEc8JGgm.webp', NULL, '2025-08-21 22:38:03', '2025-08-21 22:38:03'),
(32, 11, 'locations/gallery/Kh3q6FdzUYbSX4N3BSsAn4Hvsf1Yfn0dxvU7bTgE.webp', NULL, '2025-08-21 22:38:03', '2025-08-21 22:38:03'),
(33, 11, 'locations/gallery/Oh6WPNIlNzNF0zqpVdj9DHvHP8gXYHmgiPZKgRFu.webp', NULL, '2025-08-21 22:38:03', '2025-08-21 22:38:03'),
(34, 12, 'locations/gallery/xpRMG34vT5mj5TrIqkAEGoMV2i4kzUaIqhqcClsf.jpg', NULL, '2025-08-21 23:15:09', '2025-08-21 23:15:09'),
(35, 12, 'locations/gallery/BPNGNZecHj7BHyJXpgtzBgi9YmLB1S71zuKs4dPX.webp', NULL, '2025-08-21 23:15:09', '2025-08-21 23:15:09'),
(36, 12, 'locations/gallery/3LpWKLROoZqWYeguohF3dK0ywGCVdRpA8ojIvfJT.jpg', NULL, '2025-08-21 23:15:09', '2025-08-21 23:15:09'),
(37, 13, 'locations/gallery/5vv4jU5vPiCW07ipoikNCYsgkJXJG3ZtU2uvum88.jpg', NULL, '2025-08-21 23:18:59', '2025-08-21 23:18:59'),
(38, 13, 'locations/gallery/wJp0zvYC9n5US58UuJdp4dYgzmcINlwz0cINaGCr.jpg', NULL, '2025-08-21 23:18:59', '2025-08-21 23:18:59'),
(39, 13, 'locations/gallery/NlPsd8oNSLRXvwDk9f2fxgGMUUuTMzyugjAH2Yyf.jpg', NULL, '2025-08-21 23:18:59', '2025-08-21 23:18:59'),
(40, 4, 'locations/gallery/MbUF2SW2TpeKLDtEo2vLanFOzLyHsGB1tue9DcmS.jpg', NULL, '2025-09-17 07:42:02', '2025-09-17 07:42:02');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_01_01_000010_add_role_to_users_table', 2),
(6, '2025_01_01_000020_rename_users_table_to_tourists', 3),
(7, '2025_07_28_041114_add_otp_columns_to_tourists_table', 4),
(8, '2025_01_02_000000_create_admins_table', 5),
(9, '2025_01_03_000000_create_hotels_table', 6),
(10, '2025_01_01_000030_add_location_to_tourists_table', 7),
(11, '2025_01_05_000000_create_activities_table', 8),
(12, '2025_01_04_000000_create_locations_table', 8),
(13, '2025_01_10_000000_create_activity_location_table', 8),
(14, '2025_01_20_000000_add_status_to_activities_table', 9),
(15, '2025_01_21_000000_add_image_to_activities_table', 10),
(16, '2025_01_01_000040_add_profile_image_to_tourists_table', 11),
(17, '2025_01_22_000000_add_main_image_to_locations_table', 12),
(18, '2025_01_23_000000_create_location_images_table', 12),
(19, '2025_08_21_075633_update_hotels_table', 13),
(20, '2025_01_24_000000_add_name_email_to_hotels_table', 14),
(21, '2024_01_04_000000_create_emergency_services_table', 15),
(22, '2025_01_06_000000_create_facilities_table', 16),
(23, '2025_01_07_000000_create_room_types_table', 16),
(24, '2025_01_11_000000_create_hotel_facilities_table', 16),
(25, '2025_01_12_000000_create_hotel_rooms_table', 16),
(26, '2025_01_13_000000_create_bookings_table', 16),
(28, '2025_01_25_000000_add_coordinates_to_locations_table', 16),
(29, '2025_01_26_000000_add_status_to_locations_table', 16),
(30, '2025_01_27_000000_add_hotel_details_to_hotels_table', 16),
(31, '2025_01_28_000000_create_hotel_images_table', 16),
(32, '2025_01_29_000000_add_map_url_to_hotels_table', 16),
(33, '2025_09_09_192452_add_payment_fields_to_bookings_table', 17),
(34, '2025_09_09_202537_add_missing_columns_to_bookings_table', 18),
(35, '2025_01_14_000000_create_reviews_table', 19),
(37, '2025_09_16_223038_create_hotel_notifications_table', 20),
(39, '2025_09_25_130217_create_failed_login_attempts_table', 21);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `tourist_id` bigint(20) UNSIGNED NOT NULL,
  `hotel_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `booking_id`, `tourist_id`, `hotel_id`, `rating`, `title`, `description`, `is_approved`, `created_at`, `updated_at`) VALUES
(1, 6, 43, 10, 3, 'Good', 'Enjoyable Trip', 1, '2025-09-09 17:14:29', '2025-09-09 17:14:29'),
(2, 5, 43, 10, 5, 'wow', 'amazing place', 1, '2025-09-09 17:19:16', '2025-09-09 17:19:16'),
(3, 8, 43, 10, 3, 'Good experience', 'Happy Happy Happy', 1, '2025-09-10 06:00:07', '2025-09-10 06:00:07'),
(4, 9, 43, 10, 4, 'good', 'good good good', 1, '2025-09-11 12:34:51', '2025-09-11 12:34:51'),
(5, 17, 43, 10, 4, '2', 'qqqqqqwertyuuuuu', 1, '2025-09-11 12:44:10', '2025-09-11 12:44:10'),
(6, 16, 43, 10, 3, '12w3erd', '1234rergedrf', 1, '2025-09-11 12:49:36', '2025-09-11 12:49:36'),
(11, 19, 25, 6, 5, 'Excellent Stay!', 'Had an absolutely wonderful time at this hotel. The staff was incredibly friendly and helpful, the room was clean and comfortable, and the location was perfect. Would definitely recommend to anyone visiting the area!', 1, '2025-09-11 17:05:06', '2025-09-11 17:05:06'),
(12, 20, 25, 6, 4, 'Great Experience', 'Really enjoyed our stay here. The room was spacious and well-maintained. The breakfast was delicious and the hotel amenities were great. Only minor issue was the WiFi speed could be better.', 1, '2025-09-11 17:05:06', '2025-09-11 17:05:06'),
(13, 21, 25, 6, 5, 'Perfect Vacation', 'This hotel exceeded all our expectations! From the moment we arrived, we were treated like royalty. The room was beautiful, the service was impeccable, and the facilities were top-notch.', 1, '2025-09-11 17:05:06', '2025-09-11 17:05:06'),
(14, 22, 25, 6, 3, 'Good Value', 'Decent hotel for the price. The room was clean and the staff was polite. However, the room could use some updating and the air conditioning was a bit noisy at night.', 1, '2025-09-11 17:05:06', '2025-09-11 17:05:06'),
(15, 23, 25, 6, 4, 'Nice Stay', 'Overall a pleasant experience. The hotel is well-located and the staff is professional. The room was comfortable and clean. Would consider staying here again.', 1, '2025-09-11 17:05:06', '2025-09-11 17:05:06'),
(16, 24, 25, 6, 5, 'Outstanding Service', 'The level of service at this hotel is truly exceptional. Every staff member we encountered was professional, courteous, and went above and beyond to ensure our comfort.', 1, '2025-09-11 17:05:06', '2025-09-11 17:05:06'),
(17, 4, 43, 10, 3, 'Good Experience', 'Friendly hotel and staff', 1, '2025-09-16 17:13:37', '2025-09-16 17:13:37'),
(18, 37, 43, 10, 4, 'we', 'sssdcvdwesssssssssssss', 1, '2025-09-16 17:17:33', '2025-09-16 17:17:33'),
(19, 36, 43, 10, 4, 'ede', '2wwwwwwwwwwwwwwwwwwwwwwwwwwwwww', 1, '2025-09-16 17:24:36', '2025-09-16 17:24:36');

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `max_occupancy` int(11) NOT NULL DEFAULT 2,
  `base_price` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`id`, `name`, `description`, `icon`, `max_occupancy`, `base_price`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Standard Room', 'Comfortable room with basic amenities, perfect for budget-conscious travelers', 'fas fa-bed', 2, 75.00, 1, 1, '2025-09-09 03:27:04', '2025-09-09 03:27:04'),
(2, 'Deluxe Room', 'Spacious room with enhanced amenities and modern furnishings', 'fas fa-bed', 2, 120.00, 1, 2, '2025-09-09 03:27:04', '2025-09-09 03:27:04'),
(3, 'Twin Room', 'Room with two single beds, ideal for friends or colleagues traveling together', 'fas fa-bed', 2, 85.00, 1, 3, '2025-09-09 03:27:04', '2025-09-09 03:27:04'),
(4, 'Family Room', 'Large room designed for families with additional space and amenities', 'fas fa-users', 4, 150.00, 1, 4, '2025-09-09 03:27:04', '2025-09-09 03:27:04'),
(5, 'Junior Suite', 'Elegant suite with separate living area and upgraded amenities', 'fas fa-crown', 2, 200.00, 1, 5, '2025-09-09 03:27:04', '2025-09-09 03:27:04'),
(6, 'Executive Suite', 'Luxurious suite with premium amenities and exceptional service', 'fas fa-crown', 3, 300.00, 1, 6, '2025-09-09 03:27:04', '2025-09-09 03:27:04'),
(7, 'Presidential Suite', 'The ultimate luxury experience with lavish amenities and personalized service', 'fas fa-gem', 4, 500.00, 1, 7, '2025-09-09 03:27:04', '2025-09-09 03:27:04'),
(8, 'Single Room', 'Cozy room designed for solo travelers with all essential amenities', 'fas fa-user', 1, 60.00, 1, 8, '2025-09-09 03:27:04', '2025-09-09 03:27:04'),
(9, 'Accessible Room', 'Specially designed room with accessibility features for guests with disabilities', 'fas fa-wheelchair', 2, 85.00, 1, 9, '2025-09-09 03:27:04', '2025-09-09 03:27:04'),
(10, 'Studio Room', 'Open-plan room with kitchenette, perfect for extended stays', 'fas fa-home', 2, 110.00, 1, 10, '2025-09-09 03:27:04', '2025-09-09 03:27:04');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('2lTFaGwsigkUX2PJxdtqf7nT0Uw7NyThxyJHLWol', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.104.1 Chrome/138.0.7204.235 Electron/37.3.1 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUHA2dUIxTmExSzUxa2lvTTZ2cTVHVzZOV2Y1YkZuRUpnNmROSnB6ZiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTE0OiJodHRwOi8vbG9jYWxob3N0L1RyaXBtYXRlL3B1YmxpYy9yZWdpc3Rlcj9pZD1lZTMyYzlmMC1kYTFkLTRmNWItOTc0MC01MmRhNTc2ZTdlNWImdnNjb2RlQnJvd3NlclJlcUlkPTE3NTg4NTQyOTMxNjEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1758854293),
('3KvJQk4ruKd71KS9TYKeOu9eDPEgbeF3qjdcpAwi', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTEhBZVNHZzkwaUs4cjdQNDV5UG5VU1d3OE12SDZ5OTZDVEl1cVM4OCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1758860625),
('cfHrHRD48fu5PL0GvDFpaIVWLVCXLgDW0osXAXYf', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.104.1 Chrome/138.0.7204.235 Electron/37.3.1 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiakpzUmVldnRVeTJUQU0wQVpDTGpYV0hXamhNOHdXWms1akVDMTVJdSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTE0OiJodHRwOi8vbG9jYWxob3N0L1RyaXBtYXRlL3B1YmxpYy9yZWdpc3Rlcj9pZD1lZTMyYzlmMC1kYTFkLTRmNWItOTc0MC01MmRhNTc2ZTdlNWImdnNjb2RlQnJvd3NlclJlcUlkPTE3NTg4NTM5NTI4NTIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1758853953),
('F8PS513NaAdlgY1pDns4Y3ugPJc08vdmHZphzGC4', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.104.1 Chrome/138.0.7204.235 Electron/37.3.1 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWjJ3T1VNMG9PUDlzVE9WZElsejJUd1NBYUdBUklOUTdhekloZUtSTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTE0OiJodHRwOi8vbG9jYWxob3N0L1RyaXBtYXRlL3B1YmxpYy9yZWdpc3Rlcj9pZD1lZTMyYzlmMC1kYTFkLTRmNWItOTc0MC01MmRhNTc2ZTdlNWImdnNjb2RlQnJvd3NlclJlcUlkPTE3NTg4NTU0NzgwMzQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1758855478),
('gxJwkt8qcoLdDB0deERB35qaCq1D2Zb2ZytBFA5m', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.104.1 Chrome/138.0.7204.235 Electron/37.3.1 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiajVQQkZ1WU8wRWZEWEV1elpCek5sVmhOeFJnZU9FNm5DUFNYek1HdCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTExOiJodHRwOi8vbG9jYWxob3N0L1RyaXBtYXRlL3B1YmxpYy9sb2dpbj9pZD1lZTMyYzlmMC1kYTFkLTRmNWItOTc0MC01MmRhNTc2ZTdlNWImdnNjb2RlQnJvd3NlclJlcUlkPTE3NTg4NjA2NzIxMDciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1758860672),
('LkoLRKBEe1eAQWzgQxk5JCWZQZujhjx7DAfmh0Uz', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.104.1 Chrome/138.0.7204.235 Electron/37.3.1 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiV3d5aVRqWnZBbFA0QmxCSXZCSHV3U2t5MEJTWWxta0R3Ulk4elJQSiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTE0OiJodHRwOi8vbG9jYWxob3N0L1RyaXBtYXRlL3B1YmxpYy9yZWdpc3Rlcj9pZD1lZTMyYzlmMC1kYTFkLTRmNWItOTc0MC01MmRhNTc2ZTdlNWImdnNjb2RlQnJvd3NlclJlcUlkPTE3NTg4NTQwMDIxMTIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1758854002),
('nz9tXdxx9P0re35DYUwc97pIxLM3JQOI2YSUPrBm', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.104.1 Chrome/138.0.7204.235 Electron/37.3.1 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZk0wTlRVYnp2WXdpWFozUGpXMW9jWmJaOVJvZDc1UndXQjQ4M2VxQSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTE0OiJodHRwOi8vbG9jYWxob3N0L1RyaXBtYXRlL3B1YmxpYy9yZWdpc3Rlcj9pZD1lZTMyYzlmMC1kYTFkLTRmNWItOTc0MC01MmRhNTc2ZTdlNWImdnNjb2RlQnJvd3NlclJlcUlkPTE3NTg4NTU1Nzc5ODEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1758855578),
('nzGukYPN5zCNeDJmputrtUwuXtPiOrY1KOFcUgYM', NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-GB) WindowsPowerShell/5.1.26100.6584', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNnpQSGNJQkZoVUlqS01GOVVVME1taXZjVzBuM0Q3bjdldDJDVVl3aSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly9sb2NhbGhvc3QvVHJpcG1hdGUvcHVibGljL3JlZ2lzdGVyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1758855535),
('R1aB34YhjzSnmmwYq75jyRJQ1aEptbnDe8EKIfWN', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.104.1 Chrome/138.0.7204.235 Electron/37.3.1 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYkdvakNRenhYVHhMQ2VJYXdNb0pNcnBZRmtnMVYyNnIySHJVSGFUWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTE0OiJodHRwOi8vbG9jYWxob3N0L1RyaXBtYXRlL3B1YmxpYy9yZWdpc3Rlcj9pZD1lZTMyYzlmMC1kYTFkLTRmNWItOTc0MC01MmRhNTc2ZTdlNWImdnNjb2RlQnJvd3NlclJlcUlkPTE3NTg4NjA2Mjc4MjQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1758860627),
('sKUfkLrdso61qR4vfi2EmBpCZRTieucublEocR8C', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieWFZZWNxYzNEZDFiNHZkWXdBellCekU1TEF2b2JlQWZlQXM1WGdQcyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9mb3Jnb3QtcGFzc3dvcmQiO319', 1758852624),
('UitpN4mQuOVe0oImUJ5f6kyaTDbHsYhCwbW3M6fR', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.104.1 Chrome/138.0.7204.235 Electron/37.3.1 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTmpIWkFuRTNBV1Y4VE5YYjJnaGt4WTJHN3U4a2dqRHBYZ2ZQb1hUUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTAwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDEvbG9naW4/aWQ9ZWUzMmM5ZjAtZGExZC00ZjViLTk3NDAtNTJkYTU3NmU3ZTViJnZzY29kZUJyb3dzZXJSZXFJZD0xNzU4NzkwMjc3NTI2Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1758790277),
('uL4UIEsxwQU4kZtBbgnaitZ4Y4xIFwc6pnfm15qc', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.104.1 Chrome/138.0.7204.235 Electron/37.3.1 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZGVSNzh3SDJ3WmpqOURMbnB5Y3J4TjFCYVlEUHNESW5JWUMyd3RnbiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTE0OiJodHRwOi8vbG9jYWxob3N0L1RyaXBtYXRlL3B1YmxpYy9yZWdpc3Rlcj9pZD1lZTMyYzlmMC1kYTFkLTRmNWItOTc0MC01MmRhNTc2ZTdlNWImdnNjb2RlQnJvd3NlclJlcUlkPTE3NTg4NTYyNDkwNzAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1758856249),
('ZofEaO2BelkChehpsaKfkfv2uaNmdiWFAVwUL2vt', NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-GB) WindowsPowerShell/5.1.26100.6584', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTGxpSzJTRkdOME5nT0dXd3pLcW1NZURzZFNrU2s3NWNsakE0cmt0MiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly9sb2NhbGhvc3QvVHJpcG1hdGUvcHVibGljL3JlZ2lzdGVyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1758854314);

-- --------------------------------------------------------

--
-- Table structure for table `tourists`
--

CREATE TABLE `tourists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `mobile` varchar(25) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `otp_verified` tinyint(1) NOT NULL DEFAULT 0,
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tourists`
--

INSERT INTO `tourists` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `mobile`, `profile_image`, `otp`, `otp_verified`, `location`) VALUES
(25, 'Nandana Welagedara', 'nandanawelagedara71@gmail.com', NULL, '$2y$12$hVhYIfbt8RfC0AwEruUwG.EH5yMNygk3DEofthEEaeH8CIb.l.mfm', NULL, '2025-07-30 05:57:27', '2025-07-30 05:57:27', '+94238762789', NULL, NULL, 1, NULL),
(43, 'Lakmina Welagedara', 'lkpcc500@gmail.com', NULL, '$2y$12$PnQ5nBAOqgp5xvKjLac.fOAL2/jYHqqL6KcDqwjv/Wuay4Kv87cSO', 'x9I3Cy7N36VPfno3Z2WRj8JVNDh1nvCJ80nnal0wMq5aVjyXfpBX0HWx49i3', '2025-08-04 13:12:29', '2025-09-26 02:11:01', '+94742931329', 'profile_images/KXiTrgFJ4mwOJCzjEBN9AqNIKChWLLeVjSAvv76Y.jpg', NULL, 1, 'Sri Lanka'),
(46, 'lakila', 'sakilalakmal77@gmail.com', NULL, '$2y$12$GVXS6kWnXsSwA3gN6IM3S.Y6SC.CgED2hh0YU9zaZdqX4ClWEDgmq', NULL, '2025-08-06 04:40:36', '2025-08-06 04:42:10', '+94761712123', 'profile_images/8v2lqxEzpwlkXgUmQkgfxbrLRkUsO6SPTfhYZPHI.png', NULL, 1, 'Sri Lanka'),
(47, 'Test User', 'test@example.com', NULL, '$2y$12$uIBPUymHbKEVvh4Z..PoOOCVrwKZimJ5MZWCe9p8qC0mdF5wjhpe.', NULL, '2025-09-09 14:17:52', '2025-09-09 14:17:52', '1234567890', NULL, NULL, 0, 'Test City'),
(51, 'Lakmina Welagedara', 'pettasamii@gmail.com', NULL, '$2y$12$65gYIGGIXgVcrKhKSmWnDeXuKyhTyOu8vc2uTrDHs7aIrhBolAdXy', 'QZwI9UqsYKC7OQK4JYwwY0c5cyUR50xA7Dk5vWiA8cH5kz4mvkrE5H6Pokfc', '2025-09-17 04:08:51', '2025-09-17 04:29:05', '+941111111111', NULL, NULL, 1, 'Sri Lanka'),
(52, 'Lakmina Welagedara', 'gojivaj618@dotxan.com', NULL, '$2y$12$RW.bPuft6bALJX9/KhJmauCH6YYY6GNplu9P6up6VafizcrCEpFqi', NULL, '2025-09-25 07:21:44', '2025-09-25 07:21:44', '+94222222222222222', NULL, NULL, 1, 'Sri Lanka'),
(54, 'Test Demo', 'demo@test.com', NULL, '$2y$12$ImJdHaTU/3rNK5OEUg0GTenxIWeHF0ot7chbMWcD.DUOPWxrZCqG6', NULL, '2025-09-25 09:40:45', '2025-09-25 09:40:45', '9876543210', NULL, NULL, 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_location`
--
ALTER TABLE `activity_location`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_location_activity_id_foreign` (`activity_id`),
  ADD KEY `activity_location_location_id_foreign` (`location_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_username_unique` (`username`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookings_booking_reference_unique` (`booking_reference`),
  ADD KEY `bookings_room_type_id_foreign` (`room_type_id`),
  ADD KEY `bookings_hotel_id_check_in_date_check_out_date_index` (`hotel_id`,`check_in_date`,`check_out_date`),
  ADD KEY `bookings_tourist_id_status_index` (`tourist_id`,`status`),
  ADD KEY `bookings_booking_reference_index` (`booking_reference`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `emergency_services`
--
ALTER TABLE `emergency_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emergency_services_latitude_longitude_index` (`latitude`,`longitude`),
  ADD KEY `emergency_services_type_index` (`type`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `failed_login_attempts`
--
ALTER TABLE `failed_login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `failed_login_attempts_email_ip_address_index` (`email`,`ip_address`),
  ADD KEY `failed_login_attempts_locked_until_index` (`locked_until`),
  ADD KEY `failed_login_attempts_email_index` (`email`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hotels_username_unique` (`username`),
  ADD UNIQUE KEY `hotels_email_unique` (`email`);

--
-- Indexes for table `hotel_facilities`
--
ALTER TABLE `hotel_facilities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hotel_facilities_hotel_id_facility_id_unique` (`hotel_id`,`facility_id`),
  ADD KEY `hotel_facilities_facility_id_foreign` (`facility_id`);

--
-- Indexes for table `hotel_images`
--
ALTER TABLE `hotel_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_images_hotel_id_foreign` (`hotel_id`);

--
-- Indexes for table `hotel_notifications`
--
ALTER TABLE `hotel_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_notifications_hotel_id_is_read_index` (`hotel_id`,`is_read`),
  ADD KEY `hotel_notifications_hotel_id_created_at_index` (`hotel_id`,`created_at`);

--
-- Indexes for table `hotel_rooms`
--
ALTER TABLE `hotel_rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hotel_rooms_hotel_id_room_type_id_unique` (`hotel_id`,`room_type_id`),
  ADD KEY `hotel_rooms_room_type_id_foreign` (`room_type_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location_images`
--
ALTER TABLE `location_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_images_location_id_foreign` (`location_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reviews_booking_id_unique` (`booking_id`),
  ADD KEY `reviews_tourist_id_foreign` (`tourist_id`),
  ADD KEY `reviews_hotel_id_foreign` (`hotel_id`);

--
-- Indexes for table `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tourists`
--
ALTER TABLE `tourists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `activity_location`
--
ALTER TABLE `activity_location`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `emergency_services`
--
ALTER TABLE `emergency_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_login_attempts`
--
ALTER TABLE `failed_login_attempts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `hotel_facilities`
--
ALTER TABLE `hotel_facilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `hotel_images`
--
ALTER TABLE `hotel_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `hotel_notifications`
--
ALTER TABLE `hotel_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `hotel_rooms`
--
ALTER TABLE `hotel_rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `location_images`
--
ALTER TABLE `location_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tourists`
--
ALTER TABLE `tourists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_location`
--
ALTER TABLE `activity_location`
  ADD CONSTRAINT `activity_location_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activity_location_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_room_type_id_foreign` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_tourist_id_foreign` FOREIGN KEY (`tourist_id`) REFERENCES `tourists` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_facilities`
--
ALTER TABLE `hotel_facilities`
  ADD CONSTRAINT `hotel_facilities_facility_id_foreign` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hotel_facilities_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_images`
--
ALTER TABLE `hotel_images`
  ADD CONSTRAINT `hotel_images_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_notifications`
--
ALTER TABLE `hotel_notifications`
  ADD CONSTRAINT `hotel_notifications_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_rooms`
--
ALTER TABLE `hotel_rooms`
  ADD CONSTRAINT `hotel_rooms_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hotel_rooms_room_type_id_foreign` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `location_images`
--
ALTER TABLE `location_images`
  ADD CONSTRAINT `location_images_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_tourist_id_foreign` FOREIGN KEY (`tourist_id`) REFERENCES `tourists` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
