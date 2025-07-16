-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 16, 2025 at 04:44 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dentalign`
--

-- --------------------------------------------------------

--
-- Table structure for table `Appointment`
--

CREATE TABLE `Appointment` (
  `AppointmentID` int(11) NOT NULL,
  `PatientID` int(11) NOT NULL,
  `DoctorID` int(11) NOT NULL,
  `DateTime` datetime NOT NULL,
  `AppointmentType` varchar(100) DEFAULT NULL,
  `Reason` text DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `Status` enum('Pending','Approved','Declined','Completed','Rescheduled','Cancelled','Pending Cancellation') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Appointment`
--

INSERT INTO `Appointment` (`AppointmentID`, `PatientID`, `DoctorID`, `DateTime`, `AppointmentType`, `Reason`, `CreatedAt`, `Status`) VALUES
(1, 1, 2, '2025-06-22 08:00:00', 'Consultation', 'Lets gooo hello world', '2025-06-20 16:00:18', 'Completed'),
(8, 1, 3, '2025-06-26 10:00:00', 'Cleaning', 'asgagsagagasga', '2025-06-23 11:54:20', 'Completed'),
(22, 1, 3, '2025-07-16 13:00:00', 'Consultation', 'hello world hehehe', '2025-06-25 13:46:50', 'Rescheduled'),
(23, 1, 3, '2025-06-27 09:00:00', 'Cleaning', 'second book of the day lets go', '2025-06-25 13:47:11', 'Completed'),
(24, 1, 3, '2025-06-30 08:00:00', 'Consultation', 'This ia atestetest', '2025-06-27 14:44:47', 'Completed'),
(25, 1, 3, '2025-07-07 09:00:00', 'Cleaning', 'Hello worldd', '2025-06-27 14:45:26', 'Completed'),
(26, 1, 3, '2025-06-29 09:00:00', 'Cleaning', 'sdgdahdasjndsjsj', '2025-06-27 14:54:46', 'Completed'),
(30, 1, 3, '2025-07-03 08:00:00', 'Consultation', 'hello world pls work', '2025-07-01 14:28:12', 'Cancelled'),
(31, 1, 3, '2025-07-11 08:00:00', 'Consultation', 'bsaofbaoifhaifia', '2025-07-09 03:02:36', 'Cancelled'),
(32, 1, 3, '2025-07-11 09:00:00', 'Cleaning', 'astahyahyadhadh', '2025-07-09 07:24:23', 'Completed'),
(33, 1, 2, '2025-07-12 08:00:00', 'Consultation', 'Hello world welcome', '2025-07-10 00:44:17', 'Completed'),
(34, 1, 3, '2025-07-14 08:00:00', 'Consultation', 'Test Appointment 1', '2025-07-10 02:23:34', 'Completed'),
(35, 1, 3, '2025-07-12 10:00:00', 'Cleaning', 'Test Appointment 2', '2025-07-10 02:23:43', 'Completed'),
(36, 1, 3, '2025-07-12 12:00:00', 'Filling', 'Test Appointment 1', '2025-07-10 02:23:53', 'Cancelled'),
(37, 1, 2, '2025-07-12 08:00:00', 'Consultation', 'hello world hadga', '2025-07-10 02:39:54', 'Completed'),
(38, 1, 2, '2025-07-12 09:00:00', 'Emergency', 'EMERGENCYYY', '2025-07-10 02:40:23', 'Pending Cancellation'),
(39, 1, 2, '2025-07-14 08:00:00', 'Consultation', 'adasfsafafaf', '2025-07-12 11:41:48', 'Completed'),
(40, 1, 2, '2025-07-14 09:00:00', 'Consultation', 'asdsafsafsaf', '2025-07-12 11:44:53', 'Completed'),
(41, 1, 3, '2025-07-15 08:00:00', 'Consultation', 'adadadadsadasd', '2025-07-13 07:30:36', 'Pending Cancellation'),
(42, 14, 3, '2025-07-26 17:00:00', 'Cleaning', 'yeoahawihdaw', '2025-07-16 02:02:05', 'Pending'),
(43, 14, 3, '2025-07-25 17:00:00', 'Root Canal', 'dwaihdhauhuwuw', '2025-07-16 02:05:42', 'Pending'),
(44, 14, 3, '2025-07-19 15:00:00', 'Filling', 'ylyufygugyuygugyu', '2025-07-16 02:19:36', 'Pending'),
(45, 14, 3, '2025-07-18 15:00:00', 'Emergency', 'osiaciasdaidhawidha', '2025-07-16 02:31:26', 'Pending'),
(46, 1, 3, '2025-07-18 10:00:00', 'Emergency', 'asdafasfsadad', '2025-07-16 06:54:49', 'Pending'),
(47, 15, 2, '2025-07-30 08:00:00', 'Emergency', 'EMERGENCYYYYY', '2025-07-16 08:33:51', 'Approved'),
(48, 1, 3, '2025-07-20 10:00:00', 'Consultation', 'Test appointment for debugging', '2025-07-16 11:04:23', 'Pending Cancellation'),
(49, 1, 3, '2025-07-20 08:00:00', 'Consultation', 'Test appointment - Consultation', '2025-07-16 11:05:05', 'Pending Cancellation'),
(50, 1, 3, '2025-07-20 09:00:00', 'Cleaning', 'Test appointment - Cleaning', '2025-07-16 11:05:05', 'Pending Cancellation'),
(51, 1, 3, '2025-07-21 08:00:00', 'Follow up', 'Test appointment - Follow up', '2025-07-16 11:05:05', 'Pending Cancellation'),
(52, 1, 3, '2025-07-18 09:00:00', 'Filling', 'asfdasfasfaf', '2025-07-16 11:21:41', 'Pending'),
(53, 1, 3, '2025-07-18 11:00:00', 'Root Canal', 'tqetqtgetgfsgs', '2025-07-16 11:26:06', 'Pending'),
(54, 1, 3, '2025-07-18 08:00:00', 'Root Canal', 'asfagyagafgasga', '2025-07-16 11:26:35', 'Pending'),
(55, 1, 2, '2025-07-18 08:00:00', 'Emergency', 'asfagaegaegag', '2025-07-16 11:26:50', 'Approved'),
(56, 1, 3, '2025-07-18 13:00:00', 'Hello', 'sdgbsgsgsgg', '2025-07-16 11:36:31', 'Pending'),
(57, 1, 3, '2025-07-22 10:00:00', 'Hello', 'asfdasgvsafsa', '2025-07-16 14:13:06', 'Pending'),
(58, 16, 3, '2025-07-18 12:00:00', 'Checkup', 'I need checkupp', '2025-07-16 14:40:08', 'Pending'),
(59, 16, 2, '2025-07-20 08:00:00', 'Cleaning', 'Dr. Matthewww', '2025-07-16 14:40:27', 'Pending');

--
-- Triggers `Appointment`
--
DELIMITER $$
CREATE TRIGGER `create_appointment_report_after_appointment_insert` AFTER INSERT ON `Appointment` FOR EACH ROW BEGIN
    DECLARE patient_record_id INT;
    
    -- Get the PatientRecord ID for the patient
    SELECT RecordID INTO patient_record_id 
    FROM PatientRecord 
    WHERE PatientID = NEW.PatientID 
    LIMIT 1;
    
    -- Insert AppointmentReport
    INSERT INTO AppointmentReport (PatientRecordID, AppointmentID, OralNotes, Diagnosis, XrayImages)
    VALUES (patient_record_id, NEW.AppointmentID, NULL, NULL, NULL);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `delete_appointment_report_after_appointment_delete` AFTER DELETE ON `Appointment` FOR EACH ROW BEGIN
    DELETE FROM AppointmentReport WHERE AppointmentID = OLD.AppointmentID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `AppointmentReport`
--

CREATE TABLE `AppointmentReport` (
  `AppointmentReportID` int(11) NOT NULL,
  `PatientRecordID` int(11) NOT NULL,
  `AppointmentID` int(11) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `OralNotes` text DEFAULT NULL,
  `Diagnosis` text DEFAULT NULL,
  `XrayImages` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `AppointmentReport`
--

INSERT INTO `AppointmentReport` (`AppointmentReportID`, `PatientRecordID`, `AppointmentID`, `CreatedAt`, `OralNotes`, `Diagnosis`, `XrayImages`) VALUES
(1, 1, 1, '2025-06-24 00:31:51', 'adadadad', '', ''),
(4, 1, 8, '2025-06-24 00:31:51', NULL, NULL, NULL),
(31, 1, 22, '2025-06-25 13:46:50', NULL, NULL, NULL),
(32, 1, 23, '2025-06-25 13:47:11', NULL, NULL, NULL),
(33, 1, 24, '2025-06-27 14:44:47', NULL, NULL, NULL),
(34, 1, 25, '2025-06-27 14:45:26', '', '', ''),
(35, 1, 26, '2025-06-27 14:54:46', NULL, NULL, NULL),
(36, 1, 30, '2025-07-01 14:28:12', '', 'Hello world', ''),
(37, 1, 31, '2025-07-09 03:02:36', '', '', ''),
(38, 1, 32, '2025-07-09 07:24:23', '', 'asdgtsdhshs', ''),
(39, 1, 33, '2025-07-10 00:44:17', '\n\n[7/16/2025, 5:08:18 PM] Doctor changed from Jeane  Diputado to Dr. Matthew Angelo Lumayno - General Dentistry because of: afguaighaa', '', ''),
(40, 1, 34, '2025-07-10 02:23:34', '', '', ''),
(41, 1, 35, '2025-07-10 02:23:43', '', '', ''),
(42, 1, 36, '2025-07-10 02:23:53', NULL, NULL, NULL),
(43, 1, 37, '2025-07-10 02:39:54', '', '', ''),
(44, 1, 38, '2025-07-10 02:40:23', NULL, NULL, NULL),
(45, 1, 39, '2025-07-12 11:41:48', '', '', ''),
(46, 1, 40, '2025-07-12 11:44:53', 'adadadad', '', ''),
(47, 1, 41, '2025-07-13 07:30:36', NULL, NULL, NULL),
(48, 31, 42, '2025-07-16 02:02:05', NULL, NULL, NULL),
(49, 31, 43, '2025-07-16 02:05:42', NULL, NULL, NULL),
(50, 31, 44, '2025-07-16 02:19:36', NULL, NULL, NULL),
(51, 31, 45, '2025-07-16 02:31:26', NULL, NULL, NULL),
(52, 1, 46, '2025-07-16 06:54:49', NULL, NULL, NULL),
(53, 32, 47, '2025-07-16 08:33:51', '', '', ''),
(54, 1, 48, '2025-07-16 11:04:23', NULL, NULL, NULL),
(55, 1, 49, '2025-07-16 11:05:05', NULL, NULL, NULL),
(56, 1, 50, '2025-07-16 11:05:05', NULL, NULL, NULL),
(57, 1, 51, '2025-07-16 11:05:05', NULL, NULL, NULL),
(58, 1, 52, '2025-07-16 11:21:41', NULL, NULL, NULL),
(59, 1, 53, '2025-07-16 11:26:06', NULL, NULL, NULL),
(60, 1, 54, '2025-07-16 11:26:35', NULL, NULL, NULL),
(61, 1, 55, '2025-07-16 11:26:50', '', '', ''),
(62, 1, 56, '2025-07-16 11:36:31', NULL, NULL, NULL),
(63, 1, 57, '2025-07-16 14:13:06', NULL, NULL, NULL),
(64, 33, 58, '2025-07-16 14:40:08', NULL, NULL, NULL),
(65, 33, 59, '2025-07-16 14:40:27', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blocked_slots`
--

CREATE TABLE `blocked_slots` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `blocked_date` date NOT NULL,
  `blocked_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blocked_slots`
--

INSERT INTO `blocked_slots` (`id`, `doctor_id`, `blocked_date`, `blocked_time`, `created_at`) VALUES
(3, 3, '2025-07-25', '17:00:00', '2025-07-16 02:02:36'),
(4, 3, '2025-07-19', '08:00:00', '2025-07-16 02:16:50'),
(5, 3, '2025-07-19', '15:00:00', '2025-07-16 02:16:50'),
(6, 3, '2025-07-18', '17:00:00', '2025-07-16 02:30:20'),
(7, 3, '2025-07-17', '08:00:00', '2025-07-16 02:31:02'),
(8, 3, '2025-07-23', '11:00:00', '2025-07-16 08:29:34'),
(9, 3, '2025-07-23', '13:00:00', '2025-07-16 08:29:34'),
(10, 3, '2025-07-23', '15:00:00', '2025-07-16 08:29:34'),
(11, 3, '2025-07-23', '17:00:00', '2025-07-16 08:29:34'),
(12, 3, '2025-07-16', '15:00:00', '2025-07-16 08:57:35'),
(13, 3, '2025-07-16', '17:00:00', '2025-07-16 08:57:35'),
(14, 2, '2025-07-30', '10:00:00', '2025-07-16 10:54:41');

-- --------------------------------------------------------

--
-- Table structure for table `CLINIC_STAFF`
--

CREATE TABLE `CLINIC_STAFF` (
  `ClinicStaffID` int(11) NOT NULL,
  `StaffType` varchar(100) NOT NULL COMMENT 'e.g., Doctor, Nurse, Receptionist, Admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `CLINIC_STAFF`
--

INSERT INTO `CLINIC_STAFF` (`ClinicStaffID`, `StaffType`) VALUES
(2, 'Doctor'),
(3, 'Doctor'),
(4, 'DentalAssistant');

-- --------------------------------------------------------

--
-- Table structure for table `DentalChartItem`
--

CREATE TABLE `DentalChartItem` (
  `DentalChartItemID` int(11) NOT NULL,
  `DentalChartID` int(11) NOT NULL,
  `ToothNumber` varchar(5) NOT NULL,
  `Status` text DEFAULT NULL,
  `Notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `DentalChartItem`
--

INSERT INTO `DentalChartItem` (`DentalChartItemID`, `DentalChartID`, `ToothNumber`, `Status`, `Notes`) VALUES
(33, 1, '1', 'Healthy', ''),
(34, 1, '2', 'Healthy', ''),
(35, 1, '3', 'Healthy', ''),
(36, 1, '4', 'Healthy', ''),
(37, 1, '5', 'Healthy', ''),
(38, 1, '6', 'Healthy', ''),
(39, 1, '7', 'Healthy', ''),
(40, 1, '8', 'Healthy', ''),
(41, 1, '9', 'Healthy', ''),
(42, 1, '10', 'Healthy', ''),
(43, 1, '11', 'Healthy', ''),
(44, 1, '12', 'Healthy', ''),
(45, 1, '13', 'Healthy', ''),
(46, 1, '14', 'Healthy', ''),
(47, 1, '15', '', ''),
(48, 1, '16', '', ''),
(49, 1, '17', 'Healthy', ''),
(50, 1, '18', 'Healthy', ''),
(51, 1, '19', 'Healthy', ''),
(52, 1, '20', 'Healthy', ''),
(53, 1, '21', 'Healthy', ''),
(54, 1, '22', 'Healthy', ''),
(55, 1, '23', 'Treatment Needed', ''),
(56, 1, '24', 'Watch', ''),
(57, 1, '25', 'Healthy', ''),
(58, 1, '26', 'Healthy', ''),
(59, 1, '27', 'Watch', ''),
(60, 1, '28', 'Healthy', ''),
(61, 1, '29', 'Healthy', ''),
(62, 1, '30', 'Healthy', ''),
(63, 1, '31', 'Healthy', ''),
(64, 1, '32', 'Healthy', ''),
(65, 2, '1', NULL, NULL),
(66, 2, '2', NULL, NULL),
(67, 2, '3', NULL, NULL),
(68, 2, '4', NULL, NULL),
(69, 2, '5', NULL, NULL),
(70, 2, '6', NULL, NULL),
(71, 2, '7', NULL, NULL),
(72, 2, '8', NULL, NULL),
(73, 2, '9', NULL, NULL),
(74, 2, '10', NULL, NULL),
(75, 2, '11', NULL, NULL),
(76, 2, '12', NULL, NULL),
(77, 2, '13', NULL, NULL),
(78, 2, '14', NULL, NULL),
(79, 2, '15', NULL, NULL),
(80, 2, '16', NULL, NULL),
(81, 2, '17', NULL, NULL),
(82, 2, '18', NULL, NULL),
(83, 2, '19', NULL, NULL),
(84, 2, '20', NULL, NULL),
(85, 2, '21', NULL, NULL),
(86, 2, '22', NULL, NULL),
(87, 2, '23', NULL, NULL),
(88, 2, '24', NULL, NULL),
(89, 2, '25', NULL, NULL),
(90, 2, '26', NULL, NULL),
(91, 2, '27', NULL, NULL),
(92, 2, '28', NULL, NULL),
(93, 2, '29', NULL, NULL),
(94, 2, '30', NULL, NULL),
(95, 2, '31', NULL, NULL),
(96, 2, '32', NULL, NULL),
(97, 3, '1', NULL, NULL),
(98, 3, '2', NULL, NULL),
(99, 3, '3', NULL, NULL),
(100, 3, '4', NULL, NULL),
(101, 3, '5', NULL, NULL),
(102, 3, '6', NULL, NULL),
(103, 3, '7', NULL, NULL),
(104, 3, '8', NULL, NULL),
(105, 3, '9', NULL, NULL),
(106, 3, '10', NULL, NULL),
(107, 3, '11', NULL, NULL),
(108, 3, '12', NULL, NULL),
(109, 3, '13', NULL, NULL),
(110, 3, '14', NULL, NULL),
(111, 3, '15', NULL, NULL),
(112, 3, '16', NULL, NULL),
(113, 3, '17', NULL, NULL),
(114, 3, '18', NULL, NULL),
(115, 3, '19', NULL, NULL),
(116, 3, '20', NULL, NULL),
(117, 3, '21', NULL, NULL),
(118, 3, '22', NULL, NULL),
(119, 3, '23', NULL, NULL),
(120, 3, '24', NULL, NULL),
(121, 3, '25', NULL, NULL),
(122, 3, '26', NULL, NULL),
(123, 3, '27', NULL, NULL),
(124, 3, '28', NULL, NULL),
(125, 3, '29', NULL, NULL),
(126, 3, '30', NULL, NULL),
(127, 3, '31', NULL, NULL),
(128, 3, '32', NULL, NULL),
(129, 4, '1', 'Healthy', ''),
(130, 4, '2', 'Healthy', ''),
(131, 4, '3', 'Healthy', ''),
(132, 4, '4', 'Healthy', ''),
(133, 4, '5', 'Healthy', ''),
(134, 4, '6', 'Healthy', ''),
(135, 4, '7', 'Treatment Needed', 'dada'),
(136, 4, '8', 'Treatment Needed', 'Cavity'),
(137, 4, '9', 'Healthy', ''),
(138, 4, '10', 'Healthy', ''),
(139, 4, '11', 'Healthy', ''),
(140, 4, '12', 'Healthy', ''),
(141, 4, '13', 'Healthy', ''),
(142, 4, '14', 'Healthy', ''),
(143, 4, '15', 'Healthy', ''),
(144, 4, '16', 'Healthy', ''),
(145, 4, '17', 'Healthy', ''),
(146, 4, '18', 'Healthy', ''),
(147, 4, '19', 'Healthy', ''),
(148, 4, '20', 'Healthy', ''),
(149, 4, '21', 'Healthy', ''),
(150, 4, '22', 'Healthy', ''),
(151, 4, '23', 'Healthy', ''),
(152, 4, '24', 'Healthy', ''),
(153, 4, '25', 'Healthy', ''),
(154, 4, '26', 'Healthy', ''),
(155, 4, '27', 'Healthy', ''),
(156, 4, '28', 'Healthy', ''),
(157, 4, '29', 'Healthy', ''),
(158, 4, '30', 'Healthy', ''),
(159, 4, '31', 'Healthy', ''),
(160, 4, '32', 'Healthy', ''),
(161, 5, '1', NULL, NULL),
(162, 5, '2', NULL, NULL),
(163, 5, '3', NULL, NULL),
(164, 5, '4', NULL, NULL),
(165, 5, '5', NULL, NULL),
(166, 5, '6', NULL, NULL),
(167, 5, '7', NULL, NULL),
(168, 5, '8', NULL, NULL),
(169, 5, '9', NULL, NULL),
(170, 5, '10', NULL, NULL),
(171, 5, '11', NULL, NULL),
(172, 5, '12', NULL, NULL),
(173, 5, '13', NULL, NULL),
(174, 5, '14', NULL, NULL),
(175, 5, '15', NULL, NULL),
(176, 5, '16', NULL, NULL),
(177, 5, '17', NULL, NULL),
(178, 5, '18', NULL, NULL),
(179, 5, '19', NULL, NULL),
(180, 5, '20', NULL, NULL),
(181, 5, '21', NULL, NULL),
(182, 5, '22', NULL, NULL),
(183, 5, '23', NULL, NULL),
(184, 5, '24', NULL, NULL),
(185, 5, '25', NULL, NULL),
(186, 5, '26', NULL, NULL),
(187, 5, '27', NULL, NULL),
(188, 5, '28', NULL, NULL),
(189, 5, '29', NULL, NULL),
(190, 5, '30', NULL, NULL),
(191, 5, '31', NULL, NULL),
(192, 5, '32', NULL, NULL),
(193, 6, '1', NULL, NULL),
(194, 6, '2', NULL, NULL),
(195, 6, '3', NULL, NULL),
(196, 6, '4', NULL, NULL),
(197, 6, '5', NULL, NULL),
(198, 6, '6', NULL, NULL),
(199, 6, '7', NULL, NULL),
(200, 6, '8', NULL, NULL),
(201, 6, '9', NULL, NULL),
(202, 6, '10', NULL, NULL),
(203, 6, '11', NULL, NULL),
(204, 6, '12', NULL, NULL),
(205, 6, '13', NULL, NULL),
(206, 6, '14', NULL, NULL),
(207, 6, '15', NULL, NULL),
(208, 6, '16', NULL, NULL),
(209, 6, '17', NULL, NULL),
(210, 6, '18', NULL, NULL),
(211, 6, '19', NULL, NULL),
(212, 6, '20', NULL, NULL),
(213, 6, '21', NULL, NULL),
(214, 6, '22', NULL, NULL),
(215, 6, '23', NULL, NULL),
(216, 6, '24', NULL, NULL),
(217, 6, '25', NULL, NULL),
(218, 6, '26', NULL, NULL),
(219, 6, '27', NULL, NULL),
(220, 6, '28', NULL, NULL),
(221, 6, '29', NULL, NULL),
(222, 6, '30', NULL, NULL),
(223, 6, '31', NULL, NULL),
(224, 6, '32', NULL, NULL),
(225, 7, '1', NULL, NULL),
(226, 7, '2', NULL, NULL),
(227, 7, '3', NULL, NULL),
(228, 7, '4', NULL, NULL),
(229, 7, '5', NULL, NULL),
(230, 7, '6', NULL, NULL),
(231, 7, '7', NULL, NULL),
(232, 7, '8', NULL, NULL),
(233, 7, '9', NULL, NULL),
(234, 7, '10', NULL, NULL),
(235, 7, '11', NULL, NULL),
(236, 7, '12', NULL, NULL),
(237, 7, '13', NULL, NULL),
(238, 7, '14', NULL, NULL),
(239, 7, '15', NULL, NULL),
(240, 7, '16', NULL, NULL),
(241, 7, '17', NULL, NULL),
(242, 7, '18', NULL, NULL),
(243, 7, '19', NULL, NULL),
(244, 7, '20', NULL, NULL),
(245, 7, '21', NULL, NULL),
(246, 7, '22', NULL, NULL),
(247, 7, '23', NULL, NULL),
(248, 7, '24', NULL, NULL),
(249, 7, '25', NULL, NULL),
(250, 7, '26', NULL, NULL),
(251, 7, '27', NULL, NULL),
(252, 7, '28', NULL, NULL),
(253, 7, '29', NULL, NULL),
(254, 7, '30', NULL, NULL),
(255, 7, '31', NULL, NULL),
(256, 7, '32', NULL, NULL),
(257, 8, '1', 'Healthy', ''),
(258, 8, '2', 'Healthy', ''),
(259, 8, '3', 'Healthy', ''),
(260, 8, '4', 'Healthy', ''),
(261, 8, '5', 'Healthy', ''),
(262, 8, '6', 'Healthy', ''),
(263, 8, '7', 'Healthy', ''),
(264, 8, '8', 'Treatment Needed', 'Fillingas'),
(265, 8, '9', 'Healthy', ''),
(266, 8, '10', 'Healthy', ''),
(267, 8, '11', 'Healthy', ''),
(268, 8, '12', 'Healthy', ''),
(269, 8, '13', 'Healthy', ''),
(270, 8, '14', 'Healthy', ''),
(271, 8, '15', 'Healthy', ''),
(272, 8, '16', 'Healthy', ''),
(273, 8, '17', 'Healthy', ''),
(274, 8, '18', 'Healthy', ''),
(275, 8, '19', 'Healthy', ''),
(276, 8, '20', 'Healthy', ''),
(277, 8, '21', 'Healthy', ''),
(278, 8, '22', 'Healthy', ''),
(279, 8, '23', 'Healthy', ''),
(280, 8, '24', 'Healthy', ''),
(281, 8, '25', 'Healthy', ''),
(282, 8, '26', 'Healthy', ''),
(283, 8, '27', 'Healthy', ''),
(284, 8, '28', 'Healthy', ''),
(285, 8, '29', 'Healthy', ''),
(286, 8, '30', 'Healthy', ''),
(287, 8, '31', 'Healthy', ''),
(288, 8, '32', 'Healthy', '');

-- --------------------------------------------------------

--
-- Table structure for table `DentalCharts`
--

CREATE TABLE `DentalCharts` (
  `DentalChartID` int(11) NOT NULL,
  `PatientID` int(11) NOT NULL,
  `DentistID` int(11) DEFAULT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `DentalCharts`
--

INSERT INTO `DentalCharts` (`DentalChartID`, `PatientID`, `DentistID`, `CreatedAt`) VALUES
(1, 1, NULL, '2025-07-02 18:12:48'),
(2, 5, NULL, '2025-07-02 22:56:48'),
(3, 11, NULL, '2025-07-10 01:00:06'),
(4, 12, NULL, '2025-07-10 12:46:18'),
(5, 15, NULL, '2025-07-16 16:34:28'),
(6, 21, NULL, '2025-07-16 22:38:46'),
(7, 13, NULL, '2025-07-16 22:38:54'),
(8, 16, NULL, '2025-07-16 22:39:29');

-- --------------------------------------------------------

--
-- Table structure for table `Doctor`
--

CREATE TABLE `Doctor` (
  `DoctorID` int(11) NOT NULL,
  `Specialization` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Doctor`
--

INSERT INTO `Doctor` (`DoctorID`, `Specialization`) VALUES
(2, 'General Dentistry'),
(3, 'Orthodontics');

-- --------------------------------------------------------

--
-- Table structure for table `OverdueConfig`
--

CREATE TABLE `OverdueConfig` (
  `ConfigID` int(11) NOT NULL,
  `ConfigName` varchar(255) NOT NULL DEFAULT 'Default',
  `OverduePercentage` decimal(5,2) NOT NULL DEFAULT 5.00,
  `GracePeriodDays` int(11) NOT NULL DEFAULT 0,
  `IsActive` tinyint(1) NOT NULL DEFAULT 1,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `UpdatedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `OverdueConfig`
--

INSERT INTO `OverdueConfig` (`ConfigID`, `ConfigName`, `OverduePercentage`, `GracePeriodDays`, `IsActive`, `CreatedAt`, `UpdatedAt`, `UpdatedBy`) VALUES
(1, 'Default Overdue Settings', 5.00, 0, 0, '2025-07-11 06:19:13', '2025-07-11 06:24:59', NULL),
(2, 'Default Overdue Settings', 5.00, 0, 0, '2025-07-11 06:20:18', '2025-07-11 06:24:59', NULL),
(3, 'Default Overdue Settings', 7.00, 0, 0, '2025-07-11 06:24:59', '2025-07-11 07:11:16', 4),
(4, 'Default Overdue Settings', 5.00, 0, 0, '2025-07-11 07:11:16', '2025-07-16 13:36:29', 4),
(5, 'Default Overdue Settings', 10.00, 0, 0, '2025-07-16 13:36:29', '2025-07-16 13:36:45', 4),
(6, 'Default Overdue Settings', 9.00, 0, 0, '2025-07-16 13:36:45', '2025-07-16 13:38:32', 4),
(7, 'Default Overdue Settings', 7.00, 0, 0, '2025-07-16 13:38:32', '2025-07-16 13:40:12', 4),
(8, 'Default Overdue Settings', 6.00, 0, 0, '2025-07-16 13:40:12', '2025-07-16 13:40:52', 4),
(9, 'Default Overdue Settings', 7.00, 0, 0, '2025-07-16 13:40:52', '2025-07-16 13:42:29', 4),
(10, 'Default Overdue Settings', 6.00, 0, 0, '2025-07-16 13:42:29', '2025-07-16 13:53:37', 4),
(11, 'Default Overdue Settings', 7.00, 0, 1, '2025-07-16 13:53:37', '2025-07-16 13:53:37', 4);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `used_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`id`, `user_id`, `token`, `expires_at`, `created_at`, `used_at`) VALUES
(17, 1, '97aa303afc2baf581b202cb44d3725f411babcde26f0c27237a59c3685b694f8', '2025-07-09 17:04:06', '2025-07-09 16:04:06', '2025-07-09 16:04:50');

-- --------------------------------------------------------

--
-- Table structure for table `PATIENT`
--

CREATE TABLE `PATIENT` (
  `PatientID` int(11) NOT NULL,
  `InsuranceProvider` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `PATIENT`
--

INSERT INTO `PATIENT` (`PatientID`, `InsuranceProvider`) VALUES
(1, NULL),
(5, NULL),
(11, NULL),
(12, NULL),
(13, NULL),
(14, NULL),
(15, NULL),
(16, NULL),
(17, NULL),
(18, NULL),
(19, NULL),
(20, NULL),
(21, NULL),
(22, NULL),
(23, NULL),
(24, NULL),
(25, NULL),
(26, NULL),
(27, NULL),
(28, NULL),
(29, NULL);

--
-- Triggers `PATIENT`
--
DELIMITER $$
CREATE TRIGGER `create_patient_record_after_patient_insert` AFTER INSERT ON `PATIENT` FOR EACH ROW BEGIN
    INSERT INTO PatientRecord (PatientID, Height, Weight, Allergies, LastVisit)
    VALUES (NEW.PatientID, NULL, NULL, NULL, NULL);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `PatientRecord`
--

CREATE TABLE `PatientRecord` (
  `RecordID` int(11) NOT NULL,
  `PatientID` int(11) NOT NULL,
  `Height` decimal(5,2) DEFAULT NULL COMMENT 'e.g., in meters or feet',
  `Weight` decimal(5,2) DEFAULT NULL COMMENT 'e.g., in kg or lbs',
  `Allergies` text DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `LastVisit` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `PatientRecord`
--

INSERT INTO `PatientRecord` (`RecordID`, `PatientID`, `Height`, `Weight`, `Allergies`, `CreatedAt`, `LastVisit`) VALUES
(1, 1, 172.00, 70.00, 'Nothing', '2025-06-24 00:31:51', NULL),
(2, 5, 160.00, 58.00, NULL, '2025-06-24 00:31:51', NULL),
(28, 11, NULL, NULL, NULL, '2025-06-24 05:32:47', NULL),
(29, 12, NULL, NULL, NULL, '2025-07-10 04:45:48', NULL),
(30, 13, NULL, NULL, NULL, '2025-07-11 14:33:45', NULL),
(31, 14, NULL, NULL, NULL, '2025-07-16 01:59:46', NULL),
(32, 15, NULL, NULL, NULL, '2025-07-16 08:33:01', NULL),
(33, 16, 160.00, 48.00, 'Hellooo', '2025-07-16 14:15:44', NULL),
(34, 17, NULL, NULL, NULL, '2025-07-16 14:33:15', NULL),
(35, 18, NULL, NULL, NULL, '2025-07-16 14:33:42', NULL),
(36, 19, NULL, NULL, NULL, '2025-07-16 14:34:10', NULL),
(37, 20, NULL, NULL, NULL, '2025-07-16 14:35:35', NULL),
(38, 21, NULL, NULL, NULL, '2025-07-16 14:35:55', NULL),
(39, 22, NULL, NULL, NULL, '2025-07-16 14:36:25', NULL),
(40, 23, NULL, NULL, NULL, '2025-07-16 14:36:45', NULL),
(41, 24, NULL, NULL, NULL, '2025-07-16 14:37:14', NULL),
(42, 25, NULL, NULL, NULL, '2025-07-16 14:37:32', NULL),
(43, 26, NULL, NULL, NULL, '2025-07-16 14:38:06', NULL),
(44, 27, NULL, NULL, NULL, '2025-07-16 14:38:20', NULL),
(45, 28, NULL, NULL, NULL, '2025-07-16 14:43:09', NULL),
(46, 29, NULL, NULL, NULL, '2025-07-16 14:43:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `PaymentItems`
--

CREATE TABLE `PaymentItems` (
  `PaymentItemID` int(11) NOT NULL,
  `PaymentID` int(11) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `Quantity` int(11) NOT NULL DEFAULT 1,
  `Total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `TreatmentItemID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `PaymentItems`
--

INSERT INTO `PaymentItems` (`PaymentItemID`, `PaymentID`, `Description`, `Amount`, `Quantity`, `Total`, `CreatedAt`, `UpdatedAt`, `TreatmentItemID`) VALUES
(11, 9, 'General Consultation', 75.00, 1, 75.00, '2025-07-11 02:57:36', '2025-07-11 02:57:36', NULL),
(12, 10, 'Dental Cleaning', 120.00, 1, 120.00, '2025-07-11 02:57:36', '2025-07-11 02:57:36', NULL),
(13, 11, 'General Consultation', 75.00, 1, 75.00, '2025-07-11 02:57:36', '2025-07-11 02:57:36', NULL),
(14, 12, 'Dental Cleaning', 120.00, 1, 120.00, '2025-07-11 02:57:36', '2025-07-11 02:57:36', NULL),
(15, 13, 'General Consultation', 75.00, 1, 75.00, '2025-07-11 02:57:36', '2025-07-11 02:57:36', NULL),
(16, 14, 'Dental Cleaning', 120.00, 1, 120.00, '2025-07-11 02:57:36', '2025-07-11 02:57:36', NULL),
(17, 15, 'Dental Cleaning', 120.00, 1, 120.00, '2025-07-11 02:57:36', '2025-07-11 02:57:36', NULL),
(18, 16, 'General Consultation', 75.00, 1, 75.00, '2025-07-11 02:57:36', '2025-07-11 02:57:36', NULL),
(19, 17, 'General Consultation', 75.00, 1, 75.00, '2025-07-11 02:57:36', '2025-07-11 02:57:36', NULL),
(20, 18, 'Dental Cleaning', 120.00, 1, 120.00, '2025-07-11 02:57:36', '2025-07-11 02:57:36', NULL),
(21, 19, 'General Consultation', 75.00, 1, 75.00, '2025-07-11 02:57:36', '2025-07-11 02:57:36', NULL),
(22, 20, 'General Consultation', 75.00, 1, 75.00, '2025-07-11 02:57:36', '2025-07-11 02:57:36', NULL),
(23, 21, 'Dental Cleaning', 120.00, 1, 120.00, '2025-07-11 02:57:36', '2025-07-11 02:57:36', NULL),
(24, 22, 'Filling Service', 180.00, 1, 180.00, '2025-07-11 02:57:36', '2025-07-11 02:57:36', NULL),
(25, 23, 'General Consultation', 75.00, 1, 75.00, '2025-07-11 02:57:36', '2025-07-11 02:57:36', NULL),
(26, 24, 'Emergency Service', 100.00, 1, 100.00, '2025-07-11 02:57:36', '2025-07-11 02:57:36', NULL),
(32, 40, 'General Consultation', 75.00, 1, 75.00, '2025-07-12 11:41:48', '2025-07-12 11:41:48', NULL),
(33, 18, 'Topical application of fluoride', 25555.00, 1, 25555.00, '2025-07-12 11:43:40', '2025-07-12 11:43:40', 1),
(34, 41, 'General Consultation', 75.00, 1, 75.00, '2025-07-12 11:44:53', '2025-07-12 11:44:53', NULL),
(35, 42, 'General Consultation', 75.00, 1, 75.00, '2025-07-13 07:30:36', '2025-07-13 07:30:36', NULL),
(36, 43, 'Dental Cleaning', 120.00, 1, 120.00, '2025-07-16 02:02:05', '2025-07-16 02:02:05', NULL),
(37, 44, 'Root Canal Service', 850.00, 1, 850.00, '2025-07-16 02:05:42', '2025-07-16 02:05:42', NULL),
(38, 45, 'Filling Service', 180.00, 1, 180.00, '2025-07-16 02:19:36', '2025-07-16 02:19:36', NULL),
(39, 46, 'Emergency Service', 100.00, 1, 100.00, '2025-07-16 02:31:27', '2025-07-16 02:31:27', NULL),
(40, 47, 'Emergency Service', 100.00, 1, 100.00, '2025-07-16 06:54:49', '2025-07-16 06:54:49', NULL),
(41, 48, 'Emergency Service', 200.00, 1, 200.00, '2025-07-16 08:33:51', '2025-07-16 08:33:51', NULL),
(42, 18, 'Resin based composite stuff', 23455.00, 1, 23455.00, '2025-07-16 09:03:07', '2025-07-16 09:03:07', 2),
(43, 50, 'General Consultation', 75.00, 1, 75.00, '2025-07-16 11:05:26', '2025-07-16 11:05:26', NULL),
(44, 51, 'Dental Cleaning', 120.00, 1, 120.00, '2025-07-16 11:05:26', '2025-07-16 11:05:26', NULL),
(45, 52, 'Follow-up Visit', 50.00, 1, 50.00, '2025-07-16 11:05:26', '2025-07-16 11:05:26', NULL),
(46, 49, 'General Consultation', 75.00, 1, 75.00, '2025-07-16 11:05:26', '2025-07-16 11:05:26', NULL),
(47, 53, 'Filling Service', 180.00, 1, 180.00, '2025-07-16 11:21:41', '2025-07-16 11:21:41', NULL),
(48, 54, 'Root Canal Service', 850.00, 1, 850.00, '2025-07-16 11:26:06', '2025-07-16 11:26:06', NULL),
(49, 55, 'Root Canal Service', 10000.00, 1, 10000.00, '2025-07-16 11:26:35', '2025-07-16 11:26:35', NULL),
(50, 56, 'Emergency Service', 200.00, 1, 200.00, '2025-07-16 11:26:50', '2025-07-16 11:26:50', NULL),
(51, 57, 'Hello Service', 255.00, 1, 255.00, '2025-07-16 11:36:31', '2025-07-16 11:36:31', NULL),
(52, 53, 'Wowowow', 1200.00, 2, 2400.00, '2025-07-16 14:11:17', '2025-07-16 14:11:17', NULL),
(53, 58, 'Hello Service', 255.00, 1, 255.00, '2025-07-16 14:13:06', '2025-07-16 14:13:06', NULL),
(54, 59, 'Routine Checkup', 125.00, 1, 125.00, '2025-07-16 14:40:08', '2025-07-16 14:40:08', NULL),
(55, 60, 'Dental Cleaning', 1200.00, 1, 1200.00, '2025-07-16 14:40:27', '2025-07-16 14:40:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Payments`
--

CREATE TABLE `Payments` (
  `PaymentID` int(11) NOT NULL,
  `AppointmentID` int(11) NOT NULL,
  `PatientID` int(11) NOT NULL,
  `Status` enum('Pending','Paid','Failed','Refunded','Cancelled') NOT NULL DEFAULT 'Pending',
  `UpdatedBy` int(11) DEFAULT NULL,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Notes` text DEFAULT NULL,
  `DeadlineDate` date DEFAULT NULL,
  `PaymentMethod` varchar(50) DEFAULT 'Cash',
  `ProofOfPayment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Payments`
--

INSERT INTO `Payments` (`PaymentID`, `AppointmentID`, `PatientID`, `Status`, `UpdatedBy`, `UpdatedAt`, `Notes`, `DeadlineDate`, `PaymentMethod`, `ProofOfPayment`) VALUES
(9, 1, 1, 'Cancelled', 4, '2025-07-12 10:26:27', 'Payment cancelled by dental assistant', '2025-07-10', 'Bank Transfer', '43543637364364'),
(10, 8, 1, 'Paid', 4, '2025-07-16 11:52:07', 'Auto-created for existing appointment', '2025-07-12', 'Cash', NULL),
(11, 22, 1, 'Pending', NULL, '2025-07-11 02:54:01', 'Auto-created for existing appointment', '2025-07-27', 'Cash', NULL),
(12, 23, 1, 'Paid', 4, '2025-07-16 11:52:50', 'Auto-created for existing appointment', '2025-07-27', 'Cash', NULL),
(13, 24, 1, 'Paid', 4, '2025-07-16 11:53:17', NULL, '2025-07-30', 'Cash', NULL),
(14, 25, 1, 'Paid', 4, '2025-07-16 11:53:46', NULL, '2025-08-06', 'Cash', NULL),
(15, 26, 1, 'Paid', 4, '2025-07-16 11:53:11', NULL, '2025-07-29', 'Cash', NULL),
(16, 30, 1, 'Paid', 4, '2025-07-16 11:53:33', NULL, '2025-08-02', 'Cash', NULL),
(17, 31, 1, 'Paid', 4, '2025-07-16 11:53:49', NULL, '2025-08-10', 'Cash', NULL),
(18, 32, 1, 'Paid', 4, '2025-07-16 11:42:43', 'Auto-created for existing appointment', '2025-08-10', 'Cash', NULL),
(19, 33, 1, 'Paid', 4, '2025-07-16 11:53:53', NULL, '2025-08-11', 'Cash', NULL),
(20, 34, 1, 'Paid', 4, '2025-07-16 11:54:11', NULL, '2025-08-11', 'Cash', NULL),
(21, 35, 1, 'Paid', 4, '2025-07-16 11:54:03', NULL, '2025-08-11', 'Cash', NULL),
(22, 36, 1, 'Cancelled', NULL, '2025-07-12 12:03:29', 'Payment cancelled due to appointment cancellation', '2025-08-11', 'Cash', NULL),
(23, 37, 1, 'Paid', 4, '2025-07-16 11:53:57', NULL, '2025-08-11', 'Cash', NULL),
(24, 38, 1, 'Pending', 4, '2025-07-16 13:24:19', NULL, '2025-08-11', 'Cash', NULL),
(40, 39, 1, 'Paid', 4, '2025-07-16 11:54:29', NULL, '2025-08-13', 'Cash', ''),
(41, 40, 1, 'Paid', 4, '2025-07-16 11:54:15', NULL, '2025-08-13', 'Cash', ''),
(42, 41, 1, 'Pending', 4, '2025-07-16 13:24:14', NULL, '2025-08-14', 'Cash', ''),
(43, 42, 14, 'Paid', 4, '2025-07-16 08:41:48', NULL, '2025-08-25', 'Cash', ''),
(44, 43, 14, 'Paid', 4, '2025-07-16 12:42:13', 'Auto-created for new appointment', '2025-08-24', 'Cash', NULL),
(45, 44, 14, 'Pending', NULL, '2025-07-16 02:19:36', 'Auto-created for new appointment', '2025-08-18', 'Cash', ''),
(46, 45, 14, 'Pending', NULL, '2025-07-16 02:31:26', 'Auto-created for new appointment', '2025-08-17', 'Cash', ''),
(47, 46, 1, 'Pending', NULL, '2025-07-16 06:54:49', 'Auto-created for new appointment', '2025-08-17', 'Cash', ''),
(48, 47, 15, 'Cancelled', 4, '2025-07-16 09:07:26', 'Payment cancelled by dental assistant', '2025-08-29', 'Cash', NULL),
(49, 48, 1, 'Pending', NULL, '2025-07-16 11:04:23', 'Auto-created for new appointment', '2025-08-19', 'Cash', ''),
(50, 49, 1, 'Pending', NULL, '2025-07-16 11:05:05', 'Auto-created for new appointment', '2025-08-19', 'Cash', ''),
(51, 50, 1, 'Pending', NULL, '2025-07-16 11:05:05', 'Auto-created for new appointment', '2025-08-19', 'Cash', ''),
(52, 51, 1, 'Pending', NULL, '2025-07-16 11:05:05', 'Auto-created for new appointment', '2025-08-20', 'Cash', ''),
(53, 52, 1, 'Pending', 4, '2025-07-16 14:11:17', 'Auto-created for new appointment', '2025-07-15', 'Cash', NULL),
(54, 53, 1, 'Pending', NULL, '2025-07-16 11:26:06', 'Auto-created for new appointment', '2025-08-17', 'Cash', ''),
(55, 54, 1, 'Paid', 4, '2025-07-16 11:42:50', 'Auto-created for new appointment', '2025-08-17', 'Cash', NULL),
(56, 55, 1, 'Pending', NULL, '2025-07-16 11:26:50', 'Auto-created for new appointment', '2025-08-17', 'Cash', ''),
(57, 56, 1, 'Pending', NULL, '2025-07-16 11:36:31', 'Auto-created for new appointment', '2025-08-17', 'Cash', ''),
(58, 57, 1, 'Pending', NULL, '2025-07-16 14:13:06', 'Auto-created for new appointment', '2025-08-21', 'Cash', ''),
(59, 58, 16, 'Pending', NULL, '2025-07-16 14:40:08', 'Auto-created for new appointment', '2025-08-17', 'Cash', ''),
(60, 59, 16, 'Pending', NULL, '2025-07-16 14:40:27', 'Auto-created for new appointment', '2025-08-18', 'Cash', '');

-- --------------------------------------------------------

--
-- Table structure for table `ServicePrices`
--

CREATE TABLE `ServicePrices` (
  `ServicePriceID` int(11) NOT NULL,
  `ServiceName` varchar(100) NOT NULL,
  `ServicePrice` decimal(10,2) NOT NULL,
  `IsActive` tinyint(1) DEFAULT 1,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ServicePrices`
--

INSERT INTO `ServicePrices` (`ServicePriceID`, `ServiceName`, `ServicePrice`, `IsActive`, `CreatedAt`, `UpdatedAt`) VALUES
(1, 'Consultation', 75.00, 1, '2025-07-16 07:01:25', '2025-07-16 07:01:25'),
(2, 'Cleaning', 1200.00, 1, '2025-07-16 07:01:25', '2025-07-16 11:11:24'),
(3, 'Checkup', 125.00, 1, '2025-07-16 07:01:25', '2025-07-16 13:23:01'),
(4, 'Filling', 180.00, 1, '2025-07-16 07:01:25', '2025-07-16 07:01:25'),
(5, 'Root Canal', 10000.00, 0, '2025-07-16 07:01:25', '2025-07-16 11:56:14'),
(6, 'Extraction', 150.00, 1, '2025-07-16 07:01:25', '2025-07-16 07:01:25'),
(7, 'Orthodontics', 2500.00, 1, '2025-07-16 07:01:25', '2025-07-16 07:01:25'),
(8, 'Emergency', 200.00, 1, '2025-07-16 07:01:25', '2025-07-16 07:01:25'),
(9, 'Follow up', 50.00, 0, '2025-07-16 07:01:25', '2025-07-16 11:55:59'),
(10, 'Hello', 255.00, 1, '2025-07-16 11:10:50', '2025-07-16 11:11:12');

-- --------------------------------------------------------

--
-- Table structure for table `TreatmentPlan`
--

CREATE TABLE `TreatmentPlan` (
  `TreatmentPlanID` int(11) NOT NULL,
  `AppointmentReportID` int(11) NOT NULL,
  `Status` enum('pending','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending',
  `DentistNotes` text DEFAULT NULL,
  `AssignedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `TreatmentPlan`
--

INSERT INTO `TreatmentPlan` (`TreatmentPlanID`, `AppointmentReportID`, `Status`, `DentistNotes`, `AssignedAt`) VALUES
(1, 38, 'completed', 'Hello world', '2025-07-09 18:30:14'),
(3, 34, 'pending', 'asfnkaskfasf', '2025-07-16 11:01:56');

-- --------------------------------------------------------

--
-- Table structure for table `TreatmentPlanItem`
--

CREATE TABLE `TreatmentPlanItem` (
  `TreatmentItemID` int(11) NOT NULL,
  `TreatmentPlanID` int(11) NOT NULL,
  `ToothNumber` varchar(5) NOT NULL,
  `ProcedureCode` varchar(50) NOT NULL,
  `Description` text DEFAULT NULL,
  `Cost` decimal(10,2) NOT NULL,
  `ScheduledDate` date DEFAULT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `CompletedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `TreatmentPlanItem`
--

INSERT INTO `TreatmentPlanItem` (`TreatmentItemID`, `TreatmentPlanID`, `ToothNumber`, `ProcedureCode`, `Description`, `Cost`, `ScheduledDate`, `CreatedAt`, `CompletedAt`) VALUES
(1, 1, '1', 'D1208', 'Topical application of fluoride', 25555.00, '2025-07-11', '2025-07-10 00:30:14', '2025-07-12 11:43:40'),
(2, 1, '10', 'D2330', 'Resin based composite stuff', 23455.00, '2025-07-14', '2025-07-10 00:59:05', '2025-07-16 09:03:07'),
(4, 3, '10', 'D2160', 'Amalgam', 2300.00, '2025-07-17', '2025-07-16 17:01:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `USER`
--

CREATE TABLE `USER` (
  `UserID` int(11) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UserType` varchar(50) NOT NULL COMMENT 'e.g., Patient, ClinicStaff, Admin',
  `PasswordHash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `USER`
--

INSERT INTO `USER` (`UserID`, `FirstName`, `LastName`, `Email`, `CreatedAt`, `UserType`, `PasswordHash`) VALUES
(1, 'Matt Erron', 'Cabarrubias', 'matt.cabarrubias@gmail.com', '2025-06-16 14:24:50', 'Patient', '$2y$10$ym7hW7H1ectnFxo.WaoNDecwCvKSCay1cCSjm9S.7/DJ/cNmnlnNC'),
(2, 'Matthew Angelo', 'Lumayno', 'matthew.lumayno@gmail.com', '2025-06-19 03:09:10', 'ClinicStaff', '$2y$10$iBfRH9IIaeupUaFEJyOESOG7IhjejpZIJMhUhB0hAzNY0d0qemE9W'),
(3, 'Jeane ', 'Diputado', 'jeane@gmail.com', '2025-06-21 06:20:13', 'ClinicStaff', '$2y$10$xuTwNTGDGGni2x919pFvXe3l8gEGE3mf.DTIK60goXpQGk2/AFgF6'),
(4, 'Joseph Gabriel', 'Pascua', 'josephpascua@gmail.com', '2025-06-22 16:38:09', 'ClinicStaff', '$2y$10$MZijGj8xMmoofv78ub.uN.stPpzpjYOs4kTV6IARnNlXs5jklQ7EK'),
(5, 'Simon Gabriel', 'Gementiza', 'simongementiza@gmail.com', '2025-06-23 03:41:26', 'Patient', '$2y$10$.q/KB313P83140gDwkIWtOm23/32TOmMbpucSlGZ209p0jtUkik/O'),
(11, 'Jemuel', 'Valencia', 'jemuelvalencia@gmail.com', '2025-06-24 05:32:47', 'Patient', '$2y$10$LRry2Qi/j7ytdAxaDrWN2.wlhAlaEB3t/FaEsQKGC2Bx/cqn2X6vi'),
(12, 'Charles Jade', 'Argawanon', 'charles.jade@gmail.com', '2025-07-10 04:45:48', 'Patient', '$2y$10$0Og30QyqhFXG1sHOX1ld8.HGRqCSNJvc6sEV5nowurqKVGOtgFDBO'),
(13, 'Khen Andrei', 'Lim', 'khenlim@gmail.com', '2025-07-11 14:33:45', 'Patient', '$2y$10$.5nECVEZHqfIt2KGjQ1IJOPOIlVHlOqOUUcF02hTvRZb0g7uVjK1C'),
(14, 'test', 'user', 'test@gmail.com', '2025-07-16 01:59:46', 'Patient', '$2y$10$03C4yjUzvZOQsLCzZss14.AIG0izsJRQlQknTDKvTxg510GrP/xR2'),
(15, 'Sir', 'Opone', 'siropone@gmail.com', '2025-07-16 08:33:01', 'Patient', '$2y$10$sRtNdSoy0JnxT.g0Lc8P3OV35QK4JSVezjevAaG3uAvabOsemvr1K'),
(16, 'Elisa Pamela', 'Magno', 'elisapamelamagno@gmail.com', '2025-07-16 14:15:44', 'Patient', '$2y$10$16qNTzxd9bhci22cGUO8OuuuyI8rshFugzfstUL.zPG7vk6jl.iqW'),
(17, 'Ethan Job', 'Leones', 'ethanleones@gmail.com', '2025-07-16 14:33:15', 'Patient', '$2y$10$l5uMN4djJLq98hK1E4gbUuhk5XjRezP1ZhK3IqAN.ezZbjVE5usca'),
(18, 'Jhanell', 'Mingo', 'jhanellmingo@gmail.com', '2025-07-16 14:33:42', 'Patient', '$2y$10$bnSL8c579GUrrEf3n/ZfBuqMwzS1D/9Bj7RRqJMP9MlMD10PP.JVK'),
(19, 'Derrick Angelo', 'Yu', 'derrickangelo@gmail.com', '2025-07-16 14:34:10', 'Patient', '$2y$10$XODJhuEWWGPKrArqygYGmOReshmsjX/jidLOhpO1iC7FpYx5yRlaq'),
(20, 'Cris Lawrence', 'Lucero', 'crislawrence@gmail.com', '2025-07-16 14:35:35', 'Patient', '$2y$10$hndPGHa3g1B9BawaPoawiuQByAK7LLO3br1gdiKYqy1DOSXQgiSzO'),
(21, 'Yza Hilary', 'Alagon', 'yzaalagon@gmail.com', '2025-07-16 14:35:55', 'Patient', '$2y$10$HBEBgADHwlStRUkXemCSIe745UvxsyfelB9FZl84d13j.0C7AmbD2'),
(22, 'Charlz', 'Despues', 'charlzdespues@gmail.com', '2025-07-16 14:36:25', 'Patient', '$2y$10$.J4lYF7cuhNZ1.kNwXKwUOkprL8vBBeyZlyN3E4odVHK2lAthjuiu'),
(23, 'Draco', 'Diaz', 'dracodiaz@gmail.com', '2025-07-16 14:36:45', 'Patient', '$2y$10$rL1jGpHMlLbvtYyn6YnwqOonn4XhiQTFR8FOLgV71nZIy6jb9wbv6'),
(24, 'Cassandra Jeane', 'Encabo', 'cassandraencabo@gmail.com', '2025-07-16 14:37:14', 'Patient', '$2y$10$ILIHobowghjrMYJuuqXN1eZXC18ISkMASDdlC3zl21vN8IjB4HBHK'),
(25, 'Nyla Kate', 'Caparoso', 'nylakate@gmail.com', '2025-07-16 14:37:32', 'Patient', '$2y$10$.henYuf17SNfNMDqV2CMze1TALEnZc5y6J/DptVUEd4OtX/ngZaC.'),
(26, 'Samantha Cassandra', 'Camus', 'samanthacamus@gmail.com', '2025-07-16 14:38:06', 'Patient', '$2y$10$KvBkK1C2Xb56Y9h7t5EBaOPW9oC7j7IdU3j8f7/v.SjgpcY6UwkRK'),
(27, 'Judie Marie', 'Sarting', 'judiemarie@gmail.com', '2025-07-16 14:38:20', 'Patient', '$2y$10$5T4ctGQHU7eK/VrRXR/zaeL8xQ07Hns4k9HOvuHKxi/qTcOdKFa46'),
(28, 'Martin Shawn', 'Manabat', 'martinshawn@gmail.com', '2025-07-16 14:43:09', 'Patient', '$2y$10$poE2208sT4CIPMvgzA0sA.TgaGgnWe490nF.XkDhfQo1K/DMDuG8m'),
(29, 'Jian Bryce', 'Machacon', 'jianbryce@gmail.com', '2025-07-16 14:43:33', 'Patient', '$2y$10$Asjo7r9WaZtkIhKbU4iQC.34fytInSNVEu07iYv1vpxQbFY6MN0Cu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Appointment`
--
ALTER TABLE `Appointment`
  ADD PRIMARY KEY (`AppointmentID`),
  ADD KEY `FK_Appointment_Patient` (`PatientID`),
  ADD KEY `FK_Appointment_Doctor` (`DoctorID`),
  ADD KEY `IDX_Appointment_DateTime` (`DateTime`);

--
-- Indexes for table `AppointmentReport`
--
ALTER TABLE `AppointmentReport`
  ADD PRIMARY KEY (`AppointmentReportID`),
  ADD UNIQUE KEY `AppointmentID` (`AppointmentID`),
  ADD KEY `FK_AppointmentReport_PatientRecord` (`PatientRecordID`);

--
-- Indexes for table `blocked_slots`
--
ALTER TABLE `blocked_slots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_slot` (`doctor_id`,`blocked_date`,`blocked_time`);

--
-- Indexes for table `CLINIC_STAFF`
--
ALTER TABLE `CLINIC_STAFF`
  ADD PRIMARY KEY (`ClinicStaffID`);

--
-- Indexes for table `DentalChartItem`
--
ALTER TABLE `DentalChartItem`
  ADD PRIMARY KEY (`DentalChartItemID`),
  ADD KEY `dentalchartitem_ibfk_1` (`DentalChartID`);

--
-- Indexes for table `DentalCharts`
--
ALTER TABLE `DentalCharts`
  ADD PRIMARY KEY (`DentalChartID`),
  ADD KEY `PatientID` (`PatientID`),
  ADD KEY `DentistID` (`DentistID`);

--
-- Indexes for table `Doctor`
--
ALTER TABLE `Doctor`
  ADD PRIMARY KEY (`DoctorID`);

--
-- Indexes for table `OverdueConfig`
--
ALTER TABLE `OverdueConfig`
  ADD PRIMARY KEY (`ConfigID`),
  ADD KEY `UpdatedBy` (`UpdatedBy`),
  ADD KEY `idx_overdue_config_active` (`IsActive`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_token` (`token`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_expires_at` (`expires_at`),
  ADD KEY `idx_cleanup` (`expires_at`,`used_at`);

--
-- Indexes for table `PATIENT`
--
ALTER TABLE `PATIENT`
  ADD PRIMARY KEY (`PatientID`);

--
-- Indexes for table `PatientRecord`
--
ALTER TABLE `PatientRecord`
  ADD PRIMARY KEY (`RecordID`),
  ADD UNIQUE KEY `PatientID` (`PatientID`);

--
-- Indexes for table `PaymentItems`
--
ALTER TABLE `PaymentItems`
  ADD PRIMARY KEY (`PaymentItemID`),
  ADD KEY `idx_payment_id` (`PaymentID`),
  ADD KEY `fk_treatmentitem_payment` (`TreatmentItemID`);

--
-- Indexes for table `Payments`
--
ALTER TABLE `Payments`
  ADD PRIMARY KEY (`PaymentID`),
  ADD KEY `FK_Payments_Appointment` (`AppointmentID`),
  ADD KEY `FK_Payments_Patient` (`PatientID`),
  ADD KEY `FK_Payments_UpdatedBy` (`UpdatedBy`);

--
-- Indexes for table `ServicePrices`
--
ALTER TABLE `ServicePrices`
  ADD PRIMARY KEY (`ServicePriceID`),
  ADD UNIQUE KEY `unique_service_name` (`ServiceName`);

--
-- Indexes for table `TreatmentPlan`
--
ALTER TABLE `TreatmentPlan`
  ADD PRIMARY KEY (`TreatmentPlanID`),
  ADD KEY `fk_treatmentplan_appointment` (`AppointmentReportID`);

--
-- Indexes for table `TreatmentPlanItem`
--
ALTER TABLE `TreatmentPlanItem`
  ADD PRIMARY KEY (`TreatmentItemID`),
  ADD KEY `fk_treatmentitem_plan` (`TreatmentPlanID`);

--
-- Indexes for table `USER`
--
ALTER TABLE `USER`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Appointment`
--
ALTER TABLE `Appointment`
  MODIFY `AppointmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `AppointmentReport`
--
ALTER TABLE `AppointmentReport`
  MODIFY `AppointmentReportID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `blocked_slots`
--
ALTER TABLE `blocked_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `DentalChartItem`
--
ALTER TABLE `DentalChartItem`
  MODIFY `DentalChartItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=289;

--
-- AUTO_INCREMENT for table `DentalCharts`
--
ALTER TABLE `DentalCharts`
  MODIFY `DentalChartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `OverdueConfig`
--
ALTER TABLE `OverdueConfig`
  MODIFY `ConfigID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `PatientRecord`
--
ALTER TABLE `PatientRecord`
  MODIFY `RecordID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `PaymentItems`
--
ALTER TABLE `PaymentItems`
  MODIFY `PaymentItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `Payments`
--
ALTER TABLE `Payments`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `ServicePrices`
--
ALTER TABLE `ServicePrices`
  MODIFY `ServicePriceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `TreatmentPlan`
--
ALTER TABLE `TreatmentPlan`
  MODIFY `TreatmentPlanID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `TreatmentPlanItem`
--
ALTER TABLE `TreatmentPlanItem`
  MODIFY `TreatmentItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `USER`
--
ALTER TABLE `USER`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `Appointment`
  ADD CONSTRAINT `FK_Appointment_Doctor` FOREIGN KEY (`DoctorID`) REFERENCES `Doctor` (`DoctorID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Appointment_Patient` FOREIGN KEY (`PatientID`) REFERENCES `PATIENT` (`PatientID`) ON UPDATE CASCADE;

--
-- Constraints for table `appointmentreport`
--
ALTER TABLE `AppointmentReport`
  ADD CONSTRAINT `FK_AppointmentReport_Appointment` FOREIGN KEY (`AppointmentID`) REFERENCES `Appointment` (`AppointmentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_AppointmentReport_PatientRecord` FOREIGN KEY (`PatientRecordID`) REFERENCES `PatientRecord` (`RecordID`) ON UPDATE CASCADE;

--
-- Constraints for table `blocked_slots`
--
ALTER TABLE `blocked_slots`
  ADD CONSTRAINT `blocked_slots_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `Doctor` (`DoctorID`);

--
-- Constraints for table `clinic_staff`
--
ALTER TABLE `CLINIC_STAFF`
  ADD CONSTRAINT `FK_ClinicStaff_User` FOREIGN KEY (`ClinicStaffID`) REFERENCES `USER` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dentalchartitem`
--
ALTER TABLE `DentalChartItem`
  ADD CONSTRAINT `dentalchartitem_ibfk_1` FOREIGN KEY (`DentalChartID`) REFERENCES `DentalCharts` (`DentalChartID`) ON DELETE CASCADE;

--
-- Constraints for table `dentalcharts`
--
ALTER TABLE `DentalCharts`
  ADD CONSTRAINT `dentalcharts_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `PATIENT` (`PatientID`) ON DELETE CASCADE;

--
-- Constraints for table `doctor`
--
ALTER TABLE `Doctor`
  ADD CONSTRAINT `FK_Doctor_ClinicStaff` FOREIGN KEY (`DoctorID`) REFERENCES `CLINIC_STAFF` (`ClinicStaffID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `overdueconfig`
--
ALTER TABLE `OverdueConfig`
  ADD CONSTRAINT `overdueconfig_ibfk_1` FOREIGN KEY (`UpdatedBy`) REFERENCES `CLINIC_STAFF` (`ClinicStaffID`) ON DELETE SET NULL;

--
-- Constraints for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD CONSTRAINT `password_reset_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `USER` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `patient`
--
ALTER TABLE `PATIENT`
  ADD CONSTRAINT `FK_Patient_User` FOREIGN KEY (`PatientID`) REFERENCES `USER` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patientrecord`
--
ALTER TABLE `PatientRecord`
  ADD CONSTRAINT `FK_PatientRecord_Patient` FOREIGN KEY (`PatientID`) REFERENCES `PATIENT` (`PatientID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `paymentitems`
--
ALTER TABLE `PaymentItems`
  ADD CONSTRAINT `fk_treatmentitem_payment` FOREIGN KEY (`TreatmentItemID`) REFERENCES `TreatmentPlanItem` (`TreatmentItemID`) ON DELETE CASCADE,
  ADD CONSTRAINT `paymentitems_ibfk_1` FOREIGN KEY (`PaymentID`) REFERENCES `Payments` (`PaymentID`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `Payments`
  ADD CONSTRAINT `FK_Payments_Appointment` FOREIGN KEY (`AppointmentID`) REFERENCES `Appointment` (`AppointmentID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Payments_Patient` FOREIGN KEY (`PatientID`) REFERENCES `PATIENT` (`PatientID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Payments_UpdatedBy` FOREIGN KEY (`UpdatedBy`) REFERENCES `CLINIC_STAFF` (`ClinicStaffID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `treatmentplan`
--
ALTER TABLE `TreatmentPlan`
  ADD CONSTRAINT `fk_treatmentplan_appointment` FOREIGN KEY (`AppointmentReportID`) REFERENCES `AppointmentReport` (`AppointmentReportID`) ON DELETE CASCADE,
  ADD CONSTRAINT `treatmentplan_ibfk_1` FOREIGN KEY (`AppointmentReportID`) REFERENCES `AppointmentReport` (`AppointmentReportID`);

--
-- Constraints for table `treatmentplanitem`
--
ALTER TABLE `TreatmentPlanItem`
  ADD CONSTRAINT `fk_treatmentitem_plan` FOREIGN KEY (`TreatmentPlanID`) REFERENCES `TreatmentPlan` (`TreatmentPlanID`) ON DELETE CASCADE,
  ADD CONSTRAINT `treatmentplanitem_ibfk_1` FOREIGN KEY (`TreatmentPlanID`) REFERENCES `TreatmentPlan` (`TreatmentPlanID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
