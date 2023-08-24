-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2023 at 07:00 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ride_a_long`
--

-- --------------------------------------------------------

--
-- Table structure for table `coordinates`
--

CREATE TABLE `coordinates` (
  `address` varchar(255) NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coordinates`
--

INSERT INTO `coordinates` (`address`, `latitude`, `longitude`) VALUES
('1204 Wertland St, Charlottesville, VA 22903, USA', 38.0339064, -78.4966265),
('1305 Wertland St, Charlottesville, VA 22903, USA', 38.0352141, -78.4975918),
('1826 University Ave, Charlottesville, VA 22904, USA', 38.0355514, -78.5034260),
('701 Club Dr, Keswick, VA 22947, USA', 38.0167803, -78.3666045),
('85 Engineer\'s Way, Charlottesville, VA 22903, USA', 38.0316188, -78.5108459),
('927 Bing Ln, Charlottesville, VA 22903, USA', 38.0205486, -78.5074067),
('Blacksburg, VA 24061, USA', 37.2283843, -80.4234167),
('Monroe Hall, Charlottesville, VA 22903, USA', 38.0348370, -78.5064309),
('Richmond, VA, USA', 37.5407246, -77.4360481);

-- --------------------------------------------------------

--
-- Table structure for table `driver`
--

CREATE TABLE `driver` (
  `email` varchar(255) NOT NULL,
  `car_license_plate` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driver`
--

INSERT INTO `driver` (`email`, `car_license_plate`) VALUES
('nid3dhu@virginia.edu', 'BTS576');

-- --------------------------------------------------------

--
-- Table structure for table `driver_car`
--

CREATE TABLE `driver_car` (
  `license_plate` varchar(7) NOT NULL,
  `make` varchar(63) NOT NULL,
  `color` varchar(63) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driver_car`
--

INSERT INTO `driver_car` (`license_plate`, `make`, `color`) VALUES
('BTS576', 'MINI', 'Silver');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `sender_email` varchar(255) NOT NULL,
  `recipient_email` varchar(255) NOT NULL,
  `time_sent` datetime NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `reporter_email` varchar(255) NOT NULL,
  `reportee_email` varchar(255) NOT NULL,
  `reason` varchar(63) NOT NULL,
  `info` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`reporter_email`, `reportee_email`, `reason`, `info`) VALUES
('nid3dhu@virginia.edu', 't1r@virginia.edu', 'Inappropriate', ''),
('s1a@virginia.edu', 'nid3dhu@virginia.edu', 'too good', '');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `id` int(10) UNSIGNED NOT NULL,
  `rider_email` varchar(255) NOT NULL,
  `pickup_addr` varchar(255) DEFAULT NULL,
  `dropoff_addr` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `response`
--

CREATE TABLE `response` (
  `id` int(10) UNSIGNED NOT NULL,
  `rider_email` varchar(255) NOT NULL,
  `response` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `response`
--
DELIMITER $$
CREATE TRIGGER `after_response_insert` AFTER INSERT ON `response` FOR EACH ROW BEGIN
	IF NEW.response = 1 THEN
		INSERT INTO ride_riders (id, rider_email, pickup_addr, dropoff_addr)
        SELECT NEW.id, NEW.rider_email, pickup_addr, dropoff_addr
        FROM request WHERE id = NEW.id AND rider_email = NEW.rider_email;
    END IF;
	DELETE FROM request WHERE id = NEW.id AND rider_email = NEW.rider_email;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ride`
--

CREATE TABLE `ride` (
  `id` int(10) UNSIGNED NOT NULL,
  `driver_email` varchar(255) NOT NULL,
  `start_time` datetime NOT NULL,
  `orig_addr` varchar(255) NOT NULL,
  `dest_addr` varchar(255) NOT NULL,
  `seats_total` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ride`
--

INSERT INTO `ride` (`id`, `driver_email`, `start_time`, `orig_addr`, `dest_addr`, `seats_total`, `description`) VALUES
(10, 'nid3dhu@virginia.edu', '2023-08-26 13:21:00', '1305 Wertland St, Charlottesville, VA 22903, USA', 'Richmond, VA, USA', 4, 'ghost ride da whip');

-- --------------------------------------------------------

--
-- Table structure for table `rider`
--

CREATE TABLE `rider` (
  `email` varchar(255) NOT NULL,
  `contributions` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rider`
--

INSERT INTO `rider` (`email`, `contributions`) VALUES
('nid3dhu@virginia.edu', 'lovely'),
('s1a@virginia.edu', 'very fun guy'),
('t1r@virginia.edu', 'will pay for gas'),
('t2u@virginia.edu', 'Will pay for gas'),
('t9u@virginia.edu', 'serenade');

-- --------------------------------------------------------

--
-- Table structure for table `ride_riders`
--

CREATE TABLE `ride_riders` (
  `id` int(10) UNSIGNED NOT NULL,
  `rider_email` varchar(255) NOT NULL,
  `pickup_addr` varchar(255) DEFAULT NULL,
  `dropoff_addr` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `year` int(10) UNSIGNED DEFAULT NULL,
  `major` varchar(63) DEFAULT NULL,
  `bio` varchar(255) DEFAULT NULL,
  `picture` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`email`, `password`, `first_name`, `last_name`, `phone`, `year`, `major`, `bio`, `picture`) VALUES
('nid3dhu@virginia.edu', '$2y$10$jSNTrt9L0JGftb/eCfGhp.1sbPOUp2p.9GQfs0YQBIvh2DDgghBMS', 'Nicholas', 'Davidson', '9312005811', 4, 'Computer Science', 'I designed this website :)', NULL),
('s1a@virginia.edu', '$2y$10$GYJ8YjYrama8F1t7V93oCOtd7TaTNLHwSab4FuwcVmjH1A0.PzLgq', 'Somebody', 'Anonymous', '1234567890', 2, 'Philosophy', 'I\'m just a silly goofy dude', NULL),
('t1r@virginia.edu', '$2y$10$a7SiI1lG6jLFRefziJmUGuL3iEt39pAj1Avn9Kx/9KTeHdVbVGA1S', 'Test', 'User', '1234567891', 3, 'cs', 'this is a bio', NULL),
('t2u@virginia.edu', '$2y$10$yEF9V/QuCo2RG5xvkfdxR.KljYCcVW4Ru5R6iAE3d.gLslBtXon3C', 'Test', 'User', '1012023003', NULL, NULL, NULL, NULL),
('t9u@virginia.edu', '$2y$10$H5ML3thu49G1o8EHSe3jsOdERza2lItj2heGRa4BCkFHRcIRdeAqC', 'test', 'user', '1112223333', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_emergency_contact`
--

CREATE TABLE `user_emergency_contact` (
  `user_email` varchar(255) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `relationship` varchar(63) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_emergency_contact`
--

INSERT INTO `user_emergency_contact` (`user_email`, `phone`, `first_name`, `last_name`, `relationship`) VALUES
('nid3dhu@virginia.edu', '9312658853', 'Amanda', 'Davidson', 'Mother'),
('s1a@virginia.edu', '1234567890', 'Bob', 'Anonymous', 'Father');

-- --------------------------------------------------------

--
-- Table structure for table `waypoints`
--

CREATE TABLE `waypoints` (
  `ride` int(10) UNSIGNED NOT NULL,
  `address` varchar(255) NOT NULL,
  `order` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `coordinates`
--
ALTER TABLE `coordinates`
  ADD PRIMARY KEY (`address`);

--
-- Indexes for table `driver`
--
ALTER TABLE `driver`
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `car_license_plate` (`car_license_plate`);

--
-- Indexes for table `driver_car`
--
ALTER TABLE `driver_car`
  ADD PRIMARY KEY (`license_plate`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`sender_email`,`recipient_email`,`time_sent`),
  ADD KEY `recipient_email` (`recipient_email`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`reporter_email`,`reportee_email`),
  ADD KEY `reportee_email` (`reportee_email`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`,`rider_email`),
  ADD KEY `rider_email` (`rider_email`),
  ADD KEY `pickup_addr` (`pickup_addr`),
  ADD KEY `dropoff_addr` (`dropoff_addr`);

--
-- Indexes for table `response`
--
ALTER TABLE `response`
  ADD PRIMARY KEY (`id`,`rider_email`),
  ADD KEY `rider_email` (`rider_email`);

--
-- Indexes for table `ride`
--
ALTER TABLE `ride`
  ADD PRIMARY KEY (`id`),
  ADD KEY `driver_email` (`driver_email`),
  ADD KEY `orig_addr` (`orig_addr`),
  ADD KEY `dest_addr` (`dest_addr`);

--
-- Indexes for table `rider`
--
ALTER TABLE `rider`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `ride_riders`
--
ALTER TABLE `ride_riders`
  ADD PRIMARY KEY (`id`,`rider_email`),
  ADD KEY `rider_email` (`rider_email`),
  ADD KEY `pickup_waypoint` (`pickup_addr`),
  ADD KEY `dropoff_waypoint` (`dropoff_addr`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `user_emergency_contact`
--
ALTER TABLE `user_emergency_contact`
  ADD PRIMARY KEY (`user_email`,`phone`);

--
-- Indexes for table `waypoints`
--
ALTER TABLE `waypoints`
  ADD PRIMARY KEY (`ride`,`address`),
  ADD KEY `ride` (`ride`,`order`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ride`
--
ALTER TABLE `ride`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `driver`
--
ALTER TABLE `driver`
  ADD CONSTRAINT `driver_ibfk_1` FOREIGN KEY (`email`) REFERENCES `user` (`email`) ON DELETE CASCADE,
  ADD CONSTRAINT `driver_ibfk_2` FOREIGN KEY (`car_license_plate`) REFERENCES `driver_car` (`license_plate`) ON DELETE CASCADE;

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`sender_email`) REFERENCES `user` (`email`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`recipient_email`) REFERENCES `user` (`email`) ON DELETE CASCADE;

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`reporter_email`) REFERENCES `user` (`email`) ON DELETE CASCADE,
  ADD CONSTRAINT `report_ibfk_2` FOREIGN KEY (`reportee_email`) REFERENCES `user` (`email`) ON DELETE CASCADE;

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`id`) REFERENCES `ride` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `request_ibfk_2` FOREIGN KEY (`rider_email`) REFERENCES `rider` (`email`) ON DELETE CASCADE,
  ADD CONSTRAINT `request_ibfk_3` FOREIGN KEY (`pickup_addr`) REFERENCES `coordinates` (`address`),
  ADD CONSTRAINT `request_ibfk_4` FOREIGN KEY (`dropoff_addr`) REFERENCES `coordinates` (`address`);

--
-- Constraints for table `response`
--
ALTER TABLE `response`
  ADD CONSTRAINT `response_ibfk_1` FOREIGN KEY (`id`) REFERENCES `ride` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `response_ibfk_2` FOREIGN KEY (`rider_email`) REFERENCES `rider` (`email`) ON DELETE CASCADE;

--
-- Constraints for table `ride`
--
ALTER TABLE `ride`
  ADD CONSTRAINT `ride_ibfk_1` FOREIGN KEY (`driver_email`) REFERENCES `driver` (`email`) ON DELETE CASCADE,
  ADD CONSTRAINT `ride_ibfk_2` FOREIGN KEY (`orig_addr`) REFERENCES `coordinates` (`address`),
  ADD CONSTRAINT `ride_ibfk_3` FOREIGN KEY (`dest_addr`) REFERENCES `coordinates` (`address`);

--
-- Constraints for table `rider`
--
ALTER TABLE `rider`
  ADD CONSTRAINT `rider_ibfk_1` FOREIGN KEY (`email`) REFERENCES `user` (`email`) ON DELETE CASCADE;

--
-- Constraints for table `ride_riders`
--
ALTER TABLE `ride_riders`
  ADD CONSTRAINT `ride_riders_ibfk_1` FOREIGN KEY (`id`) REFERENCES `ride` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ride_riders_ibfk_2` FOREIGN KEY (`rider_email`) REFERENCES `rider` (`email`) ON DELETE CASCADE;

--
-- Constraints for table `user_emergency_contact`
--
ALTER TABLE `user_emergency_contact`
  ADD CONSTRAINT `user_emergency_contact_ibfk_1` FOREIGN KEY (`user_email`) REFERENCES `user` (`email`) ON DELETE CASCADE;

--
-- Constraints for table `waypoints`
--
ALTER TABLE `waypoints`
  ADD CONSTRAINT `waypoints_ibfk_1` FOREIGN KEY (`ride`) REFERENCES `ride` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
