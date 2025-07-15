<?php

require_once "User.php";

class DentalAssistant extends User
{
    private $clinicStaffTable = "CLINIC_STAFF";

    public $dentalAssistantID;
    public $staffType = "DentalAssistant";

    public function createDentalAssistant()
    {
        $this->conn->begin_transaction();

        try {
            $this->userType = "ClinicStaff";
            if (!$this->create()) {
                throw new Exception("Failed to create user");
            }

            $clinicStaffQuery =
                "INSERT INTO " .
                $this->clinicStaffTable .
                " 
                                (ClinicStaffID, StaffType) VALUES (?, ?)";
            $stmt = $this->conn->prepare($clinicStaffQuery);

            if (!$stmt) {
                error_log(
                    "Failed to prepare patient query: " . $this->conn->error
                );
                throw new Exception("Database error occured");
            }

            $stmt->bind_param("is", $this->userID, $this->staffType);

            if (!$stmt->execute()) {
                throw new Exception("Failed to create clinic staff record");
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
    public function findDentalAssistantByEmail($email)
    {
        // This query joins the USER and CLINIC_STAFF tables.
        // It's just like the doctor query, but without the Doctor table join.
        $query =
            "SELECT u.UserID, u.FirstName, u.LastName, u.Email, u.PasswordHash, u.UserType, u.CreatedAt,
                         cs.StaffType
                  FROM " .
            $this->table .
            " u
                  INNER JOIN " .
            $this->clinicStaffTable .
            " cs ON u.UserID = cs.ClinicStaffID
                  WHERE u.Email = ? AND cs.StaffType = 'DentalAssistant' LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->userID = $row["UserID"];
            $this->dentalAssistantID = $row["UserID"]; // Set this property
            $this->firstName = $row["FirstName"];
            $this->lastName = $row["LastName"];
            $this->email = $row["Email"];
            $this->passwordHash = $row["PasswordHash"];
            $this->userType = $row["UserType"]; // Will be "ClinicStaff"
            $this->createdAt = $row["CreatedAt"];
            $this->staffType = $row["StaffType"]; // Will be "DentalAssistant"
            return true;
        }

        return false;
    }

    public function findById($userID)
    {
        // This query is just like the Doctor's, but without the final join
        $query =
            "SELECT u.UserID, u.FirstName, u.LastName, u.Email, u.UserType, u.CreatedAt,
                         cs.StaffType
                  FROM " .
            $this->table .
            " u
                  INNER JOIN " .
            $this->clinicStaffTable .
            " cs ON u.UserID = cs.ClinicStaffID
                  WHERE u.UserID = ? AND cs.StaffType = 'DentalAssistant' LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userID);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->userID = $row["UserID"];
            $this->dentalAssistantID = $row["UserID"]; // Set the dentalAssistantID
            $this->firstName = $row["FirstName"];
            $this->lastName = $row["LastName"];
            $this->email = $row["Email"];
            $this->userType = $row["UserType"]; // Will be "ClinicStaff"
            $this->createdAt = $row["CreatedAt"];
            $this->staffType = $row["StaffType"]; // Will be "DentalAssistant"
            return true;
        }

        return false;
    }
    //    public function findDentalAssistantByEmail($email)
    //     {
    //         $query =
    //             "SELECT u.UserID, u.FirstName, u.LastName, u.Email, u.PasswordHash, u.UserType, u.CreatedAt,
    //                          cs.StaffType
    //                   FROM " .
    //             $this->table .
    //             " u
    //                   INNER JOIN " .
    //             $this->clinicStaffTable .
    //             " cs ON u.UserID = cs.ClinicStaffID
    //                   WHERE u.Email = ? AND cs.StaffType = 'DentalAssistant' LIMIT 1";

    //         $stmt = $this->conn->prepare($query);
    //         $stmt->bind_param("s", $email);
    //         $stmt->execute();

    //         $result = $stmt->get_result();

    //         if ($result->num_rows > 0) {
    //             $row = $result->fetch_assoc();
    //             $this->userID = $row["UserID"];
    //             $this->dentalAssistantID = $row["UserID"];
    //             $this->firstName = $row["FirstName"];
    //             $this->lastName = $row["LastName"];
    //             $this->email = $row["Email"];
    //             $this->passwordHash = $row["PasswordHash"];
    //             $this->userType = $row["UserType"];
    //             $this->createdAt = $row["CreatedAt"];
    //             $this->staffType = $row["StaffType"];
    //             return true;
    //         }

    //         return false;
    //     }
}
