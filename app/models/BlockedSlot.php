<?php
// app/models/BlockedSlot.php

class BlockedSlot {
    protected $conn;
    protected $table = 'blocked_slots';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all blocked time slots for a specific doctor and date
    public function getByDoctorAndDate($doctorId, $date) {
        $query = "SELECT blocked_time FROM " . $this->table . " WHERE doctor_id = ? AND blocked_date = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("is", $doctorId, $date);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $blockedTimes = [];
        while ($row = $result->fetch_assoc()) {
            // Format time to H:i:s for consistent comparison
            $blockedTimes[] = date("H:i:s", strtotime($row['blocked_time']));
        }
        return $blockedTimes;
    }

    // A simple way to update: delete all for the day, then re-insert the new list
    public function updateForDoctor($doctorId, $date, $timesToBlock) {
        $this->conn->begin_transaction();
        try {
            // Step 1: Delete all existing blocks for this doctor on this day
            $deleteQuery = "DELETE FROM " . $this->table . " WHERE doctor_id = ? AND blocked_date = ?";
            $deleteStmt = $this->conn->prepare($deleteQuery);
            $deleteStmt->bind_param("is", $doctorId, $date);
            $deleteStmt->execute();

            // Step 2: If there are new times to block, insert them
            if (!empty($timesToBlock)) {
                $insertQuery = "INSERT INTO " . $this->table . " (doctor_id, blocked_date, blocked_time) VALUES (?, ?, ?)";
                $insertStmt = $this->conn->prepare($insertQuery);
                
                foreach ($timesToBlock as $time) {
                    $insertStmt->bind_param("iss", $doctorId, $date, $time);
                    $insertStmt->execute();
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Failed to update blocked slots: " . $e->getMessage());
            return false;
        }
    }
}