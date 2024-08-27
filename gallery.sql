-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 09, 2024 at 03:03 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gallery`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`) VALUES
(1, 'admin', '1c6637a8f2e1f75e06ff9984894d6bd16a3a36a9'),
(8, 'dina', '43814346e21444aaf4f70841bf7ed5ae93f55a9d');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `password`) VALUES
(1, 'dinaji', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2'),
(2, 'imesha', '222');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `tables` varchar(10) NOT NULL,
  `parking` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `adults` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `name`, `email`, `tables`, `parking`, `date`, `adults`) VALUES
(22, 'dina', 'dinajirajapaksha08@gmail.com', '3', 'Yes', '2024-08-02', '1'),
(26, 'dina', 'induwara419@gmail.com', '5', 'Yes', '2024-08-23', '1'),
(27, 'Rotti', 'pittu@gmail.com', '2', 'Yes', '0000-00-00', '1'),
(28, 'dina', 'dinajirajapaksha08@gmail.com', '3', 'Yes', '2024-08-08', '2'),
(29, 'menda', 'induwaramendis419@gmail.com', '2', 'Yes', '2024-08-20', '1');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES
(2, 1, 5, 'Rice and Curry', 900, 1, '7.png'),
(3, 1, 2, 'Rotti', 200, 1, '14.png'),
(8, 3, 2, 'Rotti', 200, 1, '14.png'),
(9, 3, 3, 'Hoppers', 250, 1, '19.png'),
(16, 2, 5, 'Rice and Curry', 900, 1, '7.png'),
(17, 2, 18, 'Orange juice', 900, 1, 'drink-1.png'),
(18, 2, 17, 'Noodles', 1200, 1, 'dish-1.png');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `number` varchar(10) NOT NULL,
  `message` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` date NOT NULL DEFAULT current_timestamp(),
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`) VALUES
(2, 2, 'Rajapakshage Dinaji ', '0776453423', 'cash on delivery', 'flat no.426/17,2ND LANE,ALASWATHTHA,THITHTHAWELLA., rffff - 60000', 'Strawberry ice cream ( 700 x 1 ) - ', 700, '2024-08-02', ''),
(3, 2, 'Rajapakshage Dinaji ', '07758125', 'cash on delivery', 'flat no.426/17,2ND LANE,ALASWATHTHA,THITHTHAWELLA., 2555 - 60000', 'cofee ( 900 x 1 ) - ', 900, '2024-08-02', 'pending'),
(4, 2, 'Rajapakshage Dinaji ', '120463', 'credit card', 'flat no.426/17,2ND LANE,ALASWATHTHA,THITHTHAWELLA., wehera - 60000', 'shushi ( 1200 x 1 ) - Rotti ( 200 x 1 ) - ', 1400, '2024-08-04', 'pending'),
(5, 2, 'nethmi', '4', 'credit card', 'flat no.426/17,2ND LANE,ALASWATHTHA,THITHTHAWELLA., colombo 10 - 60000', 'Rotti ( 200 x 1 ) - Pittu ( 250 x 1 ) - Hoppers ( 250 x 1 ) - ', 700, '2024-08-04', 'pending'),
(6, 2, 'Rajapakshage Dinaji ', '122', 'paytm', 'flat no.426/17,2ND LANE,ALASWATHTHA,THITHTHAWELLA., colombo 10 - 60000', 'Noodles ( 1200 x 1 ) - Cup Cake ( 800 x 1 ) - Orange juice ( 900 x 1 ) - ', 2900, '2024-08-04', 'pending'),
(7, 4, 'Rajapakshage Dinaji ', '12345678', 'cash on delivery', 'flat no.426/17,2ND LANE,ALASWATHTHA,THITHTHAWELLA., colombo 10 - 60000', 'chinise crabs ( 1300 x 1 ) - cofee ( 900 x 2 ) - ', 3100, '2024-08-05', 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`) VALUES
(1, 'Pittu', 250, '13.png'),
(2, 'Rotti', 200, '14.png'),
(3, 'Hoppers', 250, '19.png'),
(4, 'Kiri Hodi', 450, '24.png'),
(5, 'Rice and Curry', 900, '7.png'),
(6, 'Chicken Curry', 800, '12.png'),
(7, 'corn soup', 1200, 'Australian1.png'),
(8, 'Australian Meat loaf', 1200, 'Australian2.png'),
(9, 'Biriyani', 1500, 'Australian3.png'),
(10, 'Avacado Bread', 1200, 'Australian5.png'),
(11, 'chinise crabs', 1300, 'Australian6.png'),
(12, 'Burger', 900, 'burger-2.png'),
(13, 'soup', 800, 'chinesefirst.png'),
(14, 'Strawberry ice cream', 700, 'dessert-1.png'),
(15, 'chocalate cake', 850, 'dessert-2.png'),
(16, 'Cup Cake', 800, 'dessert-4.png'),
(17, 'Noodles', 1200, 'dish-1.png'),
(18, 'Orange juice', 900, 'drink-1.png'),
(19, 'pizza', 1200, 'pizza-1.png'),
(20, 'cofee', 900, 'drink-2.png'),
(21, 'shushi', 1200, 'chinesesecond.png');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`) VALUES
(1, 'menda', 'menda@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef'),
(2, 'Dinaji', 'dinajirajapaksha08@gmail.com', '618dcdfb0cd9ae4481164961c4796dd8e3930c8d'),
(3, 'nethmi', 'nemthmi@gmail.com', 'b815aa1c17f6bf32eb4deaa2a3dd51aa6396e2b1'),
(4, 'mendis', 'induwaramendis419@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
