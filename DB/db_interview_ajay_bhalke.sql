-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2022 at 09:50 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_interview_ajay_bhalke`
--

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `text` varchar(100) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `opened` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `text`, `parent_id`, `opened`, `created_at`, `updated_at`) VALUES
(1, 'Electronics', 0, 0, '2022-04-04 02:09:37', '2022-04-04 02:09:37'),
(2, 'Home Appliances', 0, 0, '2022-04-04 02:09:51', '2022-04-04 02:09:51'),
(3, 'Mobiles', 1, 0, '2022-04-04 02:10:04', '2022-04-04 02:10:04'),
(4, 'Laptop', 1, 0, '2022-04-04 02:10:12', '2022-04-04 02:10:12'),
(5, 'Android', 3, 0, '2022-04-04 02:10:23', '2022-04-04 02:10:23'),
(6, 'iPhone', 3, 0, '2022-04-04 02:10:39', '2022-04-04 02:10:39'),
(7, 'TV', 2, 0, '2022-04-04 02:11:03', '2022-04-04 02:11:03'),
(8, 'LED', 7, 0, '2022-04-04 02:11:11', '2022-04-04 02:11:11'),
(9, 'LCD', 7, 0, '2022-04-04 02:11:21', '2022-04-04 02:11:21'),
(10, 'Dress', 0, 0, '2022-04-04 02:11:53', '2022-04-04 02:11:53'),
(11, 'Women', 10, 0, '2022-04-04 02:12:39', '2022-04-04 02:12:39'),
(12, 'Men', 10, 0, '2022-04-04 02:12:44', '2022-04-04 02:12:44'),
(13, 'Top', 11, 0, '2022-04-04 02:13:11', '2022-04-04 02:13:11'),
(14, 'Tshirt', 11, 0, '2022-04-04 02:13:33', '2022-04-04 02:13:33'),
(15, 'Skirt', 11, 0, '2022-04-04 02:13:43', '2022-04-04 02:13:43'),
(16, 'Shirt', 12, 0, '2022-04-04 02:13:53', '2022-04-04 02:13:53'),
(17, 'Pant', 12, 0, '2022-04-04 02:13:58', '2022-04-04 02:13:58'),
(18, 'Tshirt', 12, 0, '2022-04-04 02:14:04', '2022-04-04 02:14:04'),
(19, 'Samsung', 5, 0, '2022-04-04 02:14:52', '2022-04-04 02:14:52'),
(20, 'Redmi', 5, 0, '2022-04-04 02:15:05', '2022-04-04 02:15:05');

-- --------------------------------------------------------

--
-- Table structure for table `test_cases`
--

CREATE TABLE `test_cases` (
  `id` int(11) NOT NULL,
  `name` varchar(2000) NOT NULL,
  `module_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `test_cases`
--

INSERT INTO `test_cases` (`id`, `name`, `module_id`, `created_at`, `updated_at`) VALUES
(13, 'Test case for electronics', 1, '2022-04-04 02:15:44', '2022-04-04 02:15:44'),
(14, 'This is new test case for electronics', 1, '2022-04-04 02:15:57', '2022-04-04 02:15:57'),
(15, 'Test case for mobile phones', 3, '2022-04-04 02:16:12', '2022-04-04 02:16:12'),
(16, 'Screen issue', 19, '2022-04-04 02:16:37', '2022-04-04 02:16:37'),
(17, 'Battery issue', 19, '2022-04-04 02:16:45', '2022-04-04 02:16:45'),
(18, 'Touchpad issue', 19, '2022-04-04 02:16:52', '2022-04-04 02:16:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_cases`
--
ALTER TABLE `test_cases`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `test_cases`
--
ALTER TABLE `test_cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
