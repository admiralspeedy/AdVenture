-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2017 at 02:22 AM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `adventure`
--

-- --------------------------------------------------------

--
-- Table structure for table `advertisements`
--

CREATE TABLE `advertisements` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `price` int(11) NOT NULL DEFAULT '1',
  `description` varchar(500) NOT NULL,
  `tags` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `postal` varchar(7) NOT NULL,
  `make` varchar(50) NOT NULL,
  `year` int(11) NOT NULL,
  `kilometers` int(11) NOT NULL,
  `transmission` varchar(9) NOT NULL DEFAULT 'Automatic',
  `propertyType` varchar(9) NOT NULL DEFAULT 'For Rent',
  `propertyDetails` varchar(500) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `advertisements`
--

INSERT INTO `advertisements` (`id`, `userid`, `type`, `title`, `price`, `description`, `tags`, `time`, `email`, `postal`, `make`, `year`, `kilometers`, `transmission`, `propertyType`, `propertyDetails`, `deleted`) VALUES
(1, 3, 1, '2017 Ford F150', 20000, 'This is fairly new 2017 Ford F150 with only 5000KM!', 'f150,ford,truck,red', 1510552676, 'ashf@gmail.com', 'S4X1Y1', 'Ford F150', 2017, 5000, 'Automatic', 'For Rent', '', 0),
(2, 3, 2, 'Small House', 200000, 'This is small house for sale. At $200,000 it is a great deal because it doesn\'t need much work and was recently renovated!', 'house,nograss,white,little,sale', 1510552821, 'ashf@gmail.com', 'S0G 3W0', '', 0, 0, 'Automatic', 'For Sale', 'There is barely a yard, because who needs grass?', 0),
(3, 2, 0, 'Concrete Cement Mixer', 150, 'Our brand new and heavy duty cement mixer is perfect for concrete, stucco, and mortar and ideal for inoculating seeds and mixing feeds. Featured a solid steel construction and 2 rubber wheels, this machine is easy to use and easily rolled nearly anywhere. Buy yours today!', 'concrete,mixer,sturdy,cement', 1510552985, 'brickfactory@concrete.uk', 'S4N 7L8', '', 0, 0, 'Automatic', 'For Rent', '', 0),
(4, 2, 0, 'Cement Bricks 8 in. x 4 in. x 2 in.', 2, 'This 8 in. x 4 in. x 2 in. Cement Brick has versatile uses and is ideal for walkways, patios or driveways. It is durable and offers up to 1,900 psi compression strength. Excellent aerodynamic qualities also make it a perfect projectile. Just take a throw and check it out! An affordable repellent against all pesky hostiles in the neighborhood.', 'cement,brick,projectile,durable', 1510553911, 'brickfactory@concrete.uk', 'S4N 7L8', '', 0, 0, 'Automatic', 'For Rent', '', 1),
(5, 2, 1, 'Mack CU713 Mixer Truck', 85000, 'MACK GU713 concrete mixer truck. MP7 engine, manual transmission, MTM paver drum. Two pushers and Bridgemaster.  We have a total of seven (7) of these trucks available.', 'truck,mack,concrete,mixer,big,new', 1510554493, 'brickfactory@concrete.uk', 'S4N 7L8', 'Mack', 2008, 1500, 'Automatic', 'For Rent', '', 0),
(6, 4, 2, 'Basement for rent', 850, 'This single bedroom basement bachelor suite is available for rent starting September 1st or earlier at your request. The whole basement will be shared only with me, the landlord. European/Canadian students are especially, highly welcome!\r\nMonthly rent of just $850 offers you the included benefits of:\r\n- Heat\r\n- Water supply\r\n- Electricity\r\n- Wi-Fi internet connection\r\n- Electrified open parking lot', 'rent,quiet,wifi,female,parking', 1510556021, 'ivbet@rambler.ru', 'S4S 0B5', '', 0, 0, 'Automatic', 'For Rent', 'No kids, No smoking, No drugs, No pets, No vegetarians, females only!', 0),
(7, 3, 2, 'Test Ad 1234', 100, 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', '', 1510556241, '', 'S4X1Y1', '', 0, 0, 'Automatic', 'For Rent', 'TEST', 1),
(8, 6, 0, 'Big Bang Theory T-Shirt', 5, 'This Big Bang Theory T-shirt has only been worn once and is in new condition. Hoping to sell this quick!', 'shirt,clothing,cheap', 1510713312, 'ricksanchez@hotmail.com', 'S4S 0A2', '', 0, 0, 'Automatic', 'For Rent', '', 0),
(9, 6, 2, 'Need Roommate', 600, 'Looking for a new roommate to move-in. Apartment is close to the University of Regina\'s campus and the Super Store on Albert Street. Located in such a prime location, we are hoping to fill the room fast!', 'apartment,rent,rental,property,campus,university', 1510713500, 'ricksanchez@hotmail.com', 'S4S 0A2', '', 0, 0, 'Automatic', 'For Rent', 'Rent is $600/month plus utilities and extra amenities. Pets are allowed. Security deposit of $200 must be paid before moving in.', 0),
(10, 5, 0, 'Nintendo 3DS', 100, 'I am selling my Nintendo 3DS. It is in acceptable condition. There are several games downloaded onto the device. ', 'gaming,nintendo,3DS', 1510714249, 'shelby_p@hotmail.com', 'S4S 0A2', '', 0, 0, 'Automatic', 'For Rent', '', 0),
(11, 7, 0, 'Test Advertisement', 5, 'Test', 'test', 1510881564, 'test1@hotmail.com', 'S4S0A2', '', 0, 0, 'Automatic', 'For Rent', '', 1),
(12, 7, 0, 'Test Advertisement', 5, 'Test', 'test', 1510881731, 'test2@hotmail.com', 'S4S0A2', '', 0, 0, 'Automatic', 'For Rent', '', 1),
(13, 7, 0, 'Test Advertisement', 5, 'test', 'test', 1510881788, 'test2@hotmail.com', 's4s0a2', '', 0, 0, 'Automatic', 'For Rent', '', 1),
(14, 7, 0, 'Test Advertisement', 5, 'test', 'test', 1510881836, 'test2@hotmail.com', 's4s0a2', '', 0, 0, 'Automatic', 'For Rent', '', 1),
(15, 7, 0, 'Test Advertisement', 5, 'test', 'test', 1510881884, 'test2@hotmail.com', 's4s0a2', '', 0, 0, 'Automatic', 'For Rent', '', 1),
(16, 7, 0, 'Test Advertisement', 5, 'test', 'test', 1510881955, 'test2@hotmail.com', 's4s0a2', '', 0, 0, 'Automatic', 'For Rent', '', 1),
(17, 7, 0, 'Test Advertisement', 5, 'test', 'test', 1510882008, 'test2@hotmail.com', 's4s0a2', '', 0, 0, 'Automatic', 'For Rent', '', 1),
(18, 7, 0, 'Test Advertisement', 5, 'test', 'test', 1510882057, 'test2@hotmail.com', 's4s0a2', '', 0, 0, 'Automatic', 'For Rent', '', 1),
(19, 7, 0, 'Test Advertisement 2', 10, 'Test2', 'test2', 1510882330, 'test2@hotmail.com', 's4s0a2', '', 0, 0, 'Automatic', 'For Rent', '', 1),
(20, 7, 0, 'Test Advertisement', 5, 'test', 'test', 1510882465, 'test2@hotmail.com', 's4s0a2', '', 0, 0, 'Automatic', 'For Rent', '', 1),
(21, 7, 0, 'Test Advertisement', 6, 'test', 'test', 1510882517, 'test2@hotmail.com', 's4s0a2', '', 0, 0, 'Automatic', 'For Rent', '', 1),
(22, 7, 0, 'Test Advertisement', 5, 'test', 'test', 1510882596, 'test2@hotmail.com', 's4s0a2', '', 0, 0, 'Automatic', 'For Rent', '', 1),
(23, 7, 0, 'Test Advertisement', 5, 'test', 'test', 1510882639, 'test2@hotmail.com', 's4s0a2', '', 0, 0, 'Automatic', 'For Rent', '', 1),
(24, 7, 0, 'Test Advertisement', 5, 'test', 'test', 1510882688, 'test2@hotmail.com', 's4s0a2', '', 0, 0, 'Automatic', 'For Rent', '', 1),
(25, 7, 0, 'Test Advertisement', 5, 'test', 'test', 1510882774, 'test2@hotmail.com', 's4s0a2', '', 0, 0, 'Automatic', 'For Rent', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `user1` int(11) NOT NULL,
  `user2` int(11) NOT NULL,
  `user1Name` varchar(20) NOT NULL,
  `user2Name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `user1`, `user2`, `user1Name`, `user2Name`) VALUES
(1, 5, 6, 'Shelby_Piechotta', 'Rick_Sanchez'),
(2, 5, 4, 'Shelby_Piechotta', 'Ivan Betonov'),
(3, 7, 5, 'TestCase1', 'Shelby_Piechotta');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `adid` int(11) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `adid`, `image`) VALUES
(1, 1, '1510552676.jpg'),
(2, 1, '1510552677.jpg'),
(3, 2, '1510552822.jpg'),
(4, 3, '1510552984.jpg'),
(5, 3, '1510552985.jpg'),
(6, 3, '1510552986.jpg'),
(7, 4, '1510553911.jpeg'),
(8, 4, '1510553911.jpg'),
(9, 5, '1510554494.jpg'),
(10, 6, '1510556020.jpg'),
(11, 6, '1510556021.jpg'),
(12, 6, '1510556022.jpg'),
(13, 7, '1510556242.jpg'),
(14, 8, '1510713312.jpg'),
(15, 9, '1510713500.jpg'),
(16, 9, '1510713501.jpg'),
(17, 10, '1510714250.jpg'),
(18, 11, '1510881564.jpg'),
(19, 12, '1510881732.jpg'),
(20, 13, '1510881788.jpg'),
(21, 14, '1510881836.jpg'),
(22, 15, '1510881885.jpg'),
(23, 16, '1510881955.jpg'),
(24, 17, '1510882008.jpg'),
(25, 18, '1510882058.jpg'),
(26, 19, '1510882331.jpg'),
(27, 20, '1510882465.jpg'),
(28, 21, '1510882517.jpg'),
(29, 22, '1510882596.jpg'),
(30, 23, '1510882640.jpg'),
(31, 24, '1510882688.jpg'),
(32, 25, '1510882774.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `convo` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `in1ConvoView` int(1) NOT NULL DEFAULT '0',
  `in1MessageView` int(1) NOT NULL DEFAULT '0',
  `in2ConvoView` int(1) NOT NULL DEFAULT '0',
  `in2MessageView` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `convo`, `sender`, `message`, `time`, `in1ConvoView`, `in1MessageView`, `in2ConvoView`, `in2MessageView`) VALUES
(1, 1, 5, 'Hello,', 1510714422, 1, 1, 1, 1),
(2, 1, 5, 'I would like to buy your Big Bang Theory T-Shirt please.', 1510714442, 1, 1, 1, 1),
(3, 1, 6, 'Sure, I\'ll sell you the Big Bang Theory T-Shirt for $5! I can meet you in the Riddell Center at the U of R around 9am tomorrow. Does that work for you?', 1510714529, 1, 1, 0, 1),
(6, 3, 7, 'Test #1', 1510884685, 0, 1, 1, 0),
(7, 3, 7, 'Test #2', 1510884764, 0, 1, 1, 0),
(8, 3, 7, 'Test #3', 1510884789, 0, 1, 1, 0),
(9, 3, 7, 'Test #4', 1510884838, 0, 1, 1, 0),
(10, 3, 7, 'Test #5', 1510884871, 0, 1, 1, 0),
(11, 3, 7, 'Test #6', 1510884898, 0, 1, 1, 0),
(12, 3, 7, 'Test #7', 1510885003, 0, 1, 1, 0),
(13, 3, 7, 'Test #8', 1510885046, 0, 1, 1, 0),
(14, 3, 7, 'Test #9', 1510885171, 0, 1, 1, 0),
(15, 3, 7, 'Test #10', 1510885213, 0, 1, 1, 0),
(17, 2, 5, 'Hello, is your basement still available for rent?', 1510895847, 1, 1, 1, 1),
(18, 2, 4, 'Correct, the basement suite is still available, would you like to know more about it?', 1510895902, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `raterid` int(11) NOT NULL,
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `userid`, `raterid`, `rating`) VALUES
(1, 2, 4, 5),
(2, 3, 4, 1),
(5, 6, 5, 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(20) NOT NULL,
  `rank` int(11) NOT NULL DEFAULT '0',
  `premium` int(11) NOT NULL DEFAULT '0',
  `banned` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `username`, `rank`, `premium`, `banned`) VALUES
(1, 'steffemi@uregina.ca', '$2y$10$2CxXRBwkdHYCxKPr524B.OyWzUm0zUTg.AX.y3SQmSIn8rBKOlW5u', 'Mitchell S', 1, 0, 0),
(2, 'brickfactory@concrete.uk', '$2y$10$8sH5JLH73tTGnYyUakXXOuB0yUPiu165l1t/9ZeFr8BEW1PtjQN8y', 'Connor Crete', 0, 0, 0),
(3, 'ashf@gmail.com', '$2y$10$dwayBfMInUvn8uOjnb.oNOUoHOZv68By4U7lhLwfOTXwf53xH0vuC', 'Ash Fault', 0, 0, 0),
(4, 'ivbet@rambler.ru', '$2y$10$FY0h2nPKTHAT6u23BzW97uyHd/RqKtyy3j6rskZ0oNr0tZGC0HKnW', 'Ivan Betonov', 0, 0, 0),
(5, 'shelbypiechotta@gmail.com', '$2y$10$athj30EynwTxxBSeky0SyeQycVaJIu3sqKQj..576JnFgQMx.Kiw6', 'Shelby_Piechotta', 0, 0, 0),
(6, 'ricksanchez@hotmail.com', '$2y$10$3zgK.jlnJMgQWfoQS0CIfu1cder3OiVSMxBOG9UJQdS4Auoq2Nk2m', 'Rick_Sanchez', 0, 0, 0),
(7, 'test1@hotmail.com', '$2y$10$zIdh6V1PsqQi79BpzZ0KxOmQbqh8LeFG4aP7slF1BUkzkiK6JFD.m', 'TestCase1', 0, 0, 0),
(8, 'test2@hotmail.com', '$2y$10$fOqGxahjT8T0RiVmG.UE4eaELcCBSzEeKcEYROGnzWwWEbWM/nCFC', 'TestCase2', 0, 0, 0),
(9, 'test3@hotmail.com', '$2y$10$TRaUo2AW6QmOWEmSEjsxieacYP/a8SypVMBYwylCqYvLVBRkpAVAe', 'TestCase3', 0, 0, 0),
(10, 'test4@hotmail.com', '$2y$10$PPvWlowdEVUodB4DVMW3deAb6spH9rHYMtBrmN3lEI6XDKUzPxPom', 'TestCase4', 0, 0, 0),
(11, 'test5@hotmail.com', '$2y$10$p/soi8UAzDL9DoARZn41FOIsigO55JFl9iq7QCq1xww7B4kUyrNj.', 'TestCase5', 0, 0, 0),
(12, 'test6@hotmail.com', '$2y$10$1xHDsfS0lfM1iZhP6YO4I.168FYTR9SwFfIRZmwbPIt.W0HeebNPm', 'TestCase6', 0, 0, 0),
(13, 'test7@hotmail.com', '$2y$10$CohsaAMpzHAFNq5E8kOOr.pndDAkh1yxj/.2Oc3WG8Wxka704hPaG', 'TestCase7', 0, 0, 0),
(16, 'test8@hotmail.com', '$2y$10$N2jXmnfiek1bSIJRrUPGF.serOKCYM/hLBmB9cKtN9rbTVAjVxOBO', 'TestCase8', 0, 0, 0),
(17, 'test9@hotmail.com', '$2y$10$yK1CwHSISJDZj.bzNnOtUuf3Gzxk.nywlU5FxNFupwiTSbuWZCsKe', 'TestCase9', 0, 0, 0),
(18, 'test10@hotmail.com', '$2y$10$68BIDuxsxPQ7U/HW/VgCYeVnSAz.vg1IwAJPOpxLxU3d.H0LB.dfy', 'TestCase10', 0, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advertisements`
--
ALTER TABLE `advertisements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advertisements`
--
ALTER TABLE `advertisements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
