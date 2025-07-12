<?php

require_once "AppointmentReport.php";
require_once "PatientRecord.php";
require_once "Payment.php";

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
    public $status;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($useExistingTransaction = false)
    {
        error_log("=== CREATE METHOD DEBUG START ===");
        error_log(
            "useExistingTransaction: " .
                ($useExistingTransaction ? "true" : "false")
        );

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
                " (PatientID, DoctorID, DateTime, AppointmentType, Reason, CreatedAt, Status) VALUES 
            (?, ?, ?, ?, ?, CURRENT_TIMESTAMP(), 'Pending')
            ";
            error_log("Preparing SQL query: $query");
            error_log(
                "Parameters: PatientID={$this->patientID}, DoctorID={$this->doctorID}, DateTime={$this->dateTime}, Type={$this->appointmentType}"
            );

            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                error_log(
                    "FAIL: Failed to prepare statement: " . $this->conn->error
                );
                throw new Exception(
                    "Failed to prepare appointment insert statement"
                );
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
                throw new Exception(
                    "Failed to create appointment: " . $stmt->error
                );
            }
            error_log("Appointment insert executed successfully");

            $this->appointmentID = $this->conn->insert_id;
            error_log("Appointment ID: " . $this->appointmentID);

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
                error_log(
                    "Patient record found: RecordID=" . $patientRecord->recordID
                );
            }

            // Note: AppointmentReport is automatically created by database trigger
            // No need to create it manually here
            error_log(
                "AppointmentReport will be created automatically by database trigger"
            );

            // Create a default Payment entry for the new appointment
            $payment = new Payment($this->conn);
            if (!$payment->createForAppointment($this->appointmentID, $this->patientID, $this->dateTime)) {
                error_log("WARNING: Failed to create payment entry for appointment");
                // Don't fail the appointment creation if payment creation fails
                // This is a non-critical operation
            } else {
                error_log("Payment entry created successfully for appointment");
            }

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
                  WHERE a.PatientID = ? AND a.Status IN ('Pending', 'Approved', 'Rescheduled')
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
        error_log(
            "Input parameters: patientID=$patientID, doctorID=$doctorID, dateTime=$dateTime, type=$appointmentType"
        );

        $this->conn->begin_transaction();
        error_log("Transaction started");

        try {
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

            $result = $this->create(true);
            error_log(
                "create() method result: " . ($result ? "SUCCESS" : "FAILED")
            );

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
        $checkQuery =
            "SELECT DoctorID FROM " . $this->table . " WHERE AppointmentID = ?";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bind_param("i", $appointmentID);

        if (!$checkStmt->execute()) {
            return false;
        }

        $result = $checkStmt->get_result();
        if ($result->num_rows === 0) {
            return false;
        }

        $appointment = $result->fetch_assoc();
        $doctorID = $appointment["DoctorID"];

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

    public function hasPayments($appointmentID)
    {
        $query =
            "SELECT COUNT(*) as active_payment_count FROM Payments WHERE AppointmentID = ? AND Status != 'Cancelled'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $appointmentID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row["active_payment_count"] > 0;
        }

        return false;
    }

    public function hasPaidPayments($appointmentID)
    {
        $query = "SELECT COUNT(*) as paid_payment_count FROM Payments WHERE AppointmentID = ? AND Status = 'Paid'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $appointmentID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row["paid_payment_count"] > 0;
        }

        return false;
    }

    public function isWithin24Hours($appointmentID)
    {
        $query = "SELECT DateTime FROM " . $this->table . " WHERE AppointmentID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $appointmentID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($row) {
                $appointmentDateTime = strtotime($row["DateTime"]);
                $currentTime = time();
                $timeDifference = $appointmentDateTime - $currentTime;
                
                return $timeDifference < 86400;
            }
        }

        return false;
    }

    public function cancelAppointment($appointmentID)
    {
        $query =
            "UPDATE " .
            $this->table .
            " SET Status = 'Pending Cancellation' WHERE AppointmentID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $appointmentID);

        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }

        return false;
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
                  WHERE a.PatientID = ? AND a.Status IN ('Completed', 'Declined')
                  ORDER BY a.DateTime DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $patientID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }

    public function getCancelledAppointmentsByPatient($patientID)
    {
        $query =
            "SELECT a.*, u.FirstName as DoctorFirstName, u.LastName as DoctorLastName, d.Specialization
                  FROM " .
            $this->table .
            " a
                  INNER JOIN Doctor d ON a.DoctorID = d.DoctorID
                  INNER JOIN USER u ON d.DoctorID = u.UserID
                  WHERE a.PatientID = ? AND a.Status = 'Cancelled'
                  ORDER BY a.DateTime DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $patientID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }

    public function getPendingCancellationsByPatient($patientID)
    {
        $query =
            "SELECT a.*, u.FirstName as DoctorFirstName, u.LastName as DoctorLastName, d.Specialization
                  FROM " .
            $this->table .
            " a
                  INNER JOIN Doctor d ON a.DoctorID = d.DoctorID
                  INNER JOIN USER u ON d.DoctorID = u.UserID
                  WHERE a.PatientID = ? AND a.Status = 'Pending Cancellation'
                  ORDER BY a.DateTime DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $patientID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }

    public function getPendingCancellationsByDoctor($doctorID)
    {
        $query =
            "SELECT a.*, u.FirstName as PatientFirstName, u.LastName as PatientLastName, u.Email as PatientEmail
                  FROM " .
            $this->table .
            " a
                  INNER JOIN USER u ON a.PatientID = u.UserID
                  WHERE a.DoctorID = ? AND a.Status = 'Pending Cancellation'
                  ORDER BY a.DateTime ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $doctorID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }

    public function approveCancellation($appointmentID)
    {
        // Start a transaction to ensure both appointment and payment are updated together
        $this->conn->begin_transaction();
        
        try {
            // First, update the appointment status
            $query =
                "UPDATE " .
                $this->table .
                " SET Status = 'Cancelled' WHERE AppointmentID = ? AND Status = 'Pending Cancellation'";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $appointmentID);

            if (!$stmt->execute() || $stmt->affected_rows === 0) {
                $this->conn->rollback();
                return false;
            }

            require_once __DIR__ . "/Payment.php";
            $payment = new Payment($this->conn);
            
            $paymentData = $payment->getPaymentByAppointment($appointmentID);
            
            if ($paymentData && strtolower($paymentData["Status"]) !== "cancelled") {
                $paymentCancelled = $payment->updateStatus(
                    $paymentData["PaymentID"], 
                    "Cancelled", 
                    null, // updatedBy can be null for system actions
                    "Payment cancelled due to appointment cancellation"
                );
                
                if (!$paymentCancelled) {
                    $this->conn->rollback();
                    return false;
                }
            }
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Error in approveCancellation: " . $e->getMessage());
            return false;
        }
    }

    public function denyCancellation($appointmentID, $newStatus = "Approved")
    {
        $query =
            "UPDATE " .
            $this->table .
            " SET Status = ? WHERE AppointmentID = ? AND Status = 'Pending Cancellation'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $newStatus, $appointmentID);

        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }

        return false;
    }

    public function getAppointmentById($appointmentID)
    {
        $query =
            "SELECT a.*, 
                    ud.FirstName as DoctorFirstName, ud.LastName as DoctorLastName, d.Specialization,
                    up.FirstName as PatientFirstName, up.LastName as PatientLastName, up.Email as PatientEmail
                  FROM " .
            $this->table .
            " a
                  INNER JOIN Doctor d ON a.DoctorID = d.DoctorID
                  INNER JOIN USER ud ON d.DoctorID = ud.UserID
                  INNER JOIN USER up ON a.PatientID = up.UserID
                  WHERE a.AppointmentID = ? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $appointmentID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            }
        }

        return null;
    }

    public function getAppointmentsByDoctor($doctorID)
    {
        $query =
            "SELECT a.*, u.FirstName as PatientFirstName, u.LastName as PatientLastName, u.Email as PatientEmail
                  FROM " .
            $this->table .
            " a
                  INNER JOIN USER u ON a.PatientID = u.UserID
                  WHERE a.DoctorID = ?
                  ORDER BY a.DateTime DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $doctorID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }

    public function getUpcomingAppointmentsByDoctor($doctorID)
    {
        $query =
            "SELECT a.*, u.FirstName as PatientFirstName, u.LastName as PatientLastName, u.Email as PatientEmail
                  FROM " .
            $this->table .
            " a
                  INNER JOIN USER u ON a.PatientID = u.UserID
                  WHERE a.DoctorID = ? AND a.Status IN ('Pending', 'Approved', 'Rescheduled')
                  ORDER BY a.DateTime ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $doctorID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }

    public function getTodaysAppointmentsByDoctor($doctorID)
    {
        $query =
            "SELECT a.*, u.FirstName as PatientFirstName, u.LastName as PatientLastName, u.Email as PatientEmail
                  FROM " .
            $this->table .
            " a
                  INNER JOIN USER u ON a.PatientID = u.UserID
                  WHERE a.DoctorID = ? AND DATE(a.DateTime) = CURDATE() AND a.Status IN ('Pending', 'Approved', 'Rescheduled')
                  ORDER BY a.DateTime ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $doctorID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }

    public function getAppointmentHistoryByDoctor($doctorID)
    {
        $query =
            "SELECT a.*, u.FirstName as PatientFirstName, u.LastName as PatientLastName, u.Email as PatientEmail
                  FROM " .
            $this->table .
            " a
                  INNER JOIN USER u ON a.PatientID = u.UserID
                  WHERE a.DoctorID = ?
                  ORDER BY a.DateTime DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $doctorID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }

    public function getAppointmentsByDoctorAndDateRange(
        $doctorID,
        $startDate,
        $endDate
    ) {
        $query =
            "SELECT a.*, u.FirstName as PatientFirstName, u.LastName as PatientLastName, u.Email as PatientEmail
                  FROM " .
            $this->table .
            " a
                  INNER JOIN USER u ON a.PatientID = u.UserID
                  WHERE a.DoctorID = ? AND DATE(a.DateTime) BETWEEN ? AND ?
                  ORDER BY a.DateTime ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iss", $doctorID, $startDate, $endDate);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }

    public function getAppointmentsByDoctorAndDate($doctorID, $date)
    {
        $query =
            "SELECT a.*, u.FirstName as PatientFirstName, u.LastName as PatientLastName, u.Email as PatientEmail
                  FROM " .
            $this->table .
            " a
                  INNER JOIN USER u ON a.PatientID = u.UserID
                  WHERE a.DoctorID = ? AND DATE(a.DateTime) = ?
                  ORDER BY a.DateTime ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("is", $doctorID, $date);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }

    public function searchAppointmentsByPatient($patientID, $query)
    {
        $searchTerm = "%" . $query . "%";

        $sql =
            "SELECT 
                    a.AppointmentID,
                    a.DateTime,
                    a.AppointmentType,
                    a.Reason,
                    DATE(a.DateTime) as AppointmentDate,
                    TIME(a.DateTime) as AppointmentTime,
                    ud.FirstName as DoctorFirstName, 
                    ud.LastName as DoctorLastName, 
                    d.Specialization
                FROM " .
            $this->table .
            " a
                INNER JOIN Doctor d ON a.DoctorID = d.DoctorID
                INNER JOIN USER ud ON d.DoctorID = ud.UserID
                WHERE a.PatientID = ? AND (
                    CAST(a.AppointmentID AS CHAR) LIKE ? OR
                    a.AppointmentType LIKE ? OR
                    a.Reason LIKE ? OR
                    CONCAT(ud.FirstName, ' ', ud.LastName) LIKE ? OR
                    d.Specialization LIKE ?
                )
                ORDER BY a.DateTime DESC
                LIMIT 10";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("SQL prepare failed: " . $this->conn->error);
            return [];
        }

        $stmt->bind_param(
            "isssss",
            $patientID,
            $searchTerm,
            $searchTerm,
            $searchTerm,
            $searchTerm,
            $searchTerm
        );

        if (!$stmt->execute()) {
            error_log("SQL execute failed: " . $stmt->error);
            return [];
        }

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateAppointmentStatus($appointmentID)
    {
        if (empty($this->status)) {
            error_log("ERROR: Status is empty or not set");
            return false;
        }

        if (strtolower($this->status) === 'cancelled') {
            $this->conn->begin_transaction();
            
            try {
                $sql =
                    "UPDATE " .
                    $this->table .
                    " SET Status = ? WHERE AppointmentID = ?";
                $stmt = $this->conn->prepare($sql);

                if (!$stmt) {
                    error_log("Failed to prepare statement: " . $this->conn->error);
                    $this->conn->rollback();
                    return false;
                }

                $stmt->bind_param("si", $this->status, $appointmentID);

                if (!$stmt->execute()) {
                    error_log("Failed to update appointment status: " . $stmt->error);
                    $this->conn->rollback();
                    return false;
                }

                require_once __DIR__ . "/Payment.php";
                $payment = new Payment($this->conn);
                
                // Get the payment for this appointment
                $paymentData = $payment->getPaymentByAppointment($appointmentID);
                
                if ($paymentData && strtolower($paymentData["Status"]) !== "cancelled") {
                    // Cancel the payment with a note
                    $paymentCancelled = $payment->updateStatus(
                        $paymentData["PaymentID"], 
                        "Cancelled", 
                        null, // updatedBy can be null for system actions
                        "Payment cancelled due to appointment cancellation"
                    );
                    
                    if (!$paymentCancelled) {
                        $this->conn->rollback();
                        return false;
                    }
                }
                
                $this->conn->commit();
                return true;
                
            } catch (Exception $e) {
                $this->conn->rollback();
                error_log("Error in updateAppointmentStatus: " . $e->getMessage());
                return false;
            }
        } else {
            // For non-cancellation status updates, proceed normally
            $sql =
                "UPDATE " .
                $this->table .
                " SET Status = ? WHERE AppointmentID = ?";
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                error_log("Failed to prepare statement: " . $this->conn->error);
                return false;
            }

            $stmt->bind_param("si", $this->status, $appointmentID);

            if ($stmt->execute()) {
                return true;
            }

            error_log("Failed to update appointment status: " . $stmt->error);
            return false;
        }
    }
}
