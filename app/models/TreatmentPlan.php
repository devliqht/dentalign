<?php

class TreatmentPlan
{
    protected $conn;
    protected $table = "TreatmentPlan";
    public $treatmentPlanID;
    public $appointmentReportID;
    public $status;
    public $dentistNotes;
    public $assignedAt;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $query =
            "INSERT INTO " .
            $this->table .
            " (AppointmentReportID, Status, DentistNotes, AssignedAt)
            VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            error_log(
                "TreatmentPlan create prepare failed: " . $this->conn->error
            );
            return false;
        }

        $stmt->bind_param(
            "isss",
            $this->appointmentReportID,
            $this->status,
            $this->dentistNotes,
            $this->assignedAt
        );

        if ($stmt->execute()) {
            $this->treatmentPlanID = $this->conn->insert_id;
            return true;
        } else {
            error_log("TreatmentPlan create execute failed: " . $stmt->error);
            return false;
        }
    }

    public function findByAppointmentReportID($appointmentReportID)
    {
        $query =
            "SELECT TreatmentPlanID, AppointmentReportID, Status, DentistNotes, AssignedAt
             FROM " .
            $this->table .
            "
             WHERE AppointmentReportID = ?
             ORDER BY AssignedAt DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $appointmentReportID);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findByID($treatmentPlanID)
    {
        $query =
            "SELECT TreatmentPlanID, AppointmentReportID, Status, DentistNotes, AssignedAt
             FROM " .
            $this->table .
            "
             WHERE TreatmentPlanID = ? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $treatmentPlanID);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->treatmentPlanID = $row["TreatmentPlanID"];
            $this->appointmentReportID = $row["AppointmentReportID"];
            $this->status = $row["Status"];
            $this->dentistNotes = $row["DentistNotes"];
            $this->assignedAt = $row["AssignedAt"];
            return true;
        }
        return false;
    }

    public function getTreatmentPlansByPatientID($patientID)
    {
        $query =
            "SELECT
                    tp.TreatmentPlanID,
                    tp.AppointmentReportID,
                    tp.Status,
                    tp.DentistNotes,
                    tp.AssignedAt,
                    ar.AppointmentID,
                    a.DateTime as AppointmentDate,
                    a.AppointmentType,
                    CONCAT(u.FirstName, ' ', u.LastName) as DoctorName,
                    d.Specialization as DoctorSpecialization,
                    COUNT(tpi.TreatmentItemID) as TotalItems,
                    SUM(CASE WHEN tpi.CompletedAt IS NOT NULL THEN 1 ELSE 0 END) as CompletedItems
                  FROM " .
            $this->table .
            " tp
                  INNER JOIN AppointmentReport ar ON tp.AppointmentReportID = ar.AppointmentReportID
                  INNER JOIN Appointment a ON ar.AppointmentID = a.AppointmentID
                  INNER JOIN Doctor d ON a.DoctorID = d.DoctorID
                  INNER JOIN USER u ON d.DoctorID = u.UserID
                  LEFT JOIN TreatmentPlanItem tpi ON tp.TreatmentPlanID = tpi.TreatmentPlanID
                  WHERE a.PatientID = ?
                  GROUP BY tp.TreatmentPlanID
                  ORDER BY tp.AssignedAt DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $patientID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    public function getValidAppointmentReportsForTreatmentPlan($patientID)
    {
        $query =
            "SELECT
                    ar.AppointmentReportID,
                    ar.AppointmentID,
                    a.DateTime as AppointmentDate,
                    a.AppointmentType,
                    a.Reason,
                    CONCAT(u.FirstName, ' ', u.LastName) as DoctorName
                  FROM AppointmentReport ar
                  INNER JOIN Appointment a ON ar.AppointmentID = a.AppointmentID
                  INNER JOIN Doctor d ON a.DoctorID = d.DoctorID
                  INNER JOIN USER u ON d.DoctorID = u.UserID
                  WHERE a.PatientID = ?
                    AND a.Status = 'Completed'
                    AND ar.AppointmentReportID NOT IN (
                        SELECT AppointmentReportID FROM " .
            $this->table .
            "
                    )
                  ORDER BY a.DateTime DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $patientID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    public function updateStatus($treatmentPlanID, $status)
    {
        $query =
            "UPDATE " .
            $this->table .
            " SET Status = ? WHERE TreatmentPlanID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $status, $treatmentPlanID);
        return $stmt->execute();
    }

    public function update()
    {
        $query =
            "UPDATE " .
            $this->table .
            "
                  SET Status = ?, DentistNotes = ?
                  WHERE TreatmentPlanID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "ssi",
            $this->status,
            $this->dentistNotes,
            $this->treatmentPlanID
        );
        return $stmt->execute();
    }

    public function delete($treatmentPlanID)
    {
        // Soft delete: Set status to 'cancelled' instead of hard deleting
        $query = "UPDATE " . $this->table . " SET Status = 'cancelled' WHERE TreatmentPlanID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $treatmentPlanID);
        return $stmt->execute();
    }

    public function getTreatmentPlanWithDetails($treatmentPlanID)
    {
        $query =
            "SELECT
                    tp.TreatmentPlanID,
                    tp.AppointmentReportID,
                    tp.Status,
                    tp.DentistNotes,
                    tp.AssignedAt,
                    ar.AppointmentID,
                    a.DateTime as AppointmentDate,
                    a.AppointmentType,
                    a.Reason,
                    CONCAT(u.FirstName, ' ', u.LastName) as DoctorName,
                    d.Specialization as DoctorSpecialization,
                    CONCAT(up.FirstName, ' ', up.LastName) as PatientName
                  FROM " .
            $this->table .
            " tp
                  INNER JOIN AppointmentReport ar ON tp.AppointmentReportID = ar.AppointmentReportID
                  INNER JOIN Appointment a ON ar.AppointmentID = a.AppointmentID
                  INNER JOIN Doctor d ON a.DoctorID = d.DoctorID
                  INNER JOIN USER u ON d.DoctorID = u.UserID
                  INNER JOIN USER up ON a.PatientID = up.UserID
                  WHERE tp.TreatmentPlanID = ? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $treatmentPlanID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        return null;
    }

    public function calculateProgress($treatmentPlanID)
    {
        $query = "SELECT
                    COUNT(*) as total_items,
                    SUM(CASE WHEN CompletedAt IS NOT NULL THEN 1 ELSE 0 END) as completed_items
                  FROM TreatmentPlanItem
                  WHERE TreatmentPlanID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $treatmentPlanID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row["total_items"] == 0) {
                return 0;
            }

            return round(
                ($row["completed_items"] / $row["total_items"]) * 100,
                1
            );
        }
        return 0;
    }
}
