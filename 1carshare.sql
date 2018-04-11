-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2018 at 09:38 AM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `1carshare`
--

-- --------------------------------------------------------

--
-- Table structure for table `carsharetrips`
--

CREATE TABLE `carsharetrips` (
  `trip_id` int(4) NOT NULL,
  `user_id` int(4) DEFAULT NULL,
  `departure` char(30) DEFAULT NULL,
  `departureLongitude` float NOT NULL,
  `departureLatitude` float NOT NULL,
  `destination` char(255) DEFAULT NULL,
  `destinationLongitude` float NOT NULL,
  `destinationLatitude` float NOT NULL,
  `price` char(10) DEFAULT NULL,
  `seatsavailable` char(2) DEFAULT NULL,
  `regular` char(1) DEFAULT NULL,
  `date` char(20) DEFAULT NULL,
  `time` char(10) DEFAULT NULL,
  `monday` char(1) DEFAULT NULL,
  `tuesday` char(1) DEFAULT NULL,
  `wednesday` char(1) DEFAULT NULL,
  `thursday` char(1) DEFAULT NULL,
  `friday` char(1) DEFAULT NULL,
  `saturday` char(1) DEFAULT NULL,
  `sunday` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `carsharetrips`
--

INSERT INTO `carsharetrips` (`trip_id`, `user_id`, `departure`, `departureLongitude`, `departureLatitude`, `destination`, `destinationLongitude`, `destinationLatitude`, `price`, `seatsavailable`, `regular`, `date`, `time`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `sunday`) VALUES
(1, 6, 'BrÄko, Bosnia and Herzegovina', 18.8106, 44.8727, 'Bijeljina, Bosnia and Herzegovina', 19.215, 44.757, '45', '3', 'Y', '', '09:09', '1', '', '1', '', '', '1', ''),
(3, 6, 'BrÄko, Bosnia and Herzegovina', 18.8106, 44.8727, 'Bijeljina, Bosnia and Herzegov', 19.215, 44.757, '50', '4', 'N', 'Sat 31 Mar, 2018', '09:20', '1', '', '1', '', '', '', ''),
(6, 6, 'Bijela, Montenegro', 18.6443, 42.4573, 'Bijeljina, Bosnia and Herzegovina', 19.215, 44.757, '300', '1', 'Y', '', '09:20', '1', '', '1', '', '', '', ''),
(7, 6, 'Novi Sad, Serbia', 19.8335, 45.2671, 'BrÄko, Bosnia and Herzegovina', 18.8106, 44.8727, '3333', '3', 'N', 'Fri 30 Mar, 2018', '08:20', '', '', '', '', '', '', ''),
(8, 6, 'Janja, Bosnia and Herzegovina', 19.2469, 44.6669, 'Bijeljina, Bosnia and Herzegovina', 19.215, 44.757, '3', '3', 'N', 'Sat 31 Mar, 2018', '07:00', '', '', '', '', '', '', ''),
(9, 6, 'Bedford, UK', -0.466655, 52.136, 'London, UK', -0.127758, 51.5074, '10', '1', 'N', 'Fri 30 Mar, 2018', '09:00', '', '', '', '', '', '', ''),
(10, 6, 'Bijeljina, Bosnia and Herzegov', 19.215, 44.757, 'Novi Sad, Serbia', 19.8335, 45.2671, '30', '2', 'Y', '', '09:20', '1', '1', '1', '', '', '', ''),
(11, 6, 'KojÄinovac, Bosnia and Herzeg', 19.2289, 44.692, 'Golo Brdo, Bosnia and Herzegovina', 17.4905, 44.0247, '2', '3', 'Y', '', '09:02', '1', '1', '1', '1', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `forgotpassword`
--

CREATE TABLE `forgotpassword` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rkey` char(32) NOT NULL,
  `time` int(11) NOT NULL,
  `status` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `forgotpassword`
--

INSERT INTO `forgotpassword` (`id`, `user_id`, `rkey`, `time`, `status`) VALUES
(1, 4, '9b484f26d5ad57ea7b22dff2e1bcbde1', 1521189011, 'pending'),
(2, 4, '9ec6ac884f7708b44b1e000957caa8b2', 1521189016, 'used');

-- --------------------------------------------------------

--
-- Table structure for table `rememberme`
--

CREATE TABLE `rememberme` (
  `id` int(11) NOT NULL,
  `authentificator1` char(20) DEFAULT NULL,
  `f2authentificator2` char(64) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rememberme`
--

INSERT INTO `rememberme` (`id`, `authentificator1`, `f2authentificator2`, `user_id`, `expires`) VALUES
(1, '3629b105007fe726c434', '03cf67f9f311d9cd492bdb0296c88233ef0e0b1ce2086cedeec4441d708769d4', 6, '2018-04-04 14:44:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` char(30) NOT NULL,
  `last_name` char(30) NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `activation` char(32) DEFAULT NULL,
  `activation2` char(32) DEFAULT NULL,
  `gender` char(6) DEFAULT NULL,
  `phonenumber` char(15) DEFAULT NULL,
  `moreinformation` varchar(300) DEFAULT NULL,
  `profilepicture` varchar(55) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `username`, `email`, `password`, `activation`, `activation2`, `gender`, `phonenumber`, `moreinformation`, `profilepicture`) VALUES
(6, 'Mile', 'Cacanovic', 'Lemi', 'mile_cacanovic@hotmail.com', '07480fb9e85b9396af06f006cf1c95024af2531c65fb505cfbd0add1e2f31573', 'activated', NULL, 'male', '0661111111', 'ttt', 'profilepicture/c9568e691125762694d996ccc660b1ad.jpg'),
(7, 'Nikola', 'Savic', 'Webcleric', 'nikolasavic@outlook.com', 'ea687a49e09dc0b45ff3bbcdb82d237d371dc55d3a3a83c9979b748c62664251', '4f3dfdeb415d3952ad85328dd3388925', NULL, 'male', '0038766268639', 'Ocel radit', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carsharetrips`
--
ALTER TABLE `carsharetrips`
  ADD PRIMARY KEY (`trip_id`);

--
-- Indexes for table `forgotpassword`
--
ALTER TABLE `forgotpassword`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rememberme`
--
ALTER TABLE `rememberme`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carsharetrips`
--
ALTER TABLE `carsharetrips`
  MODIFY `trip_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `forgotpassword`
--
ALTER TABLE `forgotpassword`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `rememberme`
--
ALTER TABLE `rememberme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
