-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2025 at 04:33 AM
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
-- Database: `dentalign`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
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
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`AppointmentID`, `PatientID`, `DoctorID`, `DateTime`, `AppointmentType`, `Reason`, `CreatedAt`, `Status`) VALUES
(1, 1, 2, '2025-06-22 08:00:00', 'Consultation', 'Lets gooo hello world', '2025-06-20 16:00:18', 'Completed'),
(8, 1, 3, '2025-06-26 10:00:00', 'Cleaning', 'asgagsagagasga', '2025-06-23 11:54:20', 'Completed'),
(22, 1, 3, '2025-06-27 08:00:00', 'Consultation', 'hello world hehehe', '2025-06-25 13:46:50', 'Completed'),
(23, 1, 3, '2025-06-27 09:00:00', 'Cleaning', 'second book of the day lets go', '2025-06-25 13:47:11', 'Completed'),
(24, 1, 3, '2025-06-30 08:00:00', 'Consultation', 'This ia atestetest', '2025-06-27 14:44:47', 'Completed'),
(25, 1, 3, '2025-07-07 09:00:00', 'Cleaning', 'Hello worldd', '2025-06-27 14:45:26', 'Approved'),
(26, 1, 3, '2025-06-29 09:00:00', 'Cleaning', 'sdgdahdasjndsjsj', '2025-06-27 14:54:46', 'Completed'),
(30, 1, 3, '2025-07-03 08:00:00', 'Consultation', 'hello world pls work', '2025-07-01 14:28:12', 'Cancelled'),
(31, 1, 3, '2025-07-11 08:00:00', 'Consultation', 'bsaofbaoifhaifia', '2025-07-09 03:02:36', 'Cancelled'),
(32, 1, 3, '2025-07-11 09:00:00', 'Cleaning', 'astahyahyadhadh', '2025-07-09 07:24:23', 'Completed'),
(33, 1, 3, '2025-07-12 08:00:00', 'Consultation', 'Hello world welcome', '2025-07-10 00:44:17', 'Approved'),
(34, 1, 3, '2025-07-14 08:00:00', 'Consultation', 'Test Appointment 1', '2025-07-10 02:23:34', 'Pending'),
(35, 1, 3, '2025-07-12 10:00:00', 'Cleaning', 'Test Appointment 2', '2025-07-10 02:23:43', 'Approved'),
(36, 1, 3, '2025-07-12 12:00:00', 'Filling', 'Test Appointment 1', '2025-07-10 02:23:53', 'Cancelled'),
(37, 1, 2, '2025-07-12 08:00:00', 'Consultation', 'hello world hadga', '2025-07-10 02:39:54', 'Pending'),
(38, 1, 2, '2025-07-12 09:00:00', 'Emergency', 'EMERGENCYYY', '2025-07-10 02:40:23', 'Pending Cancellation'),
(39, 1, 2, '2025-07-14 08:00:00', 'Consultation', 'adasfsafafaf', '2025-07-12 11:41:48', 'Pending'),
(40, 1, 2, '2025-07-14 09:00:00', 'Consultation', 'asdsafsafsaf', '2025-07-12 11:44:53', 'Pending'),
(41, 1, 3, '2025-07-15 08:00:00', 'Consultation', 'adadadadsadasd', '2025-07-13 07:30:36', 'Pending Cancellation'),
(42, 14, 3, '2025-07-26 17:00:00', 'Cleaning', 'yeoahawihdaw', '2025-07-16 02:02:05', 'Pending'),
(43, 14, 3, '2025-07-25 17:00:00', 'Root Canal', 'dwaihdhauhuwuw', '2025-07-16 02:05:42', 'Pending'),
(44, 14, 3, '2025-07-19 15:00:00', 'Filling', 'ylyufygugyuygugyu', '2025-07-16 02:19:36', 'Pending'),
(45, 14, 3, '2025-07-18 15:00:00', 'Emergency', 'osiaciasdaidhawidha', '2025-07-16 02:31:26', 'Pending');

--
-- Triggers `appointment`
--
DELIMITER $$
CREATE TRIGGER `create_appointment_report_after_appointment_insert` AFTER INSERT ON `appointment` FOR EACH ROW BEGIN
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
CREATE TRIGGER `delete_appointment_report_after_appointment_delete` AFTER DELETE ON `appointment` FOR EACH ROW BEGIN
    DELETE FROM AppointmentReport WHERE AppointmentID = OLD.AppointmentID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `appointmentreport`
--

CREATE TABLE `appointmentreport` (
  `AppointmentReportID` int(11) NOT NULL,
  `PatientRecordID` int(11) NOT NULL,
  `AppointmentID` int(11) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `OralNotes` text DEFAULT NULL,
  `Diagnosis` text DEFAULT NULL,
  `XrayImages` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointmentreport`
--

INSERT INTO `appointmentreport` (`AppointmentReportID`, `PatientRecordID`, `AppointmentID`, `CreatedAt`, `OralNotes`, `Diagnosis`, `XrayImages`) VALUES
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
(39, 1, 33, '2025-07-10 00:44:17', '', '', ''),
(40, 1, 34, '2025-07-10 02:23:34', NULL, NULL, NULL),
(41, 1, 35, '2025-07-10 02:23:43', NULL, NULL, NULL),
(42, 1, 36, '2025-07-10 02:23:53', NULL, NULL, NULL),
(43, 1, 37, '2025-07-10 02:39:54', NULL, NULL, NULL),
(44, 1, 38, '2025-07-10 02:40:23', NULL, NULL, NULL),
(45, 1, 39, '2025-07-12 11:41:48', NULL, NULL, NULL),
(46, 1, 40, '2025-07-12 11:44:53', 'adadadad', '', ''),
(47, 1, 41, '2025-07-13 07:30:36', NULL, NULL, NULL),
(48, 31, 42, '2025-07-16 02:02:05', NULL, NULL, NULL),
(49, 31, 43, '2025-07-16 02:05:42', NULL, NULL, NULL),
(50, 31, 44, '2025-07-16 02:19:36', NULL, NULL, NULL),
(51, 31, 45, '2025-07-16 02:31:26', NULL, NULL, NULL);

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
(1, 3, '2025-07-16', '17:00:00', '2025-07-16 01:58:45'),
(2, 3, '2025-07-23', '17:00:00', '2025-07-16 02:00:58'),
(3, 3, '2025-07-25', '17:00:00', '2025-07-16 02:02:36'),
(4, 3, '2025-07-19', '08:00:00', '2025-07-16 02:16:50'),
(5, 3, '2025-07-19', '15:00:00', '2025-07-16 02:16:50'),
(6, 3, '2025-07-18', '17:00:00', '2025-07-16 02:30:20'),
(7, 3, '2025-07-17', '08:00:00', '2025-07-16 02:31:02');

-- --------------------------------------------------------

--
-- Table structure for table `clinic_staff`
--

CREATE TABLE `clinic_staff` (
  `ClinicStaffID` int(11) NOT NULL,
  `StaffType` varchar(100) NOT NULL COMMENT 'e.g., Doctor, Nurse, Receptionist, Admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clinic_staff`
--

INSERT INTO `clinic_staff` (`ClinicStaffID`, `StaffType`) VALUES
(2, 'Doctor'),
(3, 'Doctor'),
(4, 'DentalAssistant');

-- --------------------------------------------------------

--
-- Table structure for table `dentalchartitem`
--

CREATE TABLE `dentalchartitem` (
  `DentalChartItemID` int(11) NOT NULL,
  `DentalChartID` int(11) NOT NULL,
  `ToothNumber` varchar(5) NOT NULL,
  `Status` text DEFAULT NULL,
  `Notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dentalchartitem`
--

INSERT INTO `dentalchartitem` (`DentalChartItemID`, `DentalChartID`, `ToothNumber`, `Status`, `Notes`) VALUES
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
(129, 4, '1', NULL, NULL),
(130, 4, '2', NULL, NULL),
(131, 4, '3', NULL, NULL),
(132, 4, '4', NULL, NULL),
(133, 4, '5', NULL, NULL),
(134, 4, '6', NULL, NULL),
(135, 4, '7', NULL, NULL),
(136, 4, '8', '', ''),
(137, 4, '9', NULL, NULL),
(138, 4, '10', NULL, NULL),
(139, 4, '11', NULL, NULL),
(140, 4, '12', NULL, NULL),
(141, 4, '13', NULL, NULL),
(142, 4, '14', NULL, NULL),
(143, 4, '15', NULL, NULL),
(144, 4, '16', NULL, NULL),
(145, 4, '17', NULL, NULL),
(146, 4, '18', NULL, NULL),
(147, 4, '19', NULL, NULL),
(148, 4, '20', NULL, NULL),
(149, 4, '21', NULL, NULL),
(150, 4, '22', NULL, NULL),
(151, 4, '23', NULL, NULL),
(152, 4, '24', '', 'adadadad'),
(153, 4, '25', NULL, NULL),
(154, 4, '26', NULL, NULL),
(155, 4, '27', NULL, NULL),
(156, 4, '28', NULL, NULL),
(157, 4, '29', NULL, NULL),
(158, 4, '30', NULL, NULL),
(159, 4, '31', NULL, NULL),
(160, 4, '32', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dentalcharts`
--

CREATE TABLE `dentalcharts` (
  `DentalChartID` int(11) NOT NULL,
  `PatientID` int(11) NOT NULL,
  `DentistID` int(11) DEFAULT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dentalcharts`
--

INSERT INTO `dentalcharts` (`DentalChartID`, `PatientID`, `DentistID`, `CreatedAt`) VALUES
(1, 1, NULL, '2025-07-02 18:12:48'),
(2, 5, NULL, '2025-07-02 22:56:48'),
(3, 11, NULL, '2025-07-10 01:00:06'),
(4, 12, NULL, '2025-07-10 12:46:18');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `DoctorID` int(11) NOT NULL,
  `Specialization` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`DoctorID`, `Specialization`) VALUES
(2, 'General Dentistry'),
(3, 'Orthodontics');

-- --------------------------------------------------------

--
-- Table structure for table `overdueconfig`
--

CREATE TABLE `overdueconfig` (
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
-- Dumping data for table `overdueconfig`
--

INSERT INTO `overdueconfig` (`ConfigID`, `ConfigName`, `OverduePercentage`, `GracePeriodDays`, `IsActive`, `CreatedAt`, `UpdatedAt`, `UpdatedBy`) VALUES
(1, 'Default Overdue Settings', 5.00, 0, 0, '2025-07-11 06:19:13', '2025-07-11 06:24:59', NULL),
(2, 'Default Overdue Settings', 5.00, 0, 0, '2025-07-11 06:20:18', '2025-07-11 06:24:59', NULL),
(3, 'Default Overdue Settings', 7.00, 0, 0, '2025-07-11 06:24:59', '2025-07-11 07:11:16', 4),
(4, 'Default Overdue Settings', 5.00, 0, 1, '2025-07-11 07:11:16', '2025-07-11 07:11:16', 4);

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
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `PatientID` int(11) NOT NULL,
  `InsuranceProvider` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`PatientID`, `InsuranceProvider`) VALUES
(1, NULL),
(5, NULL),
(11, NULL),
(12, NULL),
(13, NULL),
(14, NULL);

--
-- Triggers `patient`
--
DELIMITER $$
CREATE TRIGGER `create_patient_record_after_patient_insert` AFTER INSERT ON `patient` FOR EACH ROW BEGIN
    INSERT INTO PatientRecord (PatientID, Height, Weight, Allergies, LastVisit)
    VALUES (NEW.PatientID, NULL, NULL, NULL, NULL);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `patientrecord`
--

CREATE TABLE `patientrecord` (
  `RecordID` int(11) NOT NULL,
  `PatientID` int(11) NOT NULL,
  `Height` decimal(5,2) DEFAULT NULL COMMENT 'e.g., in meters or feet',
  `Weight` decimal(5,2) DEFAULT NULL COMMENT 'e.g., in kg or lbs',
  `Allergies` text DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `LastVisit` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patientrecord`
--

INSERT INTO `patientrecord` (`RecordID`, `PatientID`, `Height`, `Weight`, `Allergies`, `CreatedAt`, `LastVisit`) VALUES
(1, 1, 172.00, 70.00, 'Nothing', '2025-06-24 00:31:51', NULL),
(2, 5, 160.00, 58.00, NULL, '2025-06-24 00:31:51', NULL),
(28, 11, NULL, NULL, NULL, '2025-06-24 05:32:47', NULL),
(29, 12, NULL, NULL, NULL, '2025-07-10 04:45:48', NULL),
(30, 13, NULL, NULL, NULL, '2025-07-11 14:33:45', NULL),
(31, 14, NULL, NULL, NULL, '2025-07-16 01:59:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `paymentitems`
--

CREATE TABLE `paymentitems` (
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
-- Dumping data for table `paymentitems`
--

INSERT INTO `paymentitems` (`PaymentItemID`, `PaymentID`, `Description`, `Amount`, `Quantity`, `Total`, `CreatedAt`, `UpdatedAt`, `TreatmentItemID`) VALUES
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
(39, 46, 'Emergency Service', 100.00, 1, 100.00, '2025-07-16 02:31:27', '2025-07-16 02:31:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
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
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`PaymentID`, `AppointmentID`, `PatientID`, `Status`, `UpdatedBy`, `UpdatedAt`, `Notes`, `DeadlineDate`, `PaymentMethod`, `ProofOfPayment`) VALUES
(9, 1, 1, 'Cancelled', 4, '2025-07-12 10:26:27', 'Payment cancelled by dental assistant', '2025-07-10', 'Bank Transfer', '43543637364364'),
(10, 8, 1, 'Pending', 4, '2025-07-11 07:14:30', 'Auto-created for existing appointment', '2025-07-12', 'Cash', NULL),
(11, 22, 1, 'Pending', NULL, '2025-07-11 02:54:01', 'Auto-created for existing appointment', '2025-07-27', 'Cash', NULL),
(12, 23, 1, 'Pending', NULL, '2025-07-11 02:54:01', 'Auto-created for existing appointment', '2025-07-27', 'Cash', NULL),
(13, 24, 1, 'Pending', NULL, '2025-07-11 02:54:01', 'Auto-created for existing appointment', '2025-07-30', 'Cash', NULL),
(14, 25, 1, 'Pending', NULL, '2025-07-11 02:54:01', 'Auto-created for existing appointment', '2025-08-06', 'Cash', NULL),
(15, 26, 1, 'Pending', NULL, '2025-07-11 02:54:01', 'Auto-created for existing appointment', '2025-07-29', 'Cash', NULL),
(16, 30, 1, 'Cancelled', 4, '2025-07-12 10:22:03', 'Payment cancelled by dental assistant', '2025-08-02', 'Cash', NULL),
(17, 31, 1, 'Pending', NULL, '2025-07-11 02:54:01', 'Auto-created for existing appointment', '2025-08-10', 'Cash', NULL),
(18, 32, 1, 'Pending', NULL, '2025-07-11 02:54:01', 'Auto-created for existing appointment', '2025-08-10', 'Cash', NULL),
(19, 33, 1, 'Pending', NULL, '2025-07-11 02:54:01', 'Auto-created for existing appointment', '2025-08-11', 'Cash', NULL),
(20, 34, 1, 'Pending', 4, '2025-07-12 11:57:37', 'Auto-created for existing appointment', '2025-08-11', 'Cash', NULL),
(21, 35, 1, 'Pending', 4, '2025-07-12 11:58:06', 'Payment cancelled by dental assistant', '2025-08-11', 'Cash', NULL),
(22, 36, 1, 'Cancelled', NULL, '2025-07-12 12:03:29', 'Payment cancelled due to appointment cancellation', '2025-08-11', 'Cash', NULL),
(23, 37, 1, 'Pending', NULL, '2025-07-11 02:54:01', 'Auto-created for existing appointment', '2025-08-11', 'Cash', NULL),
(24, 38, 1, 'Pending', NULL, '2025-07-11 02:54:01', 'Auto-created for existing appointment', '2025-08-11', 'Cash', NULL),
(40, 39, 1, 'Pending', 4, '2025-07-12 11:42:38', NULL, '2025-08-13', 'Cash', ''),
(41, 40, 1, 'Pending', NULL, '2025-07-12 11:44:53', 'Auto-created for new appointment', '2025-08-13', 'Cash', ''),
(42, 41, 1, 'Pending', NULL, '2025-07-13 07:30:36', 'Auto-created for new appointment', '2025-08-14', 'Cash', ''),
(43, 42, 14, 'Pending', NULL, '2025-07-16 02:02:05', 'Auto-created for new appointment', '2025-08-25', 'Cash', ''),
(44, 43, 14, 'Pending', NULL, '2025-07-16 02:05:42', 'Auto-created for new appointment', '2025-08-24', 'Cash', ''),
(45, 44, 14, 'Pending', NULL, '2025-07-16 02:19:36', 'Auto-created for new appointment', '2025-08-18', 'Cash', ''),
(46, 45, 14, 'Pending', NULL, '2025-07-16 02:31:26', 'Auto-created for new appointment', '2025-08-17', 'Cash', '');

-- --------------------------------------------------------

--
-- Table structure for table `treatmentplan`
--

CREATE TABLE `treatmentplan` (
  `TreatmentPlanID` int(11) NOT NULL,
  `AppointmentReportID` int(11) NOT NULL,
  `Status` enum('pending','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending',
  `DentistNotes` text DEFAULT NULL,
  `AssignedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `treatmentplan`
--

INSERT INTO `treatmentplan` (`TreatmentPlanID`, `AppointmentReportID`, `Status`, `DentistNotes`, `AssignedAt`) VALUES
(1, 38, 'in_progress', 'Hello world', '2025-07-09 18:30:14');

-- --------------------------------------------------------

--
-- Table structure for table `treatmentplanitem`
--

CREATE TABLE `treatmentplanitem` (
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
-- Dumping data for table `treatmentplanitem`
--

INSERT INTO `treatmentplanitem` (`TreatmentItemID`, `TreatmentPlanID`, `ToothNumber`, `ProcedureCode`, `Description`, `Cost`, `ScheduledDate`, `CreatedAt`, `CompletedAt`) VALUES
(1, 1, '1', 'D1208', 'Topical application of fluoride', 25555.00, '2025-07-11', '2025-07-10 00:30:14', '2025-07-12 11:43:40'),
(2, 1, '10', 'D2330', 'Resin based composite stuff', 23455.00, '2025-07-14', '2025-07-10 00:59:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UserType` varchar(50) NOT NULL COMMENT 'e.g., Patient, ClinicStaff, Admin',
  `PasswordHash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `FirstName`, `LastName`, `Email`, `CreatedAt`, `UserType`, `PasswordHash`) VALUES
(1, 'Matt Erron', 'Cabarrubias', 'matt.cabarrubias@gmail.com', '2025-06-16 14:24:50', 'Patient', '$2y$10$ym7hW7H1ectnFxo.WaoNDecwCvKSCay1cCSjm9S.7/DJ/cNmnlnNC'),
(2, 'Matthew Angelo', 'Lumayno', 'matthew.lumayno@gmail.com', '2025-06-19 03:09:10', 'ClinicStaff', '$2y$10$iBfRH9IIaeupUaFEJyOESOG7IhjejpZIJMhUhB0hAzNY0d0qemE9W'),
(3, 'Jeane ', 'Diputado', 'jeane@gmail.com', '2025-06-21 06:20:13', 'ClinicStaff', '$2y$10$xuTwNTGDGGni2x919pFvXe3l8gEGE3mf.DTIK60goXpQGk2/AFgF6'),
(4, 'Joseph Gabriel', 'Pascua', 'josephpascua@gmail.com', '2025-06-22 16:38:09', 'ClinicStaff', '$2y$10$MZijGj8xMmoofv78ub.uN.stPpzpjYOs4kTV6IARnNlXs5jklQ7EK'),
(5, 'Simon Gabriel', 'Gementiza', 'simongementiza@gmail.com', '2025-06-23 03:41:26', 'Patient', '$2y$10$.q/KB313P83140gDwkIWtOm23/32TOmMbpucSlGZ209p0jtUkik/O'),
(11, 'Jemuel', 'Valencia', 'jemuelvalencia@gmail.com', '2025-06-24 05:32:47', 'Patient', '$2y$10$LRry2Qi/j7ytdAxaDrWN2.wlhAlaEB3t/FaEsQKGC2Bx/cqn2X6vi'),
(12, 'Charles Jade', 'Argawanon', 'charles.jade@gmail.com', '2025-07-10 04:45:48', 'Patient', '$2y$10$0Og30QyqhFXG1sHOX1ld8.HGRqCSNJvc6sEV5nowurqKVGOtgFDBO'),
(13, 'Khen Andrei', 'Lim', 'khenlim@gmail.com', '2025-07-11 14:33:45', 'Patient', '$2y$10$.5nECVEZHqfIt2KGjQ1IJOPOIlVHlOqOUUcF02hTvRZb0g7uVjK1C'),
(14, 'test', 'user', 'test@gmail.com', '2025-07-16 01:59:46', 'Patient', '$2y$10$03C4yjUzvZOQsLCzZss14.AIG0izsJRQlQknTDKvTxg510GrP/xR2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`AppointmentID`),
  ADD KEY `FK_Appointment_Patient` (`PatientID`),
  ADD KEY `FK_Appointment_Doctor` (`DoctorID`),
  ADD KEY `IDX_Appointment_DateTime` (`DateTime`);

--
-- Indexes for table `appointmentreport`
--
ALTER TABLE `appointmentreport`
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
-- Indexes for table `clinic_staff`
--
ALTER TABLE `clinic_staff`
  ADD PRIMARY KEY (`ClinicStaffID`);

--
-- Indexes for table `dentalchartitem`
--
ALTER TABLE `dentalchartitem`
  ADD PRIMARY KEY (`DentalChartItemID`),
  ADD KEY `dentalchartitem_ibfk_1` (`DentalChartID`);

--
-- Indexes for table `dentalcharts`
--
ALTER TABLE `dentalcharts`
  ADD PRIMARY KEY (`DentalChartID`),
  ADD KEY `PatientID` (`PatientID`),
  ADD KEY `DentistID` (`DentistID`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`DoctorID`);

--
-- Indexes for table `overdueconfig`
--
ALTER TABLE `overdueconfig`
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
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`PatientID`);

--
-- Indexes for table `patientrecord`
--
ALTER TABLE `patientrecord`
  ADD PRIMARY KEY (`RecordID`),
  ADD UNIQUE KEY `PatientID` (`PatientID`);

--
-- Indexes for table `paymentitems`
--
ALTER TABLE `paymentitems`
  ADD PRIMARY KEY (`PaymentItemID`),
  ADD KEY `idx_payment_id` (`PaymentID`),
  ADD KEY `fk_treatmentitem_payment` (`TreatmentItemID`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`PaymentID`),
  ADD KEY `FK_Payments_Appointment` (`AppointmentID`),
  ADD KEY `FK_Payments_Patient` (`PatientID`),
  ADD KEY `FK_Payments_UpdatedBy` (`UpdatedBy`);

--
-- Indexes for table `treatmentplan`
--
ALTER TABLE `treatmentplan`
  ADD PRIMARY KEY (`TreatmentPlanID`),
  ADD KEY `fk_treatmentplan_appointment` (`AppointmentReportID`);

--
-- Indexes for table `treatmentplanitem`
--
ALTER TABLE `treatmentplanitem`
  ADD PRIMARY KEY (`TreatmentItemID`),
  ADD KEY `fk_treatmentitem_plan` (`TreatmentPlanID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `AppointmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `appointmentreport`
--
ALTER TABLE `appointmentreport`
  MODIFY `AppointmentReportID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `blocked_slots`
--
ALTER TABLE `blocked_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `dentalchartitem`
--
ALTER TABLE `dentalchartitem`
  MODIFY `DentalChartItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT for table `dentalcharts`
--
ALTER TABLE `dentalcharts`
  MODIFY `DentalChartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `overdueconfig`
--
ALTER TABLE `overdueconfig`
  MODIFY `ConfigID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `patientrecord`
--
ALTER TABLE `patientrecord`
  MODIFY `RecordID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `paymentitems`
--
ALTER TABLE `paymentitems`
  MODIFY `PaymentItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `treatmentplan`
--
ALTER TABLE `treatmentplan`
  MODIFY `TreatmentPlanID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `treatmentplanitem`
--
ALTER TABLE `treatmentplanitem`
  MODIFY `TreatmentItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `FK_Appointment_Doctor` FOREIGN KEY (`DoctorID`) REFERENCES `doctor` (`DoctorID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Appointment_Patient` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`) ON UPDATE CASCADE;

--
-- Constraints for table `appointmentreport`
--
ALTER TABLE `appointmentreport`
  ADD CONSTRAINT `FK_AppointmentReport_Appointment` FOREIGN KEY (`AppointmentID`) REFERENCES `appointment` (`AppointmentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_AppointmentReport_PatientRecord` FOREIGN KEY (`PatientRecordID`) REFERENCES `patientrecord` (`RecordID`) ON UPDATE CASCADE;

--
-- Constraints for table `blocked_slots`
--
ALTER TABLE `blocked_slots`
  ADD CONSTRAINT `blocked_slots_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`DoctorID`);

--
-- Constraints for table `clinic_staff`
--
ALTER TABLE `clinic_staff`
  ADD CONSTRAINT `FK_ClinicStaff_User` FOREIGN KEY (`ClinicStaffID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dentalchartitem`
--
ALTER TABLE `dentalchartitem`
  ADD CONSTRAINT `dentalchartitem_ibfk_1` FOREIGN KEY (`DentalChartID`) REFERENCES `dentalcharts` (`DentalChartID`) ON DELETE CASCADE;

--
-- Constraints for table `dentalcharts`
--
ALTER TABLE `dentalcharts`
  ADD CONSTRAINT `dentalcharts_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`) ON DELETE CASCADE;

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `FK_Doctor_ClinicStaff` FOREIGN KEY (`DoctorID`) REFERENCES `clinic_staff` (`ClinicStaffID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `overdueconfig`
--
ALTER TABLE `overdueconfig`
  ADD CONSTRAINT `overdueconfig_ibfk_1` FOREIGN KEY (`UpdatedBy`) REFERENCES `clinic_staff` (`ClinicStaffID`) ON DELETE SET NULL;

--
-- Constraints for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD CONSTRAINT `password_reset_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `FK_Patient_User` FOREIGN KEY (`PatientID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patientrecord`
--
ALTER TABLE `patientrecord`
  ADD CONSTRAINT `FK_PatientRecord_Patient` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `paymentitems`
--
ALTER TABLE `paymentitems`
  ADD CONSTRAINT `fk_treatmentitem_payment` FOREIGN KEY (`TreatmentItemID`) REFERENCES `treatmentplanitem` (`TreatmentItemID`) ON DELETE CASCADE,
  ADD CONSTRAINT `paymentitems_ibfk_1` FOREIGN KEY (`PaymentID`) REFERENCES `payments` (`PaymentID`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `FK_Payments_Appointment` FOREIGN KEY (`AppointmentID`) REFERENCES `appointment` (`AppointmentID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Payments_Patient` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Payments_UpdatedBy` FOREIGN KEY (`UpdatedBy`) REFERENCES `clinic_staff` (`ClinicStaffID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `treatmentplan`
--
ALTER TABLE `treatmentplan`
  ADD CONSTRAINT `fk_treatmentplan_appointment` FOREIGN KEY (`AppointmentReportID`) REFERENCES `appointmentreport` (`AppointmentReportID`) ON DELETE CASCADE,
  ADD CONSTRAINT `treatmentplan_ibfk_1` FOREIGN KEY (`AppointmentReportID`) REFERENCES `appointmentreport` (`AppointmentReportID`);

--
-- Constraints for table `treatmentplanitem`
--
ALTER TABLE `treatmentplanitem`
  ADD CONSTRAINT `fk_treatmentitem_plan` FOREIGN KEY (`TreatmentPlanID`) REFERENCES `treatmentplan` (`TreatmentPlanID`) ON DELETE CASCADE,
  ADD CONSTRAINT `treatmentplanitem_ibfk_1` FOREIGN KEY (`TreatmentPlanID`) REFERENCES `treatmentplan` (`TreatmentPlanID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
