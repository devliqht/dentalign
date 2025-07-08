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
            if(!$this->create()){
                throw new Exception("Failed to create user");
            }

            $clinicStaffQuery =
                "INSERT INTO " .
                $this->clinicStaffTable .
                " 
                                (ClinicStaffID, StaffType) VALUES (?, ?)";
            $stmt = $this->conn->prepare($clinicStaffQuery);

            if(!$stmt){
                error_log(
                    "Failed to prepare patient query: " . $this->conn->error
                );
                throw new Exception("Database error occured");
            }

            $stmt->bind_param("is", $this->userID, $this->staffType);

            if(!$stmt->execute()){
                throw new Exception("Failed to create clinic staff record");
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
}