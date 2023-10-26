-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2023 at 05:20 AM
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
-- Database: `db_collaborate`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_tbl`
--

CREATE TABLE `account_tbl` (
  `account_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `pass` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `date_created` varchar(255) DEFAULT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_tbl`
--

INSERT INTO `account_tbl` (`account_id`, `user_id`, `email`, `pass`, `role`, `date_created`, `token`) VALUES
(9, 9, 'lloydangelomartinez@gmail.com', '$2y$10$iepegEBXvFje/tF04Ymzrebt/SmZ9Ea60.rLxFMQ35V6D5BIVSH4q', 'adviser', '2023-09-22', ''),
(11, 9, 'martinezangelo835@gmail.com', '$2y$10$iepegEBXvFje/tF04Ymzrebt/SmZ9Ea60.rLxFMQ35V6D5BIVSH4q', 'student', '2023-09-22', ''),
(12, 10, 'martinezlloydangelo21@gmail.com', '$2y$10$I0dVLo0nkIwcUT/44ZcFAuXJ.jPpCP5hN8oIgcXSvzjx7wn.SOyNS', 'student', '2023-10-11', '');

-- --------------------------------------------------------

--
-- Table structure for table `adviser_tbl`
--

CREATE TABLE `adviser_tbl` (
  `adviser_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `course` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `profile` varchar(255) NOT NULL,
  `date_created` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adviser_tbl`
--

INSERT INTO `adviser_tbl` (`adviser_id`, `name`, `course`, `contact_number`, `address`, `profile`, `date_created`) VALUES
(9, 'Lloyd Angelo T. Martinez', 'Bachelor of Science in Information Technology', '09069836725', 'Purok 1, San Roque, Guimba ', '1697419657_652c918931e5f.jpg', '2023-10-09');

-- --------------------------------------------------------

--
-- Table structure for table `classroom_tbl`
--

CREATE TABLE `classroom_tbl` (
  `room_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `date_joined` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classroom_tbl`
--

INSERT INTO `classroom_tbl` (`room_id`, `student_id`, `subject_id`, `date_joined`) VALUES
(3, 9, 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `course_tbl`
--

CREATE TABLE `course_tbl` (
  `course_id` int(11) NOT NULL,
  `adviser_id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `course_code` varchar(255) NOT NULL,
  `course_section` varchar(255) NOT NULL,
  `class_code` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `date_created` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_tbl`
--

INSERT INTO `course_tbl` (`course_id`, `adviser_id`, `course_name`, `course_code`, `course_section`, `class_code`, `status`, `date_created`) VALUES
(1, 9, 'Computer Programming', 'CC 101', 'Block A', 'hdoki', 'archieve', '2023-10-10'),
(2, 9, 'Capstone 2', 'CAP 102', 'Block A', 'jtfsn', 'active', '2023-10-10');

-- --------------------------------------------------------

--
-- Table structure for table `notes_tbl`
--

CREATE TABLE `notes_tbl` (
  `notes_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes_tbl`
--

INSERT INTO `notes_tbl` (`notes_id`, `project_id`, `course_id`, `filename`) VALUES
(18, 17, 2, 'curriculumvitae.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `project_tbl`
--

CREATE TABLE `project_tbl` (
  `project_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `instruction` text NOT NULL,
  `deadline` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `date_created` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_tbl`
--

INSERT INTO `project_tbl` (`project_id`, `course_id`, `project_name`, `instruction`, `deadline`, `status`, `date_created`) VALUES
(17, 2, 'Chapter 1', 'Title of Chapter 1: Introduction\r\n\r\nI. Introduction\r\n\r\nBackground of the Study\r\nProvide a brief overview of the general topic and its significance.\r\nExplain the background or context that led to your research.\r\nMention any relevant statistics, facts, or trends that highlight the importance of the study.\r\n\r\nStatement of the Problem\r\nClearly state the research problem or question your capstone project addresses.\r\nDefine the scope of your research and its objectives.\r\nExplain why this problem is worth investigating.\r\n\r\nPurpose of the Study\r\nClearly state the main purpose of your research.\r\nDescribe what you intend to accomplish through this study.\r\nResearch Questions or Hypotheses\r\n\r\nList the specific research questions or hypotheses that guide your investigation.\r\nSignificance of the Study\r\n\r\nExplain the potential impact and significance of your research.\r\nDiscuss how it can contribute to the field or address real world issues.\r\nScope and Limitations\r\n\r\nDescribe the boundaries of your study. What will and wont be covered\r\nDiscuss any limitations, such as time, resources, or access to data.', '2023-10-19', 'in process', '2023-10-14');

-- --------------------------------------------------------

--
-- Table structure for table `research_tbl`
--

CREATE TABLE `research_tbl` (
  `research_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `research_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `research_tbl`
--

INSERT INTO `research_tbl` (`research_id`, `student_id`, `research_title`) VALUES
(1, 9, 'Agua: Chico river Water level Monitoring System with SMS notification.'),
(2, 10, 'Thesis Management');

-- --------------------------------------------------------

--
-- Table structure for table `student_tbl`
--

CREATE TABLE `student_tbl` (
  `student_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `course` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `profile` varchar(255) NOT NULL,
  `date_created` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_tbl`
--

INSERT INTO `student_tbl` (`student_id`, `name`, `course`, `contact_number`, `address`, `profile`, `date_created`) VALUES
(9, 'Lloyd Angelo Martinez', 'Bachelor of Science in Information Technology', '097723663626', 'Purok 1, San Roque', 'Untitled-1.jpg', '2023-09-22'),
(10, 'John Doe', 'Bachelor of Science', '092837827382', 'Guimba', 'profile.png', '2023-10-11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_tbl`
--
ALTER TABLE `account_tbl`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `adviser_tbl`
--
ALTER TABLE `adviser_tbl`
  ADD PRIMARY KEY (`adviser_id`);

--
-- Indexes for table `classroom_tbl`
--
ALTER TABLE `classroom_tbl`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `course_tbl`
--
ALTER TABLE `course_tbl`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `notes_tbl`
--
ALTER TABLE `notes_tbl`
  ADD PRIMARY KEY (`notes_id`);

--
-- Indexes for table `project_tbl`
--
ALTER TABLE `project_tbl`
  ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `research_tbl`
--
ALTER TABLE `research_tbl`
  ADD PRIMARY KEY (`research_id`);

--
-- Indexes for table `student_tbl`
--
ALTER TABLE `student_tbl`
  ADD PRIMARY KEY (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_tbl`
--
ALTER TABLE `account_tbl`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `adviser_tbl`
--
ALTER TABLE `adviser_tbl`
  MODIFY `adviser_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `classroom_tbl`
--
ALTER TABLE `classroom_tbl`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `course_tbl`
--
ALTER TABLE `course_tbl`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notes_tbl`
--
ALTER TABLE `notes_tbl`
  MODIFY `notes_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `project_tbl`
--
ALTER TABLE `project_tbl`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `research_tbl`
--
ALTER TABLE `research_tbl`
  MODIFY `research_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student_tbl`
--
ALTER TABLE `student_tbl`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
