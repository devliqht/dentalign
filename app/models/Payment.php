<?php

require_once "OverdueConfig.php";

class Payment
{
    protected $conn;
    protected $table = "Payments";

    public $paymentID;
    public $appointmentID;
    public $patientID;
    public $status;
    public $updatedBy;
    public $updatedAt;
    public $notes;
    public $deadlineDate;
    public $paymentMethod;
    public $proofOfPayment;

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
                  (AppointmentID, PatientID, Status, UpdatedBy, Notes, DeadlineDate, PaymentMethod, ProofOfPayment) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->notes = htmlspecialchars(strip_tags($this->notes));
        $this->paymentMethod = htmlspecialchars(
            strip_tags($this->paymentMethod ?? "Cash")
        );
        $this->proofOfPayment = htmlspecialchars(
            strip_tags($this->proofOfPayment ?? "")
        );

        $stmt->bind_param(
            "iisissss",
            $this->appointmentID,
            $this->patientID,
            $this->status,
            $this->updatedBy,
            $this->notes,
            $this->deadlineDate,
            $this->paymentMethod,
            $this->proofOfPayment
        );

        if ($stmt->execute()) {
            $this->paymentID = $this->conn->insert_id;
            return true;
        }

        return false;
    }

    // Create a default payment entry for a new appointment
    public function createForAppointment(
        $appointmentID,
        $patientID,
        $appointmentDateTime = null,
        $updatedBy = null
    ) {
        $this->appointmentID = $appointmentID;
        $this->patientID = $patientID;
        $this->status = "Pending";
        $this->updatedBy = $updatedBy;
        $this->notes = "Auto-created for new appointment";

        // Set deadline to 30 days after appointment date, or 30 days from now if no date provided
        if ($appointmentDateTime) {
            $this->deadlineDate = date(
                "Y-m-d",
                strtotime($appointmentDateTime . " + 30 days")
            );
        } else {
            $this->deadlineDate = date("Y-m-d", strtotime("+30 days"));
        }

        $this->paymentMethod = "Cash";
        $this->proofOfPayment = "";

        return $this->create();
    }

    // Soft delete - set status to 'Cancelled' instead of deleting
    public function softDelete(
        $paymentID,
        $updatedBy = null,
        $notes = "Payment cancelled"
    ) {
        $query =
            "UPDATE " .
            $this->table .
            " 
                  SET Status = 'Cancelled', UpdatedBy = ?, Notes = ?, UpdatedAt = CURRENT_TIMESTAMP 
                  WHERE PaymentID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isi", $updatedBy, $notes, $paymentID);

        return $stmt->execute();
    }

    public function getPaymentsByPatient($patientID)
    {
        $query =
            "SELECT 
                    p.PaymentID,
                    p.AppointmentID,
                    p.PatientID,
                    p.Status,
                    p.UpdatedBy,
                    p.UpdatedAt,
                    p.Notes,
                    p.DeadlineDate,
                    p.PaymentMethod,
                    p.ProofOfPayment,
                    a.DateTime as AppointmentDateTime,
                    a.AppointmentType,
                    a.Reason,
                    CONCAT(COALESCE(u.FirstName, ''), ' ', COALESCE(u.LastName, '')) as DoctorName,
                    COALESCE(d.Specialization, 'General') as Specialization,
                    COALESCE(staff_user.FirstName, 'System') as UpdatedByName
                  FROM " .
            $this->table .
            " p
                  LEFT JOIN Appointment a ON p.AppointmentID = a.AppointmentID
                  LEFT JOIN Doctor d ON a.DoctorID = d.DoctorID
                  LEFT JOIN USER u ON d.DoctorID = u.UserID
                  LEFT JOIN CLINIC_STAFF staff ON p.UpdatedBy = staff.ClinicStaffID
                  LEFT JOIN USER staff_user ON staff.ClinicStaffID = staff_user.UserID
                  WHERE p.PatientID = ?
                  ORDER BY p.UpdatedAt DESC";

        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            error_log(
                "SQL Error in getPaymentsByPatient: " . $this->conn->error
            );
            return [];
        }

        $stmt->bind_param("i", $patientID);
        $stmt->execute();

        $result = $stmt->get_result();
        $payments = $result->fetch_all(MYSQLI_ASSOC);

        // Add total amounts and calculate overdue amounts
        $overdueConfig = new OverdueConfig($this->conn);
        foreach ($payments as &$payment) {
            $payment["total_amount"] = $this->getTotalAmount(
                $payment["PaymentID"]
            );
            $payment["original_amount"] = $payment["total_amount"];

            // Calculate overdue amount if applicable
            if (
                $overdueConfig->isPaymentOverdue($payment["DeadlineDate"]) &&
                strtolower($payment["Status"]) === "pending"
            ) {
                $payment[
                    "total_amount"
                ] = $overdueConfig->calculateOverdueAmount(
                    $payment["total_amount"],
                    $payment["DeadlineDate"]
                );
                $payment["is_overdue"] = true;
                $payment["overdue_amount"] =
                    $payment["total_amount"] - $payment["original_amount"];
            } else {
                $payment["is_overdue"] = false;
                $payment["overdue_amount"] = 0;
            }
        }

        return $payments;
    }

    public function getPaymentByAppointment($appointmentID)
    {
        $query =
            "SELECT 
                    p.PaymentID,
                    p.AppointmentID,
                    p.PatientID,
                    p.Status,
                    p.UpdatedBy,
                    p.UpdatedAt,
                    p.Notes,
                    p.DeadlineDate,
                    p.PaymentMethod,
                    p.ProofOfPayment,
                    a.DateTime as AppointmentDateTime,
                    a.AppointmentType,
                    a.Reason,
                    CONCAT(COALESCE(u.FirstName, ''), ' ', COALESCE(u.LastName, '')) as DoctorName,
                    COALESCE(d.Specialization, 'General') as Specialization,
                    COALESCE(staff_user.FirstName, 'System') as UpdatedByName
                  FROM " .
            $this->table .
            " p
                  LEFT JOIN Appointment a ON p.AppointmentID = a.AppointmentID
                  LEFT JOIN Doctor d ON a.DoctorID = d.DoctorID
                  LEFT JOIN USER u ON d.DoctorID = u.UserID
                  LEFT JOIN CLINIC_STAFF staff ON p.UpdatedBy = staff.ClinicStaffID
                  LEFT JOIN USER staff_user ON staff.ClinicStaffID = staff_user.UserID
                  WHERE p.AppointmentID = ?
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            error_log(
                "SQL Error in getPaymentByAppointment: " . $this->conn->error
            );
            return null;
        }

        $stmt->bind_param("i", $appointmentID);
        $stmt->execute();

        $result = $stmt->get_result();
        $payment = $result->fetch_assoc();

        if ($payment) {
            // Apply overdue calculations
            $overdueConfig = new OverdueConfig($this->conn);
            $payment["total_amount"] = $this->getTotalAmount(
                $payment["PaymentID"]
            );
            $payment["original_amount"] = $payment["total_amount"];

            // Calculate overdue amount if applicable
            if (
                $overdueConfig->isPaymentOverdue($payment["DeadlineDate"]) &&
                strtolower($payment["Status"]) === "pending"
            ) {
                $payment[
                    "total_amount"
                ] = $overdueConfig->calculateOverdueAmount(
                    $payment["total_amount"],
                    $payment["DeadlineDate"]
                );
                $payment["is_overdue"] = true;
                $payment["overdue_amount"] =
                    $payment["total_amount"] - $payment["original_amount"];
            } else {
                $payment["is_overdue"] = false;
                $payment["overdue_amount"] = 0;
            }
        }

        return $payment;
    }

    public function updateStatus($paymentID, $status, $updatedBy, $notes = null)
    {
        $query =
            "UPDATE " .
            $this->table .
            " 
                  SET Status = ?, UpdatedBy = ?, Notes = ?, UpdatedAt = CURRENT_TIMESTAMP 
                  WHERE PaymentID = ?";

        $stmt = $this->conn->prepare($query);

        // Clean data
        $status = htmlspecialchars(strip_tags($status));
        $notes = $notes ? htmlspecialchars(strip_tags($notes)) : null;

        $stmt->bind_param("sisi", $status, $updatedBy, $notes, $paymentID);

        return $stmt->execute();
    }

    public function updatePaymentDetails(
        $paymentID,
        $status,
        $updatedBy,
        $notes = null,
        $paymentMethod = null,
        $deadlineDate = null,
        $proofOfPayment = null
    ) {
        $query =
            "UPDATE " .
            $this->table .
            " 
                  SET Status = ?, UpdatedBy = ?, Notes = ?, PaymentMethod = ?, DeadlineDate = ?, ProofOfPayment = ?, UpdatedAt = CURRENT_TIMESTAMP 
                  WHERE PaymentID = ?";

        $stmt = $this->conn->prepare($query);

        // Clean data
        $status = htmlspecialchars(strip_tags($status));
        $notes = $notes ? htmlspecialchars(strip_tags($notes)) : null;
        $paymentMethod = $paymentMethod
            ? htmlspecialchars(strip_tags($paymentMethod))
            : null;
        $proofOfPayment = $proofOfPayment
            ? htmlspecialchars(strip_tags($proofOfPayment))
            : null;

        $stmt->bind_param(
            "sissssi",
            $status,
            $updatedBy,
            $notes,
            $paymentMethod,
            $deadlineDate,
            $proofOfPayment,
            $paymentID
        );

        return $stmt->execute();
    }

    public function getPaymentWithBreakdown($paymentID)
    {
        // Get payment details
        $paymentQuery =
            "SELECT 
                           p.PaymentID,
                           p.AppointmentID,
                           p.PatientID,
                           p.Status,
                           p.UpdatedBy,
                           p.UpdatedAt,
                           p.Notes,
                           p.DeadlineDate,
                           p.PaymentMethod,
                           p.ProofOfPayment,
                           a.DateTime as AppointmentDateTime,
                           a.AppointmentType,
                           a.Reason,
                           CONCAT(COALESCE(u.FirstName, ''), ' ', COALESCE(u.LastName, '')) as DoctorName,
                           COALESCE(d.Specialization, 'General') as Specialization,
                           CONCAT(COALESCE(p_user.FirstName, ''), ' ', COALESCE(p_user.LastName, '')) as PatientName,
                           p_user.Email as PatientEmail
                         FROM " .
            $this->table .
            " p
                         LEFT JOIN Appointment a ON p.AppointmentID = a.AppointmentID
                         LEFT JOIN Doctor d ON a.DoctorID = d.DoctorID
                         LEFT JOIN USER u ON d.DoctorID = u.UserID
                         LEFT JOIN PATIENT pat ON p.PatientID = pat.PatientID
                         LEFT JOIN USER p_user ON pat.PatientID = p_user.UserID
                         WHERE p.PaymentID = ?
                         LIMIT 1";

        $stmt = $this->conn->prepare($paymentQuery);

        if (!$stmt) {
            error_log(
                "SQL Error in getPaymentWithBreakdown: " . $this->conn->error
            );
            return null;
        }

        $stmt->bind_param("i", $paymentID);
        $stmt->execute();
        $payment = $stmt->get_result()->fetch_assoc();

        if (!$payment) {
            return null;
        }

        $this->ensurePaymentItems($paymentID);

        $itemsQuery = "SELECT 
                         PaymentItemID,
                         Description,
                         Amount,
                         Quantity,
                         Total,
                         TreatmentItemID
                       FROM PaymentItems
                       WHERE PaymentID = ?
                       ORDER BY PaymentItemID";

        $stmt = $this->conn->prepare($itemsQuery);
        $stmt->bind_param("i", $paymentID);
        $stmt->execute();
        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $payment["items"] = $items;
        $payment["total_amount"] = !empty($items)
            ? array_sum(array_column($items, "Total"))
            : 0;

        // Apply overdue calculations
        $overdueConfig = new OverdueConfig($this->conn);
        $payment["original_amount"] = $payment["total_amount"];

        // Calculate overdue amount if applicable
        if (
            $overdueConfig->isPaymentOverdue($payment["DeadlineDate"]) &&
            strtolower($payment["Status"]) === "pending"
        ) {
            $payment["total_amount"] = $overdueConfig->calculateOverdueAmount(
                $payment["total_amount"],
                $payment["DeadlineDate"]
            );
            $payment["is_overdue"] = true;
            $payment["overdue_amount"] =
                $payment["total_amount"] - $payment["original_amount"];
        } else {
            $payment["is_overdue"] = false;
            $payment["overdue_amount"] = 0;
        }

        return $payment;
    }

    public function getTotalAmount($paymentID)
    {
        $query =
            "SELECT SUM(Total) as total_amount FROM PaymentItems WHERE PaymentID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $paymentID);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        return $result["total_amount"] ?? 0;
    }

    public function ensurePaymentItems($paymentID)
    {
        // Check if payment items exist for this payment
        $checkQuery =
            "SELECT COUNT(*) as count FROM PaymentItems WHERE PaymentID = ?";
        $stmt = $this->conn->prepare($checkQuery);
        $stmt->bind_param("i", $paymentID);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result["count"] == 0) {
            // Get appointment type to create default payment item
            $appointmentQuery = "SELECT a.AppointmentType FROM Payments p 
                               JOIN Appointment a ON p.AppointmentID = a.AppointmentID 
                               WHERE p.PaymentID = ?";
            $stmt = $this->conn->prepare($appointmentQuery);
            $stmt->bind_param("i", $paymentID);
            $stmt->execute();
            $appointmentResult = $stmt->get_result()->fetch_assoc();

            if ($appointmentResult) {
                $appointmentType = $appointmentResult["AppointmentType"];

                // Determine price based on appointment type
                $prices = [
                    "Consultation" => 75.0,
                    "Cleaning" => 120.0,
                    "Checkup" => 95.0,
                    "Filling" => 180.0,
                    "Root Canal" => 850.0,
                    "Extraction" => 150.0,
                    "Orthodontics" => 2500.0,
                ];

                $amount = $prices[$appointmentType] ?? 100.0;
                $description =
                    $appointmentType === "Consultation"
                        ? "General Consultation"
                        : ($appointmentType === "Cleaning"
                            ? "Dental Cleaning"
                            : ($appointmentType === "Checkup"
                                ? "Routine Checkup"
                                : $appointmentType . " Service"));

                // Insert default payment item
                $insertQuery = "INSERT INTO PaymentItems (PaymentID, Description, Amount, Quantity, Total) 
                               VALUES (?, ?, ?, 1, ?)";
                $stmt = $this->conn->prepare($insertQuery);
                $stmt->bind_param(
                    "isdd",
                    $paymentID,
                    $description,
                    $amount,
                    $amount
                );
                $stmt->execute();

                return true;
            }
        }

        return false;
    }

    public function getPaymentsByDeadline($patientID, $limit = 5)
    {
        $query =
            "SELECT 
                    p.PaymentID,
                    p.AppointmentID,
                    p.PatientID,
                    p.Status,
                    p.UpdatedBy,
                    p.UpdatedAt,
                    p.Notes,
                    p.DeadlineDate,
                    p.PaymentMethod,
                    p.ProofOfPayment,
                    a.DateTime as AppointmentDateTime,
                    a.AppointmentType,
                    a.Reason,
                    CONCAT(COALESCE(u.FirstName, ''), ' ', COALESCE(u.LastName, '')) as DoctorName,
                    COALESCE(d.Specialization, 'General') as Specialization
                  FROM " .
            $this->table .
            " p
                  LEFT JOIN Appointment a ON p.AppointmentID = a.AppointmentID
                  LEFT JOIN Doctor d ON a.DoctorID = d.DoctorID
                  LEFT JOIN USER u ON d.DoctorID = u.UserID
                  WHERE p.PatientID = ? AND p.Status IN ('Pending', 'Overdue') AND p.DeadlineDate IS NOT NULL
                  ORDER BY p.DeadlineDate ASC
                  LIMIT ?";

        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            error_log(
                "SQL Error in getPaymentsByDeadline: " . $this->conn->error
            );
            return [];
        }

        $stmt->bind_param("ii", $patientID, $limit);
        $stmt->execute();

        $result = $stmt->get_result();
        $payments = $result->fetch_all(MYSQLI_ASSOC);

        $overdueConfig = new OverdueConfig($this->conn);
        foreach ($payments as &$payment) {
            $payment["total_amount"] = $this->getTotalAmount(
                $payment["PaymentID"]
            );
            $payment["original_amount"] = $payment["total_amount"];

            // Calculate overdue amount if applicable
            if ($overdueConfig->isPaymentOverdue($payment["DeadlineDate"])) {
                $payment[
                    "total_amount"
                ] = $overdueConfig->calculateOverdueAmount(
                    $payment["total_amount"],
                    $payment["DeadlineDate"]
                );
                $payment["is_overdue"] = true;
                $payment["overdue_amount"] =
                    $payment["total_amount"] - $payment["original_amount"];
            } else {
                $payment["is_overdue"] = false;
                $payment["overdue_amount"] = 0;
            }
        }

        return $payments;
    }

    public function updateOverdueStatuses()
    {
        $overdueConfig = new OverdueConfig($this->conn);

        // Get all pending payments with deadlines
        $query =
            "SELECT PaymentID, DeadlineDate FROM " .
            $this->table .
            " 
                  WHERE Status = 'Pending' AND DeadlineDate IS NOT NULL";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $payments = $result->fetch_all(MYSQLI_ASSOC);

        $updatedCount = 0;

        foreach ($payments as $payment) {
            if ($overdueConfig->isPaymentOverdue($payment["DeadlineDate"])) {
                // Update status to overdue
                $updateQuery =
                    "UPDATE " .
                    $this->table .
                    " 
                               SET Status = 'Overdue', UpdatedAt = CURRENT_TIMESTAMP 
                               WHERE PaymentID = ?";
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bind_param("i", $payment["PaymentID"]);

                if ($updateStmt->execute()) {
                    $updatedCount++;
                }
            }
        }

        return $updatedCount;
    }

    public function getOverdueConfig()
    {
        $overdueConfig = new OverdueConfig($this->conn);
        return $overdueConfig->getActiveConfig();
    }
}
?> 