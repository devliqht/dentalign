-- Migration script to create AppointmentReport for existing appointments without one

-- Insert AppointmentReport for existing appointments who don't have one
INSERT INTO AppointmentReport (PatientRecordID, AppointmentID, BloodPressure, PulseRate, Temperature, RespiratoryRate, GeneralAppearance)
SELECT pr.RecordID, a.AppointmentID, NULL, NULL, NULL, NULL, NULL
FROM Appointment a
INNER JOIN PatientRecord pr ON a.PatientID = pr.PatientID
LEFT JOIN AppointmentReport ar ON a.AppointmentID = ar.AppointmentID
WHERE ar.AppointmentID IS NULL;

-- Verify the migration
SELECT 'Appointments without AppointmentReport:' as Status, COUNT(*) as Count
FROM Appointment a
LEFT JOIN AppointmentReport ar ON a.AppointmentID = ar.AppointmentID
WHERE ar.AppointmentID IS NULL

UNION ALL

SELECT 'Total Appointments:' as Status, COUNT(*) as Count
FROM Appointment

UNION ALL

SELECT 'Total AppointmentReports:' as Status, COUNT(*) as Count
FROM AppointmentReport; 