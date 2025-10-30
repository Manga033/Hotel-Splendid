-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2025 at 10:40 PM
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
-- Database: `hotel_splendid_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `guest_id` int(11) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `num_of_guests` int(11) DEFAULT 1,
  `num_of_children` int(11) DEFAULT 0,
  `type` enum('standard','nonrefundable','corporate','agency','other') DEFAULT 'standard',
  `total_price` decimal(10,2) DEFAULT 0.00,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `guest_id`, `check_in_date`, `check_out_date`, `num_of_guests`, `num_of_children`, `type`, `total_price`, `status`, `created_at`) VALUES
(1, 1, '2025-11-01', '2025-11-05', 1, 0, 'standard', 320.00, 'confirmed', '2025-10-30 10:52:25'),
(2, 2, '2025-11-03', '2025-11-04', 2, 0, 'nonrefundable', 120.00, 'completed', '2025-10-30 10:52:25'),
(3, 3, '2025-11-10', '2025-11-13', 2, 1, 'corporate', 480.00, 'pending', '2025-10-30 10:52:25'),
(4, 4, '2025-12-01', '2025-12-07', 3, 1, 'agency', 960.00, 'confirmed', '2025-10-30 10:52:25'),
(5, 5, '2025-12-10', '2025-12-12', 2, 0, 'standard', 240.00, 'cancelled', '2025-10-30 10:52:25'),
(6, 1, '2025-10-30', '2025-11-01', 2, 0, 'standard', 200.00, 'pending', '2025-10-30 18:38:10');

-- --------------------------------------------------------

--
-- Table structure for table `booking_rooms`
--

CREATE TABLE `booking_rooms` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `nightly_rate` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_rooms`
--

INSERT INTO `booking_rooms` (`id`, `booking_id`, `room_id`, `nightly_rate`) VALUES
(1, 1, 1, 80.00),
(2, 2, 2, 120.00),
(3, 3, 3, 160.00),
(4, 4, 4, 160.00),
(5, 5, 5, 120.00);

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE `guests` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('male','female','prefer_not_to_say') DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(225) NOT NULL,
  `tel_num` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`id`, `first_name`, `last_name`, `dob`, `gender`, `email`, `username`, `password`, `tel_num`, `country`, `city`, `address`) VALUES
(1, 'Danin', 'Mangafic', '1995-03-12', 'male', 'danin.mangafic@example.com', 'daninm', 'password123', '+38761111222', 'Bosnia and Herzegovina', 'Sarajevo', 'Titova 5'),
(2, 'Davud', 'Mahmutovic', '1996-07-21', 'male', 'davud.mahmutovic@example.com', 'davudm', 'password', '+38761222333', 'Bosnia and Herzegovina', 'Sarajevo', 'Zmaja od Bosne 12'),
(3, 'Muhamed', 'Sarajlic', '1997-02-15', 'male', 'muhamed.sarajlic@example.com', 'muhameds', 'pass123', '+38761333444', 'Bosnia and Herzegovina', 'Tuzla', 'Alije Izetbegovica 8'),
(4, 'Rijad', 'Pleho', '1994-11-09', 'male', 'rijad.pleho@example.com', 'rijadp', 'sifra123', '+38761444555', 'Bosnia and Herzegovina', 'Mostar', 'Marsala Tita 16'),
(5, 'Eldar', 'Musovic', '1995-05-30', 'male', 'eldar.musovic@example.com', 'eldarm', 'sifrasifra', '+38761555666', 'Bosnia and Herzegovina', 'Zenica', 'Kralja Tvrtka 7');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `guest_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `guest_id`, `rating`, `title`, `comment`, `created_at`) VALUES
(1, 1, 5, 'Fantastic stay', 'Everything was perfect, clean and quiet.', '2025-10-30 10:53:24'),
(3, 3, 3, 'Average', 'Room was fine, but Wi-Fi was weak.', '2025-10-30 10:53:24'),
(4, 4, 5, 'Loved it', 'Spacious room and friendly reception.', '2025-10-30 10:53:24'),
(5, 5, 2, 'Could be better', 'Room was cold and needs renovation.', '2025-10-30 10:53:24');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `floor` int(11) DEFAULT NULL,
  `type` enum('single','double','suite','deluxe','family') DEFAULT 'single',
  `base_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `floor`, `type`, `base_price`, `description`, `status`) VALUES
(1, '101', 1, 'single', 80.00, 'Single room with balcony', 'available'),
(2, '102', 1, 'double', 120.00, 'Double room with city view', 'available'),
(3, '201', 2, 'suite', 200.00, 'Luxury suite with living area', 'occupied'),
(4, '202', 2, 'deluxe', 160.00, 'Deluxe room with modern decor', 'available'),
(5, '301', 3, 'family', 180.00, 'Family room with two bedrooms', 'maintenance');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_bookings_guest` (`guest_id`),
  ADD KEY `idx_bookings_dates` (`check_in_date`,`check_out_date`);

--
-- Indexes for table `booking_rooms`
--
ALTER TABLE `booking_rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_id` (`booking_id`,`room_id`),
  ADD KEY `idx_booking_rooms_room` (`room_id`);

--
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reviews_guest` (`guest_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_number` (`room_number`),
  ADD KEY `idx_rooms_type` (`type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_rooms`
--
ALTER TABLE `booking_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `guests`
--
ALTER TABLE `guests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_bookings_guest` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `booking_rooms`
--
ALTER TABLE `booking_rooms`
  ADD CONSTRAINT `fk_booking_rooms_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_booking_rooms_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_guest` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
