<?php

class DentalChartItem
{
    private $conn;
    private $table = "dentalchartitem";

    public $dentalChartItemID;
    public $dentalChartID;
    public $toothNumber;
    public $status;
    public $notes;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create a new dental chart item
    public function create()
    {
        $query =
            "INSERT INTO " .
            $this->table .
            " 
                  SET DentalChartID = ?, ToothNumber = ?, Status = ?, Notes = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param(
            "isss",
            $this->dentalChartID,
            $this->toothNumber,
            $this->status,
            $this->notes
        );

        if ($stmt->execute()) {
            $this->dentalChartItemID = $this->conn->insert_id;
            return true;
        }

        return false;
    }

    // Update an existing dental chart item
    public function update()
    {
        $query =
            "UPDATE " .
            $this->table .
            " 
                  SET Status = ?, Notes = ? 
                  WHERE DentalChartItemID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "ssi",
            $this->status,
            $this->notes,
            $this->dentalChartItemID
        );

        return $stmt->execute();
    }

    // Find dental chart item by chart ID and tooth number
    public function findByChartAndTooth($chartID, $toothNumber)
    {
        $query =
            "SELECT DentalChartItemID, DentalChartID, ToothNumber, Status, Notes 
                  FROM " .
            $this->table .
            " 
                  WHERE DentalChartID = ? AND ToothNumber = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("is", $chartID, $toothNumber);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->dentalChartItemID = $row["DentalChartItemID"];
            $this->dentalChartID = $row["DentalChartID"];
            $this->toothNumber = $row["ToothNumber"];
            $this->status = $row["Status"];
            $this->notes = $row["Notes"];
            return true;
        }

        return false;
    }

    // Get all teeth for a dental chart
    public function getTeethByChartID($chartID)
    {
        $query =
            "SELECT DentalChartItemID, DentalChartID, ToothNumber, Status, Notes 
                  FROM " .
            $this->table .
            " 
                  WHERE DentalChartID = ? 
                  ORDER BY CAST(ToothNumber AS UNSIGNED)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $chartID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }

    // Update or create tooth record
    public function updateOrCreate($chartID, $toothNumber, $status, $notes)
    {
        try {
            if ($this->findByChartAndTooth($chartID, $toothNumber)) {
                // Update existing record
                $this->status = $status;
                $this->notes = $notes;
                $result = $this->update();
                if (!$result) {
                    error_log(
                        "Failed to update dental chart item - Chart ID: $chartID, Tooth: $toothNumber"
                    );
                }
                return $result;
            } else {
                // Create new record
                $this->dentalChartID = $chartID;
                $this->toothNumber = $toothNumber;
                $this->status = $status;
                $this->notes = $notes;
                $result = $this->create();
                if (!$result) {
                    error_log(
                        "Failed to create dental chart item - Chart ID: $chartID, Tooth: $toothNumber, Error: " .
                            $this->conn->error
                    );
                }
                return $result;
            }
        } catch (Exception $e) {
            error_log("Exception in updateOrCreate: " . $e->getMessage());
            return false;
        }
    }

    // Initialize all teeth for a dental chart (1-32 for adult teeth)
    public function initializeAllTeeth($chartID)
    {
        $success = true;

        for ($i = 1; $i <= 32; $i++) {
            if (!$this->findByChartAndTooth($chartID, (string) $i)) {
                $this->dentalChartID = $chartID;
                $this->toothNumber = (string) $i;
                $this->status = null;
                $this->notes = null;

                if (!$this->create()) {
                    $success = false;
                }
            }
        }

        return $success;
    }

    // Get tooth name by number (Universal Numbering System)
    public static function getToothName($toothNumber)
    {
        $toothNames = [
            "1" => "Upper Right Third Molar",
            "2" => "Upper Right Second Molar",
            "3" => "Upper Right First Molar",
            "4" => "Upper Right Second Premolar",
            "5" => "Upper Right First Premolar",
            "6" => "Upper Right Canine",
            "7" => "Upper Right Lateral Incisor",
            "8" => "Upper Right Central Incisor",
            "9" => "Upper Left Central Incisor",
            "10" => "Upper Left Lateral Incisor",
            "11" => "Upper Left Canine",
            "12" => "Upper Left First Premolar",
            "13" => "Upper Left Second Premolar",
            "14" => "Upper Left First Molar",
            "15" => "Upper Left Second Molar",
            "16" => "Upper Left Third Molar",
            "17" => "Lower Left Third Molar",
            "18" => "Lower Left Second Molar",
            "19" => "Lower Left First Molar",
            "20" => "Lower Left Second Premolar",
            "21" => "Lower Left First Premolar",
            "22" => "Lower Left Canine",
            "23" => "Lower Left Lateral Incisor",
            "24" => "Lower Left Central Incisor",
            "25" => "Lower Right Central Incisor",
            "26" => "Lower Right Lateral Incisor",
            "27" => "Lower Right Canine",
            "28" => "Lower Right First Premolar",
            "29" => "Lower Right Second Premolar",
            "30" => "Lower Right First Molar",
            "31" => "Lower Right Second Molar",
            "32" => "Lower Right Third Molar",
        ];

        return $toothNames[$toothNumber] ?? "Unknown Tooth";
    }
} ?> 