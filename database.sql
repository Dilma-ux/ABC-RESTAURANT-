-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 31, 2024
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Database: `restaurant_db`
--

-- --------------------------------------------------------
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `position` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table `users`
INSERT INTO `users` (`user_id`, `name`, `email`, `mobile`, `position`, `password`) VALUES
(1, 'Admin User', 'admin@example.com', '1234567890', 'admin', 'hashed_password_here');

-- --------------------------------------------------------
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table `services`
INSERT INTO `services` (`service_id`, `name`, `icon`, `description`) VALUES
(1, 'Dine-In', 'fas fa-utensils', 'Enjoy our dine-in service.');

-- --------------------------------------------------------
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `facility_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`facility_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table `facilities`
INSERT INTO `facilities` (`facility_id`, `name`, `icon`, `description`) VALUES
(1, 'Free Wi-Fi', 'fas fa-wifi', 'Enjoy free Wi-Fi during your visit.');

-- --------------------------------------------------------
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(255) NOT NULL,
  `special_offer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table `menu`
INSERT INTO `menu` (`menu_id`, `name`, `description`, `price`, `category`, `special_offer`) VALUES
(1, 'Pizza', 'Delicious pizza with fresh ingredients', 9.99, 'Main Course', 'Buy 1 Get 1 Free');

-- --------------------------------------------------------
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `service_type` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` time NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'confirmed',
  PRIMARY KEY (`reservation_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table `reservations`
INSERT INTO `reservations` (`reservation_id`, `user_id`, `service_type`, `location`, `reservation_date`, `reservation_time`, `status`) VALUES
(1, 1, 'Dine-In', 'Main Branch', '2024-08-31', '18:00:00', 'confirmed');

-- --------------------------------------------------------
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `items` text NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `delivery_location` varchar(255) DEFAULT NULL,
  `phone_number` varchar(15) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`payment_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table `payments`
INSERT INTO `payments` (`payment_id`, `user_id`, `items`, `total_price`, `delivery_location`, `phone_number`, `status`) VALUES
(1, 1, 'Pizza x2', 19.98, '123 Main St', '1234567890', 'completed');

-- --------------------------------------------------------
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `faq_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`faq_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table `faqs`
INSERT INTO `faqs` (`faq_id`, `user_id`, `question`, `answer`, `status`) VALUES
(1, 1, 'What are your opening hours?', 'We are open from 10 AM to 10 PM.', 'answered');

-- --------------------------------------------------------
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `activity` text NOT NULL,
  `activity_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table `user_activity`
INSERT INTO `user_activity` (`id`, `user_id`, `activity`, `activity_date`) VALUES
(1, 1, 'User logged in.', '2024-08-31 10:00:00');

-- --------------------------------------------------------
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table `gallery`
INSERT INTO `gallery` (`id`, `name`, `description`, `image_path`) VALUES
(1, 'Restaurant Interior', 'Our beautiful restaurant interior.', '/images/interior.jpg');

-- --------------------------------------------------------

--
-- AUTO_INCREMENT for dumped tables
--

ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `facilities`
  MODIFY `facility_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `menu`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `faqs`
  MODIFY `faq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `user_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

