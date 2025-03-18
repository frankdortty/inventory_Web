-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2025 at 07:37 AM
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
-- Database: `accounting`
--

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `explains` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `name`, `explains`, `amount`, `date`, `created_at`) VALUES
(1, 'l', 'll', 22.00, '0000-00-00', '2025-01-19 04:40:55'),
(2, 'Lanch', 'Lanch with clieny', 32500.00, '0000-00-00', '2025-01-19 17:42:29'),
(3, 'lanch ', 'lanch with jill', 300000.00, '2025-03-18', '2025-03-18 05:07:21'),
(4, 'Mathew James', 'lanch with jill', 835000.00, '2025-03-17', '2025-03-18 05:09:24'),
(5, 'franklyn okorie', 'lanch with jill', 300000.00, '2025-03-11', '2025-03-18 05:10:15'),
(6, 'holder', 'lanch with jill', 300000.00, '2025-03-13', '2025-03-18 05:12:07');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) NOT NULL,
  `category` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `sale_price`, `category`, `quantity`, `image`, `created_at`, `updated_at`) VALUES
(6, 'Capricon', 'rich in poor', 54000.00, 22000.00, 'nice coopon', 290, 'uploads/products/product_67d85007a8e428.31599799.png', '2025-03-17 16:38:31', '2025-03-17 16:38:31'),
(7, 'holder', 'this is diopjert', 43000.00, 49000.00, 'nice coopon', 400, 'uploads/products/product_67d8c1e406e7a7.86249809.png', '2025-03-18 00:44:20', '2025-03-18 00:44:20');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `contact_info` varchar(255) DEFAULT NULL,
  `product` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `tax` decimal(10,2) DEFAULT NULL,
  `profit` varchar(100) NOT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `customer_name`, `contact_info`, `product`, `quantity`, `subtotal`, `tax`, `profit`, `total`, `created_at`) VALUES
(11, 'ulochim', '09087654356', 'iphone 13 Pro max', 2, 48.00, 4.80, '', 52.80, '2025-01-08 14:56:38'),
(12, 'ulochikdi', '90087654567', 'iphone 13 Pro max', 2, 48.00, 4.80, '', 52.80, '2025-01-08 15:11:55'),
(13, 'retls', '34567890', 'product test', 3, 62.97, 6.30, '', 69.27, '2025-01-11 09:51:41'),
(14, 'chigochim', '98765438907', 'xiaomi mi a3', 2, 390000.00, 39000.00, '', 429000.00, '2025-01-19 19:29:21'),
(28, 'kim', '0987654', 'Product Test', 3, 62.97, 6.30, '', 69.27, '2025-01-19 19:42:21'),
(29, 'kilom', '234567890', 'iphone 13 Pro max', 3, 72.00, 7.20, '3', 79.20, '2025-01-19 20:27:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `full_name`, `email`, `password`, `company_name`, `created_at`) VALUES
(4, 'franklyn', 'Okorie Franklyn', 'okoriefrankly16@gmail.com', '$2y$10$x5BY6z4.1KV94rXrxRoIFuwzigDCcdkiGdBJ7ZyTekpdJ7SonFqAG', 'ionstech', '2024-12-28 13:14:53'),
(38, 'fff', 'fff', 'okoriefrankly16@gmail.co', '$2y$10$NECdO3uxUU5w12guKEUjJOxYFG82CPUfvBJdElGQ6G7WldPJknDPa', '2345', '2024-12-28 13:32:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
