-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 21, 2025 at 07:03 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `expense_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

DROP TABLE IF EXISTS `collections`;
CREATE TABLE IF NOT EXISTS `collections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `member_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `collections`
--

INSERT INTO `collections` (`id`, `member_id`, `amount`, `created_at`) VALUES
(1, 9, 5000.00, '2025-01-17 10:04:50'),
(2, 5, 300.00, '2025-01-17 10:27:15'),
(3, 13, 460.00, '2025-01-17 10:44:59'),
(4, 11, 900.00, '2025-01-17 10:46:08'),
(5, 13, 4500.00, '2025-01-21 04:36:21'),
(6, 12, 1235.00, '2025-01-21 04:36:36'),
(7, 3, 1399.00, '2025-01-21 04:36:57'),
(8, 9, 500.00, '2025-01-21 04:38:15'),
(9, 14, 1000.00, '2025-01-21 05:57:48');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
CREATE TABLE IF NOT EXISTS `expenses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `expense_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `exclude_member` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `name`, `expense_type`, `amount`, `created_at`, `exclude_member`) VALUES
(1, NULL, 'breakfast', 500.00, '2025-01-17 10:05:09', '5,3,14,11,12,13'),
(2, NULL, 'breakfast', 200.00, '2025-01-17 10:27:42', '3,14,11,12,13'),
(3, NULL, 'breakfast', 300.00, '2025-01-17 10:44:13', '11,12,13'),
(4, NULL, 'breakfast', 210.00, '2025-01-17 10:44:32', '5,3,14,11,12,13'),
(5, NULL, 'dinner', 1450.00, '2025-01-17 10:44:39', ''),
(6, NULL, 'breakfast', 1220.00, '2025-01-17 10:44:50', '9,5,3'),
(7, NULL, 'breakfast', 1000.00, '2025-01-17 10:45:42', '12'),
(8, NULL, 'lunch', 1000.00, '2025-01-17 15:26:32', '11,12,13'),
(9, NULL, 'lunch', 1800.00, '2025-01-21 04:37:09', ''),
(10, NULL, 'breakfast', 1850.00, '2025-01-21 04:37:39', '9,5,3,13'),
(11, NULL, 'breakfast', 1500.00, '2025-01-21 05:58:41', ''),
(12, NULL, 'Bill', 1000.00, '2025-01-21 06:00:10', '9,5,3,16,17,14,11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone_number` varchar(15) NOT NULL,
  `balance` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone_number`, `balance`) VALUES
(9, 'talha khan', 'talha1234@gmail.com', '112233', '03425664528', 3567.38),
(5, 'talha1', 'talha1@gmail.com', 'talha1', '2147483647', -922.62),
(3, 'yaseen', 'yaseen11@gmail.com', 'yaseen11', '2147483647', 276.38),
(16, 'Talha', 'talha123@gmail.com', '1122', '03475664524', -166.67),
(17, 'izaz uddin', 'izazuddin@gmail.com', NULL, '03137766778', -166.67),
(14, 'izazuddin', 'izazuddin1@gmail.com', '12345', '03190750829', -1044.29),
(11, 'waqas', 'waqas122@gmail.com', 'waqas1', '03415665635', -819.29),
(12, 'khan', 'khan09@gmail.com', 'khan09', '03486478578', -817.62),
(13, 'kashif', 'kashif@gmail.com', 'kashif112', '03467757667', 3357.38);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
