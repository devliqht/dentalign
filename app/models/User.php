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
}
?>
