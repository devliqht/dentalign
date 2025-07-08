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
                  (PaymentID, Description, Amount, Quantity, Total) 
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->total = $this->amount * $this->quantity; 

        $stmt->bind_param(
            "isdid",
            $this->paymentID,
            $this->description,
            $this->amount,
            $this->quantity,
            $this->total
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
} ?> 