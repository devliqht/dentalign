<?php

class DentalChart
{
    private $conn;
    private $table = "DentalCharts";

    public $dentalChartID;
    public $patientID;
    public $dentistID;
    public $createdAt;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        if ($this->dentistID === null) {
            $query =
                "INSERT INTO " .
                $this->table .
                " 
                      SET PatientID = ?, DentistID = NULL";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $this->patientID);
        } else {
            $query =
                "INSERT INTO " .
                $this->table .
                " 
                      SET PatientID = ?, DentistID = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ii", $this->patientID, $this->dentistID);
        }

        if ($stmt->execute()) {
            $this->dentalChartID = $this->conn->insert_id;
            return true;
        }

        return false;
    }

    // Find dental chart by patient ID
    public function findByPatientID($patientID)
    {
        $query =
            "SELECT DentalChartID, PatientID, DentistID, CreatedAt 
                  FROM " .
            $this->table .
            " 
                  WHERE PatientID = ? 
                  ORDER BY CreatedAt DESC LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $patientID);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->dentalChartID = $row["DentalChartID"];
            $this->patientID = $row["PatientID"];
            $this->dentistID = $row["DentistID"];
            $this->createdAt = $row["CreatedAt"];
            return true;
        }

        return false;
    }

    // Get dental chart by ID
    public function findByID($chartID)
    {
        $query =
            "SELECT DentalChartID, PatientID, DentistID, CreatedAt 
                  FROM " .
            $this->table .
            " 
                  WHERE DentalChartID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $chartID);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->dentalChartID = $row["DentalChartID"];
            $this->patientID = $row["PatientID"];
            $this->dentistID = $row["DentistID"];
            $this->createdAt = $row["CreatedAt"];
            return true;
        }

        return false;
    }

    // Create dental chart for patient if it doesn't exist
    public function createForPatient($patientID, $dentistID = null)
    {
        if ($this->findByPatientID($patientID)) {
            return true;
        }

        $this->patientID = $patientID;

        if ($dentistID === null || $dentistID === 0 || $dentistID === "") {
            $this->dentistID = null;
        } else {
            $this->dentistID = $dentistID;
        }

        return $this->create();
    }

    // Get all charts for a patient
    public function getChartsByPatientID($patientID)
    {
        $query =
            "SELECT dc.*, u.FirstName, u.LastName 
                  FROM " .
            $this->table .
            " dc 
                  LEFT JOIN USER u ON dc.DentistID = u.UserID 
                  WHERE dc.PatientID = ? 
                  ORDER BY dc.CreatedAt DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $patientID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }
} ?> 