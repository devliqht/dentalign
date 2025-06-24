-- Create payment_items table for storing payment breakdown details
CREATE TABLE IF NOT EXISTS PaymentItems (
    PaymentItemID INT(11) NOT NULL AUTO_INCREMENT,
    PaymentID INT(11) NOT NULL,
    Description VARCHAR(255) NOT NULL,
    Amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    Quantity INT(11) NOT NULL DEFAULT 1,
    Total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UpdatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (PaymentItemID),
    FOREIGN KEY (PaymentID) REFERENCES payments(PaymentID) ON DELETE CASCADE,
    INDEX idx_payment_id (PaymentID)
);

-- Insert some sample payment items for testing
-- Note: You'll need to update PaymentID values based on your existing payments

-- Example breakdown for a dental checkup
INSERT INTO PaymentItems (PaymentID, Description, Amount, Quantity, Total) VALUES
(1, 'Consultation Fee', 75.00, 1, 75.00),
(1, 'X-Ray', 50.00, 2, 100.00),
(1, 'Cleaning', 120.00, 1, 120.00);

-- Example breakdown for a filling procedure
INSERT INTO PaymentItems (PaymentID, Description, Amount, Quantity, Total) VALUES
(2, 'Consultation Fee', 75.00, 1, 75.00),
(2, 'Composite Filling', 180.00, 2, 360.00),
(2, 'Local Anesthesia', 25.00, 1, 25.00),
(2, 'Follow-up Care', 50.00, 1, 50.00);

-- Example breakdown for orthodontic consultation
INSERT INTO PaymentItems (PaymentID, Description, Amount, Quantity, Total) VALUES
(3, 'Orthodontic Consultation', 150.00, 1, 150.00),
(3, 'Panoramic X-Ray', 85.00, 1, 85.00),
(3, 'Treatment Planning', 100.00, 1, 100.00);

-- Create trigger to update total when amount or quantity changes
DELIMITER //
CREATE TRIGGER update_payment_item_total
    BEFORE UPDATE ON PaymentItems
    FOR EACH ROW
BEGIN
    SET NEW.Total = NEW.Amount * NEW.Quantity;
END//
DELIMITER ;

-- Create trigger to set total on insert
DELIMITER //
CREATE TRIGGER set_payment_item_total
    BEFORE INSERT ON PaymentItems
    FOR EACH ROW
BEGIN
    SET NEW.Total = NEW.Amount * NEW.Quantity;
END//
DELIMITER ; 