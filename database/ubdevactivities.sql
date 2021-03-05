-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 05, 2021 at 12:19 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ubdevactivities`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `status`) VALUES
(1, 'Web Application', 1),
(2, 'Mobile Application', 1),
(3, 'Desktop Application', 1),
(4, 'Embedded Application', 1),
(5, 'Iot ', 1),
(7, 'hello', 1);

-- --------------------------------------------------------

--
-- Table structure for table `creators`
--

CREATE TABLE `creators` (
  `creator_id` int(11) NOT NULL,
  `names` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `status` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `creators`
--

INSERT INTO `creators` (`creator_id`, `names`, `username`, `password`, `email`, `status`) VALUES
(4, 'Joseph SHYIRAMBERE', 'xcoder', '$2y$10$salt22CharactersOrmore827ccb0eea8a706c4c34a16891f84e7b', 'scode@gmail.com', 1),
(5, 'Aristide TUYISENGE', 'ganza', '$2y$10$salt22CharactersOrmore827ccb0eea8a706c4c34a16891f84e7b', 'tuyisengear@gmail.com', 1),
(7, 'Patrick ISHIMWE', 'patty', '$2y$10$salt22CharactersOrmore827ccb0eea8a706c4c34a16891f84e7b', 'paty12345@gmail.com', 1),
(8, 'Jeanne Beula Nzabihimana', 'beula', '$2y$10$salt22CharactersOrmore827ccb0eea8a706c4c34a16891f84e7b', 'jeanneBeula@gmail.com', 1),
(9, 'Jeanne Beula Nzabihimana', 'bbe', '$2y$10$salt22CharactersOrmorec20ad4d76fe97759aa27a0c99bff6710', '123@gmail.com', 1),
(10, 'Patrick ISHIMWE', '3', '$2y$10$salt22CharactersOrmore827ccb0eea8a706c4c34a16891f84e7b', '23@gmail.com', 1),
(11, 'jb', 'jb', '$2y$10$salt22CharactersOrmore827ccb0eea8a706c4c34a16891f84e7b', 'jb@gmail.com', 1),
(12, 'Emmanuel NIYONGABO', 'emmy1', '$2y$10$salt22CharactersOrmore827ccb0eea8a706c4c34a16891f84e7b', '12345@gmail.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `language_id` int(11) NOT NULL,
  `language_name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`language_id`, `language_name`, `status`) VALUES
(2, 'HTML.CSS,PHP', 1),
(3, 'HTML,CSS.JAVASCRIPT,PHP', 1),
(4, 'python', 1),
(6, 'javascript', 1),
(7, 'Ruby', 1),
(8, 'language_name', 1);

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `template_id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `template_name` varchar(100) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `template_file` varchar(255) NOT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  `status` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`template_id`, `creator_id`, `template_name`, `category_id`, `language_id`, `description`, `template_file`, `date_created`, `status`) VALUES
(23, 5, 'successfully registerd template nndnd', 1, 6, 'successfully registerd template nndndsuccessfully registerd template nndndsuccessfully registerd template nndndsuccessfully registerd template nndnd', 'd dnd', '2021-01-29 19:49:21', 1),
(24, 5, 'nndnd', 1, 6, 'ngggggggggggggggggggggggggggggggggggg', 'd dnd', '2021-01-29 19:54:40', 1),
(25, 5, 'hdhdhd', 1, 6, 'hdhdhdhhf', 'd dnd', '2021-02-24 15:26:31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `topic`
--

CREATE TABLE `topic` (
  `topic_id` int(11) NOT NULL,
  `creator_id` int(11) DEFAULT NULL,
  `topic_category_id` int(11) NOT NULL,
  `topic_title` varchar(100) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  `status` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `topic`
--

INSERT INTO `topic` (`topic_id`, `creator_id`, `topic_category_id`, `topic_title`, `date_created`, `status`) VALUES
(13, 7, 2, 'how to fo mac compute', '2021-01-29 21:00:43', 1),
(14, 7, 4, 'how to fo mac nnnnnnn compute', '2021-01-29 21:01:23', 1);

-- --------------------------------------------------------

--
-- Table structure for table `topic_category`
--

CREATE TABLE `topic_category` (
  `topic_category_id` int(11) NOT NULL,
  `topic_category` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `topic_category`
--

INSERT INTO `topic_category` (`topic_category_id`, `topic_category`, `status`) VALUES
(2, 'Recommendations To Programmers', 1),
(3, 'Technology', 1),
(4, 'Technical Supports', 1),
(5, 'Operating system', 1),
(7, 'programmers Guides', 1),
(8, 'topic_category', 1);

-- --------------------------------------------------------

--
-- Table structure for table `topic_descriptions`
--

CREATE TABLE `topic_descriptions` (
  `topic_descr_id` int(11) NOT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `topic_descriptions`
--

INSERT INTO `topic_descriptions` (`topic_descr_id`, `topic_id`, `description`, `date_created`, `status`) VALUES
(3, 14, 'ganzaaar', '2021-01-29 21:16:09', 1),
(6, 13, 'dhdjdj', '2021-01-29 21:19:30', 1),
(13, 13, '<p>hdhhdhd</p>\r\n<p>hdhhdhd</p>', '2021-03-05 09:33:45', 1),
(14, 13, '<p>hdhhdhd</p>\r\n<p>hdhhdhd</p>', '2021-03-05 09:33:46', 1),
(15, 14, 'asdfghjhkl', '2021-03-05 10:13:24', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `creators`
--
ALTER TABLE `creators`
  ADD PRIMARY KEY (`creator_id`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`language_id`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`template_id`),
  ADD KEY `creator_id` (`creator_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `topic`
--
ALTER TABLE `topic`
  ADD PRIMARY KEY (`topic_id`),
  ADD KEY `creator_id` (`creator_id`),
  ADD KEY `topic_category_id` (`topic_category_id`);

--
-- Indexes for table `topic_category`
--
ALTER TABLE `topic_category`
  ADD PRIMARY KEY (`topic_category_id`);

--
-- Indexes for table `topic_descriptions`
--
ALTER TABLE `topic_descriptions`
  ADD PRIMARY KEY (`topic_descr_id`),
  ADD KEY `fk_topic_descriptions_topic_idx` (`topic_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `creators`
--
ALTER TABLE `creators`
  MODIFY `creator_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `language_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `topic`
--
ALTER TABLE `topic`
  MODIFY `topic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `topic_category`
--
ALTER TABLE `topic_category`
  MODIFY `topic_category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `topic_descriptions`
--
ALTER TABLE `topic_descriptions`
  MODIFY `topic_descr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `templates`
--
ALTER TABLE `templates`
  ADD CONSTRAINT `templates_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `creators` (`creator_id`),
  ADD CONSTRAINT `templates_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `language` (`language_id`),
  ADD CONSTRAINT `templates_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`);

--
-- Constraints for table `topic`
--
ALTER TABLE `topic`
  ADD CONSTRAINT `topic_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `creators` (`creator_id`),
  ADD CONSTRAINT `topic_ibfk_2` FOREIGN KEY (`topic_category_id`) REFERENCES `topic_category` (`topic_category_id`);

--
-- Constraints for table `topic_descriptions`
--
ALTER TABLE `topic_descriptions`
  ADD CONSTRAINT `fk_topic_descriptions_topic` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`topic_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
