-- Create password reset tokens table
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    used_at DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES USER(UserID) ON DELETE CASCADE,
    INDEX idx_token (token),
    INDEX idx_user_id (user_id),
    INDEX idx_expires_at (expires_at)
);

-- Add index for cleanup of expired tokens
CREATE INDEX idx_cleanup ON password_reset_tokens (expires_at, used_at); 