<?php

class PatientRecord
{
    protected $conn;
    protected $table = "PatientRecord";

    public $recordID;
    public $patientID;
    public $height;
    public $weight;
    public $allergies;
    public $createdAt;
    public $lastVisit;

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
                  (PatientID, Height, Weight, Allergies, LastVisit) 
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            error_log("Failed to prepare PatientRecord insert statement: " . $this->conn->error);
            return false;
        }

        // Clean data
        $this->allergies = $this->allergies ? htmlspecialchars(strip_tags($this->allergies)) : null;

        $stmt->bind_param(
            "iddss",
            $this->patientID,
            $this->height,
            $this->weight,
            $this->allergies,
            $this->lastVisit
        );

        if ($stmt->execute()) {
            $this->recordID = $this->conn->insert_id;
            return true;
        } else {
            error_log("Failed to execute PatientRecord insert: " . $stmt->error);
            return false;
        }
    }

    public function createForPatient($patientID)
    {
        $this->patientID = $patientID;
        $this->height = null;
        $this->weight = null;
        $this->allergies = null;
        $this->lastVisit = null;

        return $this->create();
    }

    public function findByPatientID($patientID)
    {
        $query =
            "SELECT RecordID, PatientID, Height, Weight, Allergies, CreatedAt, LastVisit 
                  FROM " .
            $this->table .
            " 
                  WHERE PatientID = ? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $patientID);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->recordID = $row["RecordID"];
            $this->patientID = $row["PatientID"];
            $this->height = $row["Height"];
            $this->weight = $row["Weight"];
            $this->allergies = $row["Allergies"];
            $this->createdAt = $row["CreatedAt"];
            $this->lastVisit = $row["LastVisit"];
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
                  SET Height = ?, Weight = ?, Allergies = ?, LastVisit = ? 
                  WHERE RecordID = ?";

        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->allergies = $this->allergies ? htmlspecialchars(strip_tags($this->allergies)) : null;

        $stmt->bind_param(
            "ddssi",
            $this->height,
            $this->weight,
            $this->allergies,
            $this->lastVisit,
            $this->recordID
        );

        return $stmt->execute();
    }

    public function updateLastVisit($patientID, $visitDate = null)
    {
        if ($visitDate === null) {
            $visitDate = date("Y-m-d");
        }

        $query =
            "UPDATE " .
            $this->table .
            " 
                  SET LastVisit = ? 
                  WHERE PatientID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $visitDate, $patientID);

        return $stmt->execute();
    }

    public function getRecordByPatientID($patientID)
    {
        $query =
            "SELECT * FROM " . $this->table . " WHERE PatientID = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $patientID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }

        return null;
    }
}

?>
