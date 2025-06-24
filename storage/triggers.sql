-- SQL Triggers for automatic record creation

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

-- View existing triggers (for verification)
-- SHOW TRIGGERS;

-- To drop triggers if needed:
-- DROP TRIGGER IF EXISTS create_patient_record_after_patient_insert;
-- DROP TRIGGER IF EXISTS create_appointment_report_after_appointment_insert;
-- DROP TRIGGER IF EXISTS delete_appointment_report_after_appointment_delete; 