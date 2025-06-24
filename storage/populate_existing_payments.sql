-- Populate PaymentItems for existing payments
-- This script will add default payment items to existing payments that don't have any breakdown

INSERT INTO PaymentItems (PaymentID, Description, Amount, Quantity, Total)
SELECT 
    p.PaymentID,
    CASE 
        WHEN a.AppointmentType = 'Consultation' THEN 'General Consultation'
        WHEN a.AppointmentType = 'Cleaning' THEN 'Dental Cleaning'
        WHEN a.AppointmentType = 'Checkup' THEN 'Routine Checkup'
        WHEN a.AppointmentType = 'Filling' THEN 'Dental Filling'
        WHEN a.AppointmentType = 'Root Canal' THEN 'Root Canal Treatment'
        WHEN a.AppointmentType = 'Extraction' THEN 'Tooth Extraction'
        WHEN a.AppointmentType = 'Orthodontics' THEN 'Orthodontic Treatment'
        ELSE CONCAT(a.AppointmentType, ' Service')
    END as Description,
    CASE 
        WHEN a.AppointmentType = 'Consultation' THEN 75.00
        WHEN a.AppointmentType = 'Cleaning' THEN 120.00
        WHEN a.AppointmentType = 'Checkup' THEN 95.00
        WHEN a.AppointmentType = 'Filling' THEN 180.00
        WHEN a.AppointmentType = 'Root Canal' THEN 850.00
        WHEN a.AppointmentType = 'Extraction' THEN 150.00
        WHEN a.AppointmentType = 'Orthodontics' THEN 2500.00
        ELSE 100.00
    END as Amount,
    1 as Quantity,
    CASE 
        WHEN a.AppointmentType = 'Consultation' THEN 75.00
        WHEN a.AppointmentType = 'Cleaning' THEN 120.00
        WHEN a.AppointmentType = 'Checkup' THEN 95.00
        WHEN a.AppointmentType = 'Filling' THEN 180.00
        WHEN a.AppointmentType = 'Root Canal' THEN 850.00
        WHEN a.AppointmentType = 'Extraction' THEN 150.00
        WHEN a.AppointmentType = 'Orthodontics' THEN 2500.00
        ELSE 100.00
    END as Total
FROM payments p
JOIN Appointment a ON p.AppointmentID = a.AppointmentID
WHERE p.PaymentID NOT IN (SELECT DISTINCT PaymentID FROM PaymentItems);

-- Add administrative fee for higher-cost procedures
INSERT INTO PaymentItems (PaymentID, Description, Amount, Quantity, Total)
SELECT 
    p.PaymentID,
    'Administrative Fee',
    25.00,
    1,
    25.00
FROM payments p
JOIN Appointment a ON p.AppointmentID = a.AppointmentID
WHERE a.AppointmentType IN ('Root Canal', 'Orthodontics', 'Extraction')
AND p.PaymentID NOT IN (SELECT DISTINCT PaymentID FROM PaymentItems WHERE Description = 'Administrative Fee');

-- Add consultation fee for complex procedures
INSERT INTO PaymentItems (PaymentID, Description, Amount, Quantity, Total)
SELECT 
    p.PaymentID,
    'Initial Consultation',
    50.00,
    1,
    50.00
FROM payments p
JOIN Appointment a ON p.AppointmentID = a.AppointmentID
WHERE a.AppointmentType IN ('Root Canal', 'Orthodontics')
AND p.PaymentID NOT IN (SELECT DISTINCT PaymentID FROM PaymentItems WHERE Description = 'Initial Consultation'); 