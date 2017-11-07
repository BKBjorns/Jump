-- phpMyAdmin SQL Dump
-- version 4.7.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 07, 2017 at 07:03 PM
-- Server version: 5.6.35
-- PHP Version: 7.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `Jump2`
--

-- --------------------------------------------------------

--
-- Table structure for table `Attend`
--

CREATE TABLE `Attend` (
  `eventID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `numberofattendens` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Attend`
--

INSERT INTO `Attend` (`eventID`, `userID`, `numberofattendens`) VALUES
(1, 2, 0),
(2, 2, 0),
(3, 2, 0),
(1, 6, 0),
(26, 6, 0),
(13, 7, 0),
(26, 7, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Events`
--

CREATE TABLE `Events` (
  `eventID` int(11) NOT NULL,
  `title` char(255) NOT NULL,
  `description` char(255) NOT NULL,
  `startdate` date NOT NULL,
  `enddate` int(11) NOT NULL,
  `time` time NOT NULL,
  `price` int(11) NOT NULL,
  `location` char(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `link` char(255) NOT NULL,
  `host` char(255) NOT NULL,
  `school` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Events`
--

INSERT INTO `Events` (`eventID`, `title`, `description`, `startdate`, `enddate`, `time`, `price`, `location`, `image`, `link`, `host`, `school`) VALUES
(12, 'Pizza Night', 'guilgfycuelcvhjdvchj dhiowöf hiuoweöf', '2017-11-16', 0, '11:02:00', 0, '', 'b5aa52c3a575992f1c5afb55438af2e6.jpg', '', '', 0),
(13, 'Hola', 'dbjelw cuiö', '2017-11-10', 0, '07:59:00', 0, 'JTH entrance', '70d9212e8b1103859f476c7c8c08319a.jpg', '', 'HINT', 0),
(22, 'chjdks', 'njkcdlsvbji', '2017-12-05', 0, '08:09:00', 0, 'ebwnjkf', 'cba1ccbdd44adb9a167a6dc1d9156ae5.jpg', '', '', 0),
(23, 'nvjdfkl', 'ffkkfp', '2017-12-06', 0, '08:59:00', 0, 'cnksdl', 'Franken.jpg', '', '', 0),
(26, 'please work', 'gui', '2017-11-18', 0, '08:59:00', 0, 'fui', 'b1c500db12ce67dcba054c1635f2c84a.jpg', '', 'HINT', 0),
(27, 'New event', '1njo jeofbjö', '2017-11-16', 0, '09:09:00', 0, 'School', '70d9212e8b1103859f476c7c8c08319a.jpg', '', 'HINT', 0),
(28, 'Try new event', 'hiwuo iocbewj ch', '2017-11-23', 0, '08:09:00', 0, 'At school', '14224710_1136082926471900_551082390034042004_n.jpg', '', 'HINT', 0),
(29, 'Event for HINT', 'fhuiewo bepu9gfgew nuuifowhnjig opres hufp', '2017-11-10', 0, '08:59:00', 0, 'School!', '51f17b7e9ee538fc58e68b4074a60d35_large.jpeg', '', 'HINT', 0),
(32, 'hej', 'hu  u', '2017-11-16', 0, '07:09:00', 0, 'hh', 'b5aa52c3a575992f1c5afb55438af2e6.jpg', '', '', 0),
(35, 'evnet', 'jdiosp', '2017-11-16', 0, '08:59:00', 0, 'hopdbi', 'EMO.jpg', '', '$array[0]', 0),
(36, 'opdqwå', 'ejfopå', '2017-11-10', 0, '08:59:00', 0, 'rdjfio', 'EMO.jpg', '', 'HINT', 0),
(40, 'jp', 'feop', '2017-11-16', 0, '08:09:00', 0, 'fep', 'EMO.jpg', '', 'HINT', 0),
(43, 'hiulg', 'yui', '2017-11-24', 0, '08:08:00', 0, '', 'EMO.jpg', '', 'HINT', 0),
(47, 'hoeq', 'e3oå', '2017-11-16', 0, '09:00:00', 0, 'djope', 'Ska?rmavbild 2017-11-07 kl. 10.01.38.png', '', 'HINT', 0),
(51, 'gudiw', 'e3o', '2017-11-16', 0, '07:59:00', 0, 'jfiheowö', 'EMO.jpg', '', 'JSA', 3);

-- --------------------------------------------------------

--
-- Table structure for table `Location`
--

CREATE TABLE `Location` (
  `locationID` int(11) NOT NULL,
  `country` int(11) NOT NULL,
  `city` int(11) NOT NULL,
  `adress` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Schools`
--

CREATE TABLE `Schools` (
  `schoolID` int(11) NOT NULL,
  `schoolname` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Schools`
--

INSERT INTO `Schools` (`schoolID`, `schoolname`) VALUES
(1, 'JTH'),
(2, 'HLK'),
(3, 'JIBS'),
(4, 'HÄLSO'),
(5, 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `userID` int(11) NOT NULL,
  `type` varchar(13) NOT NULL,
  `userpass` char(255) NOT NULL,
  `email` char(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `school` varchar(50) NOT NULL,
  `firstname` char(255) NOT NULL,
  `lastname` char(255) NOT NULL,
  `organisation` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`userID`, `type`, `userpass`, `email`, `image`, `school`, `firstname`, `lastname`, `organisation`) VALUES
(5, 'admin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'admin@mail.com', 'IMG_0069.jpg', '', 'Admin', 'Admin', ''),
(6, 'student', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'student@mail.com', 'Franken.jpg', '', 'One', 'Student', ''),
(7, 'organisation', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'organisation@mail.com', '7e8d2866fcb13b1ac425b6b8029dc9bd.jpg', '1', '', '', 'HINT'),
(8, 'organisation', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'jj@mail.com', '', 'JTH', '', '', 'jj'),
(9, 'organisation', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'hi@mail.com', '', 'JTH', '', '', 'Hi-Tech'),
(10, 'student', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'testing@mail.com', '', '', 'test', '', ''),
(12, 'organisation', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'jsa@mail.com', '', '3', '', '', 'JSA');

-- --------------------------------------------------------

--
-- Table structure for table `User Type`
--

CREATE TABLE `User Type` (
  `typeID` int(11) NOT NULL,
  `type` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `User Type`
--

INSERT INTO `User Type` (`typeID`, `type`) VALUES
(1, 'Admin'),
(2, 'Student'),
(3, 'Organisation');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Events`
--
ALTER TABLE `Events`
  ADD PRIMARY KEY (`eventID`);

--
-- Indexes for table `Location`
--
ALTER TABLE `Location`
  ADD PRIMARY KEY (`locationID`);

--
-- Indexes for table `Schools`
--
ALTER TABLE `Schools`
  ADD PRIMARY KEY (`schoolID`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `User Type`
--
ALTER TABLE `User Type`
  ADD PRIMARY KEY (`typeID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Events`
--
ALTER TABLE `Events`
  MODIFY `eventID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;