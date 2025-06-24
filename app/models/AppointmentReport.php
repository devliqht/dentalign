<?php

class AppointmentReport
{
    protected $conn;
    protected $table = "AppointmentReport";

    public $appointmentReportID;
    public $patientRecordID;
    public $appointmentID;
    public $bloodPressure;
    public $pulseRate;
    public $temperature;
    public $respiratoryRate;
    public $generalAppearance;
    public $createdAt;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        error_log("=== APPOINTMENT REPORT CREATE DEBUG START ===");
        
        $query =
            "INSERT INTO " .
            $this->table .
            " 
                  (PatientRecordID, AppointmentID, BloodPressure, PulseRate, Temperature, RespiratoryRate, GeneralAppearance) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        error_log("SQL Query: $query");
        error_log("Parameters: PatientRecordID={$this->patientRecordID}, AppointmentID={$this->appointmentID}");
        
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            error_log("FAIL: Failed to prepare AppointmentReport statement: " . $this->conn->error);
            return false;
        }
        error_log("Statement prepared successfully");

        // Clean data
        $this->bloodPressure = htmlspecialchars(
            strip_tags($this->bloodPressure)
        );
        $this->generalAppearance = htmlspecialchars(
            strip_tags($this->generalAppearance)
        );

        $stmt->bind_param(
            "iisidis",
            $this->patientRecordID,
            $this->appointmentID,
            $this->bloodPressure,
            $this->pulseRate,
            $this->temperature,
            $this->respiratoryRate,
            $this->generalAppearance
        );
        error_log("Parameters bound successfully");

        if ($stmt->execute()) {
            $this->appointmentReportID = $this->conn->insert_id;
            error_log("AppointmentReport insert successful, ID: " . $this->appointmentReportID);
            error_log("=== APPOINTMENT REPORT CREATE DEBUG END - SUCCESS ===");
            return true;
        } else {
            error_log("FAIL: AppointmentReport execute failed: " . $stmt->error);
            error_log("MySQL Error Code: " . $stmt->errno);
            error_log("=== APPOINTMENT REPORT CREATE DEBUG END - FAILED ===");
            return false;
        }
    }

    public function createForAppointment($appointmentID, $patientRecordID)
    {
        error_log("=== APPOINTMENT REPORT DEBUG START ===");
        error_log("Input: appointmentID=$appointmentID, patientRecordID=$patientRecordID");
        
        $this->appointmentID = $appointmentID;
        $this->patientRecordID = $patientRecordID;
        $this->bloodPressure = null;
        $this->pulseRate = null;
        $this->temperature = null;
        $this->respiratoryRate = null;
        $this->generalAppearance = null;
        
        error_log("Properties set, calling create()");
        $result = $this->create();
        error_log("create() result: " . ($result ? "SUCCESS" : "FAILED"));
        error_log("=== APPOINTMENT REPORT DEBUG END ===");

        return $result;
    }

    public function findByAppointmentID($appointmentID)
    {
        $query =
            "SELECT AppointmentReportID, PatientRecordID, AppointmentID, BloodPressure, PulseRate, Temperature, RespiratoryRate, GeneralAppearance, CreatedAt 
                  FROM " .
            $this->table .
            " 
                  WHERE AppointmentID = ? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $appointmentID);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->appointmentReportID = $row["AppointmentReportID"];
            $this->patientRecordID = $row["PatientRecordID"];
            $this->appointmentID = $row["AppointmentID"];
            $this->bloodPressure = $row["BloodPressure"];
            $this->pulseRate = $row["PulseRate"];
            $this->temperature = $row["Temperature"];
            $this->respiratoryRate = $row["RespiratoryRate"];
            $this->generalAppearance = $row["GeneralAppearance"];
            $this->createdAt = $row["CreatedAt"];
            return true;
        }

        return false;
    }

    public function update()
    {
        $query =
            "UPDATE " .
            $this->table .
            " 
                  SET BloodPressure = ?, PulseRate = ?, Temperature = ?, RespiratoryRate = ?, GeneralAppearance = ? 
                  WHERE AppointmentReportID = ?";

        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->bloodPressure = htmlspecialchars(
            strip_tags($this->bloodPressure)
        );
        $this->generalAppearance = htmlspecialchars(
            strip_tags($this->generalAppearance)
        );

        $stmt->bind_param(
            "sidisi",
            $this->bloodPressure,
            $this->pulseRate,
            $this->temperature,
            $this->respiratoryRate,
            $this->generalAppearance,
            $this->appointmentReportID
        );

        return $stmt->execute();
    }

    public function getReportByAppointmentID($appointmentID)
    {
        $query =
            "SELECT ar.*, pr.Height, pr.Weight, pr.Allergies
                  FROM " .
            $this->table .
            " ar
                  LEFT JOIN PatientRecord pr ON ar.PatientRecordID = pr.RecordID
                  WHERE ar.AppointmentID = ? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $appointmentID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }

        return null;
    }

    public function getReportsByPatientRecordID($patientRecordID)
    {
        $query =
            "SELECT ar.*, a.DateTime, a.AppointmentType, a.Reason
                  FROM " .
            $this->table .
            " ar
                  LEFT JOIN Appointment a ON ar.AppointmentID = a.AppointmentID
                  WHERE ar.PatientRecordID = ?
                  ORDER BY ar.CreatedAt DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $patientRecordID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }

    public function deleteByAppointmentID($appointmentID)
    {
        $query = "DELETE FROM " . $this->table . " WHERE AppointmentID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $appointmentID);

        return $stmt->execute();
    }
}

?>
