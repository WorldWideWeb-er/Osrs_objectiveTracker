-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2021 at 02:14 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `osrsgimdatabase`
--

-- --------------------------------------------------------

--
-- Table structure for table `gim_user`
--

CREATE TABLE `gim_user` (
  `gim_user_id` int(11) NOT NULL,
  `gim_username` text NOT NULL,
  `gim_password` text NOT NULL,
  `gim_permissions` text NOT NULL,
  `gim_email` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gim_user`
--

INSERT INTO `gim_user` (`gim_user_id`, `gim_username`, `gim_password`, `gim_permissions`, `gim_email`) VALUES
(1, 'test', '1234', 'admin', 'admin'),
(3, 'admin', '1234', 'default', 'none@gmail.com'),
(6, 'trb', '1234', 'default', 'nate.weber.work@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `gim_user_objectives`
--

CREATE TABLE `gim_user_objectives` (
  `gim_obj_id` int(11) NOT NULL,
  `gim_username` text NOT NULL,
  `gim_obj_name` text NOT NULL,
  `gim_obj_type` text NOT NULL,
  `gim_obj_date` text NOT NULL,
  `gim_obj_complete` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gim_user_objectives`
--

INSERT INTO `gim_user_objectives` (`gim_obj_id`, `gim_username`, `gim_obj_name`, `gim_obj_type`, `gim_obj_date`, `gim_obj_complete`) VALUES
(9, 'test', 'Complete Desert Treasure', 'Quest Completion', 'Date', '30%'),
(17, 'test', 'Obj Name', 'Increase Level', 'December,10, 2021', '10%'),
(18, 'test', 'Dragon Slayer 2', 'Quest Completion', 'December,10, 2021', '80%'),
(19, 'test', 'getting boss pet?', 'Collect Item', 'December,10, 2021', '0%'),
(20, 'admin', 'Complete Desert Treasure', 'Quest Completion', 'December,10, 2021', '100%'),
(22, 'trb', 'Complete One Small Favor', 'Quest Completion', 'December,10, 2021', '20%'),
(23, 'admin', 'Obj Name', 'Quest Completion', 'December,11, 2021', '80%');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gim_user`
--
ALTER TABLE `gim_user`
  ADD PRIMARY KEY (`gim_user_id`);

--
-- Indexes for table `gim_user_objectives`
--
ALTER TABLE `gim_user_objectives`
  ADD PRIMARY KEY (`gim_obj_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gim_user`
--
ALTER TABLE `gim_user`
  MODIFY `gim_user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `gim_user_objectives`
--
ALTER TABLE `gim_user_objectives`
  MODIFY `gim_obj_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
