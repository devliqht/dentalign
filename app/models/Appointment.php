<?php
require_once "AppointmentReport.php";
require_once "PatientRecord.php";

class Appointment
{
    protected $conn;
    protected $table = "Appointment";

    public $appointmentID;
    public $patientID;
    public $doctorID;
    public $dateTime;
    public $appointmentType;
    public $reason;
    public $createdAt;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $this->conn->begin_transaction();

        try {
            $query =
                "INSERT INTO " .
                $this->table .
                " (PatientID, DoctorID, DateTime, AppointmentType, Reason, CreatedAt) VALUES 
            (?, ?, ?, ?, ?, CURRENT_TIMESTAMP())
            ";
            $stmt = $this->conn->prepare($query);

            $stmt->bind_param(
                "iisss",
                $this->patientID,
                $this->doctorID,
                $this->dateTime,
                $this->appointmentType,
                $this->reason
            );

            if (!$stmt->execute()) {
                throw new Exception("Failed to create appointment");
            }

            $this->appointmentID = $this->conn->insert_id;

            // Get patient record ID
            $patientRecord = new PatientRecord($this->conn);
            if (!$patientRecord->findByPatientID($this->patientID)) {
                throw new Exception("Patient record not found");
            }

            // Create AppointmentReport automatically
            $appointmentReport = new AppointmentReport($this->conn);
            if (
                !$appointmentReport->createForAppointment(
                    $this->appointmentID,
                    $patientRecord->recordID
                )
            ) {
                throw new Exception("Failed to create appointment report");
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function getAppointmentsByPatient($patientID)
    {
        $query =
            "SELECT a.*, u.FirstName as DoctorFirstName, u.LastName as DoctorLastName, d.Specialization
                  FROM " .
            $this->table .
            " a
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

    public function getUpcomingAppointmentsByPatient($patientID)
    {
        $query =
            "SELECT a.*, u.FirstName as DoctorFirstName, u.LastName as DoctorLastName, d.Specialization
                  FROM " .
            $this->table .
            " a
                  INNER JOIN Doctor d ON a.DoctorID = d.DoctorID
                  INNER JOIN USER u ON d.DoctorID = u.UserID
                  WHERE a.PatientID = ? AND a.DateTime >= NOW()
                  ORDER BY a.DateTime ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $patientID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }

    public function checkDoctorAvailability($doctorID, $dateTime)
    {
        $query =
            "SELECT COUNT(*) as count FROM " .
            $this->table .
            " 
                  WHERE DoctorID = ? AND DateTime = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("is", $doctorID, $dateTime);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row["count"] == 0;
        }

        return false;
    }

    public function createAppointment(
        $patientID,
        $doctorID,
        $dateTime,
        $appointmentType,
        $reason
    ) {
        if (!$this->checkDoctorAvailability($doctorID, $dateTime)) {
            return false;
        }

        $this->patientID = $patientID;
        $this->doctorID = $doctorID;
        $this->dateTime = $dateTime;
        $this->appointmentType = $appointmentType;
        $this->reason = $reason;

        return $this->create();
    }

    public function getAvailableTimeSlots($doctorID, $date)
    {
        // Define clinic hours (8 AM to 6 PM, 1-hour slots)
        $timeSlots = [];
        for ($hour = 8; $hour < 18; $hour++) {
            $timeSlots[] = sprintf("%02d:00", $hour);
        }

        $query =
            "SELECT TIME_FORMAT(TIME(DateTime), '%H:%i') as time FROM " .
            $this->table .
            " 
                  WHERE DoctorID = ? AND DATE(DateTime) = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("is", $doctorID, $date);

        $bookedTimes = [];
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $bookedTimes[] = $row["time"];
            }
        }

        // remove the booked times
        return array_diff($timeSlots, $bookedTimes);
    }

    public function getAllTimeSlotsWithStatus($doctorID, $date)
    {
        // Define clinic hours (8 AM to 6 PM, 1-hour slots)
        $allTimeSlots = [];
        for ($hour = 8; $hour < 18; $hour++) {
            $timeSlot = sprintf("%02d:00", $hour);
            $allTimeSlots[] = [
                "time" => $timeSlot,
                "available" => true,
            ];
        }

        $query =
            "SELECT TIME_FORMAT(TIME(DateTime), '%H:%i') as time FROM " .
            $this->table .
            " 
                  WHERE DoctorID = ? AND DATE(DateTime) = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("is", $doctorID, $date);

        $bookedTimes = [];
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $bookedTimes[] = $row["time"];
            }
        }

        // mark booked times as unavailable
        foreach ($allTimeSlots as &$slot) {
            if (in_array($slot["time"], $bookedTimes)) {
                $slot["available"] = false;
            }
        }

        return $allTimeSlots;
    }

    public function rescheduleAppointment($appointmentID, $newDateTime)
    {
        // first check if the appointment exists and belongs to the current session
        $checkQuery =
            "SELECT DoctorID FROM " . $this->table . " WHERE AppointmentID = ?";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bind_param("i", $appointmentID);

        if (!$checkStmt->execute()) {
            return false;
        }

        $result = $checkStmt->get_result();
        if ($result->num_rows === 0) {
            return false; // Appointment doesn't exist
        }

        $appointment = $result->fetch_assoc();
        $doctorID = $appointment["DoctorID"];

        // Check if the new time slot is available
        if (!$this->checkDoctorAvailability($doctorID, $newDateTime)) {
            return false; // new time slot is not available
        }

        $query =
            "UPDATE " .
            $this->table .
            " SET DateTime = ? WHERE AppointmentID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $newDateTime, $appointmentID);

        return $stmt->execute();
    }

    public function cancelAppointment($appointmentID)
    {
        $this->conn->begin_transaction();

        try {
            // Delete associated AppointmentReport first
            $appointmentReport = new AppointmentReport($this->conn);
            $appointmentReport->deleteByAppointmentID($appointmentID);

            // Delete the appointment
            $query = "DELETE FROM " . $this->table . " WHERE AppointmentID = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $appointmentID);

            if (!$stmt->execute()) {
                throw new Exception("Failed to cancel appointment");
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function getCompletedAppointmentsByPatient($patientID)
    {
        $query =
            "SELECT a.*, u.FirstName as DoctorFirstName, u.LastName as DoctorLastName, d.Specialization
                  FROM " .
            $this->table .
            " a
                  INNER JOIN Doctor d ON a.DoctorID = d.DoctorID
                  INNER JOIN USER u ON d.DoctorID = u.UserID
                  WHERE a.PatientID = ? AND a.DateTime < NOW()
                  ORDER BY a.DateTime DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $patientID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }

    public function getAppointmentById($appointmentID)
    {
        $query =
            "SELECT a.*, u.FirstName as DoctorFirstName, u.LastName as DoctorLastName, d.Specialization
                  FROM " .
            $this->table .
            " a
                  INNER JOIN Doctor d ON a.DoctorID = d.DoctorID
                  INNER JOIN USER u ON d.DoctorID = u.UserID
                  WHERE a.AppointmentID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $appointmentID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }

        return null;
    }
}

?>
