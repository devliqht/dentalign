<?php

require_once 'User.php';

class Doctor extends User {
    private $doctorTable = 'Doctor';
    private $clinicStaffTable = 'CLINIC_STAFF';

    public $doctorID;
    public $specialization;
    public $staffType = 'Doctor';

    public function createDoctor() {
        $this->conn->begin_transaction();
        
        try {
            $this->userType = 'ClinicStaff';
            if (!$this->create()) {
                throw new Exception("Failed to create user");
            }
            
            $clinicStaffQuery = "INSERT INTO " . $this->clinicStaffTable . " 
                                (ClinicStaffID, StaffType) VALUES (?, ?)";
            $stmt = $this->conn->prepare($clinicStaffQuery);
            $stmt->bind_param("is", $this->userID, $this->staffType);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to create clinic staff record");
            }
            
            $doctorQuery = "INSERT INTO " . $this->doctorTable . " 
                           (DoctorID, Specialization) VALUES (?, ?)";
            $stmt = $this->conn->prepare($doctorQuery);
            $stmt->bind_param("is", $this->userID, $this->specialization);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to create doctor record");
            }
            
            $this->doctorID = $this->userID;
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function findDoctorByEmail($email) {
        $query = "SELECT u.UserID, u.FirstName, u.LastName, u.Email, u.PasswordHash, u.UserType, u.CreatedAt,
                         cs.StaffType, d.Specialization
                  FROM " . $this->table . " u
                  INNER JOIN " . $this->clinicStaffTable . " cs ON u.UserID = cs.ClinicStaffID
                  INNER JOIN " . $this->doctorTable . " d ON cs.ClinicStaffID = d.DoctorID
                  WHERE u.Email = ? LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->userID = $row['UserID'];
            $this->doctorID = $row['UserID'];
            $this->firstName = $row['FirstName'];
            $this->lastName = $row['LastName'];
            $this->email = $row['Email'];
            $this->passwordHash = $row['PasswordHash'];
            $this->userType = $row['UserType'];
            $this->createdAt = $row['CreatedAt'];
            $this->staffType = $row['StaffType'];
            $this->specialization = $row['Specialization'];
            return true;
        }
        
        return false;
    }

    public function getAllDoctors() {
        $query = "SELECT u.UserID, u.FirstName, u.LastName, u.Email, d.Specialization
                  FROM " . $this->table . " u
                  INNER JOIN " . $this->clinicStaffTable . " cs ON u.UserID = cs.ClinicStaffID
                  INNER JOIN " . $this->doctorTable . " d ON cs.ClinicStaffID = d.DoctorID
                  ORDER BY u.LastName, u.FirstName";
        
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
