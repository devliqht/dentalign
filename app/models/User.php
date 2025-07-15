<?php

class User
{
    protected $conn;
    protected $table = "USER";

    public $userID;
    public $firstName;
    public $lastName;
    public $email;
    public $passwordHash;
    public $createdAt;
    public $userType;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $query =
            "INSERT INTO " .
            $this->table .
            " 
                  (FirstName, LastName, Email, PasswordHash, UserType) 
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->firstName = htmlspecialchars(strip_tags($this->firstName));
        $this->lastName = htmlspecialchars(strip_tags($this->lastName));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->userType = htmlspecialchars(strip_tags($this->userType));

        $stmt->bind_param(
            "sssss",
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->passwordHash,
            $this->userType
        );

        if ($stmt->execute()) {
            $this->userID = $this->conn->insert_id;
            return true;
        }

        return false;
    }

    public function findByEmail($email)
    {
        $query =
            "SELECT UserID, FirstName, LastName, Email, PasswordHash, UserType, CreatedAt 
                  FROM " .
            $this->table .
            " 
                  WHERE Email = ? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->userID = $row["UserID"];
            $this->firstName = $row["FirstName"];
            $this->lastName = $row["LastName"];
            $this->email = $row["Email"];
            $this->passwordHash = $row["PasswordHash"];
            $this->userType = $row["UserType"];
            $this->createdAt = $row["CreatedAt"];
            return true;
        }

        return false;
    }

    public function emailExists($email)
    {
        $query =
            "SELECT UserID FROM " . $this->table . " WHERE Email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    public function verifyPassword($password)
    {
        return password_verify($password, $this->passwordHash);
    }

    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function findById($userId)
    {
        $query =
            "SELECT UserID, FirstName, LastName, Email, PasswordHash, UserType, CreatedAt 
                  FROM " .
            $this->table .
            " 
                  WHERE UserID = ? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->userID = $row["UserID"];
            $this->firstName = $row["FirstName"];
            $this->lastName = $row["LastName"];
            $this->email = $row["Email"];
            $this->passwordHash = $row["PasswordHash"];
            $this->userType = $row["UserType"];
            $this->createdAt = $row["CreatedAt"];
            return true;
        }

        return false;
    }

    public function updateProfile($userId, $firstName, $email)
    {
        $query =
            "UPDATE " .
            $this->table .
            " SET FirstName = ?, Email = ? WHERE UserID = ?";

        $stmt = $this->conn->prepare($query);

        // Clean data
        $firstName = htmlspecialchars(strip_tags($firstName));
        $email = htmlspecialchars(strip_tags($email));

        $stmt->bind_param("ssi", $firstName, $email, $userId);

        if ($stmt->execute()) {
            $this->firstName = $firstName;
            $this->email = $email;
            return true;
        }

        return false;
    }

    public function updatePassword($userId, $newPasswordHash)
    {
        $query =
            "UPDATE " . $this->table . " SET PasswordHash = ? WHERE UserID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $newPasswordHash, $userId);

        if ($stmt->execute()) {
            $this->passwordHash = $newPasswordHash;
            return true;
        }

        return false;
    }

    public function emailExistsForOtherUser($email, $userId)
    {
        $query =
            "SELECT UserID FROM " .
            $this->table .
            " WHERE Email = ? AND UserID != ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $email, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    public function generatePasswordResetToken($userId, $email = null)
    {
        $this->cleanupExpiredTokens();

        $token = bin2hex(random_bytes(32));

        if ($email) {
            if (!$this->findByEmail($email)) {
                return false;
            }
            $userId = $this->userID;
        }

        $query =
            "INSERT INTO password_reset_tokens (user_id, token, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("is", $userId, $token);

        if ($stmt->execute()) {
            return $token;
        }

        return false;
    }

    public function validatePasswordResetToken($token)
    {
        $query = "SELECT user_id, expires_at, used_at FROM password_reset_tokens 
                  WHERE token = ? AND expires_at > NOW() AND used_at IS NULL LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $token);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $tokenData = $result->fetch_assoc();
            return $tokenData["user_id"];
        }

        return false;
    }

    public function usePasswordResetToken($token)
    {
        $query =
            "UPDATE password_reset_tokens SET used_at = NOW() WHERE token = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $token);

        return $stmt->execute();
    }

    /**
     * Reset password using token
     */
    public function resetPasswordWithToken($token, $newPassword)
    {
        $userId = $this->validatePasswordResetToken($token);

        if (!$userId) {
            return false; // Invalid or expired token
        }

        $newPasswordHash = $this->hashPassword($newPassword);

        if ($this->updatePassword($userId, $newPasswordHash)) {
            $this->usePasswordResetToken($token);
            return true;
        }

        return false;
    }

    /**
     * Clean up expired tokens (call periodically)
     */
    public function cleanupExpiredTokens()
    {
        $query =
            "DELETE FROM password_reset_tokens WHERE expires_at < NOW() OR used_at IS NOT NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }

    /**
     * Get user data by reset token
     */
    public function getUserByResetToken($token)
    {
        $query = "SELECT u.UserID, u.FirstName, u.LastName, u.Email 
                  FROM USER u 
                  INNER JOIN password_reset_tokens prt ON u.UserID = prt.user_id 
                  WHERE prt.token = ? AND prt.expires_at > NOW() AND prt.used_at IS NULL 
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $token);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return false;
    }

    /**
     * Get user data by reset token (including used tokens for success display)
     */
    public function getUserByAnyResetToken($token)
    {
        $query = "SELECT u.UserID, u.FirstName, u.LastName, u.Email, prt.used_at
                  FROM USER u 
                  INNER JOIN password_reset_tokens prt ON u.UserID = prt.user_id 
                  WHERE prt.token = ? AND prt.expires_at > NOW()
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $token);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return false;
    }
}
