-- Migration to update Payments table with new fields
-- Add new fields: DeadlineDate, PaymentMethod, ProofOfPayment
-- Update Status ENUM to include 'Cancelled'

USE dentalign;

-- Add new columns to Payments table
ALTER TABLE Payments 
ADD COLUMN DeadlineDate DATE NULL AFTER Notes,
ADD COLUMN PaymentMethod VARCHAR(50) NULL DEFAULT 'Cash' AFTER DeadlineDate,
ADD COLUMN ProofOfPayment TEXT NULL AFTER PaymentMethod;

-- Update Status ENUM to include 'Cancelled'
ALTER TABLE Payments 
MODIFY COLUMN Status ENUM('Pending', 'Paid', 'Failed', 'Refunded', 'Cancelled') NOT NULL DEFAULT 'Pending';

-- Create Payment entries for existing appointments that don't have them
-- This will create a Payment entry for each appointment with default values
INSERT INTO Payments (AppointmentID, PatientID, Status, UpdatedBy, Notes, DeadlineDate, PaymentMethod)
SELECT 
    a.AppointmentID,
    a.PatientID,
    'Pending' as Status,
    NULL as UpdatedBy,
    'Auto-created for existing appointment' as Notes,
    DATE_ADD(a.DateTime, INTERVAL 30 DAY) as DeadlineDate,
    'Cash' as PaymentMethod
FROM Appointment a
LEFT JOIN Payments p ON a.AppointmentID = p.AppointmentID
WHERE p.PaymentID IS NULL;

-- Update UpdatedBy to store DentalAssistant names (we'll handle this in code)
-- For now, we'll leave it as INT and handle the name lookup in the application 