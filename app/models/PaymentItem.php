<?php

class PaymentItem
{
    protected $conn;
    protected $table = "PaymentItems";

    public $paymentItemID;
    public $paymentID;
    public $description;
    public $amount;
    public $quantity;
    public $total;
    public $treatmentItemID;

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
                  (PaymentID, Description, Amount, Quantity, Total, TreatmentItemID) 
                  VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->total = $this->amount * $this->quantity;

        $stmt->bind_param(
            "isdidi",
            $this->paymentID,
            $this->description,
            $this->amount,
            $this->quantity,
            $this->total,
            $this->treatmentItemID
        );

        if ($stmt->execute()) {
            $this->paymentItemID = $this->conn->insert_id;
            return true;
        }

        return false;
    }

    public function getItemsByPayment($paymentID)
    {
        $query =
            "SELECT 
                    PaymentItemID,
                    PaymentID,
                    Description,
                    Amount,
                    Quantity,
                    Total
                  FROM " .
            $this->table .
            "
                  WHERE PaymentID = ?
                  ORDER BY PaymentItemID";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $paymentID);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function update($paymentItemID, $description, $amount, $quantity)
    {
        $total = $amount * $quantity;

        $query =
            "UPDATE " .
            $this->table .
            " 
                  SET Description = ?, Amount = ?, Quantity = ?, Total = ? 
                  WHERE PaymentItemID = ?";

        $stmt = $this->conn->prepare($query);

        $description = htmlspecialchars(strip_tags($description));

        $stmt->bind_param(
            "sdidi",
            $description,
            $amount,
            $quantity,
            $total,
            $paymentItemID
        );

        return $stmt->execute();
    }

    public function delete($paymentItemID)
    {
        $query = "DELETE FROM " . $this->table . " WHERE PaymentItemID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $paymentItemID);

        return $stmt->execute();
    }

    public function deleteByPayment($paymentID)
    {
        $query = "DELETE FROM " . $this->table . " WHERE PaymentID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $paymentID);

        return $stmt->execute();
    }

    public function createMultiple($paymentID, $items)
    {
        $this->conn->begin_transaction();

        try {
            foreach ($items as $item) {
                $this->paymentID = $paymentID;
                $this->description = $item["description"];
                $this->amount = $item["amount"];
                $this->quantity = $item["quantity"] ?? 1;

                if (!$this->create()) {
                    throw new Exception(
                        "Failed to create payment item: " . $item["description"]
                    );
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function createFromTreatmentPlanItem($treatmentItemID, $appointmentID)
    {
        // Get TreatmentPlanItem details
        require_once "app/models/TreatmentPlanItem.php";
        require_once "app/models/TreatmentPlan.php";
        require_once "app/models/Payment.php";

        $treatmentPlanItem = new TreatmentPlanItem($this->conn);
        if (!$treatmentPlanItem->findByID($treatmentItemID)) {
            return false;
        }

        // Get the appointment ID from the TreatmentPlan
        $treatmentPlan = new TreatmentPlan($this->conn);
        if (!$treatmentPlan->findByID($treatmentPlanItem->treatmentPlanID)) {
            return false;
        }

        // Get the appointmentID from the AppointmentReport
        $appointmentQuery = "SELECT AppointmentID FROM AppointmentReport WHERE AppointmentReportID = ?";
        $stmt = $this->conn->prepare($appointmentQuery);
        $stmt->bind_param("i", $treatmentPlan->appointmentReportID);
        $stmt->execute();
        $appointmentResult = $stmt->get_result()->fetch_assoc();

        if (!$appointmentResult) {
            return false;
        }

        $appointmentID = $appointmentResult['AppointmentID'];

        // Get or create Payment for this appointment
        $payment = new Payment($this->conn);
        $existingPayment = $payment->getPaymentByAppointment($appointmentID);

        if ($existingPayment) {
            $paymentID = $existingPayment['PaymentID'];
        } else {
            // Get patient ID from appointment
            $patientQuery = "SELECT PatientID FROM Appointment WHERE AppointmentID = ?";
            $stmt = $this->conn->prepare($patientQuery);
            $stmt->bind_param("i", $appointmentID);
            $stmt->execute();
            $patientResult = $stmt->get_result()->fetch_assoc();

            if (!$patientResult) {
                return false;
            }

            // Create new payment
            $payment->appointmentID = $appointmentID;
            $payment->patientID = $patientResult['PatientID'];
            $payment->status = 'Pending';
            $payment->notes = 'Auto-created from completed treatment';
            $payment->deadlineDate = date('Y-m-d', strtotime('+30 days'));
            $payment->paymentMethod = 'Cash';

            if (!$payment->create()) {
                return false;
            }
            $paymentID = $payment->paymentID;
        }

        // Check if PaymentItem already exists for this TreatmentPlanItem
        $checkQuery = "SELECT PaymentItemID FROM PaymentItems WHERE TreatmentItemID = ?";
        $stmt = $this->conn->prepare($checkQuery);
        $stmt->bind_param("i", $treatmentItemID);
        $stmt->execute();
        $existing = $stmt->get_result()->fetch_assoc();

        if ($existing) {
            // Already charged
            return true;
        }

        // Create PaymentItem from TreatmentPlanItem
        $this->paymentID = $paymentID;
        $this->description = $treatmentPlanItem->description ?: "Treatment: " . $treatmentPlanItem->procedureCode;
        $this->amount = $treatmentPlanItem->cost;
        $this->quantity = 1;
        $this->treatmentItemID = $treatmentItemID;

        return $this->create();
    }

    public function isChargedToAccount($treatmentItemID)
    {
        $query = "SELECT COUNT(*) as count FROM PaymentItems WHERE TreatmentItemID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $treatmentItemID);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        return $result['count'] > 0;
    }

    public function removeByTreatmentItemID($treatmentItemID)
    {
        $query = "DELETE FROM " . $this->table . " WHERE TreatmentItemID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $treatmentItemID);

        return $stmt->execute();
    }
} ?> 