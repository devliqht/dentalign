<?php

class OverdueConfig
{
    protected $conn;
    protected $table = "OverdueConfig";

    public $configID;
    public $configName;
    public $overduePercentage;
    public $gracePeriodDays;
    public $isActive;
    public $createdAt;
    public $updatedAt;
    public $updatedBy;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        error_log("DEBUG: OverdueConfig::create called");

        $query =
            "INSERT INTO " .
            $this->table .
            " 
                  (ConfigName, OverduePercentage, GracePeriodDays, IsActive, UpdatedBy) 
                  VALUES (?, ?, ?, ?, ?)";

        error_log("DEBUG: Create query: " . $query);

        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            error_log("DEBUG: Prepare failed: " . $this->conn->error);
            return false;
        }

        $this->configName = htmlspecialchars(strip_tags($this->configName));
        $this->overduePercentage = $this->overduePercentage ?? 5.0;
        $this->gracePeriodDays = $this->gracePeriodDays ?? 0;
        $this->isActive = $this->isActive ?? 1;

        error_log(
            "DEBUG: Binding params - Name: {$this->configName}, Percentage: {$this->overduePercentage}, " .
                "Grace: {$this->gracePeriodDays}, Active: {$this->isActive}, UpdatedBy: {$this->updatedBy}"
        );

        $stmt->bind_param(
            "sdiii",
            $this->configName,
            $this->overduePercentage,
            $this->gracePeriodDays,
            $this->isActive,
            $this->updatedBy
        );

        if ($stmt->execute()) {
            $this->configID = $this->conn->insert_id;
            error_log("DEBUG: Insert successful, new ID: " . $this->configID);
            return true;
        } else {
            error_log("DEBUG: Execute failed: " . $stmt->error);
        }

        return false;
    }

    public function getActiveConfig()
    {
        $query =
            "SELECT ConfigID, ConfigName, OverduePercentage, GracePeriodDays, IsActive, CreatedAt, UpdatedAt, UpdatedBy 
                  FROM " .
            $this->table .
            " 
                  WHERE IsActive = 1 
                  ORDER BY UpdatedAt DESC 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->configID = $row["ConfigID"];
            $this->configName = $row["ConfigName"];
            $this->overduePercentage = $row["OverduePercentage"];
            $this->gracePeriodDays = $row["GracePeriodDays"];
            $this->isActive = $row["IsActive"];
            $this->createdAt = $row["CreatedAt"];
            $this->updatedAt = $row["UpdatedAt"];
            $this->updatedBy = $row["UpdatedBy"];
            return $row;
        }

        // Return default config if none found
        return [
            "ConfigID" => null,
            "ConfigName" => "Default",
            "OverduePercentage" => 5.0,
            "GracePeriodDays" => 0,
            "IsActive" => 1,
        ];
    }

    public function getAllConfigs()
    {
        $query =
            "SELECT c.ConfigID, c.ConfigName, c.OverduePercentage, c.GracePeriodDays, c.IsActive, 
                         c.CreatedAt, c.UpdatedAt, c.UpdatedBy,
                         CONCAT(COALESCE(u.FirstName, ''), ' ', COALESCE(u.LastName, '')) as UpdatedByName
                  FROM " .
            $this->table .
            " c
                  LEFT JOIN CLINIC_STAFF staff ON c.UpdatedBy = staff.ClinicStaffID
                  LEFT JOIN USER u ON staff.ClinicStaffID = u.UserID
                  ORDER BY c.UpdatedAt DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateConfig(
        $configID,
        $configName,
        $overduePercentage,
        $gracePeriodDays,
        $updatedBy
    ) {
        $deactivateQuery =
            "UPDATE " . $this->table . " SET IsActive = 0 WHERE IsActive = 1";
        $this->conn->query($deactivateQuery);

        $query =
            "UPDATE " .
            $this->table .
            " 
                  SET ConfigName = ?, OverduePercentage = ?, GracePeriodDays = ?, IsActive = 1, UpdatedBy = ?, UpdatedAt = CURRENT_TIMESTAMP
                  WHERE ConfigID = ?";

        $stmt = $this->conn->prepare($query);

        $configName = htmlspecialchars(strip_tags($configName));

        $stmt->bind_param(
            "sdiii",
            $configName,
            $overduePercentage,
            $gracePeriodDays,
            $updatedBy,
            $configID
        );

        return $stmt->execute();
    }

    public function createNewConfig(
        $configName,
        $overduePercentage,
        $gracePeriodDays,
        $updatedBy
    ) {
        error_log(
            "DEBUG: OverdueConfig::createNewConfig called with: " .
                "Name: $configName, Percentage: $overduePercentage, Grace: $gracePeriodDays, User: $updatedBy"
        );

        // First, deactivate all configs
        $deactivateQuery =
            "UPDATE " . $this->table . " SET IsActive = 0 WHERE IsActive = 1";
        $deactivateResult = $this->conn->query($deactivateQuery);
        error_log(
            "DEBUG: Deactivate query result: " .
                ($deactivateResult ? "SUCCESS" : "FAILED")
        );

        if (!$deactivateResult) {
            error_log("DEBUG: Deactivate query error: " . $this->conn->error);
        }

        // Create new active config
        $this->configName = $configName;
        $this->overduePercentage = $overduePercentage;
        $this->gracePeriodDays = $gracePeriodDays;
        $this->isActive = 1;
        $this->updatedBy = $updatedBy;

        $result = $this->create();
        error_log("DEBUG: Create result: " . ($result ? "SUCCESS" : "FAILED"));

        return $result;
    }

    public function calculateOverdueAmount($originalAmount, $deadlineDate)
    {
        $activeConfig = $this->getActiveConfig();

        if (!$deadlineDate) {
            return $originalAmount;
        }

        $today = new DateTime();
        $deadline = new DateTime($deadlineDate);

        // Add grace period to deadline
        if ($activeConfig["GracePeriodDays"] > 0) {
            $deadline->modify("+" . $activeConfig["GracePeriodDays"] . " days");
        }

        // Check if payment is overdue
        if ($today > $deadline) {
            $overduePercentage = $activeConfig["OverduePercentage"] / 100;
            $overdueAmount = $originalAmount * $overduePercentage;
            return $originalAmount + $overdueAmount;
        }

        return $originalAmount;
    }

    public function isPaymentOverdue($deadlineDate)
    {
        if (!$deadlineDate) {
            return false;
        }

        $activeConfig = $this->getActiveConfig();
        $today = new DateTime();
        $deadline = new DateTime($deadlineDate);

        // Add grace period to deadline
        if ($activeConfig["GracePeriodDays"] > 0) {
            $deadline->modify("+" . $activeConfig["GracePeriodDays"] . " days");
        }

        return $today > $deadline;
    }

    public function deleteConfig($configID)
    {
        // Don't allow deletion of the last remaining config
        $countQuery = "SELECT COUNT(*) as count FROM " . $this->table;
        $countStmt = $this->conn->prepare($countQuery);
        $countStmt->execute();
        $countResult = $countStmt->get_result()->fetch_assoc();

        if ($countResult["count"] <= 1) {
            return false; // Can't delete the last config
        }

        $query = "DELETE FROM " . $this->table . " WHERE ConfigID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $configID);

        return $stmt->execute();
    }
} ?> 