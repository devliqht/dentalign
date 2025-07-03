<?php

class AppointmentReport
{
    protected $conn;
    protected $table = "AppointmentReport";

    public $appointmentReportID;
    public $patientRecordID;
    public $appointmentID;
    public $oralNotes;
    public $diagnosis;
    public $xrayImages;
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
                  (PatientRecordID, AppointmentID, OralNotes, Diagnosis, XrayImages) 
                  VALUES (?, ?, ?, ?, ?)";

        error_log("SQL Query: $query");
        error_log(
            "Parameters: PatientRecordID={$this->patientRecordID}, AppointmentID={$this->appointmentID}"
        );

        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            error_log(
                "FAIL: Failed to prepare AppointmentReport statement: " .
                    $this->conn->error
            );
            return false;
        }
        error_log("Statement prepared successfully");

        $this->oralNotes = htmlspecialchars(strip_tags($this->oralNotes));
        $this->diagnosis = htmlspecialchars(strip_tags($this->diagnosis));

        $stmt->bind_param(
            "iisss",
            $this->patientRecordID,
            $this->appointmentID,
            $this->oralNotes,
            $this->diagnosis,
            $this->xrayImages
        );

        if ($stmt->execute()) {
            $this->appointmentReportID = $this->conn->insert_id;
            error_log(
                "AppointmentReport insert successful, ID: " .
                    $this->appointmentReportID
            );
            error_log("=== APPOINTMENT REPORT CREATE DEBUG END - SUCCESS ===");
            return true;
        } else {
            error_log(
                "FAIL: AppointmentReport execute failed: " . $stmt->error
            );
            error_log("MySQL Error Code: " . $stmt->errno);
            error_log("=== APPOINTMENT REPORT CREATE DEBUG END - FAILED ===");
            return false;
        }
    }

    public function createForAppointment($appointmentID, $patientRecordID)
    {
        error_log("=== APPOINTMENT REPORT DEBUG START ===");
        error_log(
            "Input: appointmentID=$appointmentID, patientRecordID=$patientRecordID"
        );

        $this->appointmentID = $appointmentID;
        $this->patientRecordID = $patientRecordID;
        $this->oralNotes = null;
        $this->diagnosis = null;
        $this->xrayImages = null;

        error_log("Properties set, calling create()");
        $result = $this->create();
        error_log("create() result: " . ($result ? "SUCCESS" : "FAILED"));
        error_log("=== APPOINTMENT REPORT DEBUG END ===");

        return $result;
    }

    public function findByAppointmentID($appointmentID)
    {
        $query =
            "SELECT AppointmentReportID, PatientRecordID, AppointmentID, OralNotes, Diagnosis, XrayImages, CreatedAt 
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
            $this->oralNotes = $row["OralNotes"];
            $this->diagnosis = $row["Diagnosis"];
            $this->xrayImages = $row["XrayImages"];
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
                  SET OralNotes = ?, Diagnosis = ?, XrayImages = ?
                  WHERE AppointmentReportID = ?";

        $stmt = $this->conn->prepare($query);

        $this->oralNotes = htmlspecialchars(strip_tags($this->oralNotes));
        $this->diagnosis = htmlspecialchars(strip_tags($this->diagnosis));

        $stmt->bind_param(
            "sssi",
            $this->oralNotes,
            $this->diagnosis,
            $this->xrayImages,
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
