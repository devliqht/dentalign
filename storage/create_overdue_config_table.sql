-- Create overdue configuration table
CREATE TABLE IF NOT EXISTS `OverdueConfig` (
    `ConfigID` int(11) NOT NULL AUTO_INCREMENT,
    `ConfigName` varchar(255) NOT NULL DEFAULT 'Default',
    `OverduePercentage` decimal(5,2) NOT NULL DEFAULT 5.00,
    `GracePeriodDays` int(11) NOT NULL DEFAULT 0,
    `IsActive` tinyint(1) NOT NULL DEFAULT 1,
    `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `UpdatedBy` int(11) NULL,
    PRIMARY KEY (`ConfigID`),
    FOREIGN KEY (`UpdatedBy`) REFERENCES `CLINIC_STAFF`(`ClinicStaffID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default configuration
INSERT INTO `OverdueConfig` (`ConfigName`, `OverduePercentage`, `GracePeriodDays`, `IsActive`) 
VALUES ('Default Overdue Settings', 5.00, 0, 1) 
ON DUPLICATE KEY UPDATE `OverduePercentage` = VALUES(`OverduePercentage`);

-- Add index for active configuration lookup
CREATE INDEX `idx_overdue_config_active` ON `OverdueConfig` (`IsActive`); 