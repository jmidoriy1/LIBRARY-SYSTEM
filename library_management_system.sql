-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2024 at 10:05 AM
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
-- Database: `library_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `pin` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`, `pin`) VALUES
(5, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1231);

-- --------------------------------------------------------

--
-- Table structure for table `tblauthors`
--

CREATE TABLE `tblauthors` (
  `id` int(11) NOT NULL,
  `AuthorName` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblauthors`
--

INSERT INTO `tblauthors` (`id`, `AuthorName`, `creationDate`, `UpdationDate`) VALUES
(8, 'David S. Dummit', '2024-11-15 07:38:03', NULL),
(9, 'Donald E. Knuth', '2024-11-15 07:38:23', NULL),
(10, 'Gallian, Joseph A', '2024-11-15 07:44:55', NULL),
(11, 'Thomas H. Cormen', '2024-11-15 07:46:52', NULL),
(12, 'DK', '2024-11-15 07:51:07', NULL),
(13, 'Berajah Jayne', '2024-11-15 07:52:14', NULL),
(14, 'Charles Petzold', '2024-11-15 07:53:53', NULL),
(15, 'Kevin Kurtz MA', '2024-11-15 07:54:42', NULL),
(16, 'Michael DiGiacomo', '2024-11-15 07:59:24', NULL),
(17, 'Raymond Murphy', '2024-11-15 08:01:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblbooks`
--

CREATE TABLE `tblbooks` (
  `id` int(11) NOT NULL,
  `BookName` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `Copies` int(11) DEFAULT NULL,
  `IssuedCopies` int(1) NOT NULL DEFAULT 0,
  `BookNumber` int(100) NOT NULL,
  `CatId` int(11) DEFAULT NULL,
  `AuthorId` int(11) DEFAULT NULL,
  `datepublished` date DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `archive` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblbooks`
--

INSERT INTO `tblbooks` (`id`, `BookName`, `Copies`, `IssuedCopies`, `BookNumber`, `CatId`, `AuthorId`, `datepublished`, `RegDate`, `UpdationDate`, `archive`) VALUES
(45, 'The Art of Computer Programming, Volumes 1-3 Boxed Set', 2, 0, 201485419, 16, 9, '1998-01-01', '2024-11-15 07:39:31', '2024-11-15 04:26:56', 0),
(46, 'Abstract Algebra', 1, 0, 135693020, 18, 8, '1998-01-01', '2024-11-15 07:43:46', '2024-11-30 00:28:14', 0),
(47, 'Contemporary abstract algebra', 1, 1, 2147483647, 18, 10, '2002-01-01', '2024-11-15 07:46:22', '2024-11-30 00:28:35', 1),
(49, 'Python Programming Language', 3, 0, 1423241886, 16, 13, '2019-05-01', '2024-11-15 07:52:49', '2024-11-30 02:08:47', 1),
(50, 'Code: The Hidden Language of Computer Hardware and Software', 3, 1, 137909101, 16, 14, '2022-08-07', '2024-11-15 07:54:30', '2024-11-30 02:06:26', 1),
(51, 'The Fascinating Science Book for Kids: 500 Amazing Facts!', 3, 0, 1647398703, 17, 15, '2020-12-29', '2024-11-15 07:55:36', '2024-12-02 03:59:13', 1),
(52, 'Knowledge Encyclopedia Science!', 2, 0, 1465473637, 17, 12, '2018-08-07', '2024-11-15 07:58:13', NULL, 1),
(53, 'The English Grammar Workbook for Adults: A Self-Study Guide to Improve Functional Writing', 5, 0, 1646113195, 19, 16, '2020-06-02', '2024-11-15 08:00:09', NULL, 1),
(54, 'English for Everyone Course Book Level 1 Beginner: A Complete Self-Study Program ', 3, 0, 744098564, 19, 12, '2024-01-01', '2024-11-15 08:00:43', NULL, 1),
(55, 'English Grammar in Use Book with Answers: A Self-Study Reference and Practice Book for Intermediate Learners of English', 3, 0, 1108457657, 19, 17, '2019-01-24', '2024-11-15 08:01:36', '2024-11-17 09:32:20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblcategory`
--

CREATE TABLE `tblcategory` (
  `id` int(11) NOT NULL,
  `CategoryName` varchar(150) DEFAULT NULL,
  `Status` int(1) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcategory`
--

INSERT INTO `tblcategory` (`id`, `CategoryName`, `Status`, `CreationDate`, `UpdationDate`) VALUES
(16, 'Technology', 1, '2024-11-15 07:19:45', '2024-11-15 07:19:45'),
(17, 'Science', 1, '2024-11-15 07:19:49', '2024-11-15 07:56:43'),
(18, 'Math', 1, '2024-11-15 07:19:59', '2024-11-15 07:19:59'),
(19, 'English', 1, '2024-11-15 07:20:04', '2024-11-15 07:20:04'),
(20, 'Filipino', 1, '2024-11-19 03:15:53', '2024-12-02 11:34:37');

-- --------------------------------------------------------

--
-- Table structure for table `tblfaculty`
--

CREATE TABLE `tblfaculty` (
  `id` int(11) NOT NULL,
  `FacultyID` varchar(250) DEFAULT NULL,
  `FullName` varchar(250) DEFAULT NULL,
  `name` varchar(250) NOT NULL,
  `LastName` varchar(250) NOT NULL,
  `email` varchar(250) DEFAULT NULL,
  `MobileNumber` varchar(11) DEFAULT NULL,
  `Password` varchar(250) DEFAULT NULL,
  `Status` int(1) DEFAULT 1,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblfaculty`
--

INSERT INTO `tblfaculty` (`id`, `FacultyID`, `FullName`, `name`, `LastName`, `email`, `MobileNumber`, `Password`, `Status`, `RegDate`, `UpdationDate`) VALUES
(2, 'JBESTF101', 'Marlon Alegado', 'Marlon', 'Alegado', 'marlon@gmail.com', '948221123', '202cb962ac59075b964b07152d234b70', 1, '2024-11-15 08:55:58', '2024-12-02 11:38:21'),
(3, 'JBESTF102', 'Jonathan Pagurayan', 'Jonathan', 'Pagurayan', 'pagurayan@gmail.com', '2147483647', 'caf1a3dfb505ffed0d024130f58c5cfa', 1, '2024-11-30 02:48:53', '2024-12-02 05:14:44'),
(4, 'JBESTF103', 'Chris Heruela', 'Chris', 'Heruela', 'heruela@gmail.com', '09213131123', '202cb962ac59075b964b07152d234b70', 1, '2024-11-30 08:47:39', '2024-12-02 05:15:01');

-- --------------------------------------------------------

--
-- Table structure for table `tblissuedbookdetails`
--

CREATE TABLE `tblissuedbookdetails` (
  `id` int(11) NOT NULL,
  `BookID` int(11) DEFAULT NULL,
  `StudentID` varchar(100) NOT NULL,
  `FacultyID` varchar(100) NOT NULL,
  `IssueDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `ReturnDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `ReturnStatus` int(1) NOT NULL DEFAULT 0,
  `studorfac` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblissuedbookdetails`
--

INSERT INTO `tblissuedbookdetails` (`id`, `BookID`, `StudentID`, `FacultyID`, `IssueDate`, `ReturnDate`, `ReturnStatus`, `studorfac`) VALUES
(85, 137909101, 'JBEST105', '', '2024-11-30 00:24:47', '2024-11-29 18:35:00', 1, 1),
(86, 137909101, 'JBEST105', '', '2024-11-30 00:24:56', '2024-11-29 17:59:00', 1, 1),
(87, 137909101, 'JBEST102', '', '2024-11-30 00:25:04', '2024-12-02 03:54:33', 1, 1),
(89, 2147483647, 'JBEST101', '', '2024-11-17 00:28:35', '2024-12-02 03:57:42', 0, 1),
(90, 1423241886, 'JBEST105', '', '2024-11-30 01:17:48', '2024-12-02 03:53:38', 1, 1),
(91, 1423241886, 'JBEST102', '', '2024-11-30 01:17:57', '2024-11-29 19:08:00', 1, 1),
(92, 1646113195, 'JBEST105', '', '2024-11-30 01:18:06', NULL, 0, 1),
(93, 1647398703, 'JBEST105', '', '2024-11-30 01:18:23', '2024-12-01 20:59:00', 1, 1),
(94, 744098564, 'JBEST105', '', '2024-11-30 01:18:35', NULL, 0, 1),
(95, 1423241886, 'JBEST105', '', '2024-11-30 01:18:42', '2024-12-01 20:58:00', 1, 1),
(96, 1465473637, '', 'JBESTF101', '2024-11-30 02:05:19', '2024-11-30 10:33:00', 1, 0),
(97, 1423241886, '', 'JBESTF101', '2024-11-30 02:05:30', NULL, 0, 0),
(98, 1646113195, '', 'JBESTF101', '2024-11-30 02:05:45', NULL, 0, 0),
(99, 1647398703, '', 'JBESTF101', '2024-11-30 02:05:59', NULL, 0, 0),
(100, 137909101, '', 'JBESTF101', '2024-11-30 02:06:07', NULL, 0, 0),
(101, 137909101, '', 'JBESTF101', '2024-11-30 02:06:17', NULL, 0, 0),
(102, 137909101, '', 'JBESTF101', '2024-11-17 02:06:26', '2024-12-02 04:16:08', 0, 0),
(103, 201485419, 'JBEST105', '', '2024-11-29 19:07:32', NULL, 0, 1),
(104, 1647398703, '', 'JBESTF101', '2024-12-01 01:47:49', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblrequestedbookdetails`
--

CREATE TABLE `tblrequestedbookdetails` (
  `studfacid` varchar(250) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `BookName` varchar(250) NOT NULL,
  `CategoryName` varchar(250) NOT NULL,
  `AuthorName` varchar(250) NOT NULL,
  `BookNumber` int(11) NOT NULL,
  `RequestDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `id` int(11) NOT NULL,
  `entity` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblrequestedbookdetails`
--

INSERT INTO `tblrequestedbookdetails` (`studfacid`, `Name`, `BookName`, `CategoryName`, `AuthorName`, `BookNumber`, `RequestDate`, `id`, `entity`) VALUES
('JBEST105', 'Marck gonzalez', 'Code: The Hidden Language of Computer Hardware and Software', 'Technology', 'Charles Petzold', 137909101, '2024-11-18 09:21:59', 3, 1),
('JBEST105', 'Marck gonzalez', 'English Grammar in Use Book with Answers: A Self-Study Reference and Practice Book for Intermediate Learners of English', 'English', 'Raymond Murphy', 1108457657, '2024-11-30 17:25:13', 5, 1),
('JBEST105', 'Marck gonzalez', 'English for Everyone Course Book Level 1 Beginner: A Complete Self-Study Program ', 'English', 'DK', 744098564, '2024-11-30 18:08:07', 6, 1),
('JBEST105', 'Marck gonzalez', 'Python Programming Language', 'Technology', 'Berajah Jayne', 1423241886, '2024-11-30 18:30:53', 7, 1),
('JBEST105', 'Marck gonzalez', 'Python Programming Language', 'Technology', 'Berajah Jayne', 1423241886, '2024-11-30 18:32:16', 8, 1),
('JBESTF101', 'Marlon alegado', 'The Fascinating Science Book for Kids: 500 Amazing Facts!', 'Science', 'Kevin Kurtz MA', 1647398703, '2024-12-01 01:37:49', 15, 0),
('JBESTF101', 'Marlon alegado', 'Python Programming Language', 'Technology', 'Berajah Jayne', 1423241886, '2024-12-01 01:50:42', 18, 0),
('JBESTF101', 'Marlon alegado', 'Python Programming Language', 'Technology', 'Berajah Jayne', 1423241886, '2024-12-01 01:51:18', 19, 0),
('JBESTF102', 'Jonathan Pagurayan', 'Python Programming Language', 'Technology', 'Berajah Jayne', 1423241886, '2024-12-01 01:58:32', 20, 0),
('JBEST105', 'Marck gonzalez', 'Knowledge Encyclopedia Science!', 'Science', 'DK', 1465473637, '2024-12-01 01:59:35', 21, 1),
('JBESTF102', 'Jonathan Pagurayan', 'Knowledge Encyclopedia Science!', 'Science', 'DK', 1465473637, '2024-12-01 02:00:22', 22, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblstudents`
--

CREATE TABLE `tblstudents` (
  `id` int(11) NOT NULL,
  `StudentID` varchar(100) NOT NULL,
  `FullName` varchar(250) DEFAULT NULL,
  `Name` varchar(250) NOT NULL,
  `LastName` varchar(250) NOT NULL,
  `email` varchar(250) DEFAULT NULL,
  `MobileNumber` varchar(11) DEFAULT NULL,
  `Password` varchar(250) DEFAULT NULL,
  `Status` int(1) DEFAULT 1,
  `RegDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblstudents`
--

INSERT INTO `tblstudents` (`id`, `StudentID`, `FullName`, `Name`, `LastName`, `email`, `MobileNumber`, `Password`, `Status`, `RegDate`, `UpdationDate`) VALUES
(1, 'JBEST101', 'Joshua Yeung', 'Joshua', 'Yeung', 'josh@gmail.com', '11111111111', '123', 1, '2024-11-15 07:24:09', '2024-12-02 05:14:29'),
(2, 'JBEST102', 'Melgene Bagotchay', 'Melgene', 'Bagotchay', 'Mel@gmail.com', '09664392100', '321', 1, '2024-11-15 07:25:21', '2024-12-02 05:14:12'),
(3, 'JBEST103', 'Adrian Pandaan', 'Adrian', 'Pandaan', 'ed@gmail.com', '0966433250', '111', 1, '2024-11-15 07:26:03', '2024-12-02 05:14:22'),
(4, 'JBEST104', 'Louisse Bertillo', 'Louisse', 'Bertillo', 'louisse@gmail.com', '09482139251', '222', 1, '2024-11-15 07:26:35', '2024-12-02 05:14:01'),
(5, 'JBEST105', 'Marck Gonzalez', 'Marck', 'Gonzalez', 'marck@gmail.com', '0913049819', '202cb962ac59075b964b07152d234b70', 1, '2024-11-15 12:25:52', '2024-12-02 05:45:12'),
(6, 'JBEST106', 'Chrisian Eclipse', 'Christian', 'Eclipse', '123@gmail.com', '09603126788', '81dc9bdb52d04dc20036dbd8313ed055', 1, '2024-11-15 12:34:33', '2024-12-02 05:13:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tblauthors`
--
ALTER TABLE `tblauthors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblbooks`
--
ALTER TABLE `tblbooks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblfaculty`
--
ALTER TABLE `tblfaculty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblissuedbookdetails`
--
ALTER TABLE `tblissuedbookdetails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblrequestedbookdetails`
--
ALTER TABLE `tblrequestedbookdetails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblstudents`
--
ALTER TABLE `tblstudents`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblauthors`
--
ALTER TABLE `tblauthors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tblbooks`
--
ALTER TABLE `tblbooks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tblfaculty`
--
ALTER TABLE `tblfaculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tblissuedbookdetails`
--
ALTER TABLE `tblissuedbookdetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `tblrequestedbookdetails`
--
ALTER TABLE `tblrequestedbookdetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tblstudents`
--
ALTER TABLE `tblstudents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
