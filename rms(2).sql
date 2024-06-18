-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2024 at 07:11 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rms`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`) VALUES
(1, 'Department A'),
(2, 'Department B'),
(3, 'Department C');

--
-- Triggers `departments`
--
DELIMITER $$
CREATE TRIGGER `add_department_to_funding` AFTER INSERT ON `departments` FOR EACH ROW BEGIN
  INSERT INTO `department_funding` (`department_id`, `amount`)
  VALUES (NEW.department_id, 0);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `department_funding`
--

CREATE TABLE `department_funding` (
  `id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `department_funding`
--

INSERT INTO `department_funding` (`id`, `department_id`, `amount`) VALUES
(1, 1, 21146),
(2, 2, 6264),
(3, 3, 12299);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `ID` int(11) NOT NULL,
  `user_id` varchar(30) NOT NULL,
  `Password` varchar(30) NOT NULL,
  `Role` varchar(10) NOT NULL,
  `account` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`ID`, `user_id`, `Password`, `Role`, `account`) VALUES
(2, 'admin@gmail.com', 'admin123*', 'Admin', ''),
(13, 'yoyo@gmail.com', 'student123*', 'Student', 'Activate'),
(14, 'mama@gmail.com', 'teacher123*', 'Teacher', 'Activate'),
(15, 'shivam@gmail.com', 'student123*', 'Student', '');

-- --------------------------------------------------------

--
-- Table structure for table `research_info`
--

CREATE TABLE `research_info` (
  `research_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `journal_name` varchar(100) NOT NULL,
  `authors` varchar(255) NOT NULL,
  `research_title` varchar(255) NOT NULL,
  `research_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `research_info`
--

INSERT INTO `research_info` (`research_id`, `teacher_id`, `journal_name`, `authors`, `research_title`, `research_date`) VALUES
(61, 8, 'The Astronomical Journal, 2010', 'M Doi, M Tanaka, M Fukugita, JE Gunn, N Yasuda, Ž Ivezi?, J Brinkmann, E de Haars…', 'Yomama', '2024-04-01');

-- --------------------------------------------------------

--
-- Table structure for table `research_submissions`
--

CREATE TABLE `research_submissions` (
  `submission_id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `research_name` varchar(255) NOT NULL,
  `research_description` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_info`
--

CREATE TABLE `student_info` (
  `roll_no` varchar(20) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `middle_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `father_name` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `mobile_no` varchar(11) NOT NULL,
  `profile_image` varchar(100) NOT NULL,
  `dob` varchar(10) NOT NULL,
  `other_phone` varchar(11) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `semester` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student_info`
--

INSERT INTO `student_info` (`roll_no`, `first_name`, `middle_name`, `last_name`, `father_name`, `email`, `mobile_no`, `profile_image`, `dob`, `other_phone`, `gender`, `semester`) VALUES
('220911596', 'yoyo', 'yoyo', 'yoyo', '', 'yoyo@gmail.com', '', 'Profimage.png', '', '', 'Select Gen', 0),
('65', 'Shiavm', 'Prakash', 'Singh', '', 'shivam@gmail.com', '', 'Myphoto-removebg-preview.png', '', '', 'Select Gen', 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_requests`
--

CREATE TABLE `student_requests` (
  `request_id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `request_status` varchar(20) NOT NULL DEFAULT 'Pending',
  `request_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student_requests`
--

INSERT INTO `student_requests` (`request_id`, `student_id`, `teacher_id`, `department_id`, `request_status`, `request_date`) VALUES
(7, '220911596', 8, 1, 'Approved', '2024-04-17 01:23:34');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_departments`
--

CREATE TABLE `teacher_departments` (
  `teacher_id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `teacher_departments`
--

INSERT INTO `teacher_departments` (`teacher_id`, `department_name`) VALUES
(8, 'Department A'),
(8, 'Department B');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_info`
--

CREATE TABLE `teacher_info` (
  `teacher_id` int(11) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `middle_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `phone_no` varchar(11) NOT NULL,
  `profile_image` blob NOT NULL,
  `teacher_status` varchar(10) NOT NULL,
  `other_phone` int(11) NOT NULL,
  `gender` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `teacher_info`
--

INSERT INTO `teacher_info` (`teacher_id`, `first_name`, `middle_name`, `last_name`, `email`, `phone_no`, `profile_image`, `teacher_status`, `other_phone`, `gender`) VALUES
(8, 'yomama', 'mama', 'mama', 'mama@gmail.com', '', 0x7374617277617273322e6a7067, 'Select Sta', 0, 'Select Gen');

-- --------------------------------------------------------

--
-- Table structure for table `total_funding`
--

CREATE TABLE `total_funding` (
  `id` int(11) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `total_funding`
--

INSERT INTO `total_funding` (`id`, `amount`) VALUES
(1, 50000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `department_funding`
--
ALTER TABLE `department_funding`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `research_info`
--
ALTER TABLE `research_info`
  ADD PRIMARY KEY (`research_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `research_submissions`
--
ALTER TABLE `research_submissions`
  ADD PRIMARY KEY (`submission_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `student_info`
--
ALTER TABLE `student_info`
  ADD PRIMARY KEY (`roll_no`);

--
-- Indexes for table `student_requests`
--
ALTER TABLE `student_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD UNIQUE KEY `unique_request` (`student_id`,`teacher_id`,`department_id`),
  ADD KEY `fk_teacher_id_request` (`teacher_id`),
  ADD KEY `fk_department_id_request` (`department_id`);

--
-- Indexes for table `teacher_departments`
--
ALTER TABLE `teacher_departments`
  ADD PRIMARY KEY (`teacher_id`,`department_name`);

--
-- Indexes for table `teacher_info`
--
ALTER TABLE `teacher_info`
  ADD PRIMARY KEY (`teacher_id`);

--
-- Indexes for table `total_funding`
--
ALTER TABLE `total_funding`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `department_funding`
--
ALTER TABLE `department_funding`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `research_info`
--
ALTER TABLE `research_info`
  MODIFY `research_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `research_submissions`
--
ALTER TABLE `research_submissions`
  MODIFY `submission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `student_requests`
--
ALTER TABLE `student_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `teacher_info`
--
ALTER TABLE `teacher_info`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `total_funding`
--
ALTER TABLE `total_funding`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50002;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `department_funding`
--
ALTER TABLE `department_funding`
  ADD CONSTRAINT `department_funding_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE;

--
-- Constraints for table `research_info`
--
ALTER TABLE `research_info`
  ADD CONSTRAINT `research_info_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher_info` (`teacher_id`);

--
-- Constraints for table `research_submissions`
--
ALTER TABLE `research_submissions`
  ADD CONSTRAINT `research_submissions_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student_info` (`roll_no`),
  ADD CONSTRAINT `research_submissions_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teacher_info` (`teacher_id`);

--
-- Constraints for table `student_requests`
--
ALTER TABLE `student_requests`
  ADD CONSTRAINT `fk_department_id_request` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_student_id_request` FOREIGN KEY (`student_id`) REFERENCES `student_info` (`roll_no`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_teacher_id_request` FOREIGN KEY (`teacher_id`) REFERENCES `teacher_info` (`teacher_id`) ON DELETE CASCADE;

--
-- Constraints for table `teacher_departments`
--
ALTER TABLE `teacher_departments`
  ADD CONSTRAINT `fk_teacher_id` FOREIGN KEY (`teacher_id`) REFERENCES `teacher_info` (`teacher_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
