-- Comprehensive setup script for Appointment and PatientRecord system
-- Run this script to set up the automatic creation system

-- Step 1: Migrate existing patients to have PatientRecords
INSERT INTO PatientRecord (PatientID, Height, Weight, Allergies, LastVisit)
SELECT p.PatientID, NULL, NULL, NULL, NULL
FROM PATIENT p
LEFT JOIN PatientRecord pr ON p.PatientID = pr.PatientID
WHERE pr.PatientID IS NULL;

-- Step 2: Migrate existing appointments to have AppointmentReports
INSERT INTO AppointmentReport (PatientRecordID, AppointmentID, BloodPressure, PulseRate, Temperature, RespiratoryRate, GeneralAppearance)
SELECT pr.RecordID, a.AppointmentID, NULL, NULL, NULL, NULL, NULL
FROM Appointment a
INNER JOIN PatientRecord pr ON a.PatientID = pr.PatientID
LEFT JOIN AppointmentReport ar ON a.AppointmentID = ar.AppointmentID
WHERE ar.AppointmentID IS NULL;

-- Step 3: Create triggers for automatic record creation

-- Drop existing triggers if they exist
DROP TRIGGER IF EXISTS create_patient_record_after_patient_insert;
DROP TRIGGER IF EXISTS create_appointment_report_after_appointment_insert;
DROP TRIGGER IF EXISTS delete_appointment_report_after_appointment_delete;

-- Trigger to create PatientRecord when a Patient is created
DELIMITER //
CREATE TRIGGER create_patient_record_after_patient_insert
AFTER INSERT ON PATIENT
FOR EACH ROW
BEGIN
    INSERT INTO PatientRecord (PatientID, Height, Weight, Allergies, LastVisit)
    VALUES (NEW.PatientID, NULL, NULL, NULL, NULL);
END//
DELIMITER ;

-- Trigger to create AppointmentReport when an Appointment is created  
DELIMITER //
CREATE TRIGGER create_appointment_report_after_appointment_insert
AFTER INSERT ON Appointment
FOR EACH ROW
BEGIN
    DECLARE patient_record_id INT;
    
    -- Get the PatientRecord ID for the patient
    SELECT RecordID INTO patient_record_id 
    FROM PatientRecord 
    WHERE PatientID = NEW.PatientID 
    LIMIT 1;
    
    -- Insert AppointmentReport
    INSERT INTO AppointmentReport (PatientRecordID, AppointmentID, BloodPressure, PulseRate, Temperature, RespiratoryRate, GeneralAppearance)
    VALUES (patient_record_id, NEW.AppointmentID, NULL, NULL, NULL, NULL, NULL);
END//
DELIMITER ;

-- Trigger to delete AppointmentReport when an Appointment is deleted
DELIMITER //
CREATE TRIGGER delete_appointment_report_after_appointment_delete
AFTER DELETE ON Appointment
FOR EACH ROW
BEGIN
    DELETE FROM AppointmentReport WHERE AppointmentID = OLD.AppointmentID;
END//
DELIMITER ;

-- Step 4: Verification queries
SELECT 'Setup completed successfully!' as Message;

SELECT 'Migration Status' as Report, 
       'Patients without PatientRecord' as Description, 
       COUNT(*) as Count
FROM PATIENT p
LEFT JOIN PatientRecord pr ON p.PatientID = pr.PatientID
WHERE pr.PatientID IS NULL

UNION ALL

SELECT 'Migration Status' as Report, 
       'Appointments without AppointmentReport' as Description, 
       COUNT(*) as Count
FROM Appointment a
LEFT JOIN AppointmentReport ar ON a.AppointmentID = ar.AppointmentID
WHERE ar.AppointmentID IS NULL

UNION ALL

SELECT 'Summary' as Report, 
       'Total Patients' as Description, 
       COUNT(*) as Count
FROM PATIENT

UNION ALL

SELECT 'Summary' as Report, 
       'Total PatientRecords' as Description, 
       COUNT(*) as Count
FROM PatientRecord

UNION ALL

SELECT 'Summary' as Report, 
       'Total Appointments' as Description, 
       COUNT(*) as Count
FROM Appointment

UNION ALL

SELECT 'Summary' as Report, 
       'Total AppointmentReports' as Description, 
       COUNT(*) as Count
FROM AppointmentReport;

-- Show created triggers
SHOW TRIGGERS; 