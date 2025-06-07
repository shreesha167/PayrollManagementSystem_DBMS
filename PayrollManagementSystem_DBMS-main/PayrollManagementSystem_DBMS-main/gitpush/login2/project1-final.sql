-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2024 at 05:33 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project1`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateSalary` (IN `salary` INT)   BEGIN
    UPDATE employee AS e
    JOIN attendance AS a ON e.emp_id = a.emp_id
    SET e.salary = 
        CASE 
            WHEN a.days_worked BETWEEN 10 AND 12 THEN e.salary / 1.3 
            WHEN a.days_worked BETWEEN 13 AND 15 THEN e.salary / 1.2 
            WHEN a.days_worked BETWEEN 16 AND 20 THEN e.salary / 1.1 
            ELSE e.salary
        END;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `emp_id` int(10) NOT NULL,
  `atten_id` int(10) NOT NULL,
  `days_worked` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`emp_id`, `atten_id`, `days_worked`) VALUES
(102, 93, 15),
(103, 94, 15),
(103, 96, 17),
(105, 97, 11),
(123, 99, 15);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `dept_id` int(10) NOT NULL,
  `dept_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`dept_id`, `dept_name`) VALUES
(1, 'IOT'),
(2, 'Testing'),
(3, 'Data Analysis'),
(4, 'Data Science'),
(5, 'Business Intelligence');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `emp_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `joining_date` date NOT NULL,
  `city` varchar(50) NOT NULL,
  `states` varchar(50) NOT NULL,
  `salary` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`emp_id`, `fname`, `lname`, `joining_date`, `city`, `states`, `salary`, `dept_id`) VALUES
(102, 'Vrushali', 'Patil', '2011-06-18', 'Boston', 'Massachusetts', 12500, 1),
(103, 'Pratik', 'Parija', '2013-09-19', 'Chicago', 'Illinois', 25000, 2),
(104, 'Chetan', 'Mistry', '2012-04-11', 'Miami', 'Florida', 60000, 3),
(105, 'Anugraha', 'Varkey', '2016-08-17', 'Atlanta', 'Georgia', 26923, 4),
(107, 'shankar', 's', '2024-03-16', 'bangalore', 'karnataka', 50000, 5),
(123, 'shiva', 'subramaniyam s', '2024-02-22', 'bangalore', 'Karnataka', 16667, 5);

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `dept_id` int(10) NOT NULL,
  `proj_id` int(10) NOT NULL,
  `proj_name` varchar(50) NOT NULL,
  `proj_desc` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`dept_id`, `proj_id`, `proj_name`, `proj_desc`) VALUES
(3, 25, 'Research', 'focus on everything'),
(4, 24, 'Nothing', 'do nothing'),
(5, 23, 'Test', 'focus');

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `uname1` varchar(20) NOT NULL,
  `email` varchar(25) NOT NULL,
  `upswd1` varchar(20) NOT NULL,
  `upswd2` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`uname1`, `email`, `upswd1`, `upswd2`) VALUES
('sathya', 'sathya@gmail.com', '123', '123'),
('shakthi', 'shakthi@gmail.com', '123', '123'),
('shiva', 'shiva@gmail.com', 'shiva', 'shiva');

-- --------------------------------------------------------

--
-- Table structure for table `salary`
--

CREATE TABLE `salary` (
  `salary_id` int(11) NOT NULL,
  `gross_salary` int(11) NOT NULL,
  `acc_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salary`
--

INSERT INTO `salary` (`salary_id`, `gross_salary`, `acc_id`) VALUES
(11, 57600, 40),
(12, 76800, 40),
(13, 96000, 41),
(14, 115200, 43),
(15, 57600, 41);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`emp_id`,`atten_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`dept_id`),
  ADD UNIQUE KEY `dept_id` (`dept_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`emp_id`),
  ADD UNIQUE KEY `emp_id` (`emp_id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`dept_id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `uname1` (`uname1`);

--
-- Indexes for table `salary`
--
ALTER TABLE `salary`
  ADD PRIMARY KEY (`salary_id`),
  ADD KEY `acc_id` (`acc_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attend` FOREIGN KEY (`emp_id`) REFERENCES `employee` (`emp_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `dept_id` FOREIGN KEY (`dept_id`) REFERENCES `department` (`dept_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
