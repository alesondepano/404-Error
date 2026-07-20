-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 20, 2026 at 03:20 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `error404_motors`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(120) NOT NULL,
  `table_name` varchar(80) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `table_name`, `record_id`, `details`, `ip_address`, `created_at`) VALUES
(1, NULL, 'Ran setup', 'users', NULL, 'Initial setup and seed data checked', '::1', '2026-07-07 00:23:36'),
(2, 1, 'Logged in', 'users', 1, '{\"role\":\"admin\"}', '::1', '2026-07-07 00:24:06'),
(3, 1, 'Logged out', 'users', 1, NULL, '::1', '2026-07-07 00:27:56'),
(4, NULL, 'Added product to cart', 'products', 3, '{\"quantity\":1}', '::1', '2026-07-07 00:28:12'),
(5, 1, 'Logged in', 'users', 1, '{\"role\":\"admin\"}', '::1', '2026-07-09 22:14:35'),
(6, 1, 'Logged in', 'users', 1, '{\"role\":\"admin\"}', '::1', '2026-07-10 17:29:12'),
(7, 1, 'Viewed reports', 'audit_logs', NULL, NULL, '::1', '2026-07-10 17:42:04'),
(8, 1, 'Logged out', 'users', 1, NULL, '::1', '2026-07-10 17:42:49'),
(9, NULL, 'Added product to cart', 'products', 12, '{\"quantity\":1}', '::1', '2026-07-13 07:16:38'),
(10, 1, 'Logged in', 'users', 1, '{\"role\":\"admin\"}', '::1', '2026-07-13 07:16:53'),
(11, 1, 'Logged out', 'users', 1, NULL, '::1', '2026-07-13 07:17:07'),
(12, NULL, 'Registered buyer account', 'users', 2, '{\"email\":\"bolorsean@gmail.com\"}', '::1', '2026-07-13 07:17:57'),
(13, NULL, 'Confirmed buyer e-mail', 'users', 2, '{\"email\":\"bolorsean@gmail.com\"}', '::1', '2026-07-13 07:18:56'),
(14, 2, 'Logged in', 'users', 2, '{\"role\":\"buyer\"}', '::1', '2026-07-13 07:19:07'),
(15, 2, 'Added product to cart', 'products', 13, '{\"quantity\":1}', '::1', '2026-07-13 07:19:14'),
(16, 2, 'Prepared checkout', 'orders', NULL, '{\"subtotal\":2495000}', '::1', '2026-07-13 07:19:22'),
(17, 2, 'Placed order', 'orders', 1, '{\"order_number\":\"404-20260713071933-461\",\"total\":2495000}', '::1', '2026-07-13 07:19:33'),
(18, 2, 'Logged out', 'users', 2, NULL, '::1', '2026-07-13 07:20:04'),
(19, 1, 'Logged in', 'users', 1, '{\"role\":\"admin\"}', '::1', '2026-07-13 07:20:57'),
(20, 1, 'Viewed reports', 'audit_logs', NULL, NULL, '::1', '2026-07-13 07:21:15'),
(21, 1, 'Logged in', 'users', 1, '{\"role\":\"admin\"}', '::1', '2026-07-14 20:52:46'),
(22, 1, 'Logged out', 'users', 1, NULL, '::1', '2026-07-14 20:52:57'),
(23, 2, 'Logged in', 'users', 2, '{\"role\":\"buyer\"}', '::1', '2026-07-14 20:53:18'),
(24, 2, 'Added product to cart', 'products', 11, '{\"quantity\":1}', '::1', '2026-07-14 20:53:26'),
(25, 2, 'Prepared checkout', 'orders', NULL, '{\"subtotal\":2899000}', '::1', '2026-07-14 20:53:29'),
(26, 2, 'Placed order', 'orders', 2, '{\"order_number\":\"404-20260714205332-943\",\"total\":2899000}', '::1', '2026-07-14 20:53:32'),
(27, 2, 'Logged out', 'users', 2, NULL, '::1', '2026-07-14 20:53:35'),
(28, 1, 'Logged in', 'users', 1, '{\"role\":\"admin\"}', '::1', '2026-07-15 00:06:50'),
(29, 1, 'Viewed reports', 'audit_logs', NULL, NULL, '::1', '2026-07-15 00:07:36'),
(30, 1, 'Created vehicle stock', 'products', 19, '{\"sku\":\"EV-004\",\"price\":1798000,\"stock\":1}', '::1', '2026-07-15 00:47:49'),
(31, 1, 'Created vehicle stock', 'products', 20, '{\"sku\":\"EV-005\",\"price\":2850000,\"stock\":1}', '::1', '2026-07-15 00:54:21'),
(32, 1, 'Created vehicle stock', 'products', 21, '{\"sku\":\"PUP-003\",\"price\":1450000,\"stock\":1}', '::1', '2026-07-15 01:01:09'),
(33, 1, 'Created vehicle stock', 'products', 22, '{\"sku\":\"PUP-004\",\"price\":1890000,\"stock\":1}', '::1', '2026-07-15 01:03:25'),
(34, 1, 'Created vehicle stock', 'products', 23, '{\"sku\":\"PUP-005\",\"price\":1980000,\"stock\":1}', '::1', '2026-07-15 01:05:41'),
(35, 1, 'Created vehicle stock', 'products', 24, '{\"sku\":\"PUP-004\",\"price\":1890000,\"stock\":1}', '::1', '2026-07-15 01:13:00'),
(36, 1, 'Created vehicle stock', 'products', 25, '{\"sku\":\"PUP-005\",\"price\":1890000,\"stock\":1}', '::1', '2026-07-15 01:15:19'),
(37, 1, 'Created vehicle stock', 'products', 26, '{\"sku\":\"SED-003\",\"price\":1480000,\"stock\":1}', '::1', '2026-07-15 01:20:12'),
(38, 1, 'Created vehicle stock', 'products', 27, '{\"sku\":\"SED-004\",\"price\":1480000,\"stock\":1}', '::1', '2026-07-15 01:22:56'),
(39, 1, 'Created vehicle stock', 'products', 28, '{\"sku\":\"SED-005\",\"price\":1950000,\"stock\":1}', '::1', '2026-07-15 01:26:55'),
(40, 1, 'Created vehicle stock', 'products', 29, '{\"sku\":\"SUV-004\",\"price\":2690000,\"stock\":1}', '::1', '2026-07-15 01:29:41'),
(41, 1, 'Created vehicle stock', 'products', 30, '{\"sku\":\"SUV-005\",\"price\":2250000,\"stock\":1}', '::1', '2026-07-15 01:31:41'),
(42, 1, 'Added product to cart', 'products', 19, '{\"quantity\":1}', '::1', '2026-07-15 01:39:01'),
(43, 1, 'Logged out', 'users', 1, NULL, '::1', '2026-07-19 16:09:46'),
(44, NULL, 'Registered buyer account', 'users', 3, '{\"email\":\"alesondepano@gmail.com\"}', '::1', '2026-07-19 16:10:24'),
(45, 3, 'Logged in', 'users', 3, '{\"role\":\"buyer\"}', '::1', '2026-07-19 16:11:28'),
(46, 3, 'Added product to cart', 'products', 17, '{\"quantity\":1}', '::1', '2026-07-19 16:13:56'),
(47, 3, 'Updated cart', 'products', 17, '{\"quantity\":1}', '::1', '2026-07-19 16:17:16'),
(48, 3, 'Logged out', 'users', 3, NULL, '::1', '2026-07-19 16:23:25'),
(49, 1, 'Logged in', 'users', 1, '{\"role\":\"admin\"}', '::1', '2026-07-19 16:23:58'),
(50, 1, 'Updated user', 'users', 3, '{\"email\":\"alesondepano@gmail.com\",\"role\":\"buyer\"}', '::1', '2026-07-19 16:24:27'),
(51, 1, 'Added product to cart', 'products', 19, '{\"quantity\":1}', '::1', '2026-07-19 16:24:35'),
(52, 1, 'Prepared checkout', 'orders', NULL, '{\"subtotal\":1798000}', '::1', '2026-07-19 16:24:40'),
(53, 1, 'Placed order', 'orders', 3, '{\"order_number\":\"404-20260719162446-119\",\"total\":1798000}', '::1', '2026-07-19 16:24:46'),
(54, 1, 'Viewed reports', 'audit_logs', NULL, NULL, '::1', '2026-07-19 16:33:11'),
(55, 1, 'Viewed reports', 'audit_logs', NULL, NULL, '::1', '2026-07-19 16:35:57'),
(56, 1, 'Viewed reports', 'audit_logs', NULL, NULL, '::1', '2026-07-19 16:35:58'),
(57, 1, 'Viewed reports', 'audit_logs', NULL, NULL, '::1', '2026-07-19 16:35:59'),
(58, 1, 'Viewed reports', 'audit_logs', NULL, NULL, '::1', '2026-07-19 16:35:59'),
(59, 1, 'Viewed reports', 'audit_logs', NULL, NULL, '::1', '2026-07-19 16:36:00'),
(60, 1, 'Viewed reports', 'audit_logs', NULL, NULL, '::1', '2026-07-19 16:36:02');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Sedans', 'Comfortable daily-drive cars for city and highway use.'),
(2, 'SUVs', 'Family-ready vehicles with flexible space and road presence.'),
(3, 'Pickup Trucks', 'Work-capable vehicles for hauling and outdoor trips.'),
(4, 'Electric Cars', 'Efficient electric units for modern buyers.');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `order_number` varchar(40) NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `payment_method` varchar(60) NOT NULL,
  `payment_reference` varchar(120) DEFAULT NULL,
  `status` enum('pending','approved','released','cancelled') NOT NULL DEFAULT 'pending',
  `shipping_address` text NOT NULL,
  `contact_number` varchar(80) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `buyer_id`, `order_number`, `total_amount`, `payment_method`, `payment_reference`, `status`, `shipping_address`, `contact_number`, `created_at`) VALUES
(1, 2, '404-20260713071933-461', 2495000.00, 'Cash on Delivery', '', 'pending', 'Paranaque City', '09685402085', '2026-07-13 07:19:33'),
(2, 2, '404-20260714205332-943', 2899000.00, 'Cash on Delivery', '', 'pending', 'Paranaque City', '09685402085', '2026-07-14 20:53:32'),
(3, 1, '404-20260719162446-119', 1798000.00, 'Cash on Delivery', '', 'pending', '404 Motors Exchange Main Office', '+63 900 404 0404', '2026-07-19 16:24:46');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`) VALUES
(1, 1, 13, 1, 2495000.00),
(2, 2, 11, 1, 2899000.00),
(3, 3, 19, 1, 1798000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `sku` varchar(40) NOT NULL,
  `name` varchar(120) NOT NULL,
  `model_year` int(11) NOT NULL,
  `mileage` int(11) NOT NULL DEFAULT 0,
  `transmission` varchar(40) NOT NULL,
  `fuel_type` varchar(40) NOT NULL,
  `color` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `sku`, `name`, `model_year`, `mileage`, `transmission`, `fuel_type`, `color`, `description`, `image_url`, `price`, `stock_quantity`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(9, 1, 'SED-001', 'Honda Civic RS', 2024, 1094, 'Automatic', 'Gasoline', 'Platinum White Pearl', 'A sporty and fuel-efficient sedan with a premium interior, advanced safety features, and a turbocharged engine.', 'assets/Honda Civic RS.jpg', 1789000.00, 4, 'active', NULL, NULL, '2026-07-10 18:19:30', '2026-07-10 18:19:30'),
(10, 1, 'SED-002', 'Toyota Corolla Altis G', 2024, 6874, 'Automatic', 'Gasoline', 'Silver Metallic', 'A reliable and fuel-efficient sedan with a spacious cabin, modern infotainment system, and advanced safety features for everyday driving.', 'assets/Toyota Corolla Altis G.webp\r\n', 1450000.00, 6, 'active', NULL, NULL, '2026-07-10 18:20:40', '2026-07-10 18:20:40'),
(11, 4, 'EV-001', 'Tesla Model 3 Long Range', 2025, 8912, 'Automatic', 'Electric', 'Pearl White', 'A premium all-electric sedan with long driving range, fast charging, advanced driver assistance features, and a minimalist interior.', 'assets/Tesla Model 3 Long Range.webp', 2899000.00, 4, 'active', NULL, 2, '2026-07-10 18:22:16', '2026-07-14 20:53:32'),
(12, 4, 'EV-002', 'BYD Seal Premium', 2025, 4908, 'Automatic', 'Electric', 'Aurora White', 'A stylish electric sedan with impressive range, modern technology, and a spacious interior.', 'assets/BYD Seal Premiun.jpg', 2198000.00, 4, 'active', NULL, NULL, '2026-07-10 18:23:56', '2026-07-10 18:23:56'),
(13, 4, 'EV-003', 'Hyundai Ioniq 6', 2025, 3749, 'Automatic', 'Electric', 'Gravity Gold', 'An aerodynamic electric sedan offering excellent efficiency, premium comfort, and advanced safety features.', 'assets/Hyundai loniq 6.jpg\r\n', 2495000.00, 2, 'active', NULL, 2, '2026-07-10 18:24:07', '2026-07-13 07:19:33'),
(14, 3, 'PUP-001', 'Toyota Hilux GR Sport', 2025, 5864, 'Automatic', 'Diesel', 'Emotional Red', 'A rugged and powerful pickup truck built for both off-road adventures and everyday driving with advanced safety features.', 'assets/Toyota Hilux GR Sport.webp', 2150000.00, 4, 'active', NULL, NULL, '2026-07-10 18:51:27', '2026-07-10 18:51:27'),
(15, 3, 'PUP-002', 'Ford Ranger Wildtrak', 2025, 2907, 'Automatic', 'Diesel', 'Absolute Black', 'A premium pickup with a powerful turbo diesel engine, spacious cabin, advanced technology, and exceptional towing capability.', 'assets/Ford Ranger Wildtrak.webp\r\n', 1998000.00, 5, 'active', NULL, NULL, '2026-07-10 18:51:46', '2026-07-10 18:51:46'),
(16, 2, 'SUV-001', 'Toyota Fortuner LTD', 2025, 7942, 'Automatic', 'Diesel', 'Attitude Black', 'A premium 7-seater SUV with a powerful diesel engine, spacious cabin, and advanced safety features.', 'assets/Toyota Fortuner LTD.JPG', 2450000.00, 5, 'active', NULL, NULL, '2026-07-10 18:56:11', '2026-07-10 18:56:11'),
(17, 2, 'SUV-002', 'Ford Everest Titanium', 2025, 5687, 'Automatic', 'Diesel', 'Aluminum Metallic', 'A modern SUV offering advanced driver assistance, a refined interior, and exceptional off-road capability.', 'assets/Ford Everest Titanium.WEBP', 2590000.00, 4, 'active', NULL, NULL, '2026-07-10 18:56:23', '2026-07-10 18:56:23'),
(18, 2, 'SUV-003', 'Honda CR-V RS e:HEV', 2025, 4896, 'Automatic', 'Hybrid', 'Platinum White Pearl', 'A stylish and fuel-efficient hybrid SUV with a premium interior, spacious seating, and advanced Honda SENSING safety technology.', 'assets/Honda CR-V.WEBP', 2790000.00, 6, 'active', NULL, NULL, '2026-07-10 18:56:43', '2026-07-10 18:56:43'),
(19, 4, 'EV-004', 'BYD Atto 3', 2024, 6356, 'Automatic', 'Electric', 'Pearl White', 'A modern electric crossover with efficient performance, smart technology, and a practical interior.', 'assets/BYD Atto 3 2024.JPG', 1798000.00, 0, 'active', 1, 1, '2026-07-15 00:47:49', '2026-07-19 16:24:46'),
(20, 4, 'EV-005', 'Tesla Model 3', 2023, 5766, 'Automatic', 'Electric BOBO ka ba', 'Crimson Red', 'A premium electric sedan with quick acceleration, long range, minimalist design, and advanced technology.', 'assets/Tesla Model 3 2023.AVIF', 2850000.00, 1, 'active', 1, 1, '2026-07-15 00:54:21', '2026-07-15 00:54:21'),
(21, 3, 'PUP-003', 'Mitsubishi Strada Athlete', 2021, 7610, 'Automatic', 'Diesel', 'Metallic Orange', 'A dependable pickup with sporty styling, diesel efficiency, and practical utility for daily and business use.', 'assets/Mitsubishi Strada Athlete 2021.WEBP', 1450000.00, 1, 'active', 1, 1, '2026-07-15 01:01:09', '2026-07-15 01:01:09'),
(24, 3, 'PUP-004', 'Ford Ranger Wildtrak', 2022, 2398, 'Automatic', 'Diesel', 'Golden Orange', 'A tough and tech-ready pickup with strong towing capability, a bold design, and a comfortable cabin.', 'assets/Ford Ranger Wildtrak 2022.JPG', 1890000.00, 1, 'active', 1, 1, '2026-07-15 01:13:00', '2026-07-15 01:13:00'),
(25, 3, 'PUP-005', 'Toyota Hilux Conquest', 2023, 6908, 'Automatic', 'Diesel', 'Dark Gray Metallic', 'A durable pickup truck built for work and adventure, offering strong diesel power and rugged capability.', 'assets/Toyota Hilux Conquest 2023.JPG', 1890000.00, 1, 'active', 1, 1, '2026-07-15 01:15:19', '2026-07-15 01:15:19'),
(26, 1, 'SED-003', 'Honda Civic RS Turbo', 2023, 6767, 'Automatic', 'Diesel', 'White', 'A stylish compact sedan with premium interior quality, sharp handling, and elegant design.', 'assets/Honda Civic RS Turbo 2023.JPG', 1480000.00, 1, 'active', 1, 1, '2026-07-15 01:20:12', '2026-07-15 01:20:12'),
(27, 1, 'SED-004', 'Mazda 3 Sport Sedan', 2022, 6903, 'Automatic', 'Diesel', 'Black', 'A stylish compact sedan with premium interior quality, sharp handling, and elegant design.', 'assets/Mazda 3 Sport Sedan 2022.JPG', 1480000.00, 1, 'active', 1, 1, '2026-07-15 01:22:56', '2026-07-15 01:22:56'),
(28, 1, 'SED-005', 'Toyota Camry Hybrid', 2021, 5097, 'Automatic', 'Hybrid', 'Black', 'A refined hybrid sedan with smooth performance, excellent fuel economy, and a comfortable executive-style interior.', 'assets/Toyota Camry Hybrid.AVIF', 1950000.00, 1, 'active', 1, 1, '2026-07-15 01:26:55', '2026-07-15 01:26:55'),
(29, 2, 'SUV-004', 'Ford Everest Titanium', 2023, 9122, 'Automatic', 'Diesel', 'Black', 'A premium SUV with advanced safety features, comfortable seating, and excellent road presence.', 'assets/Ford Everest Titanium 2023.JPG', 2690000.00, 1, 'active', 1, 1, '2026-07-15 01:29:41', '2026-07-15 01:29:41'),
(30, 2, 'SUV-005', 'Toyota Fortuner 2.8 V', 2022, 8969, 'Automatic', 'Diesel', 'White', 'A strong 7-seater SUV with a diesel engine, spacious cabin, and reliable performance for family and long-distance trips.', 'assets/Toyota Fortuner 2.8 V.WEBP', 2250000.00, 1, 'active', 1, 1, '2026-07-15 01:31:41', '2026-07-15 01:31:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `complete_name` varchar(120) NOT NULL,
  `email` varchar(160) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `complete_address` text NOT NULL,
  `contact_numbers` varchar(80) NOT NULL,
  `role` enum('buyer','admin') NOT NULL DEFAULT 'buyer',
  `email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `confirmation_token` varchar(128) DEFAULT NULL,
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `complete_name`, `email`, `password_hash`, `complete_address`, `contact_numbers`, `role`, `email_verified`, `confirmation_token`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Error 404 Admin', 'admin@404motors.local', '$2y$10$GbQF.mKRE.DQs8hMVuuaXOPezlVVJfWKhk6uRZ69vdMkWOW0pNbY.', '404 Motors Exchange Main Office', '+63 900 404 0404', 'admin', 1, NULL, 'active', '2026-07-07 00:23:36', '2026-07-07 00:23:36'),
(2, 'Sean Bolor', 'bolorsean@gmail.com', '$2y$10$6GqvDPQjioG6LeX1RG19r.ZDA5uxXyh.5NA7X9GJm2/YGyzhGxjbm', 'Paranaque City', '09685402085', 'buyer', 1, NULL, 'active', '2026-07-13 07:17:55', '2026-07-13 07:18:56'),
(3, 'Aleson Axel D. De Pano', 'alesondepano@gmail.com', '$2y$10$7Z./EiovoICo/cFTeso7qud7Dl1o7F0DVz.HCb2nZPq8/kaB7BeIy', 'Manila, Philippines', '09761427589', 'buyer', 1, 'caba708a1ab06394c6c4a5e121ad6eb4a25f537c08e20a097132b10c115c1224', 'active', '2026-07-19 16:10:24', '2026-07-19 16:24:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_audit_user` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `fk_orders_buyer` (`buyer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_items_order` (`order_id`),
  ADD KEY `fk_order_items_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `fk_products_category` (`category_id`),
  ADD KEY `fk_products_created_by` (`created_by`),
  ADD KEY `fk_products_updated_by` (`updated_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_buyer` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `fk_products_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_products_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
