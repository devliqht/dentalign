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

        $this->view("pages/staff/dentalassistant/Dashboard", $data, $layoutConfig);
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
            "additionalHead" =>
                '<link rel="stylesheet" href="' .
                BASE_URL .
                '/app/styles/views/PaymentManagement.css">',
            "additionalScripts" =>
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

            echo json_encode([
                "success" => true,
                "appointments" => $appointments,
            ]);
        } catch(Exception $e) {
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
                $payment->updateStatus($paymentId, $status, $user["id"], $notes)
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

            require_once "app/models/PaymentItem.php";

            // Delete payment items first
            $paymentItem = new PaymentItem($this->conn);
            $paymentItem->deleteByPayment($paymentId);

            // Delete payment
            $deleteQuery = "DELETE FROM Payments WHERE PaymentID = ?";
            $stmt = $this->conn->prepare($deleteQuery);
            $stmt->bind_param("i", $paymentId);

            if ($stmt->execute()) {
                echo json_encode([
                    "success" => true,
                    "message" => "Payment deleted successfully",
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to delete payment",
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
}