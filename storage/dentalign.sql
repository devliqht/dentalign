-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 19, 2025 at 02:45 AM
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

-- --------------------------------------------------------

--
-- Table structure for table `AppointmentReport`
--

CREATE TABLE `AppointmentReport` (
  `AppointmentReportID` int(11) NOT NULL,
  `PatientRecordID` int(11) NOT NULL,
  `AppointmentID` int(11) NOT NULL,
  `BloodPressure` varchar(20) DEFAULT NULL,
  `PulseRate` int(11) DEFAULT NULL,
  `Temperature` decimal(4,1) DEFAULT NULL,
  `RespiratoryRate` int(11) DEFAULT NULL,
  `GeneralAppearance` text DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `CLINIC_STAFF`
--

CREATE TABLE `CLINIC_STAFF` (
  `ClinicStaffID` int(11) NOT NULL,
  `StaffType` varchar(100) NOT NULL COMMENT 'e.g., Doctor, Nurse, Receptionist, Admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Doctor`
--

CREATE TABLE `Doctor` (
  `DoctorID` int(11) NOT NULL,
  `Specialization` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Messages`
--

CREATE TABLE `Messages` (
  `MessageID` int(11) NOT NULL,
  `SenderID` int(11) NOT NULL,
  `ReceiverID` int(11) NOT NULL,
  `SenderType` varchar(50) DEFAULT NULL COMMENT 'e.g., Patient, Doctor, Staff for context',
  `ReceiverType` varchar(50) DEFAULT NULL COMMENT 'e.g., Patient, Doctor, Staff for context',
  `MessageText` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT NULL COMMENT 'e.g., Sent, Delivered, Read'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `PATIENT`
--

CREATE TABLE `PATIENT` (
  `PatientID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `PATIENT`
--

INSERT INTO `PATIENT` (`PatientID`) VALUES
(1);

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

-- --------------------------------------------------------

--
-- Table structure for table `Prescription`
--

CREATE TABLE `Prescription` (
  `PrescriptionID` int(11) NOT NULL,
  `AppointmentReportID` int(11) NOT NULL,
  `Medicines` text NOT NULL,
  `Diagnosis` text DEFAULT NULL,
  `DoctorNotes` text DEFAULT NULL,
  `AssignedAt` timestamp NOT NULL DEFAULT current_timestamp()
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
(1, 'Matt Erron', 'Cabarrubias', 'matt.cabarrubias@gmail.com', '2025-06-16 14:24:50', 'Patient', '$2y$10$vYsisQqh.aCDiV8gixUEV.VhZCUnT4b2Ck9vaqejhG6QAQz0DxEMe');

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
-- Indexes for table `Doctor`
--
ALTER TABLE `Doctor`
  ADD PRIMARY KEY (`DoctorID`);

--
-- Indexes for table `Messages`
--
ALTER TABLE `Messages`
  ADD PRIMARY KEY (`MessageID`),
  ADD KEY `FK_Messages_Sender` (`SenderID`),
  ADD KEY `FK_Messages_Receiver` (`ReceiverID`);

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
-- Indexes for table `Payments`
--
ALTER TABLE `Payments`
  ADD PRIMARY KEY (`PaymentID`),
  ADD KEY `FK_Payments_Appointment` (`AppointmentID`),
  ADD KEY `FK_Payments_Patient` (`PatientID`),
  ADD KEY `FK_Payments_UpdatedBy` (`UpdatedBy`);

--
-- Indexes for table `Prescription`
--
ALTER TABLE `Prescription`
  ADD PRIMARY KEY (`PrescriptionID`),
  ADD UNIQUE KEY `AppointmentReportID` (`AppointmentReportID`);

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
  MODIFY `AppointmentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `AppointmentReport`
--
ALTER TABLE `AppointmentReport`
  MODIFY `AppointmentReportID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Messages`
--
ALTER TABLE `Messages`
  MODIFY `MessageID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `PatientRecord`
--
ALTER TABLE `PatientRecord`
  MODIFY `RecordID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Payments`
--
ALTER TABLE `Payments`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Prescription`
--
ALTER TABLE `Prescription`
  MODIFY `PrescriptionID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `USER`
--
ALTER TABLE `USER`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- Constraints for table `Doctor`
--
ALTER TABLE `Doctor`
  ADD CONSTRAINT `FK_Doctor_ClinicStaff` FOREIGN KEY (`DoctorID`) REFERENCES `CLINIC_STAFF` (`ClinicStaffID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Messages`
--
ALTER TABLE `Messages`
  ADD CONSTRAINT `FK_Messages_Receiver` FOREIGN KEY (`ReceiverID`) REFERENCES `USER` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Messages_Sender` FOREIGN KEY (`SenderID`) REFERENCES `USER` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints for table `Payments`
--
ALTER TABLE `Payments`
  ADD CONSTRAINT `FK_Payments_Appointment` FOREIGN KEY (`AppointmentID`) REFERENCES `Appointment` (`AppointmentID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Payments_Patient` FOREIGN KEY (`PatientID`) REFERENCES `PATIENT` (`PatientID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Payments_UpdatedBy` FOREIGN KEY (`UpdatedBy`) REFERENCES `CLINIC_STAFF` (`ClinicStaffID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `Prescription`
--
ALTER TABLE `Prescription`
  ADD CONSTRAINT `FK_Prescription_AppointmentReport` FOREIGN KEY (`AppointmentReportID`) REFERENCES `AppointmentReport` (`AppointmentReportID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
