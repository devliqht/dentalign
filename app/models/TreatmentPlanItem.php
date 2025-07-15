<?php

class TreatmentPlanItem
{
    protected $conn;
    protected $table = "TreatmentPlanItem";

    public $treatmentItemID;
    public $treatmentPlanID;
    public $toothNumber;
    public $procedureCode;
    public $description;
    public $cost;
    public $scheduledDate;
    public $createdAt;
    public $completedAt;

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
                  (TreatmentPlanID, ToothNumber, ProcedureCode, Description, Cost, ScheduledDate, CompletedAt)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "isssdss",
            $this->treatmentPlanID,
            $this->toothNumber,
            $this->procedureCode,
            $this->description,
            $this->cost,
            $this->scheduledDate,
            $this->completedAt
        );

        if ($stmt->execute()) {
            $this->treatmentItemID = $this->conn->insert_id;
            return true;
        }
        return false;
    }

    public function isChargedToAccount($treatmentItemID)
    {
        $query =
            "SELECT COUNT(*) as count FROM PaymentItems WHERE TreatmentItemID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $treatmentItemID);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        return $result["count"] > 0;
    }

    public function findByTreatmentPlanID($treatmentPlanID)
    {
        $query =
            "SELECT 
                    tpi.TreatmentItemID,
                    tpi.TreatmentPlanID,
                    tpi.ToothNumber,
                    tpi.ProcedureCode,
                    tpi.Description,
                    tpi.Cost,
                    tpi.ScheduledDate,
                    tpi.CreatedAt,
                    tpi.CompletedAt,
                    CASE WHEN pi.PaymentItemID IS NOT NULL THEN 1 ELSE 0 END as IsChargedToAccount
                  FROM " .
            $this->table .
            " tpi
                  LEFT JOIN PaymentItems pi ON tpi.TreatmentItemID = pi.TreatmentItemID
                  WHERE tpi.TreatmentPlanID = ?
                  ORDER BY tpi.ScheduledDate ASC, tpi.CreatedAt ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $treatmentPlanID);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findByID($treatmentItemID)
    {
        $query =
            "SELECT 
                    TreatmentItemID,
                    TreatmentPlanID,
                    ToothNumber,
                    ProcedureCode,
                    Description,
                    Cost,
                    ScheduledDate,
                    CreatedAt,
                    CompletedAt
                  FROM " .
            $this->table .
            " 
                  WHERE TreatmentItemID = ? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $treatmentItemID);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->treatmentItemID = $row["TreatmentItemID"];
            $this->treatmentPlanID = $row["TreatmentPlanID"];
            $this->toothNumber = $row["ToothNumber"];
            $this->procedureCode = $row["ProcedureCode"];
            $this->description = $row["Description"];
            $this->cost = $row["Cost"];
            $this->scheduledDate = $row["ScheduledDate"];
            $this->createdAt = $row["CreatedAt"];
            $this->completedAt = $row["CompletedAt"];
            return true;
        }
        return false;
    }

    public function update()
    {
        $query =
            "UPDATE " .
            $this->table .
            " 
                  SET ToothNumber = ?, ProcedureCode = ?, Description = ?, 
                      Cost = ?, ScheduledDate = ?, CompletedAt = ?
                  WHERE TreatmentItemID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "sssdssi",
            $this->toothNumber,
            $this->procedureCode,
            $this->description,
            $this->cost,
            $this->scheduledDate,
            $this->completedAt,
            $this->treatmentItemID
        );

        return $stmt->execute();
    }

    public function markAsCompleted($treatmentItemID, $completedAt = null)
    {
        if ($completedAt === null) {
            $completedAt = date("Y-m-d H:i:s");
        }

        $query =
            "UPDATE " .
            $this->table .
            " SET CompletedAt = ? WHERE TreatmentItemID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $completedAt, $treatmentItemID);

        if ($stmt->execute()) {
            $this->autoCreatePaymentItem($treatmentItemID);
            return true;
        }

        return false;
    }

    private function autoCreatePaymentItem($treatmentItemID)
    {
        try {
            require_once "app/models/PaymentItem.php";

            $paymentItem = new PaymentItem($this->conn);
            $paymentItem->createFromTreatmentPlanItem($treatmentItemID, null);
        } catch (Exception $e) {
            error_log(
                "Failed to auto-create PaymentItem for TreatmentItemID {$treatmentItemID}: " .
                    $e->getMessage()
            );
        }
    }

    public function markAsIncomplete($treatmentItemID)
    {
        $query =
            "UPDATE " .
            $this->table .
            " SET CompletedAt = NULL WHERE TreatmentItemID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $treatmentItemID);

        if ($stmt->execute()) {
            // Auto-remove PaymentItem when TreatmentPlanItem is marked incomplete
            $this->autoRemovePaymentItem($treatmentItemID);
            return true;
        }

        return false;
    }

    private function autoRemovePaymentItem($treatmentItemID)
    {
        try {
            require_once "app/models/PaymentItem.php";

            $paymentItem = new PaymentItem($this->conn);
            $paymentItem->removeByTreatmentItemID($treatmentItemID);
        } catch (Exception $e) {
            error_log(
                "Failed to auto-remove PaymentItem for TreatmentItemID {$treatmentItemID}: " .
                    $e->getMessage()
            );
        }
    }

    public function delete($treatmentItemID)
    {
        $query = "DELETE FROM " . $this->table . " WHERE TreatmentItemID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $treatmentItemID);
        return $stmt->execute();
    }

    public function getItemsByPatientID($patientID)
    {
        $query =
            "SELECT 
                    tpi.TreatmentItemID,
                    tpi.TreatmentPlanID,
                    tpi.ToothNumber,
                    tpi.ProcedureCode,
                    tpi.Description,
                    tpi.Cost,
                    tpi.ScheduledDate,
                    tpi.CreatedAt,
                    tpi.CompletedAt,
                    tp.Status as TreatmentPlanStatus,
                    tp.DentistNotes,
                    a.DateTime as AppointmentDate,
                    a.AppointmentType,
                    CONCAT(u.FirstName, ' ', u.LastName) as DoctorName
                  FROM " .
            $this->table .
            " tpi
                  INNER JOIN TreatmentPlan tp ON tpi.TreatmentPlanID = tp.TreatmentPlanID
                  INNER JOIN AppointmentReport ar ON tp.AppointmentReportID = ar.AppointmentReportID
                  INNER JOIN Appointment a ON ar.AppointmentID = a.AppointmentID
                  INNER JOIN Doctor d ON a.DoctorID = d.DoctorID
                  INNER JOIN USER u ON d.DoctorID = u.UserID
                  WHERE a.PatientID = ?
                  ORDER BY tpi.ScheduledDate DESC, tpi.CreatedAt DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $patientID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    public function getUpcomingItemsByPatientID($patientID, $limit = 5)
    {
        $query =
            "SELECT 
                    tpi.TreatmentItemID,
                    tpi.TreatmentPlanID,
                    tpi.ToothNumber,
                    tpi.ProcedureCode,
                    tpi.Description,
                    tpi.Cost,
                    tpi.ScheduledDate,
                    tpi.CreatedAt,
                    tpi.CompletedAt,
                    tp.Status as TreatmentPlanStatus,
                    a.AppointmentType,
                    CONCAT(u.FirstName, ' ', u.LastName) as DoctorName
                  FROM " .
            $this->table .
            " tpi
                  INNER JOIN TreatmentPlan tp ON tpi.TreatmentPlanID = tp.TreatmentPlanID
                  INNER JOIN AppointmentReport ar ON tp.AppointmentReportID = ar.AppointmentReportID
                  INNER JOIN Appointment a ON ar.AppointmentID = a.AppointmentID
                  INNER JOIN Doctor d ON a.DoctorID = d.DoctorID
                  INNER JOIN USER u ON d.DoctorID = u.UserID
                  WHERE a.PatientID = ? 
                    AND tpi.CompletedAt IS NULL
                    AND tp.Status IN ('pending', 'in_progress')
                  ORDER BY tpi.ScheduledDate ASC, tpi.CreatedAt ASC
                  LIMIT ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $patientID, $limit);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    public function getTotalCostByTreatmentPlanID($treatmentPlanID)
    {
        $query =
            "SELECT SUM(Cost) as total_cost FROM " .
            $this->table .
            " WHERE TreatmentPlanID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $treatmentPlanID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row["total_cost"] ?? 0;
        }
        return 0;
    }

    public function getCompletedCostByTreatmentPlanID($treatmentPlanID)
    {
        $query =
            "SELECT SUM(Cost) as completed_cost FROM " .
            $this->table .
            " 
                  WHERE TreatmentPlanID = ? AND CompletedAt IS NOT NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $treatmentPlanID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row["completed_cost"] ?? 0;
        }
        return 0;
    }

    public static function getToothName($toothNumber)
    {
        $toothNames = [
            // Upper right quadrant (1-8)
            1 => "Upper Right Central Incisor",
            2 => "Upper Right Lateral Incisor",
            3 => "Upper Right Canine",
            4 => "Upper Right First Premolar",
            5 => "Upper Right Second Premolar",
            6 => "Upper Right First Molar",
            7 => "Upper Right Second Molar",
            8 => "Upper Right Third Molar (Wisdom)",

            // Upper left quadrant (9-16)
            9 => "Upper Left Central Incisor",
            10 => "Upper Left Lateral Incisor",
            11 => "Upper Left Canine",
            12 => "Upper Left First Premolar",
            13 => "Upper Left Second Premolar",
            14 => "Upper Left First Molar",
            15 => "Upper Left Second Molar",
            16 => "Upper Left Third Molar (Wisdom)",

            // Lower left quadrant (17-24)
            17 => "Lower Left Central Incisor",
            18 => "Lower Left Lateral Incisor",
            19 => "Lower Left Canine",
            20 => "Lower Left First Premolar",
            21 => "Lower Left Second Premolar",
            22 => "Lower Left First Molar",
            23 => "Lower Left Second Molar",
            24 => "Lower Left Third Molar (Wisdom)",

            // Lower right quadrant (25-32)
            25 => "Lower Right Central Incisor",
            26 => "Lower Right Lateral Incisor",
            27 => "Lower Right Canine",
            28 => "Lower Right First Premolar",
            29 => "Lower Right Second Premolar",
            30 => "Lower Right First Molar",
            31 => "Lower Right Second Molar",
            32 => "Lower Right Third Molar (Wisdom)",
        ];

        return $toothNames[$toothNumber] ?? "Unknown Tooth #$toothNumber";
    }

    public static function getProcedureCodes()
    {
        return [
            "D0120" => "Periodic Oral Examination",
            "D0150" => "Comprehensive Oral Examination",
            "D0210" => "Intraoral Periapical X-Ray",
            "D0220" => "Intraoral Periapical First Film",
            "D0230" => "Intraoral Periapical Each Additional Film",
            "D0272" => "Bitewings - Two Films",
            "D0274" => "Bitewings - Four Films",
            "D0330" => "Panoramic Film",
            "D1110" => "Prophylaxis - Adult",
            "D1120" => "Prophylaxis - Child",
            "D1206" => "Topical Fluoride Varnish",
            "D1208" => "Topical Fluoride - Excluding Varnish",
            "D2140" => "Amalgam - One Surface, Primary or Permanent",
            "D2150" => "Amalgam - Two Surfaces, Primary or Permanent",
            "D2160" => "Amalgam - Three Surfaces, Primary or Permanent",
            "D2161" => "Amalgam - Four or More Surfaces, Primary or Permanent",
            "D2330" => "Resin-Based Composite - One Surface, Anterior",
            "D2331" => "Resin-Based Composite - Two Surfaces, Anterior",
            "D2332" => "Resin-Based Composite - Three Surfaces, Anterior",
            "D2335" =>
                "Resin-Based Composite - Four or More Surfaces or Involving Incisal Angle",
            "D2391" => "Resin-Based Composite - One Surface, Posterior",
            "D2392" => "Resin-Based Composite - Two Surfaces, Posterior",
            "D2393" => "Resin-Based Composite - Three Surfaces, Posterior",
            "D2394" =>
                "Resin-Based Composite - Four or More Surfaces, Posterior",
            "D2740" => "Crown - Porcelain/Ceramic Substrate",
            "D2750" => "Crown - Porcelain Fused to High Noble Metal",
            "D2751" => "Crown - Porcelain Fused to Predominantly Base Metal",
            "D2752" => "Crown - Porcelain Fused to Noble Metal",
            "D2790" => "Crown - Full Cast High Noble Metal",
            "D2791" => "Crown - Full Cast Predominantly Base Metal",
            "D2792" => "Crown - Full Cast Noble Metal",
            "D2940" => "Sedative Filling",
            "D3110" => "Pulp Cap - Direct (Excluding Final Restoration)",
            "D3120" => "Pulp Cap - Indirect (Excluding Final Restoration)",
            "D3220" => "Therapeutic Pulpotomy",
            "D3310" => "Endodontic Therapy, Anterior Tooth",
            "D3320" => "Endodontic Therapy, Premolar Tooth",
            "D3330" => "Endodontic Therapy, Molar Tooth",
            "D4210" =>
                "Gingivectomy or Gingivoplasty - Four or More Contiguous Teeth",
            "D4211" => "Gingivectomy or Gingivoplasty - One to Three Teeth",
            "D4240" =>
                "Gingival Flap Procedure, Including Root Planing - Four or More Contiguous Teeth",
            "D4241" =>
                "Gingival Flap Procedure, Including Root Planing - One to Three Teeth",
            "D4341" =>
                "Periodontal Scaling and Root Planing - Four or More Teeth per Quadrant",
            "D4342" =>
                "Periodontal Scaling and Root Planing - One to Three Teeth per Quadrant",
            "D5110" => "Complete Denture - Upper",
            "D5120" => "Complete Denture - Lower",
            "D5211" => "Upper Partial Denture - Resin Base",
            "D5212" => "Lower Partial Denture - Resin Base",
            "D5213" =>
                "Upper Partial Denture - Cast Metal Framework with Resin Denture Bases",
            "D5214" =>
                "Lower Partial Denture - Cast Metal Framework with Resin Denture Bases",
            "D6010" => "Surgical Placement of Implant Body: Endosteal Implant",
            "D6040" => "Surgical Placement: Eposteal Implant",
            "D6050" => "Surgical Placement: Transosteal Implant",
            "D6055" =>
                "Connecting Bar - Implant Supported or Abutment Supported",
            "D6056" => "Prefabricated Abutment - Includes Placement",
            "D6057" => "Custom Abutment - Includes Placement",
            "D6058" => "Abutment Supported Porcelain/Ceramic Crown",
            "D6059" => "Abutment Supported Porcelain Fused to Metal Crown",
            "D6060" => "Abutment Supported Cast Metal Crown",
            "D6061" =>
                "Abutment Supported Porcelain/Ceramic Retainer for Fixed Partial Denture",
            "D6062" =>
                "Abutment Supported Cast Metal Retainer for Fixed Partial Denture",
            "D6063" => "Implant Supported Porcelain/Ceramic Crown",
            "D6064" => "Implant Supported Porcelain Fused to Metal Crown",
            "D6065" => "Implant Supported Metal Crown",
            "D7111" => "Extraction, Coronal Remnants - Primary Tooth",
            "D7140" => "Extraction, Erupted Tooth or Exposed Root",
            "D7210" =>
                "Extraction, Erupted Tooth Requiring Removal of Bone and/or Sectioning of Tooth",
            "D7220" => "Removal of Impacted Tooth - Soft Tissue",
            "D7230" => "Removal of Impacted Tooth - Partially Bony",
            "D7240" => "Removal of Impacted Tooth - Completely Bony",
            "D7250" => "Removal of Residual Tooth Roots",
            "D8010" => "Limited Orthodontic Treatment of the Primary Dentition",
            "D8020" =>
                "Limited Orthodontic Treatment of the Transitional Dentition",
            "D8030" =>
                "Limited Orthodontic Treatment of the Adolescent Dentition",
            "D8040" => "Limited Orthodontic Treatment of the Adult Dentition",
            "D8080" =>
                "Comprehensive Orthodontic Treatment of the Transitional Dentition",
            "D8090" =>
                "Comprehensive Orthodontic Treatment of the Adolescent Dentition",
            "D9110" => "Palliative (Emergency) Treatment of Dental Pain",
            "D9120" => "Fixed Partial Denture Sectioning",
            "D9210" =>
                "Local Anesthesia Not in Conjunction with Operative or Surgical Procedures",
            "D9215" =>
                "Local Anesthesia in Conjunction with Operative or Surgical Procedures",
            "D9220" => "Deep Sedation/General Anesthesia - First 15 Minutes",
            "D9221" =>
                "Deep Sedation/General Anesthesia - Each Subsequent 15 Minute Increment",
            "D9230" => "Analgesia, Anxiolysis, Inhalation of Nitrous Oxide",
            "D9241" =>
                "Intravenous Moderate (Conscious) Sedation/Analgesia - First 15 Minutes",
            "D9242" =>
                "Intravenous Moderate (Conscious) Sedation/Analgesia - Each Subsequent 15 Minute Increment",
        ];
    }
}
