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

    public function create($useExistingTransaction = false)
    {
        error_log("=== CREATE METHOD DEBUG START ===");
        error_log("useExistingTransaction: " . ($useExistingTransaction ? "true" : "false"));
        
        if (!$useExistingTransaction) {
            $this->conn->begin_transaction();
            error_log("New transaction started in create()");
        } else {
            error_log("Using existing transaction");
        }

        try {
            $query =
                "INSERT INTO " .
                $this->table .
                " (PatientID, DoctorID, DateTime, AppointmentType, Reason, CreatedAt) VALUES 
            (?, ?, ?, ?, ?, CURRENT_TIMESTAMP())
            ";
            error_log("Preparing SQL query: $query");
            error_log("Parameters: PatientID={$this->patientID}, DoctorID={$this->doctorID}, DateTime={$this->dateTime}, Type={$this->appointmentType}");
            
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                error_log("FAIL: Failed to prepare statement: " . $this->conn->error);
                throw new Exception("Failed to prepare appointment insert statement");
            }
            error_log("Statement prepared successfully");

            $stmt->bind_param(
                "iisss",
                $this->patientID,
                $this->doctorID,
                $this->dateTime,
                $this->appointmentType,
                $this->reason
            );
            error_log("Parameters bound successfully");

            if (!$stmt->execute()) {
                error_log("FAIL: Failed to execute statement: " . $stmt->error);
                throw new Exception("Failed to create appointment: " . $stmt->error);
            }
            error_log("Appointment insert executed successfully");

            $this->appointmentID = $this->conn->insert_id;
            error_log("Appointment ID: " . $this->appointmentID);

            // Get or create patient record
            $patientRecord = new PatientRecord($this->conn);
            error_log("PatientRecord object created");
            
            if (!$patientRecord->findByPatientID($this->patientID)) {
                error_log("Patient record not found, attempting to create...");
                // Patient record doesn't exist, create it
                if (!$patientRecord->createForPatient($this->patientID)) {
                    error_log("FAIL: Failed to create patient record");
                    throw new Exception("Failed to create patient record");
                }
                error_log("Patient record created successfully");
            } else {
                error_log("Patient record found: RecordID=" . $patientRecord->recordID);
            }

            // Note: AppointmentReport is automatically created by database trigger
            // No need to create it manually here
            error_log("AppointmentReport will be created automatically by database trigger");

            if (!$useExistingTransaction) {
                $this->conn->commit();
                error_log("Transaction committed in create()");
            }
            
            error_log("=== CREATE METHOD DEBUG END - SUCCESS ===");
            return true;
        } catch (Exception $e) {
            error_log("EXCEPTION in create(): " . $e->getMessage());
            if (!$useExistingTransaction) {
                $this->conn->rollback();
                error_log("Transaction rolled back in create()");
            }
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

    public function checkDoctorAvailabilityWithLock($doctorID, $dateTime)
    {
        // Use SELECT FOR UPDATE to prevent race conditions
        $query =
            "SELECT COUNT(*) as count FROM " .
            $this->table .
            " 
                  WHERE DoctorID = ? AND DateTime = ? FOR UPDATE";

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
        error_log("=== APPOINTMENT MODEL DEBUG START ===");
        error_log("Input parameters: patientID=$patientID, doctorID=$doctorID, dateTime=$dateTime, type=$appointmentType");
        
        // Start transaction and check availability with lock to prevent race conditions
        $this->conn->begin_transaction();
        error_log("Transaction started");
        
        try {
            // Set transaction timeout to prevent deadlocks
            $this->conn->query("SET SESSION innodb_lock_wait_timeout = 5");
            error_log("Lock timeout set");
            
            if (!$this->checkDoctorAvailabilityWithLock($doctorID, $dateTime)) {
                error_log("FAIL: Doctor not available with lock");
                $this->conn->rollback();
                return false;
            }
            error_log("Doctor availability with lock: CONFIRMED");

            $this->patientID = $patientID;
            $this->doctorID = $doctorID;
            $this->dateTime = $dateTime;
            $this->appointmentType = $appointmentType;
            $this->reason = $reason;
            error_log("Appointment object properties set");

            $result = $this->create(true); // Use existing transaction
            error_log("create() method result: " . ($result ? "SUCCESS" : "FAILED"));
            
            if ($result) {
                error_log("Committing transaction");
                $this->conn->commit();
                error_log("Transaction committed successfully");
            } else {
                error_log("Rolling back transaction due to create() failure");
                $this->conn->rollback();
            }
            
            error_log("=== APPOINTMENT MODEL DEBUG END ===");
            return $result;
        } catch (Exception $e) {
            error_log("EXCEPTION in createAppointment: " . $e->getMessage());
            $this->conn->rollback();
            error_log("Transaction rolled back due to exception");
            return false;
        }
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
