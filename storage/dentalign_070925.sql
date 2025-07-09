-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 09, 2025 at 06:44 AM
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
  `Status` enum('Pending','Approved','Declined','Completed','Rescheduled') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Appointment`
--

INSERT INTO `Appointment` (`AppointmentID`, `PatientID`, `DoctorID`, `DateTime`, `AppointmentType`, `Reason`, `CreatedAt`, `Status`) VALUES
(1, 1, 2, '2025-06-22 08:00:00', 'Consultation', 'Lets gooo hello world', '2025-06-20 16:00:18', 'Completed'),
(8, 1, 3, '2025-06-26 10:00:00', 'Cleaning', 'asgagsagagasga', '2025-06-23 11:54:20', 'Completed'),
(22, 1, 3, '2025-06-27 08:00:00', 'Consultation', 'hello world hehehe', '2025-06-25 13:46:50', 'Completed'),
(23, 1, 3, '2025-06-27 09:00:00', 'Cleaning', 'second book of the day lets go', '2025-06-25 13:47:11', 'Completed'),
(24, 1, 3, '2025-06-30 08:00:00', 'Consultation', 'This ia atestetest', '2025-06-27 14:44:47', 'Completed'),
(25, 1, 3, '2025-07-07 09:00:00', 'Cleaning', 'Hello worldd', '2025-06-27 14:45:26', 'Approved'),
(26, 1, 3, '2025-06-29 09:00:00', 'Cleaning', 'sdgdahdasjndsjsj', '2025-06-27 14:54:46', 'Completed'),
(30, 1, 3, '2025-07-03 08:00:00', 'Consultation', 'hello world pls work', '2025-07-01 14:28:12', 'Completed'),
(31, 1, 3, '2025-07-11 08:00:00', 'Consultation', 'bsaofbaoifhaifia', '2025-07-09 03:02:36', 'Pending');

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Appointment`
--
ALTER TABLE `Appointment`
  MODIFY `AppointmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Appointment`
--
ALTER TABLE `Appointment`
  ADD CONSTRAINT `FK_Appointment_Doctor` FOREIGN KEY (`DoctorID`) REFERENCES `Doctor` (`DoctorID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Appointment_Patient` FOREIGN KEY (`PatientID`) REFERENCES `PATIENT` (`PatientID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
