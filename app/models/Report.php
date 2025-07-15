<?php

class Report
{
    protected $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Revenue Analytics
    public function getTotalRevenue()
    {
        $query = "SELECT SUM(pi.Total) as total_revenue 
                  FROM PaymentItems pi
                  INNER JOIN Payments p ON pi.PaymentID = p.PaymentID
                  WHERE p.Status = 'Paid'";

        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row["total_revenue"] ?? 0;
    }

    public function getTodayRevenue()
    {
        $query = "SELECT SUM(pi.Total) as today_revenue 
                  FROM PaymentItems pi
                  INNER JOIN Payments p ON pi.PaymentID = p.PaymentID
                  WHERE p.Status = 'Paid' AND DATE(p.UpdatedAt) = CURDATE()";

        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row["today_revenue"] ?? 0;
    }

    public function getMonthlyRevenue($year = null, $month = null)
    {
        if (!$year) {
            $year = date("Y");
        }
        if (!$month) {
            $month = date("m");
        }

        $query = "SELECT SUM(pi.Total) as monthly_revenue 
                  FROM PaymentItems pi
                  INNER JOIN Payments p ON pi.PaymentID = p.PaymentID
                  WHERE p.Status = 'Paid' 
                  AND YEAR(p.UpdatedAt) = ? 
                  AND MONTH(p.UpdatedAt) = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $year, $month);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row["monthly_revenue"] ?? 0;
    }

    public function getWeeklyRevenue()
    {
        $query = "SELECT SUM(pi.Total) as weekly_revenue 
                  FROM PaymentItems pi
                  INNER JOIN Payments p ON pi.PaymentID = p.PaymentID
                  WHERE p.Status = 'Paid' 
                  AND WEEK(p.UpdatedAt, 1) = WEEK(CURDATE(), 1) 
                  AND YEAR(p.UpdatedAt) = YEAR(CURDATE())";

        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row["weekly_revenue"] ?? 0;
    }

    public function getRevenueByDateRange($startDate, $endDate)
    {
        $query = "SELECT DATE(p.UpdatedAt) as date, SUM(pi.Total) as daily_revenue
                  FROM PaymentItems pi
                  INNER JOIN Payments p ON pi.PaymentID = p.PaymentID
                  WHERE p.Status = 'Paid' 
                  AND DATE(p.UpdatedAt) BETWEEN ? AND ?
                  GROUP BY DATE(p.UpdatedAt)
                  ORDER BY DATE(p.UpdatedAt)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getMonthlyRevenueChart($months = 12)
    {
        $query = "SELECT 
                    YEAR(p.UpdatedAt) as year,
                    MONTH(p.UpdatedAt) as month,
                    MONTHNAME(p.UpdatedAt) as month_name,
                    SUM(pi.Total) as revenue
                  FROM PaymentItems pi
                  INNER JOIN Payments p ON pi.PaymentID = p.PaymentID
                  WHERE p.Status = 'Paid' 
                  AND p.UpdatedAt >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
                  GROUP BY YEAR(p.UpdatedAt), MONTH(p.UpdatedAt)
                  ORDER BY YEAR(p.UpdatedAt), MONTH(p.UpdatedAt)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $months);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Appointment Analytics
    public function getTotalAppointments()
    {
        $query = "SELECT COUNT(*) as total_appointments FROM Appointment";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row["total_appointments"] ?? 0;
    }

    public function getTodayAppointments()
    {
        $query = "SELECT COUNT(*) as today_appointments 
                  FROM Appointment 
                  WHERE DATE(DateTime) = CURDATE()";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row["today_appointments"] ?? 0;
    }

    public function getCompletedAppointments()
    {
        $query = "SELECT COUNT(*) as completed_appointments 
                  FROM Appointment 
                  WHERE Status = 'Completed'";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row["completed_appointments"] ?? 0;
    }

    public function getPendingAppointments()
    {
        $query = "SELECT COUNT(*) as pending_appointments 
                  FROM Appointment 
                  WHERE Status IN ('Pending', 'Approved')";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row["pending_appointments"] ?? 0;
    }

    public function getAppointmentsByStatus()
    {
        $query = "SELECT Status, COUNT(*) as count 
                  FROM Appointment 
                  GROUP BY Status
                  ORDER BY count DESC";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAppointmentsByType()
    {
        $query = "SELECT AppointmentType, COUNT(*) as count 
                  FROM Appointment 
                  GROUP BY AppointmentType
                  ORDER BY count DESC";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAppointmentsByMonth($months = 12)
    {
        $query = "SELECT 
                    YEAR(DateTime) as year,
                    MONTH(DateTime) as month,
                    MONTHNAME(DateTime) as month_name,
                    COUNT(*) as count
                  FROM Appointment 
                  WHERE DateTime >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
                  GROUP BY YEAR(DateTime), MONTH(DateTime)
                  ORDER BY YEAR(DateTime), MONTH(DateTime)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $months);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getDailyAppointments($days = 30)
    {
        $query = "SELECT 
                    DATE(DateTime) as date,
                    COUNT(*) as count
                  FROM Appointment 
                  WHERE DateTime >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                  GROUP BY DATE(DateTime)
                  ORDER BY DATE(DateTime)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $days);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Payment Analytics
    public function getPaymentStatistics()
    {
        $query = "SELECT 
                    p.Status,
                    COUNT(*) as count,
                    SUM(pi.Total) as total_amount
                  FROM Payments p
                  LEFT JOIN PaymentItems pi ON p.PaymentID = pi.PaymentID
                  GROUP BY p.Status
                  ORDER BY total_amount DESC";

        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPaymentMethods()
    {
        $query = "SELECT 
                    PaymentMethod,
                    COUNT(*) as count,
                    SUM(pi.Total) as total_amount
                  FROM Payments p
                  LEFT JOIN PaymentItems pi ON p.PaymentID = pi.PaymentID
                  WHERE p.Status = 'Paid'
                  GROUP BY PaymentMethod
                  ORDER BY total_amount DESC";

        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getOverduePayments()
    {
        $query = "SELECT COUNT(*) as overdue_count,
                         SUM(pi.Total) as overdue_amount
                  FROM Payments p
                  LEFT JOIN PaymentItems pi ON p.PaymentID = pi.PaymentID
                  WHERE p.Status = 'Overdue' OR 
                        (p.Status = 'Pending' AND p.DeadlineDate < CURDATE())";

        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return [
            "count" => $row["overdue_count"] ?? 0,
            "amount" => $row["overdue_amount"] ?? 0,
        ];
    }

    // Doctor Performance Analytics
    public function getDoctorPerformance()
    {
        $query = "SELECT 
                    CONCAT(u.FirstName, ' ', u.LastName) as doctor_name,
                    d.Specialization,
                    COUNT(a.AppointmentID) as total_appointments,
                    SUM(CASE WHEN a.Status = 'Completed' THEN 1 ELSE 0 END) as completed_appointments,
                    SUM(pi.Total) as total_revenue
                  FROM Doctor d
                  INNER JOIN USER u ON d.DoctorID = u.UserID
                  LEFT JOIN Appointment a ON d.DoctorID = a.DoctorID
                  LEFT JOIN Payments p ON a.AppointmentID = p.AppointmentID AND p.Status = 'Paid'
                  LEFT JOIN PaymentItems pi ON p.PaymentID = pi.PaymentID
                  GROUP BY d.DoctorID, u.FirstName, u.LastName, d.Specialization
                  ORDER BY total_revenue DESC";

        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Patient Analytics
    public function getNewPatientsThisMonth()
    {
        $query = "SELECT COUNT(*) as new_patients 
                  FROM PATIENT p
                  INNER JOIN USER u ON p.PatientID = u.UserID
                  WHERE YEAR(u.CreatedAt) = YEAR(CURDATE()) 
                  AND MONTH(u.CreatedAt) = MONTH(CURDATE())";

        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row["new_patients"] ?? 0;
    }

    public function getTotalPatients()
    {
        $query = "SELECT COUNT(*) as total_patients FROM PATIENT";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row["total_patients"] ?? 0;
    }

    // Summary Dashboard Data
    public function getDashboardSummary()
    {
        return [
            "revenue" => [
                "total" => $this->getTotalRevenue(),
                "today" => $this->getTodayRevenue(),
                "weekly" => $this->getWeeklyRevenue(),
                "monthly" => $this->getMonthlyRevenue(),
            ],
            "appointments" => [
                "total" => $this->getTotalAppointments(),
                "today" => $this->getTodayAppointments(),
                "completed" => $this->getCompletedAppointments(),
                "pending" => $this->getPendingAppointments(),
            ],
            "patients" => [
                "total" => $this->getTotalPatients(),
                "new_this_month" => $this->getNewPatientsThisMonth(),
            ],
            "payments" => [
                "overdue" => $this->getOverduePayments(),
            ],
        ];
    }

    // Revenue Breakdown - Appointments that contributed to revenue
    public function getAppointmentsByTotalRevenue()
    {
        $query = "SELECT 
                    a.AppointmentID,
                    a.DateTime,
                    a.AppointmentType,
                    CONCAT(p_user.FirstName, ' ', p_user.LastName) as PatientName,
                    CONCAT(d_user.FirstName, ' ', d_user.LastName) as DoctorName,
                    SUM(pi.Total) as TotalAmount
                  FROM Appointment a
                  INNER JOIN Payments p ON a.AppointmentID = p.AppointmentID
                  INNER JOIN PaymentItems pi ON p.PaymentID = pi.PaymentID
                  INNER JOIN PATIENT pat ON a.PatientID = pat.PatientID
                  INNER JOIN USER p_user ON pat.PatientID = p_user.UserID
                  INNER JOIN Doctor doc ON a.DoctorID = doc.DoctorID
                  INNER JOIN USER d_user ON doc.DoctorID = d_user.UserID
                  WHERE p.Status = 'Paid'
                  GROUP BY a.AppointmentID
                  ORDER BY a.DateTime DESC";

        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAppointmentsByTodayRevenue()
    {
        $query = "SELECT 
                    a.AppointmentID,
                    a.DateTime,
                    a.AppointmentType,
                    CONCAT(p_user.FirstName, ' ', p_user.LastName) as PatientName,
                    CONCAT(d_user.FirstName, ' ', d_user.LastName) as DoctorName,
                    SUM(pi.Total) as TotalAmount
                  FROM Appointment a
                  INNER JOIN Payments p ON a.AppointmentID = p.AppointmentID
                  INNER JOIN PaymentItems pi ON p.PaymentID = pi.PaymentID
                  INNER JOIN PATIENT pat ON a.PatientID = pat.PatientID
                  INNER JOIN USER p_user ON pat.PatientID = p_user.UserID
                  INNER JOIN Doctor doc ON a.DoctorID = doc.DoctorID
                  INNER JOIN USER d_user ON doc.DoctorID = d_user.UserID
                  WHERE p.Status = 'Paid' AND DATE(p.UpdatedAt) = CURDATE()
                  GROUP BY a.AppointmentID
                  ORDER BY a.DateTime DESC";

        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAppointmentsByWeeklyRevenue()
    {
        $query = "SELECT 
                    a.AppointmentID,
                    a.DateTime,
                    a.AppointmentType,
                    CONCAT(p_user.FirstName, ' ', p_user.LastName) as PatientName,
                    CONCAT(d_user.FirstName, ' ', d_user.LastName) as DoctorName,
                    SUM(pi.Total) as TotalAmount
                  FROM Appointment a
                  INNER JOIN Payments p ON a.AppointmentID = p.AppointmentID
                  INNER JOIN PaymentItems pi ON p.PaymentID = pi.PaymentID
                  INNER JOIN PATIENT pat ON a.PatientID = pat.PatientID
                  INNER JOIN USER p_user ON pat.PatientID = p_user.UserID
                  INNER JOIN Doctor doc ON a.DoctorID = doc.DoctorID
                  INNER JOIN USER d_user ON doc.DoctorID = d_user.UserID
                  WHERE p.Status = 'Paid' 
                  AND WEEK(p.UpdatedAt, 1) = WEEK(CURDATE(), 1) 
                  AND YEAR(p.UpdatedAt) = YEAR(CURDATE())
                  GROUP BY a.AppointmentID
                  ORDER BY a.DateTime DESC";

        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAppointmentsByMonthlyRevenue($year = null, $month = null)
    {
        if (!$year) {
            $year = date("Y");
        }
        if (!$month) {
            $month = date("m");
        }

        $query = "SELECT 
                    a.AppointmentID,
                    a.DateTime,
                    a.AppointmentType,
                    CONCAT(p_user.FirstName, ' ', p_user.LastName) as PatientName,
                    CONCAT(d_user.FirstName, ' ', d_user.LastName) as DoctorName,
                    SUM(pi.Total) as TotalAmount
                  FROM Appointment a
                  INNER JOIN Payments p ON a.AppointmentID = p.AppointmentID
                  INNER JOIN PaymentItems pi ON p.PaymentID = pi.PaymentID
                  INNER JOIN PATIENT pat ON a.PatientID = pat.PatientID
                  INNER JOIN USER p_user ON pat.PatientID = p_user.UserID
                  INNER JOIN Doctor doc ON a.DoctorID = doc.DoctorID
                  INNER JOIN USER d_user ON doc.DoctorID = d_user.UserID
                  WHERE p.Status = 'Paid' 
                  AND YEAR(p.UpdatedAt) = ? 
                  AND MONTH(p.UpdatedAt) = ?
                  GROUP BY a.AppointmentID
                  ORDER BY a.DateTime DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $year, $month);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Chart Data Formatters
    public function getRevenueChartData($months = 6)
    {
        $data = $this->getMonthlyRevenueChart($months);
        $labels = [];
        $values = [];

        foreach ($data as $row) {
            $labels[] = $row["month_name"] . " " . $row["year"];
            $values[] = floatval($row["revenue"] ?? 0);
        }

        return [
            "labels" => $labels,
            "data" => $values,
        ];
    }

    public function getAppointmentTypeChartData()
    {
        $data = $this->getAppointmentsByType();
        $labels = [];
        $values = [];

        foreach ($data as $row) {
            $labels[] = $row["AppointmentType"] ?: "Unspecified";
            $values[] = intval($row["count"]);
        }

        return [
            "labels" => $labels,
            "data" => $values,
        ];
    }

    public function getAppointmentStatusChartData()
    {
        $data = $this->getAppointmentsByStatus();
        $labels = [];
        $values = [];

        foreach ($data as $row) {
            $labels[] = ucfirst($row["Status"]);
            $values[] = intval($row["count"]);
        }

        return [
            "labels" => $labels,
            "data" => $values,
        ];
    }
}
