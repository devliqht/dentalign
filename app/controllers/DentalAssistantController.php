<?php

class DentalAssistantController extends Controller
{
    public function dashboard()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("DentalAssistant");

        $data = [
            "user" => $this->getAuthuser(),
        ];

        $layoutConfig = [
            "title" => "Dental Assistant Dashboard",
            "hideHeader" => false,
            "hideFooter" => false,
        ];

        $this->view(
            "pages/staff/dentalassistant/Dashboard",
            $data,
            $layoutConfig
        );
    }

    public function paymentManagement()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("DentalAssistant");

        $data = [
            "user" => $this->getAuthUser(),
        ];

        $layoutConfig = [
            "title" => "Payment Management",
            "hideHeader" => false,
            "hideFooter" => false,
            "additionalScripts" =>
                '<script src="' .
                BASE_URL .
                '/app/views/scripts/Toast.js"></script>' .
                '<script src="' .
                BASE_URL .
                '/app/views/scripts/SortableTable.js"></script>' .
                '<script src="' .
                BASE_URL .
                '/app/views/scripts/PaymentManagement/PaymentManagement.js"></script>',
        ];

        $this->view(
            "pages/staff/dentalassistant/PaymentManagement",
            $data,
            $layoutConfig
        );
    }

    public function getAllAppointmentsPayments()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("DentalAssistant");

        header("Content-Type: application/json");

        try {
            $query = "
                SELECT 
                    a.AppointmentID,
                    a.DateTime,
                    a.AppointmentType,
                    a.Reason,
                    'Confirmed' as AppointmentStatus,
                    CONCAT(p_user.FirstName, ' ', p_user.LastName) as PatientName,
                    p_user.Email as PatientEmail,
                    CONCAT(d_user.FirstName, ' ', d_user.LastName) as DoctorName,
                    doc.Specialization,
                    pay.PaymentID,
                    pay.Status as PaymentStatus,
                    pay.UpdatedAt as PaymentUpdatedAt,
                    pay.Notes as PaymentNotes,
                    COALESCE(
                        (SELECT SUM(Total) FROM PaymentItems WHERE PaymentID = pay.PaymentID), 
                        0
                    ) as TotalAmount
                FROM Appointment a
                LEFT JOIN PATIENT pat ON a.PatientID = pat.PatientID
                LEFT JOIN USER p_user ON pat.PatientID = p_user.UserID
                LEFT JOIN Doctor doc ON a.DoctorID = doc.DoctorID
                LEFT JOIN USER d_user ON doc.DoctorID = d_user.UserID
                LEFT JOIN Payments pay ON a.AppointmentID = pay.AppointmentID
                ORDER BY a.DateTime DESC
            ";

            $result = $this->conn->query($query);
            $appointments = $result->fetch_all(MYSQLI_ASSOC);

            // Apply overdue calculations to appointments with payments
            require_once "app/models/OverdueConfig.php";
            $overdueConfig = new OverdueConfig($this->conn);

            foreach ($appointments as &$appointment) {
                if ($appointment['PaymentID']) {
                    // Get payment items for accurate total calculation
                    $itemsQuery = "SELECT SUM(Total) as total_amount FROM PaymentItems WHERE PaymentID = ?";
                    $itemsStmt = $this->conn->prepare($itemsQuery);
                    $itemsStmt->bind_param("i", $appointment['PaymentID']);
                    $itemsStmt->execute();
                    $itemsResult = $itemsStmt->get_result()->fetch_assoc();

                    $originalAmount = $itemsResult['total_amount'] ?? $appointment['TotalAmount'];
                    $appointment['TotalAmount'] = $originalAmount;
                    $appointment['OriginalAmount'] = $originalAmount;

                    // Check if payment has a deadline date
                    $deadlineQuery = "SELECT DeadlineDate FROM Payments WHERE PaymentID = ?";
                    $deadlineStmt = $this->conn->prepare($deadlineQuery);
                    $deadlineStmt->bind_param("i", $appointment['PaymentID']);
                    $deadlineStmt->execute();
                    $deadlineResult = $deadlineStmt->get_result()->fetch_assoc();
                    $deadlineDate = $deadlineResult['DeadlineDate'] ?? null;

                    // Calculate overdue amount if applicable
                    if ($deadlineDate && $overdueConfig->isPaymentOverdue($deadlineDate) &&
                        strtolower($appointment['PaymentStatus']) === 'pending') {
                        $appointment['TotalAmount'] = $overdueConfig->calculateOverdueAmount(
                            $originalAmount,
                            $deadlineDate
                        );
                        $appointment['IsOverdue'] = true;
                        $appointment['OverdueAmount'] = $appointment['TotalAmount'] - $originalAmount;
                        $appointment['PaymentStatus'] = 'Overdue';
                    } else {
                        $appointment['IsOverdue'] = false;
                        $appointment['OverdueAmount'] = 0;
                    }

                    $appointment['DeadlineDate'] = $deadlineDate;
                } else {
                    $appointment['IsOverdue'] = false;
                    $appointment['OverdueAmount'] = 0;
                    $appointment['OriginalAmount'] = 0;
                    $appointment['DeadlineDate'] = null;
                }
            }

            echo json_encode([
                "success" => true,
                "appointments" => $appointments,
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error fetching appointments: " . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function getPaymentDetails()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("DentalAssistant");

        header("Content-Type: application/json");

        $appointmentId = $_GET["appointment_id"] ?? "";
        $paymentId = $_GET["payment_id"] ?? "";

        if (empty($appointmentId) && empty($paymentId)) {
            echo json_encode([
                "success" => false,
                "message" => "Appointment ID or Payment ID is required",
            ]);
            exit();
        }

        try {
            require_once "app/models/Payment.php";
            require_once "app/models/PaymentItem.php";

            $payment = new Payment($this->conn);

            if (!empty($paymentId)) {
                $paymentData = $payment->getPaymentWithBreakdown($paymentId);
            } else {
                $paymentData = $payment->getPaymentByAppointment(
                    $appointmentId
                );
                if ($paymentData) {
                    $paymentData = $payment->getPaymentWithBreakdown(
                        $paymentData["PaymentID"]
                    );
                }
            }

            if (!$paymentData) {
                // Get appointment details for creating new payment
                $appointmentQuery = "
                    SELECT 
                        a.AppointmentID,
                        a.DateTime,
                        a.AppointmentType,
                        a.Reason,
                        a.PatientID,
                        CONCAT(p_user.FirstName, ' ', p_user.LastName) as PatientName,
                        CONCAT(d_user.FirstName, ' ', d_user.LastName) as DoctorName
                    FROM Appointment a
                    LEFT JOIN PATIENT pat ON a.PatientID = pat.PatientID
                    LEFT JOIN USER p_user ON pat.PatientID = p_user.UserID
                    LEFT JOIN Doctor doc ON a.DoctorID = doc.DoctorID
                    LEFT JOIN USER d_user ON doc.DoctorID = d_user.UserID
                    WHERE a.AppointmentID = ?
                ";

                $stmt = $this->conn->prepare($appointmentQuery);
                $stmt->bind_param("i", $appointmentId);
                $stmt->execute();
                $appointmentData = $stmt->get_result()->fetch_assoc();

                if (!$appointmentData) {
                    echo json_encode([
                        "success" => false,
                        "message" => "Appointment not found",
                    ]);
                    exit();
                }

                echo json_encode([
                    "success" => true,
                    "payment" => null,
                    "appointment" => $appointmentData,
                ]);
            } else {
                echo json_encode([
                    "success" => true,
                    "payment" => $paymentData,
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" =>
                    "Error fetching payment details: " . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function createPayment()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("DentalAssistant");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("HTTP/1.1 405 Method Not Allowed");
            echo json_encode([
                "success" => false,
                "message" => "Method not allowed",
            ]);
            exit();
        }

        header("Content-Type: application/json");

        $rawInput = file_get_contents("php://input");
        $data = json_decode($rawInput, true);

        if (!$data) {
            echo json_encode([
                "success" => false,
                "message" => "Invalid JSON data: " . json_last_error_msg(),
            ]);
            exit();
        }

        try {
            $appointmentId = $data["appointmentId"] ?? null;
            $patientId = $data["patientId"] ?? null;
            $status = $data["status"] ?? "Pending";
            $notes = $data["notes"] ?? "";
            $items = $data["items"] ?? [];
            $deadlineDate = $data["deadlineDate"] ?? null;
            $paymentMethod = $data["paymentMethod"] ?? "Cash";
            $proofOfPayment = $data["proofOfPayment"] ?? "";

            if (!$appointmentId || !$patientId) {
                echo json_encode([
                    "success" => false,
                    "message" => "Appointment ID and Patient ID are required",
                ]);
                exit();
            }

            require_once "app/models/Payment.php";
            require_once "app/models/PaymentItem.php";

            $user = $this->getAuthUser();

            $payment = new Payment($this->conn);
            $payment->appointmentID = $appointmentId;
            $payment->patientID = $patientId;
            $payment->status = $status;
            $payment->updatedBy = $user["id"];
            $payment->notes = $notes;
            $payment->deadlineDate = $deadlineDate;
            $payment->paymentMethod = $paymentMethod;
            $payment->proofOfPayment = $proofOfPayment;

            if ($payment->create()) {
                // Add payment items
                if (!empty($items)) {
                    $paymentItem = new PaymentItem($this->conn);
                    foreach ($items as $item) {
                        $paymentItem->paymentID = $payment->paymentID;
                        $paymentItem->description = $item["description"];
                        $paymentItem->amount = $item["amount"];
                        $paymentItem->quantity = $item["quantity"] ?? 1;
                        $paymentItem->create();
                    }
                }

                echo json_encode([
                    "success" => true,
                    "message" => "Payment created successfully",
                    "paymentId" => $payment->paymentID,
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to create payment",
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error creating payment: " . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function updatePayment()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("DentalAssistant");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("HTTP/1.1 405 Method Not Allowed");
            echo json_encode([
                "success" => false,
                "message" => "Method not allowed",
            ]);
            exit();
        }

        header("Content-Type: application/json");

        $rawInput = file_get_contents("php://input");
        $data = json_decode($rawInput, true);

        if (!$data) {
            echo json_encode([
                "success" => false,
                "message" => "Invalid JSON data: " . json_last_error_msg(),
            ]);
            exit();
        }

        try {
            $paymentId = $data["paymentId"] ?? null;
            $status = $data["status"] ?? "";
            $notes = $data["notes"] ?? "";
            $paymentMethod = $data["paymentMethod"] ?? "";
            $deadlineDate = $data["deadlineDate"] ?? "";
            $proofOfPayment = $data["proofOfPayment"] ?? "";

            if (!$paymentId) {
                echo json_encode([
                    "success" => false,
                    "message" => "Payment ID is required",
                ]);
                exit();
            }

            require_once "app/models/Payment.php";
            $user = $this->getAuthUser();

            $payment = new Payment($this->conn);
            if (
                $payment->updatePaymentDetails($paymentId, $status, $user["id"], $notes, $paymentMethod, $deadlineDate, $proofOfPayment)
            ) {
                echo json_encode([
                    "success" => true,
                    "message" => "Payment updated successfully",
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to update payment",
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error updating payment: " . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function deletePayment()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("DentalAssistant");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("HTTP/1.1 405 Method Not Allowed");
            echo json_encode([
                "success" => false,
                "message" => "Method not allowed",
            ]);
            exit();
        }

        header("Content-Type: application/json");

        $rawInput = file_get_contents("php://input");
        $data = json_decode($rawInput, true);

        if (!$data) {
            echo json_encode([
                "success" => false,
                "message" => "Invalid JSON data: " . json_last_error_msg(),
            ]);
            exit();
        }

        try {
            $paymentId = $data["paymentId"] ?? null;

            if (!$paymentId) {
                echo json_encode([
                    "success" => false,
                    "message" => "Payment ID is required",
                ]);
                exit();
            }

            require_once "app/models/Payment.php";

            $user = $this->getAuthUser();
            $payment = new Payment($this->conn);

            // Use soft delete - set status to 'Cancelled' instead of deleting
            if ($payment->softDelete($paymentId, $user["id"], "Payment cancelled by dental assistant")) {
                echo json_encode([
                    "success" => true,
                    "message" => "Payment cancelled successfully",
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to cancel payment",
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error deleting payment: " . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function addPaymentItem()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("DentalAssistant");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("HTTP/1.1 405 Method Not Allowed");
            echo json_encode([
                "success" => false,
                "message" => "Method not allowed",
            ]);
            exit();
        }

        header("Content-Type: application/json");

        $rawInput = file_get_contents("php://input");
        $data = json_decode($rawInput, true);

        if (!$data) {
            echo json_encode([
                "success" => false,
                "message" => "Invalid JSON data: " . json_last_error_msg(),
            ]);
            exit();
        }

        try {
            $paymentId = $data["paymentId"] ?? null;
            $description = $data["description"] ?? "";
            $amount = $data["amount"] ?? 0;
            $quantity = $data["quantity"] ?? 1;

            if (!$paymentId || empty($description) || $amount <= 0) {
                echo json_encode([
                    "success" => false,
                    "message" =>
                        "Payment ID, description, and valid amount are required",
                ]);
                exit();
            }

            require_once "app/models/PaymentItem.php";

            $paymentItem = new PaymentItem($this->conn);
            $paymentItem->paymentID = $paymentId;
            $paymentItem->description = $description;
            $paymentItem->amount = $amount;
            $paymentItem->quantity = $quantity;

            if ($paymentItem->create()) {
                echo json_encode([
                    "success" => true,
                    "message" => "Payment item added successfully",
                    "itemId" => $paymentItem->paymentItemID,
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to add payment item",
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error adding payment item: " . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function updatePaymentItem()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("DentalAssistant");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("HTTP/1.1 405 Method Not Allowed");
            echo json_encode([
                "success" => false,
                "message" => "Method not allowed",
            ]);
            exit();
        }

        header("Content-Type: application/json");

        $rawInput = file_get_contents("php://input");
        $data = json_decode($rawInput, true);

        if (!$data) {
            echo json_encode([
                "success" => false,
                "message" => "Invalid JSON data: " . json_last_error_msg(),
            ]);
            exit();
        }

        try {
            $itemId = $data["itemId"] ?? null;
            $description = $data["description"] ?? "";
            $amount = $data["amount"] ?? 0;
            $quantity = $data["quantity"] ?? 1;

            if (!$itemId || empty($description) || $amount <= 0) {
                echo json_encode([
                    "success" => false,
                    "message" =>
                        "Item ID, description, and valid amount are required",
                ]);
                exit();
            }

            require_once "app/models/PaymentItem.php";

            $paymentItem = new PaymentItem($this->conn);
            if (
                $paymentItem->update($itemId, $description, $amount, $quantity)
            ) {
                echo json_encode([
                    "success" => true,
                    "message" => "Payment item updated successfully",
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to update payment item",
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error updating payment item: " . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function deletePaymentItem()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("DentalAssistant");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("HTTP/1.1 405 Method Not Allowed");
            echo json_encode([
                "success" => false,
                "message" => "Method not allowed",
            ]);
            exit();
        }

        header("Content-Type: application/json");

        $rawInput = file_get_contents("php://input");
        $data = json_decode($rawInput, true);

        if (!$data) {
            echo json_encode([
                "success" => false,
                "message" => "Invalid JSON data: " . json_last_error_msg(),
            ]);
            exit();
        }

        try {
            $itemId = $data["itemId"] ?? null;

            if (!$itemId) {
                echo json_encode([
                    "success" => false,
                    "message" => "Item ID is required",
                ]);
                exit();
            }

            require_once "app/models/PaymentItem.php";

            $paymentItem = new PaymentItem($this->conn);
            if ($paymentItem->delete($itemId)) {
                echo json_encode([
                    "success" => true,
                    "message" => "Payment item deleted successfully",
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to delete payment item",
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error deleting payment item: " . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function updatePaymentStatus()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("DentalAssistant");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("HTTP/1.1 405 Method Not Allowed");
            echo json_encode([
                "success" => false,
                "message" => "Method not allowed",
            ]);
            exit();
        }

        header("Content-Type: application/json");

        $rawInput = file_get_contents("php://input");
        $data = json_decode($rawInput, true);

        if (!$data) {
            echo json_encode([
                "success" => false,
                "message" => "Invalid JSON data: " . json_last_error_msg(),
            ]);
            exit();
        }

        try {
            $paymentId = $data["paymentId"] ?? null;
            $status = $data["status"] ?? "";

            if (!$paymentId || empty($status)) {
                echo json_encode([
                    "success" => false,
                    "message" => "Payment ID and status are required",
                ]);
                exit();
            }

            require_once "app/models/Payment.php";
            $user = $this->getAuthUser();

            $payment = new Payment($this->conn);
            if ($payment->updateStatus($paymentId, $status, $user["id"])) {
                echo json_encode([
                    "success" => true,
                    "message" => "Payment status updated successfully",
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to update payment status",
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" =>
                    "Error updating payment status: " . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function getOverdueConfig()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("DentalAssistant");

        header("Content-Type: application/json");

        try {
            require_once "app/models/OverdueConfig.php";

            $overdueConfig = new OverdueConfig($this->conn);
            $activeConfig = $overdueConfig->getActiveConfig();

            echo json_encode([
                "success" => true,
                "config" => $activeConfig,
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error fetching overdue configuration: " . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function updateOverdueConfig()
    {
        error_log("DEBUG: updateOverdueConfig method called");

        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("DentalAssistant");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("HTTP/1.1 405 Method Not Allowed");
            echo json_encode([
                "success" => false,
                "message" => "Method not allowed",
            ]);
            exit();
        }

        header("Content-Type: application/json");

        $rawInput = file_get_contents("php://input");
        error_log("DEBUG: Raw input: " . $rawInput);

        $data = json_decode($rawInput, true);
        error_log("DEBUG: Decoded data: " . print_r($data, true));

        if (!$data) {
            echo json_encode([
                "success" => false,
                "message" => "Invalid JSON data: " . json_last_error_msg(),
            ]);
            exit();
        }

        try {
            $configName = $data["configName"] ?? "Updated Configuration";
            $overduePercentage = $data["overduePercentage"] ?? 5.00;
            $gracePeriodDays = $data["gracePeriodDays"] ?? 0;

            // Validate input
            if ($overduePercentage < 0 || $overduePercentage > 100) {
                echo json_encode([
                    "success" => false,
                    "message" => "Overdue percentage must be between 0 and 100",
                ]);
                exit();
            }

            if ($gracePeriodDays < 0 || $gracePeriodDays > 365) {
                echo json_encode([
                    "success" => false,
                    "message" => "Grace period must be between 0 and 365 days",
                ]);
                exit();
            }

            require_once "app/models/OverdueConfig.php";

            $user = $this->getAuthUser();
            $overdueConfig = new OverdueConfig($this->conn);

            // Create new configuration (which automatically deactivates the old one)
            error_log("DEBUG: Attempting to create new config with values: " .
                      "Name: $configName, Percentage: $overduePercentage, Grace: $gracePeriodDays, User: " . $user["id"]);

            if ($overdueConfig->createNewConfig($configName, $overduePercentage, $gracePeriodDays, $user["id"])) {
                error_log("DEBUG: Configuration created successfully");
                echo json_encode([
                    "success" => true,
                    "message" => "Overdue configuration updated successfully",
                ]);
            } else {
                error_log("DEBUG: Failed to create configuration");
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to update overdue configuration",
                ]);
            }
        } catch (Exception $e) {
            error_log("DEBUG: Exception caught: " . $e->getMessage());
            error_log("DEBUG: Exception trace: " . $e->getTraceAsString());
            echo json_encode([
                "success" => false,
                "message" => "Error updating overdue configuration: " . $e->getMessage(),
            ]);
        }
        exit();
    }
}
