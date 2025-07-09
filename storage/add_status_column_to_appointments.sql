-- Add Status column to Appointment table
-- This migration adds the missing Status column that the application expects

-- Add the Status column with default value 'Pending'
ALTER TABLE Appointment 
ADD COLUMN Status VARCHAR(50) NOT NULL DEFAULT 'Pending' 
COMMENT 'Status of the appointment: Pending, Completed, Cancelled, No-Show';

-- Update existing appointments to have a status based on their date
UPDATE Appointment 
SET Status = CASE 
    WHEN DateTime < NOW() THEN 'Completed'
    ELSE 'Pending'
END;

-- Verify the column was added successfully
SELECT 'Status column added successfully!' as Message;

-- Show sample of updated appointments
SELECT AppointmentID, DateTime, Status, AppointmentType 
FROM Appointment 
ORDER BY DateTime DESC 
LIMIT 5; 