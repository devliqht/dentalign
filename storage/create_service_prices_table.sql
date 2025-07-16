CREATE TABLE `ServicePrices` (
  `ServicePriceID` int(11) NOT NULL AUTO_INCREMENT,
  `ServiceName` varchar(100) NOT NULL,
  `ServicePrice` decimal(10,2) NOT NULL,
  `IsActive` tinyint(1) DEFAULT 1,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ServicePriceID`),
  UNIQUE KEY `unique_service_name` (`ServiceName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default service prices
INSERT INTO `ServicePrices` (`ServiceName`, `ServicePrice`, `IsActive`) VALUES
('Consultation', 75.00, 1),
('Cleaning', 120.00, 1),
('Checkup', 95.00, 1),
('Filling', 180.00, 1),
('Root Canal', 850.00, 1),
('Extraction', 150.00, 1),
('Orthodontics', 2500.00, 1),
('Emergency', 200.00, 1),
('Follow up', 50.00, 1);
