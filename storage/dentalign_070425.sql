-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 04, 2025 at 02:30 AM
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
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Appointment`
--

INSERT INTO `Appointment` (`AppointmentID`, `PatientID`, `DoctorID`, `DateTime`, `AppointmentType`, `Reason`, `CreatedAt`) VALUES
(1, 1, 2, '2025-06-22 08:00:00', 'Consultation', 'Lets gooo hello world', '2025-06-20 16:00:18'),
(8, 1, 3, '2025-06-26 10:00:00', 'Cleaning', 'asgagsagagasga', '2025-06-23 11:54:20'),
(22, 1, 3, '2025-06-27 08:00:00', 'Consultation', 'hello world hehehe', '2025-06-25 13:46:50'),
(23, 1, 3, '2025-06-27 09:00:00', 'Cleaning', 'second book of the day lets go', '2025-06-25 13:47:11'),
(24, 1, 3, '2025-06-30 08:00:00', 'Consultation', 'This ia atestetest', '2025-06-27 14:44:47'),
(25, 1, 3, '2025-07-07 09:00:00', 'Cleaning', 'Hello worldd', '2025-06-27 14:45:26'),
(26, 1, 3, '2025-06-29 09:00:00', 'Cleaning', 'sdgdahdasjndsjsj', '2025-06-27 14:54:46'),
(30, 1, 3, '2025-07-03 08:00:00', 'Consultation', 'hello world pls work', '2025-07-01 14:28:12');

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
(34, 1, 25, '2025-06-27 14:45:26', NULL, NULL, NULL),
(35, 1, 26, '2025-06-27 14:54:46', NULL, NULL, NULL),
(36, 1, 30, '2025-07-01 14:28:12', NULL, NULL, NULL);

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
(4, 'Doctor');

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
(38, 1, '6', 'Treatment Needed', 'Root Canal. Extraction. Crown. Filling. Cavity. Crown. Root Canal'),
(39, 1, '7', '', ''),
(40, 1, '8', '', ''),
(41, 1, '9', 'Healthy', ''),
(42, 1, '10', 'Healthy', ''),
(43, 1, '11', 'Healthy', ''),
(44, 1, '12', 'Healthy', ''),
(45, 1, '13', 'Healthy', ''),
(46, 1, '14', 'Healthy', ''),
(47, 1, '15', 'Healthy', ''),
(48, 1, '16', 'Healthy', ''),
(49, 1, '17', 'Treatment Needed', ''),
(50, 1, '18', 'Watch', 'Filling'),
(51, 1, '19', 'Healthy', ''),
(52, 1, '20', 'Healthy', ''),
(53, 1, '21', 'Healthy', ''),
(54, 1, '22', 'Healthy', ''),
(55, 1, '23', 'Healthy', ''),
(56, 1, '24', 'Healthy', ''),
(57, 1, '25', 'Healthy', ''),
(58, 1, '26', 'Healthy', ''),
(59, 1, '27', 'Healthy', ''),
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
(96, 2, '32', NULL, NULL);

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
(2, 5, NULL, '2025-07-02 22:56:48');

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
(3, 'Orthodontics'),
(4, 'General Dentistry');

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
(11, NULL);

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
(28, 11, NULL, NULL, NULL, '2025-06-24 05:32:47', NULL);

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
(9, 7, 'Cleaning', 696.00, 1, 696.00, '2025-07-03 06:24:21', '2025-07-03 06:24:21', NULL),
(10, 8, 'Consultation', 500.00, 1, 500.00, '2025-07-03 08:56:57', '2025-07-03 08:56:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Payments`
--

CREATE TABLE `Payments` (
  `PaymentID` int(11) NOT NULL,
  `AppointmentID` int(11) NOT NULL,
  `PatientID` int(11) NOT NULL,
  `Status` varchar(50) NOT NULL COMMENT 'e.g., Pending, Paid, Failed, Refunded',
  `UpdatedBy` int(11) DEFAULT NULL,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Payments`
--

INSERT INTO `Payments` (`PaymentID`, `AppointmentID`, `PatientID`, `Status`, `UpdatedBy`, `UpdatedAt`, `Notes`) VALUES
(7, 25, 1, 'Pending', 3, '2025-07-03 08:55:34', NULL),
(8, 1, 1, 'Pending', 3, '2025-07-03 08:56:57', 'First Payment');

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
(1, 'Matt Erron', 'Cabarrubias', 'matt.cabarrubias@gmail.com', '2025-06-16 14:24:50', 'Patient', '$2y$10$vYsisQqh.aCDiV8gixUEV.VhZCUnT4b2Ck9vaqejhG6QAQz0DxEMe'),
(2, 'Matthew Angelo', 'Lumayno', 'matthew.lumayno@gmail.com', '2025-06-19 03:09:10', 'ClinicStaff', '$2y$10$iBfRH9IIaeupUaFEJyOESOG7IhjejpZIJMhUhB0hAzNY0d0qemE9W'),
(3, 'Jeane ', 'Diputado', 'jeane@gmail.com', '2025-06-21 06:20:13', 'ClinicStaff', '$2y$10$xuTwNTGDGGni2x919pFvXe3l8gEGE3mf.DTIK60goXpQGk2/AFgF6'),
(4, 'Joseph Gabriel', 'Pascua', 'josephpascua@gmail.com', '2025-06-22 16:38:09', 'ClinicStaff', '$2y$10$MZijGj8xMmoofv78ub.uN.stPpzpjYOs4kTV6IARnNlXs5jklQ7EK'),
(5, 'Simon Gabriel', 'Gementiza', 'simongementiza@gmail.com', '2025-06-23 03:41:26', 'Patient', '$2y$10$.q/KB313P83140gDwkIWtOm23/32TOmMbpucSlGZ209p0jtUkik/O'),
(11, 'Jemuel', 'Valencia', 'jemuelvalencia@gmail.com', '2025-06-24 05:32:47', 'Patient', '$2y$10$LRry2Qi/j7ytdAxaDrWN2.wlhAlaEB3t/FaEsQKGC2Bx/cqn2X6vi');

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
-- Indexes for table `CLINIC_STAFF`
--
ALTER TABLE `CLINIC_STAFF`
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
-- Indexes for table `Doctor`
--
ALTER TABLE `Doctor`
  ADD PRIMARY KEY (`DoctorID`);

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
  MODIFY `AppointmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `AppointmentReport`
--
ALTER TABLE `AppointmentReport`
  MODIFY `AppointmentReportID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `dentalchartitem`
--
ALTER TABLE `dentalchartitem`
  MODIFY `DentalChartItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `dentalcharts`
--
ALTER TABLE `dentalcharts`
  MODIFY `DentalChartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `PatientRecord`
--
ALTER TABLE `PatientRecord`
  MODIFY `RecordID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `PaymentItems`
--
ALTER TABLE `PaymentItems`
  MODIFY `PaymentItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `Payments`
--
ALTER TABLE `Payments`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `TreatmentPlan`
--
ALTER TABLE `TreatmentPlan`
  MODIFY `TreatmentPlanID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `TreatmentPlanItem`
--
ALTER TABLE `TreatmentPlanItem`
  MODIFY `TreatmentItemID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `USER`
--
ALTER TABLE `USER`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Appointment`
--
ALTER TABLE `Appointment`
  ADD CONSTRAINT `FK_Appointment_Doctor` FOREIGN KEY (`DoctorID`) REFERENCES `Doctor` (`DoctorID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Appointment_Patient` FOREIGN KEY (`PatientID`) REFERENCES `PATIENT` (`PatientID`) ON UPDATE CASCADE;

--
-- Constraints for table `AppointmentReport`
--
ALTER TABLE `AppointmentReport`
  ADD CONSTRAINT `FK_AppointmentReport_Appointment` FOREIGN KEY (`AppointmentID`) REFERENCES `Appointment` (`AppointmentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_AppointmentReport_PatientRecord` FOREIGN KEY (`PatientRecordID`) REFERENCES `PatientRecord` (`RecordID`) ON UPDATE CASCADE;

--
-- Constraints for table `CLINIC_STAFF`
--
ALTER TABLE `CLINIC_STAFF`
  ADD CONSTRAINT `FK_ClinicStaff_User` FOREIGN KEY (`ClinicStaffID`) REFERENCES `USER` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dentalchartitem`
--
ALTER TABLE `dentalchartitem`
  ADD CONSTRAINT `dentalchartitem_ibfk_1` FOREIGN KEY (`DentalChartID`) REFERENCES `dentalcharts` (`DentalChartID`) ON DELETE CASCADE;

--
-- Constraints for table `dentalcharts`
--
ALTER TABLE `dentalcharts`
  ADD CONSTRAINT `dentalcharts_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `PATIENT` (`PatientID`) ON DELETE CASCADE;

--
-- Constraints for table `Doctor`
--
ALTER TABLE `Doctor`
  ADD CONSTRAINT `FK_Doctor_ClinicStaff` FOREIGN KEY (`DoctorID`) REFERENCES `CLINIC_STAFF` (`ClinicStaffID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `PATIENT`
--
ALTER TABLE `PATIENT`
  ADD CONSTRAINT `FK_Patient_User` FOREIGN KEY (`PatientID`) REFERENCES `USER` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `PatientRecord`
--
ALTER TABLE `PatientRecord`
  ADD CONSTRAINT `FK_PatientRecord_Patient` FOREIGN KEY (`PatientID`) REFERENCES `PATIENT` (`PatientID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `PaymentItems`
--
ALTER TABLE `PaymentItems`
  ADD CONSTRAINT `fk_treatmentitem_payment` FOREIGN KEY (`TreatmentItemID`) REFERENCES `TreatmentPlanItem` (`TreatmentItemID`) ON DELETE CASCADE,
  ADD CONSTRAINT `paymentitems_ibfk_1` FOREIGN KEY (`PaymentID`) REFERENCES `payments` (`PaymentID`) ON DELETE CASCADE;

--
-- Constraints for table `Payments`
--
ALTER TABLE `Payments`
  ADD CONSTRAINT `FK_Payments_Appointment` FOREIGN KEY (`AppointmentID`) REFERENCES `Appointment` (`AppointmentID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Payments_Patient` FOREIGN KEY (`PatientID`) REFERENCES `PATIENT` (`PatientID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Payments_UpdatedBy` FOREIGN KEY (`UpdatedBy`) REFERENCES `CLINIC_STAFF` (`ClinicStaffID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `TreatmentPlan`
--
ALTER TABLE `TreatmentPlan`
  ADD CONSTRAINT `fk_treatmentplan_appointment` FOREIGN KEY (`AppointmentReportID`) REFERENCES `AppointmentReport` (`AppointmentReportID`) ON DELETE CASCADE,
  ADD CONSTRAINT `treatmentplan_ibfk_1` FOREIGN KEY (`AppointmentReportID`) REFERENCES `AppointmentReport` (`AppointmentReportID`);

--
-- Constraints for table `TreatmentPlanItem`
--
ALTER TABLE `TreatmentPlanItem`
  ADD CONSTRAINT `fk_treatmentitem_plan` FOREIGN KEY (`TreatmentPlanID`) REFERENCES `TreatmentPlan` (`TreatmentPlanID`) ON DELETE CASCADE,
  ADD CONSTRAINT `treatmentplanitem_ibfk_1` FOREIGN KEY (`TreatmentPlanID`) REFERENCES `TreatmentPlan` (`TreatmentPlanID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
