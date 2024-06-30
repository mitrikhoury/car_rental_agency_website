-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Jun 30, 2024 at 10:38 PM
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
-- Database: `db_car_rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `car_id` int(11) NOT NULL,
  `model` varchar(100) NOT NULL,
  `make` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `registration_year` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `price_per_day` decimal(10,2) NOT NULL,
  `capacity_people` int(11) NOT NULL,
  `capacity_suitcases` int(11) NOT NULL,
  `color` varchar(50) NOT NULL,
  `fuel_type` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'available',
  `avg_consumption` decimal(5,2) NOT NULL,
  `horsepower` int(11) NOT NULL,
  `length` decimal(5,2) NOT NULL,
  `width` decimal(5,2) NOT NULL,
  `gear_type` varchar(50) NOT NULL,
  `plate_number` varchar(20) NOT NULL,
  `conditions` text NOT NULL,
  `image1` varchar(255) DEFAULT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `image3` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `return_location_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`car_id`, `model`, `make`, `type`, `registration_year`, `description`, `price_per_day`, `capacity_people`, `capacity_suitcases`, `color`, `fuel_type`, `status`, `avg_consumption`, `horsepower`, `length`, `width`, `gear_type`, `plate_number`, `conditions`, `image1`, `image2`, `image3`, `city`, `return_location_name`) VALUES
(1, 'Camry', 'Toyota', 'Sedan', 2020, 'Comfortable sedan for daily use', 50.00, 5, 2, 'Silver', 'Petrol', 'available', 7.50, 180, 4.80, 1.80, 'Automatic', 'A1235', 'Good condition, no smoking', 'car1img1.jpeg', 'car1img2.jpeg', 'car1img3.jpeg', 'Nablus', 'Nablus'),
(2, 'Civic', 'Honda', 'Sedan', 2019, 'Economical sedan with good fuel efficiency', 45.00, 5, 2, 'Blue', 'Petrol', 'available', 6.80, 150, 4.50, 1.70, 'Automatic', 'A7456', 'No smokimg', 'car2img1.jpeg', 'car2img2.jpeg', 'car2img3.jpeg', 'Ramallah', 'Ramallah'),
(8, 'x5', 'BMW', 'SUV', 2020, 'This is a luxurious SUV with advanced features', 150.00, 5, 2, 'white', 'Diesel', 'available', 10.60, 300, 4.90, 2.10, 'Automatic', 'A12725', ' No smoking, no off-road driving.', 'car8img1.jpeg', 'car8img2.jpg', 'car8img3.jpeg', 'Birzeit', 'Birzeit'),
(9, 'Golf', 'VW', 'Hatchback', 2024, 'Where luxury meets performance', 100.00, 5, 2, 'Black', 'Petrol', 'available', 6.00, 180, 3.00, 2.00, 'Automatic', 'B67262', 'No smoking ', 'car9img1.jpeg', 'car9img2.jpeg', 'car9img3.jpeg', 'Nablus', 'Nablus'),
(11, 'A4', 'Audi', 'Sedan', 2023, 'Where luxury meets performance', 130.00, 5, 2, 'blue', 'Petrol', 'available', 8.90, 220, 3.00, 2.00, 'Manual', 'C88832', 'No Smoking', 'car11img1.jpeg', 'car11img2.jpeg', 'car11img3.jpeg', 'Ramallah', 'Ramallah'),
(12, 'Kodiaq', 'Skoda', 'SUV', 2024, 'This is a luxurious SUV with advanced features', 145.00, 7, 3, 'white', 'Diesel', 'available', 9.20, 195, 4.00, 2.00, 'Automatic', 'B76213', 'No Smoking , no off-road driving', 'car12img1.jpeg', 'car12img2.jpeg', 'car12img3.jpeg', 'Ramallah', 'Ramallah'),
(13, 'C-Class', 'Mercedes-Benz', 'Sedan', 2022, 'This is a luxurious Sedan with advanced features', 180.00, 5, 2, 'Black', 'Petrol', 'available', 7.30, 190, 4.00, 2.00, 'Automatic', 'A34323', 'No Smoking', 'car13img1.jpeg', 'car13img2.jpeg', 'car13img3.jpeg', 'Ramallah', 'Ramallah'),
(15, 'octavia', 'Skoda', 'Sedan', 2023, 'This is a luxurious Sedan with advanced features', 130.00, 5, 2, 'Red', 'Diesel', 'available', 6.20, 210, 4.00, 2.00, 'Automatic', 'A84732', 'No Smoking', 'car15img1.jpeg', 'car15img2.jpeg', 'car15img3.jpeg', 'Ramallah', 'Ramallah'),
(16, '3', 'Tesla', 'Sedan', 2024, 'Where luxury meets performance', 200.00, 5, 2, 'white', 'Electric', 'available', 4.00, 170, 3.00, 2.00, 'Automatic', 'A66352', 'No Smoking', 'car16img1.jpeg', 'car16img2.jpeg', 'car16img3.jpeg', 'Ramallah', 'Ramallah'),
(17, 'glc-class', 'Mercedes-Benz', 'SUV', 2024, 'This is a luxurious SUV with advanced features', 220.00, 5, 2, 'white', 'Petrol', 'available', 8.80, 220, 4.00, 2.00, 'Automatic', 'B83732', 'No smoking', 'car17img1.jpeg', 'car17img2.jpeg', 'car17img3.jpeg', 'Bethlehem', 'Bethlehem'),
(18, 'm4', 'BMW', 'Sedan', 2024, 'Where luxury meets performance', 300.00, 5, 2, 'white', 'Petrol', 'rented', 13.50, 300, 3.00, 2.00, 'Automatic', 'A44421', 'No Drift  😉', 'car18img1.jpeg', 'car18img2.jpeg', 'car18img3.jpeg', 'Ramallah', 'Ramallah');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `location_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `return_location_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`location_id`, `address`, `city`, `postal_code`, `country`, `name`, `telephone`, `return_location_name`) VALUES
(1, '123 Main St', 'Ramallah', '12345', 'Palestine', 'Ramallah', '0599345238', 'Ramallah'),
(2, '456 Elm St', 'Bethlehem', '54321', 'Palestine', 'Bethlehem', '0568347347', 'Bethlehem'),
(3, '678 Main St', 'Birzeit', '66433', 'Palestine', 'Birzeit', '0573234234', 'Birzeit'),
(5, '876 Main St', 'Jerusalem', '98412', 'Palestine', 'Jerusalem', '0527463232', 'Jerusalem'),
(6, '816 Main St', 'Nablus', '27281', 'Palestine', 'Nablus', '0592222211', 'Nablus'),
(7, '821 Main St', 'Jenin', '81239', 'Palestine', 'Jenin', '0596252212', 'Jenin');

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

CREATE TABLE `rentals` (
  `rental_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `car_id` int(11) NOT NULL,
  `rental_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `invoice_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rentals`
--

INSERT INTO `rentals` (`rental_id`, `user_id`, `car_id`, `rental_date`, `return_date`, `invoice_date`) VALUES
(3138066516, 4, 11, '2024-06-22', '2024-06-25', '2024-06-22'),
(5326650670, 4, 12, '2024-06-25', '2024-06-29', '2024-06-21'),
(5784622267, 4, 13, '2024-06-21', '2024-06-24', '2024-06-21'),
(6542939600, 2, 12, '2024-06-21', '2024-06-24', '2024-06-21'),
(6655406427, 4, 15, '2024-07-01', '2024-07-06', '2024-06-21'),
(7696021831, 4, 12, '2024-07-08', '2024-07-10', '2024-06-21'),
(8133771816, 3, 15, '2024-06-27', '2024-06-29', '2024-06-21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `id_number` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(10) NOT NULL,
  `credit_card_number` varchar(20) NOT NULL,
  `credit_card_expiry` date NOT NULL,
  `credit_card_holder` varchar(100) NOT NULL,
  `credit_card_bank` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `address`, `dob`, `id_number`, `email`, `telephone`, `username`, `password`, `role`, `credit_card_number`, `credit_card_expiry`, `credit_card_holder`, `credit_card_bank`) VALUES
(1, 'Admin', 'Ramallah', '1986-03-01', '0498374', 'admin@gamil.com', '0599235687', 'admin1', 'admin123', 'manager', '876435554', '2025-01-01', 'Admin', 'palestine bank'),
(2, 'ali ahmad', 'Birzeit', '2000-09-17', '04872932', 'AliAhmad@gmail.com', '0577772220', 'ali_2000', 'ali12345', 'customer', '221122113', '2027-07-17', 'ali ahmad', 'Arab Bank'),
(3, 'samer rami', 'Ramallah', '2003-03-07', '049873', 'samer@gmail.com', '0599926312', 'samer2003', 'samer121', 'customer', '221122333', '2026-02-08', 'samer rami', 'Arab bank'),
(4, 'Mitri khoury', 'Ramallah', '2003-04-17', '647389', 'mitkhoury@gmail.com', '0597516680', 'Mitri_21', 'mitri12345', 'customer', '112233210', '2026-03-12', 'Mitri khoury', 'Arab Bank'),
(3891439878, 'ahmad budz', '876 Main St', '2003-04-04', '77777', 'ahmadd@gmail.com', '0593733271', 'abulbudz', 'ahmad888', 'customer', '112288119', '2027-04-04', 'ahmad budz', 'Arab Bank');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`car_id`),
  ADD KEY `fk_city_location` (`city`),
  ADD KEY `idx_return_location_name` (`return_location_name`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`location_id`),
  ADD KEY `city` (`city`),
  ADD KEY `idx_return_location_name` (`return_location_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `car_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cars`
--
ALTER TABLE `cars`
  ADD CONSTRAINT `fk_city_location` FOREIGN KEY (`city`) REFERENCES `locations` (`city`),
  ADD CONSTRAINT `fk_return_location_name` FOREIGN KEY (`return_location_name`) REFERENCES `locations` (`return_location_name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
