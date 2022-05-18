-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2022 at 09:04 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `analyser`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `firstname` varchar(80) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `surname` varchar(80) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `company` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `address` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `address2` varchar(200) DEFAULT NULL,
  `town` varchar(80) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `country` varchar(80) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `postcode` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `phone_number` varchar(60) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `email` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `login` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `role` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `newsletter` int(11) DEFAULT 1,
  `blocked` int(11) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `firstname`, `surname`, `company`, `address`, `address2`, `town`, `country`, `postcode`, `phone_number`, `email`, `login`, `password`, `status`, `role`, `create_date`, `newsletter`, `blocked`) VALUES
(3527, 1, '', '', '', '', NULL, '', '', '', '', '', '', '', '', '', '2022-04-26 09:00:02', 1, 0),
(3528, 12, '', '', '', '', NULL, '', '', '', '', '', '', '', '', '', '2022-04-26 09:01:58', 1, 0),
(3529, 121, '', '', '', '', NULL, '', '', '', '', '', '', '', '', '', '2022-04-26 09:02:04', 1, 0),
(3530, 1211, '', '', '', '', NULL, '', '', '', '', '', '', '', '', '', '2022-04-26 09:02:07', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `extra` varchar(255) NOT NULL,
  `assetno` varchar(255) NOT NULL,
  `del_FAT` int(11) NOT NULL DEFAULT 0,
  `order_fcom` varchar(255) NOT NULL DEFAULT 'as_ofcom_itc',
  `json` text NOT NULL,
  `completejson` longtext NOT NULL,
  `status` varchar(255) NOT NULL,
  `jobid` int(11) NOT NULL DEFAULT 0,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3531;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
