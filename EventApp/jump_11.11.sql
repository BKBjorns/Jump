-- phpMyAdmin SQL Dump
-- version 4.7.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 11, 2017 at 11:23 AM
-- Server version: 5.6.35
-- PHP Version: 7.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `Jump2`
--

-- --------------------------------------------------------

--
-- Table structure for table `Events`
--

CREATE TABLE `Events` (
  `eventID` int(11) NOT NULL,
  `title` char(255) NOT NULL,
  `description` varchar(2000) NOT NULL,
  `startdate` date NOT NULL,
  `enddate` int(11) NOT NULL,
  `time` time NOT NULL,
  `price` int(11) NOT NULL,
  `location` char(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `link` char(255) NOT NULL,
  `host` char(255) NOT NULL,
  `school` int(1) NOT NULL,
  `country` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Events`
--

INSERT INTO `Events` (`eventID`, `title`, `description`, `startdate`, `enddate`, `time`, `price`, `location`, `image`, `link`, `host`, `school`, `country`) VALUES
(66, 'JSA Fall Bi-Annual Meeting', 'Hello all JSA members,  You are hereby invited to the JSA Fall Bi-Annual Meeting 2017. All students are invited to attend the meeting to decide on the future of the association. You are eligible to vote if you have paid your Student Union membership fee and study at JIBS. Thus if your JU card says JSA on it, congratulations, you can vote!  The meeting will include going through the current state of the association with questions and answers from current board members, proposals and motions, and election of new board members. Any members of JSA can write motions and submit them to the meeting. Motions are proposals for a change in the JSA regulations regulating the work we do.  The board positions available for this Biannual meeting are: - Vice President - Head of Education - Treasurer - Head of International - Head of Social - Deadline for board applications is 3/11, apply here: http://jibsstudents.com/get-involved/ and the deadline for submitting motions is 7/11 at 15:00. You can submit your motions by sending them to: president.jsa@js.ju.se -  If you have any questions, please do not hesitate to contact any of the board members of JSA!  We will also host two events for all your questions: 25th October, JUBEL Mingle 16-20, JUBEL 31st October, JSA Lobby Event 11-13, JIBS Lobby', '2017-12-14', 0, '15:00:00', 0, 'B1022', '22549517_1893263214083011_8095755841363828883_n.jpg', '', 'J.S.A.', 3, 0),
(67, 'Sen Kv&auml;ll Med LOK', 'Late Night With LOK   Do you want to know what it&rsquo;s like to be a part of the LOK board? Or have you always wanted to know how to write an interpellation? Maybe you just want to know what to wear on the involvement general meeting? We can answer all those questions on Late Night with LOK on October 3rd! We offer you mingle, games and free food for the 50 first students from HLK! Vegetarian options will be available. The location of the event is RIO at the students&acute; house. Our committees will also be there if you have any questions for them.', '2017-12-09', 0, '16:30:00', 0, 'Rio', '22049794_1611919338864755_8366076091818213314_n.jpg', '', 'L.O.K.', 2, 0),
(68, 'Waffle night with HINT!', 'Come join HINT the 26th of October for some delicious waffles between 17:00-21:00!  Take a short study break and join us in the entrance of JTH, to enjoy delicious waffles and coffee.   We will serve you Waffles with Cream and Jam + Coffee for only 20 kr. After that, it', '2017-11-30', 0, '17:00:00', 0, 'Entrence, JTH', '22448280_1958130247537737_2132735863065649990_n.jpg', '', 'HINT', 1, 0),
(69, 'HINT on the run', 'The time has come for the fifth edition of HINT on the Run!! Don\'t miss this opportunity to show your orientation skills in the dark all around J&ouml;nk&ouml;ping City.   HINT presents the biggest game of Hide and Seek ever all around central J&ouml;nk&ouml;ping! Team up with your friends, enter the dark void of central J&ouml;nk&ouml;ping, solve the clues and show that you\'re the greatest seeker of them all!   Enter the event in teams of 3-5 persons and receive an awesome patch!  **NOTE** that at least one of the team members must have access to the app WhatsApp and internet!  *Standby for more information and rules*  Get your tickets in the entrance of JTH on Thursday the 19th of October at 12:00-13:00!  It only costs 30 SEK per person to participate, including a patch of course! One person can buy the tickets for his/her entire team, but not more.   Both Swedish and International students are of course welcome, from all the schools of JU! There is a limited number of spots though, so make sure you get one!', '2017-12-14', 0, '17:00:00', 0, 'Meet at JTH Entrence', '22366462_1953867791297316_5252756956685629324_n.jpg', '', 'HINT', 1, 0),
(70, 'Pub crawl with HINT', 'Pub crawl with HINT is a new event.', '2017-12-06', 0, '17:30:00', 0, 'Meet at Rio', '23135128_10203388635633476_493322987_o.png', '', 'HINT', 1, 0),
(71, 'Go Exchange', 'This event is organized by J&ouml;nk&ouml;ping Student Union and International Relations Office in cooperation with HINT, JSA International, LOK, S.U.S.H.I., but more importantly, it is arranged BY students and FOR students!   Go abroad... Go discover... GO EXCHANGE!', '2017-12-04', 0, '11:00:00', 0, 'JTH, H&Auml;LSO, HLK and Campus Arena', '22851908_378643529228461_1866921942003246848_n.jpg', '', 'Student Union', 5, 0),
(72, 'Filmfestival on campus', 'Join the Filmfestival on campus!   16:00 the buffet in Rio will be open. Make sure to grab some popcorn and go to your movie at 17:30!  Get ready for our Filmfestival! On Friday we will air four films at 17:30  JTH : Amelie (E1423 Fagerhultsaulan) JIBS: Geralds game (B1024) HLK: Under the sun (He102) H&auml;lso: The dark knight (Ge308 Forum Humanum)  See you there! And don\'t forget to grab your popcorn at Rio  Join us afterwards at Rio for a fun time and some movie discussions and quiz.', '2017-12-10', 0, '16:00:00', 0, 'JTH, HLK, JIBS and H&Auml;LSO', '23213227_1626391424093623_6659823066492877839_o.jpg', '', 'Student Union', 5, 0),
(73, 'Food Safari', 'Food Safari is back!\r\n\r\nWhat is Food Safari about?\r\nMeeting new people while sharing a delicious 3-course meal in different places all over town.\r\n\r\nHow does it work?\r\n• You sign up in teams of two persons. \r\n• We arrange the teams in a way that you prepare ONE course: starter, main-dish or dessert, and you will serve it at your place. \r\n• Every meal takes place at a different location with two other teams each time. \r\n• That means that you have to prepare your dish for six students (including you and your team member). \r\n• The budget for your dish should be between 200 – 300 SEK, this your team will pay for.\r\n\r\nWe will work out your individual dinner routes through the city and sent it to you via email.\r\n\r\nWhere can I sign up?\r\nYou can sign up during the opening hours of Student Service (bottom floor in Student House)\r\nYou can sign up between October 30th and November 8th. We accept no late sign ups so BE ON TIME.\r\n\r\nWhen?\r\nThe Food Safari will take place Wednesday the 15th of November at 17.00.\r\n\r\nHow much is it?\r\nWe have to ask you for a registration fee of 40 SEK per person. But you will get a cool patch for your overall and a free entrance ticket to Akademien valid for that night only. NOTE, you will not get any refund if you can\'t attend after you\'ve registered.\r\n\r\nThere might also be some travel expenses when you need to take the bus. Please don’t miss the food people prepared for you, just because you don’t want to take the bus. However, you can only sign up within Jönköping so the trip should not be far.\r\n\r\nAfterwards?\r\nWe are all meeting in Akademien for the after party. A nice opportunity to meet again all the guys you have met during the dishes. \r\n\r\nRemember that you will get in to Akademien for free with your free entrance ticket, so there is no need to rush through the dessert :)\r\n\r\nWhat about the photo competition?\r\nAs usual, the best picture will be awarded with a nice prize!\r\n\r\nIf you have any further questions, please contact international@js.ju.se', '2017-11-22', 0, '16:00:00', 0, 'J&ouml;nk&ouml;ping', '22688602_1612619322137500_4623871321345773380_n.jpg', '', 'Student Union', 5, 0),
(74, 'HIKE-trip: &Aring;re', 'Once again &Aring;res skislopes and streets will be filled with JTH\'s beautiful students. A week of skiing, afterski and other activities.   Departure: the evening of the 27th of January Arrival back: the morning of the 2nd of February Ticketsale: Wednesday the 29th of November.  Price: 3500 kr.  The price will include round trip ticket, accomodation, breakfast, skipass, a &quot;HIKE to &Aring;re&quot;-shirt and a nice patch for your ovve.   Reserve these dates and make yourself ready for an awesome trip.', '2018-01-18', 0, '20:00:00', 0, 'Meet outside Rio', '23172721_1260668074039368_9126755569380749303_n.jpg', '', 'HIKE', 1, 0),
(76, 'Pizza night!', 'Pizza Pizza', '2017-11-27', 0, '17:00:00', 0, 'Rio', 'spinachpizza.jpg', '', 'HIKE', 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Events`
--
ALTER TABLE `Events`
  ADD PRIMARY KEY (`eventID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Events`
--
ALTER TABLE `Events`
  MODIFY `eventID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;
