-- Check if we have any staff in CLINIC_STAFF table
SELECT 'Checking for existing staff...' as Status;
SELECT COUNT(*) as StaffCount FROM CLINIC_STAFF;

-- If no staff exists, let's see what we have in the system
SELECT 'Checking USER table for potential staff...' as Status;
SELECT UserID, FirstName, LastName, UserType FROM USER WHERE UserType = 'Doctor' LIMIT 5;

-- Create payments with NULL UpdatedBy (safer approach)
SELECT 'Creating payments for existing appointments...' as Status;

INSERT INTO payments (AppointmentID, PatientID, Status, UpdatedBy, Notes)
SELECT 
    a.AppointmentID,
    a.PatientID,
    CASE 
        WHEN a.DateTime < NOW() THEN 'Paid'  -- Past appointments are paid
        ELSE 'Pending'  -- Future appointments are pending
    END as Status,
    NULL as UpdatedBy,  -- Set to NULL to avoid foreign key constraint issues
    CONCAT('Auto-generated payment for ', a.AppointmentType, ' appointment') as Notes
FROM Appointment a
LEFT JOIN payments p ON a.AppointmentID = p.AppointmentID
WHERE p.PaymentID IS NULL;  -- Only create payments for appointments that don't have them

SELECT 'Payments created successfully!' as Status;
SELECT COUNT(*) as NewPaymentsCount FROM payments;

-- Now show what we have
SELECT 'Sample of created payments:' as Status;
SELECT 
    p.PaymentID,
    p.AppointmentID,
    p.Status,
    a.AppointmentType,
    a.DateTime
FROM payments p
JOIN Appointment a ON p.AppointmentID = a.AppointmentID
LIMIT 5; 