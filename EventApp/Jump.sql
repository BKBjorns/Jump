-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 07, 2017 at 02:09 PM
-- Server version: 5.6.26
-- PHP Version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Jump`
--

-- --------------------------------------------------------

--
-- Table structure for table `Attend`
--

CREATE TABLE IF NOT EXISTS `Attend` (
  `eventID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `nrOfattendens` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Attend`
--

INSERT INTO `Attend` (`eventID`, `userID`, `nrOfattendens`) VALUES
(3, 1, 0),
(19, 1, 0),
(21, 1, 0),
(35, 1, 0),
(3, 53, 0),
(39, 53, 0),
(40, 53, 0),
(43, 53, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Countries`
--

CREATE TABLE IF NOT EXISTS `Countries` (
  `Location ID` int(11) NOT NULL,
  `Country` int(11) NOT NULL,
  `City` int(11) NOT NULL,
  `Adress` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Events`
--

CREATE TABLE IF NOT EXISTS `Events` (
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
  `host` char(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Events`
--

INSERT INTO `Events` (`eventID`, `title`, `description`, `startdate`, `enddate`, `time`, `price`, `location`, `image`, `link`, `host`) VALUES
(39, 'Go Exchange', 'bjhrknfv,d .iqekpdlÃ¶,c', '2017-11-26', 0, '03:02:00', 0, 'JTH', '2575d808d38910cdfc27d10af7d7feaf.jpg', '', 'HINT'),
(40, 'Sushi Night', '...........', '2017-11-26', 0, '23:05:00', 0, 'HÃ¤lso', '7e8d2866fcb13b1ac425b6b8029dc9bd.jpg', '', 'Sushi'),
(41, 'New Event One', 'Ut eu lacinia neque. Fusce consequat lacus in tortor porttitor semper. Quisque blandit in mauris et rhoncus. Sed dapibus semper vestibulum. Donec at arcu aliquet, accumsan dolor ut, lobortis justo. Cras consectetur mauris ante, sit amet vehicula ante eges', '2017-11-26', 0, '03:02:00', 0, 'JTH', '434d958c2269b8408e67a445f7de1f36.jpg', '', 'HiTech'),
(42, 'New Event Two', 'Ut eu lacinia neque. Fusce consequat lacus in tortor porttitor semper. Quisque blandit in mauris et rhoncus. Sed dapibus semper vestibulum. Donec at arcu aliquet, accumsan dolor ut, lobortis justo. Cras consectetur mauris ante, sit amet vehicula ante eges', '2017-12-03', 0, '14:00:00', 0, 'HÃ¤lso', 'cba1ccbdd44adb9a167a6dc1d9156ae5.jpg', '', 'Sushi'),
(43, 'New Event Three', 'Ut eu lacinia neque. Fusce consequat lacus in tortor porttitor semper. Quisque blandit in mauris et rhoncus. Sed dapibus semper vestibulum. Donec at arcu aliquet, accumsan dolor ut, lobortis justo. Cras consectetur mauris ante, sit amet vehicula ante eges', '2017-11-11', 0, '23:05:00', 0, 'HLK', 'b1c500db12ce67dcba054c1635f2c84a.jpg', '', 'BlueCrew');

-- --------------------------------------------------------

--
-- Table structure for table `Schools`
--

CREATE TABLE IF NOT EXISTS `Schools` (
  `School ID` int(11) NOT NULL,
  `School Name` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Schools`
--

INSERT INTO `Schools` (`School ID`, `School Name`) VALUES
(1, 'JTH'),
(2, 'HLK'),
(3, 'JIBS'),
(4, 'HÄLSO'),
(5, 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `userID` int(11) NOT NULL,
  `type` varchar(13) NOT NULL,
  `userpass` char(255) NOT NULL,
  `email` char(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `school` varchar(50) NOT NULL,
  `firstname` char(255) NOT NULL,
  `lastname` char(255) NOT NULL,
  `organisation` char(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`userID`, `type`, `userpass`, `email`, `image`, `school`, `firstname`, `lastname`, `organisation`) VALUES
(1, 'admin', '271e9a568c0e9e562431ccb1f5da3422162de7b8', 'jump@mail.com', 'gradient.jpg', '0', 'Jump', '', ''),
(38, 'organisation', '8be3c943b1609fffbfc51aad666d0a04adf83c9d', 'j@ki.de', '', 'JIBS', '', '', 'Organization'),
(42, 'organisation', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'hint@hint.se', '', 'JTH', '', '', 'HINT'),
(45, 'student', '0dc06e3d07aae14d4dd8f664c6b47e5880ee9836', 'aers@sf.de', '', 'JTH', 'aer', 'rsgh', ''),
(46, 'organisation', 'ae8fe380dd9aa5a7a956d9085fe7cf6b87d0d028', 'zguhnj@hu.ghu', '', 'JIBS', '', '', 'fgzh'),
(47, 'organisation', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'hint@mail.com', '', 'JTH', '', '', 'HINT'),
(48, 'student', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'one@one.com', 'gradient.png', 'jth', '', '', ''),
(49, 'organisation', '1041179cbdda366fd7b0347f09255f775170e103', 'gg@jj.nn', '', 'JTH', '', '', 'rr'),
(50, 'organisation', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'organisation@one.com', 'IMG.jpg', 'JIBS', '', '', 'JSA'),
(51, 'organisation', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'organisation@two.com', 'Moln.jpg', 'HLK', '', '', 'BlueCrew'),
(52, 'organisation', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'sushi@mail.com', 'one.jpg', 'Hälso', '', '', 'Sushi'),
(53, 'student', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'student@mail.com', 'two.jpg', 'JTH', 'Max', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `User Type`
--

CREATE TABLE IF NOT EXISTS `User Type` (
  `Type ID` int(11) NOT NULL,
  `Type` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `User Type`
--

INSERT INTO `User Type` (`Type ID`, `Type`) VALUES
(1, 'Admin'),
(2, 'Student'),
(3, 'Organisation');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Countries`
--
ALTER TABLE `Countries`
  ADD PRIMARY KEY (`Location ID`);

--
-- Indexes for table `Events`
--
ALTER TABLE `Events`
  ADD PRIMARY KEY (`eventID`);

--
-- Indexes for table `Schools`
--
ALTER TABLE `Schools`
  ADD PRIMARY KEY (`School ID`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `User Type`
--
ALTER TABLE `User Type`
  ADD PRIMARY KEY (`Type ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Events`
--
ALTER TABLE `Events`
  MODIFY `eventID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=54;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
