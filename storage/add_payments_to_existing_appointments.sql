-- Add payments for all existing Appointment
-- This script will create payment records for Appointment that don't already have them

-- First, let's create payments for all appointments
-- We'll set UpdatedBy to NULL since it allows NULL values (ON DELETE SET NULL constraint)
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
WHERE p.PaymentID IS NULL;  -- Only create payments for Appointment that don't have them

-- Now add payment items for each payment based on appointment type
-- Get the newly created payment IDs and add appropriate items

-- For Consultation Appointment
INSERT INTO PaymentItems (PaymentID, Description, Amount, Quantity, Total)
SELECT 
    p.PaymentID,
    'Consultation Fee' as Description,
    75.00 as Amount,
    1 as Quantity,
    75.00 as Total
FROM payments p
JOIN Appointment a ON p.AppointmentID = a.AppointmentID
WHERE LOWER(a.AppointmentType) LIKE '%consultation%'
AND NOT EXISTS (SELECT 1 FROM PaymentItems pi WHERE pi.PaymentID = p.PaymentID);

-- For Cleaning Appointment
INSERT INTO PaymentItems (PaymentID, Description, Amount, Quantity, Total)
SELECT 
    p.PaymentID,
    'Consultation Fee' as Description,
    50.00 as Amount,
    1 as Quantity,
    50.00 as Total
FROM payments p
JOIN Appointment a ON p.AppointmentID = a.AppointmentID
WHERE LOWER(a.AppointmentType) LIKE '%cleaning%'
AND NOT EXISTS (SELECT 1 FROM PaymentItems pi WHERE pi.PaymentID = p.PaymentID);

INSERT INTO PaymentItems (PaymentID, Description, Amount, Quantity, Total)
SELECT 
    p.PaymentID,
    'Professional Cleaning' as Description,
    120.00 as Amount,
    1 as Quantity,
    120.00 as Total
FROM payments p
JOIN Appointment a ON p.AppointmentID = a.AppointmentID
WHERE LOWER(a.AppointmentType) LIKE '%cleaning%';

-- For Checkup Appointment
INSERT INTO PaymentItems (PaymentID, Description, Amount, Quantity, Total)
SELECT 
    p.PaymentID,
    'General Checkup' as Description,
    85.00 as Amount,
    1 as Quantity,
    85.00 as Total
FROM payments p
JOIN Appointment a ON p.AppointmentID = a.AppointmentID
WHERE LOWER(a.AppointmentType) LIKE '%checkup%'
AND NOT EXISTS (SELECT 1 FROM PaymentItems pi WHERE pi.PaymentID = p.PaymentID);

INSERT INTO PaymentItems (PaymentID, Description, Amount, Quantity, Total)
SELECT 
    p.PaymentID,
    'X-Ray (if needed)' as Description,
    45.00 as Amount,
    1 as Quantity,
    45.00 as Total
FROM payments p
JOIN Appointment a ON p.AppointmentID = a.AppointmentID
WHERE LOWER(a.AppointmentType) LIKE '%checkup%';

-- For Filling Appointment
INSERT INTO PaymentItems (PaymentID, Description, Amount, Quantity, Total)
SELECT 
    p.PaymentID,
    'Consultation Fee' as Description,
    75.00 as Amount,
    1 as Quantity,
    75.00 as Total
FROM payments p
JOIN Appointment a ON p.AppointmentID = a.AppointmentID
WHERE LOWER(a.AppointmentType) LIKE '%filling%'
AND NOT EXISTS (SELECT 1 FROM PaymentItems pi WHERE pi.PaymentID = p.PaymentID);

INSERT INTO PaymentItems (PaymentID, Description, Amount, Quantity, Total)
SELECT 
    p.PaymentID,
    'Composite Filling' as Description,
    180.00 as Amount,
    1 as Quantity,
    180.00 as Total
FROM payments p
JOIN Appointment a ON p.AppointmentID = a.AppointmentID
WHERE LOWER(a.AppointmentType) LIKE '%filling%';

INSERT INTO PaymentItems (PaymentID, Description, Amount, Quantity, Total)
SELECT 
    p.PaymentID,
    'Local Anesthesia' as Description,
    25.00 as Amount,
    1 as Quantity,
    25.00 as Total
FROM payments p
JOIN Appointment a ON p.AppointmentID = a.AppointmentID
WHERE LOWER(a.AppointmentType) LIKE '%filling%';

-- For Root Canal Appointment
INSERT INTO PaymentItems (PaymentID, Description, Amount, Quantity, Total)
SELECT 
    p.PaymentID,
    'Root Canal Treatment' as Description,
    650.00 as Amount,
    1 as Quantity,
    650.00 as Total
FROM payments p
JOIN Appointment a ON p.AppointmentID = a.AppointmentID
WHERE LOWER(a.AppointmentType) LIKE '%root%canal%'
AND NOT EXISTS (SELECT 1 FROM PaymentItems pi WHERE pi.PaymentID = p.PaymentID);

INSERT INTO PaymentItems (PaymentID, Description, Amount, Quantity, Total)
SELECT 
    p.PaymentID,
    'Crown Preparation' as Description,
    350.00 as Amount,
    1 as Quantity,
    350.00 as Total
FROM payments p
JOIN Appointment a ON p.AppointmentID = a.AppointmentID
WHERE LOWER(a.AppointmentType) LIKE '%root%canal%';

-- For Orthodontic Appointment
INSERT INTO PaymentItems (PaymentID, Description, Amount, Quantity, Total)
SELECT 
    p.PaymentID,
    'Orthodontic Consultation' as Description,
    150.00 as Amount,
    1 as Quantity,
    150.00 as Total
FROM payments p
JOIN Appointment a ON p.AppointmentID = a.AppointmentID
WHERE LOWER(a.AppointmentType) LIKE '%orthodontic%'
AND NOT EXISTS (SELECT 1 FROM PaymentItems pi WHERE pi.PaymentID = p.PaymentID);

INSERT INTO PaymentItems (PaymentID, Description, Amount, Quantity, Total)
SELECT 
    p.PaymentID,
    'Panoramic X-Ray' as Description,
    85.00 as Amount,
    1 as Quantity,
    85.00 as Total
FROM payments p
JOIN Appointment a ON p.AppointmentID = a.AppointmentID
WHERE LOWER(a.AppointmentType) LIKE '%orthodontic%';

-- For any other appointment types, add a generic consultation fee
INSERT INTO PaymentItems (PaymentID, Description, Amount, Quantity, Total)
SELECT 
    p.PaymentID,
    CONCAT(a.AppointmentType, ' - Standard Fee') as Description,
    95.00 as Amount,
    1 as Quantity,
    95.00 as Total
FROM payments p
JOIN Appointment a ON p.AppointmentID = a.AppointmentID
WHERE NOT EXISTS (SELECT 1 FROM PaymentItems pi WHERE pi.PaymentID = p.PaymentID)
AND LOWER(a.AppointmentType) NOT LIKE '%consultation%'
AND LOWER(a.AppointmentType) NOT LIKE '%cleaning%'
AND LOWER(a.AppointmentType) NOT LIKE '%checkup%'
AND LOWER(a.AppointmentType) NOT LIKE '%filling%'
AND LOWER(a.AppointmentType) NOT LIKE '%root%canal%'
AND LOWER(a.AppointmentType) NOT LIKE '%orthodontic%';

-- Show summary of what was created
SELECT 
    'Summary of Payments Created' as Info,
    COUNT(*) as TotalPayments
FROM payments;

SELECT 
    'Summary of Payment Items Created' as Info,
    COUNT(*) as TotalItems,
    SUM(Total) as TotalAmount
FROM PaymentItems; 