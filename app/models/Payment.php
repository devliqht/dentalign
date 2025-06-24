<?php

class Payment
{
    protected $conn;
    protected $table = "payments";

    public $paymentID;
    public $appointmentID;
    public $patientID;
    public $status;
    public $updatedBy;
    public $updatedAt;
    public $notes;

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
                  (AppointmentID, PatientID, Status, UpdatedBy, Notes) 
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->notes = htmlspecialchars(strip_tags($this->notes));

        $stmt->bind_param(
            "iisis",
            $this->appointmentID,
            $this->patientID,
            $this->status,
            $this->updatedBy,
            $this->notes
        );

        if ($stmt->execute()) {
            $this->paymentID = $this->conn->insert_id;
            return true;
        }

        return false;
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
        return $result->fetch_all(MYSQLI_ASSOC);
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
        return $result->fetch_assoc();
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

        // Ensure payment items exist (create default ones if none exist)
        $this->ensurePaymentItems($paymentID);

        // Get payment breakdown items
        $itemsQuery = "SELECT 
                         PaymentItemID,
                         Description,
                         Amount,
                         Quantity,
                         Total
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
            $appointmentQuery = "SELECT a.AppointmentType FROM payments p 
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
} ?> 