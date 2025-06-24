<?php

require_once "User.php";
require_once "PatientRecord.php";

class Patient extends User
{
    private $patientTable = "PATIENT";

    public $patientID;

    public function createPatient()
    {
        $this->conn->begin_transaction();

        try {
            $this->userType = "Patient";
            if (!$this->create()) {
                $error = $this->conn->error;
                error_log("Failed to create user: " . $error);
                throw new Exception("Failed to create user account: " . $error);
            }

            $patientQuery =
                "INSERT INTO " .
                $this->patientTable .
                " (PatientID) VALUES (?)";
            $stmt = $this->conn->prepare($patientQuery);
            
            if (!$stmt) {
                error_log("Failed to prepare patient query: " . $this->conn->error);
                throw new Exception("Database error occurred");
            }
            
            $stmt->bind_param("i", $this->userID);

            if (!$stmt->execute()) {
                $error = $stmt->error;
                error_log("Failed to create patient record: " . $error);
                throw new Exception("Failed to create patient record: " . $error);
            }

            $this->patientID = $this->userID;

            // Note: PatientRecord is automatically created by database trigger
            // No need to create it manually here

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Patient creation failed: " . $e->getMessage());
            return false;
        }
    }

    public function findPatientByEmail($email)
    {
        $query =
            "SELECT u.UserID, u.FirstName, u.LastName, u.Email, u.PasswordHash, u.UserType, u.CreatedAt
                  FROM " .
            $this->table .
            " u
                  INNER JOIN " .
            $this->patientTable .
            " p ON u.UserID = p.PatientID
                  WHERE u.Email = ? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->userID = $row["UserID"];
            $this->patientID = $row["UserID"];
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

    public function getPatientAppointments($patientID)
    {
        $query = "SELECT a.*, u.FirstName as DoctorFirstName, u.LastName as DoctorLastName, d.Specialization
                  FROM Appointment a
                  INNER JOIN Doctor d ON a.DoctorID = d.DoctorID
                  INNER JOIN USER u ON d.DoctorID = u.UserID
                  WHERE a.PatientID = ?
                  ORDER BY a.DateTime DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $patientID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }

    public function getPatientByUserId($userId)
    {
        $query =
            "SELECT p.PatientID, u.UserID, u.FirstName, u.LastName, u.Email, u.UserType, u.CreatedAt
                  FROM " .
            $this->patientTable .
            " p
                  INNER JOIN " .
            $this->table .
            " u ON p.PatientID = u.UserID
                  WHERE u.UserID = ? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }

    public function getPatientById($patientID)
    {
        $query =
            "SELECT p.PatientID, u.UserID, u.FirstName, u.LastName, u.Email, u.UserType, u.CreatedAt
                  FROM " .
            $this->patientTable .
            " p
                  INNER JOIN " .
            $this->table .
            " u ON p.PatientID = u.UserID
                  WHERE p.PatientID = ? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $patientID);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }
}
?>
