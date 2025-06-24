-- Migration script to create PatientRecord for existing patients without one

-- Insert PatientRecord for existing patients who don't have one
INSERT INTO PatientRecord (PatientID, Height, Weight, Allergies, LastVisit)
SELECT p.PatientID, NULL, NULL, NULL, NULL
FROM PATIENT p
LEFT JOIN PatientRecord pr ON p.PatientID = pr.PatientID
WHERE pr.PatientID IS NULL;

-- Verify the migration
SELECT 'Patients without PatientRecord:' as Status, COUNT(*) as Count
FROM PATIENT p
LEFT JOIN PatientRecord pr ON p.PatientID = pr.PatientID
WHERE pr.PatientID IS NULL

UNION ALL

SELECT 'Total Patients:' as Status, COUNT(*) as Count
FROM PATIENT

UNION ALL

SELECT 'Total PatientRecords:' as Status, COUNT(*) as Count
FROM PatientRecord; 