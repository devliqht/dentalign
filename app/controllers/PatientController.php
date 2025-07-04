<?php

require_once "app/core/Controller.php";
require_once "app/models/User.php";
require_once "app/models/Appointment.php";
require_once "app/models/Patient.php";
require_once "app/models/Doctor.php";
require_once "app/models/PatientRecord.php";
require_once "app/models/AppointmentReport.php";
require_once "app/models/Payment.php";
require_once "app/models/PaymentItem.php";

class PatientController extends Controller
{
    protected function initializeMiddleware()
    {
        $this->middleware("auth", ["only" => ["*"]]);
        $this->middleware("role:Patient", ["only" => ["*"]]);
    }

    public function dashboard()
    {
        $this->requireAuth();
        $this->requireRole("Patient");

        $user = $this->getAuthUser();
        $appointment = new Appointment($this->conn);
        $patient = new Patient($this->conn);
        $payment = new Payment($this->conn);
        $patientRecord = new PatientRecord($this->conn);

        $patientData = $patient->getPatientByUserId($user["id"]);

        $upcomingAppointments = $appointment->getUpcomingAppointmentsByPatient(
            $user["id"]
        );

        $completedAppointments = $appointment->getCompletedAppointmentsByPatient(
            $user["id"]
        );

        $allAppointments = $appointment->getAppointmentsByPatient($user["id"]);

        $today = date("Y-m-d");
        $startOfWeek = date(
            "Y-m-d",
            strtotime("monday this week", strtotime($today))
        );
        $endOfWeek = date(
            "Y-m-d",
            strtotime("sunday this week", strtotime($today))
        );

        $weekAppointments = [];
        if ($patientData) {
            $weekQuery = "SELECT a.*, 
                            CONCAT(u.FirstName, ' ', u.LastName) as DoctorName,
                            d.Specialization
                          FROM Appointment a
                          LEFT JOIN Doctor d ON a.DoctorID = d.DoctorID
                          LEFT JOIN USER u ON d.DoctorID = u.UserID
                          WHERE a.PatientID = ? 
                          AND DATE(a.DateTime) BETWEEN ? AND ?
                          ORDER BY a.DateTime ASC";

            $stmt = $this->conn->prepare($weekQuery);
            $stmt->bind_param(
                "iss",
                $patientData["PatientID"],
                $startOfWeek,
                $endOfWeek
            );
            $stmt->execute();
            $result = $stmt->get_result();
            $weekAppointments = $result->fetch_all(MYSQLI_ASSOC);
        }

        $appointmentPayments = [];
        $pendingPayments = [];
        $totalPendingAmount = 0;
        $totalPaidAmount = 0;

        if ($patientData) {
            $payments = $payment->getPaymentsByPatient(
                $patientData["PatientID"]
            );
            foreach ($payments as $paymentRecord) {
                $paymentWithBreakdown = $payment->getPaymentWithBreakdown(
                    $paymentRecord["PaymentID"]
                );
                $paymentRecord["items"] = $paymentWithBreakdown["items"] ?? [];
                $paymentRecord["total_amount"] =
                    $paymentWithBreakdown["total_amount"] ?? 0;

                $appointmentPayments[
                    $paymentRecord["AppointmentID"]
                ] = $paymentRecord;

                // Track pending payments
                if (strtolower($paymentRecord["Status"]) === "pending") {
                    $pendingPayments[] = $paymentRecord;
                    $totalPendingAmount += $paymentRecord["total_amount"];
                } elseif (strtolower($paymentRecord["Status"]) === "paid") {
                    $totalPaidAmount += $paymentRecord["total_amount"];
                }
            }
        }

        $patientPhysicalInfo = null;
        if ($patientData) {
            $patientPhysicalInfo = $patientRecord->getRecordByPatientID(
                $patientData["PatientID"]
            );
        }

        $appointmentStats = [
            "total" => count($allAppointments),
            "completed" => count($completedAppointments),
            "upcoming" => count($upcomingAppointments),
        ];

        // Mock current treatments data (UI only for now)
        $currentTreatments = [
            [
                "id" => 1,
                "name" => "Orthodontic Treatment",
                "doctor" => "Dr. Smith",
                "specialization" => "Orthodontist",
                "progress" => 65,
                "next_appointment" => "2024-01-15",
                "status" => "In Progress",
                "description" => "Braces treatment for teeth alignment",
            ],
            [
                "id" => 2,
                "name" => "Root Canal Therapy",
                "doctor" => "Dr. Johnson",
                "specialization" => "Endodontist",
                "progress" => 80,
                "next_appointment" => "2024-01-20",
                "status" => "Final Stage",
                "description" => "Root canal treatment for tooth #14",
            ],
        ];

        $data = [
            "user" => $user,
            "patientData" => $patientData,
            "upcomingAppointments" => $upcomingAppointments,
            "completedAppointments" => $completedAppointments,
            "allAppointments" => $allAppointments,
            "appointmentPayments" => $appointmentPayments,
            "pendingPayments" => $pendingPayments,
            "totalPendingAmount" => $totalPendingAmount,
            "totalPaidAmount" => $totalPaidAmount,
            "patientPhysicalInfo" => $patientPhysicalInfo,
            "appointmentStats" => $appointmentStats,
            "currentTreatments" => $currentTreatments,
            "weekAppointments" => $weekAppointments,
            "startOfWeek" => $startOfWeek,
            "endOfWeek" => $endOfWeek,
            "selectedDate" => $today,
        ];

        $layoutConfig = [
            "title" => "Patient Dashboard",
            "hideHeader" => true,
            "hideFooter" => false,
            "showLoading" => true,
        ];

        $this->view("pages/patient/Dashboard", $data, $layoutConfig);
    }

    public function bookings()
    {
        $this->requireAuth();
        $this->requireRole("Patient");

        $user = $this->getAuthUser();
        $appointment = new Appointment($this->conn);
        $upcomingAppointments = $appointment->getUpcomingAppointmentsByPatient(
            $user["id"]
        );
        $completedAppointments = $appointment->getCompletedAppointmentsByPatient(
            $user["id"]
        );

        $payment = new Payment($this->conn);
        $patient = new Patient($this->conn);
        $patientData = $patient->getPatientByUserId($user["id"]);

        $appointmentPayments = [];
        if ($patientData) {
            $payments = $payment->getPaymentsByPatient(
                $patientData["PatientID"]
            );
            foreach ($payments as $paymentRecord) {
                $paymentWithBreakdown = $payment->getPaymentWithBreakdown(
                    $paymentRecord["PaymentID"]
                );
                $paymentRecord["items"] = $paymentWithBreakdown["items"] ?? [];
                $paymentRecord["total_amount"] =
                    $paymentWithBreakdown["total_amount"] ?? 0;
                $appointmentPayments[
                    $paymentRecord["AppointmentID"]
                ] = $paymentRecord;
            }
        }

        $data = [
            "user" => $user,
            "upcomingAppointments" => $upcomingAppointments,
            "completedAppointments" => $completedAppointments,
            "appointmentPayments" => $appointmentPayments,
        ];

        $additionalHead =
            '<link rel="stylesheet" href="' .
            BASE_URL .
            '/app/styles/views/Bookings.css">';

        $layoutConfig = [
            "title" => "My Bookings",
            "hideHeader" => true,
            "hideFooter" => false,
            "additionalScripts" =>
                '<script>window.BASE_URL = "' .
                BASE_URL .
                '";</script><script src="' .
                BASE_URL .
                '/app/views/scripts/AppointmentPages/Bookings.js"></script>',
            "additionalHead" => $additionalHead,
        ];

        $this->view("pages/patient/Bookings", $data, $layoutConfig);
    }

    public function bookAppointment()
    {
        $this->requireAuth();
        $this->requireRole("Patient");

        $user = $this->getAuthUser();

        $doctor = new Doctor($this->conn);
        $doctors = $doctor->getAllDoctors();

        // get doctor time slots if doctor and time selected
        $timeSlots = [];
        if (isset($_GET["doctor_id"]) && isset($_GET["date"])) {
            $appointment = new Appointment($this->conn);
            $timeSlots = $appointment->getAllTimeSlotsWithStatus(
                $_GET["doctor_id"],
                $_GET["date"]
            );
        }

        $data = [
            "user" => $user,
            "doctors" => $doctors,
            "timeSlots" => $timeSlots,
            "selectedDoctorId" => $_GET["doctor_id"] ?? "",
            "selectedDate" => $_GET["date"] ?? "",
            "csrf_token" => $this->generateCsrfToken(),
        ];

        $additionalHead =
            '<link rel="stylesheet" href="' .
            BASE_URL .
            '/app/styles/views/BookAppointment.css">';

        $layoutConfig = [
            "title" => "Book Appointment",
            "hideHeader" => true,
            "hideFooter" => false,
            "additionalScripts" =>
                '<script>window.BASE_URL = "' .
                BASE_URL .
                '";</script><script src="' .
                BASE_URL .
                '/app/views/scripts/AppointmentPages/BookAppointments.js"></script>',
            "additionalHead" => $additionalHead,
        ];

        $this->view("pages/patient/BookAppointment", $data, $layoutConfig);
    }

    public function getTimeslots()
    {
        $this->requireAuth();
        $this->requireRole("Patient");

        header("Content-Type: application/json");

        if (isset($_GET["doctor_id"]) && isset($_GET["date"])) {
            $appointment = new Appointment($this->conn);
            $timeSlots = $appointment->getAllTimeSlotsWithStatus(
                $_GET["doctor_id"],
                $_GET["date"]
            );

            echo json_encode([
                "success" => true,
                "timeSlots" => $timeSlots,
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Missing doctor_id or date parameter",
            ]);
        }
        exit();
    }

    public function payments()
    {
        $this->requireAuth();
        $this->requireRole("Patient");

        $user = $this->getAuthUser();
        $payment = new Payment($this->conn);
        $patient = new Patient($this->conn);
        $appointment = new Appointment($this->conn);

        $patientData = $patient->getPatientByUserId($user["id"]);

        if (!$patientData) {
            $this->redirect("/patient/dashboard");
            return;
        }

        $allAppointments = $appointment->getAppointmentsByPatient($user["id"]);

        $existingPayments = $payment->getPaymentsByPatient(
            $patientData["PatientID"]
        );

        $paymentsByAppointment = [];
        foreach ($existingPayments as $existingPayment) {
            $paymentWithBreakdown = $payment->getPaymentWithBreakdown(
                $existingPayment["PaymentID"]
            );
            $existingPayment["items"] = $paymentWithBreakdown["items"] ?? [];
            $existingPayment["total_amount"] =
                $paymentWithBreakdown["total_amount"] ?? 0;
            $paymentsByAppointment[
                $existingPayment["AppointmentID"]
            ] = $existingPayment;
        }

        // Create payment records for all appointments (including those without payment records)
        $payments = [];
        foreach ($allAppointments as $appointmentRecord) {
            if (
                isset(
                    $paymentsByAppointment[$appointmentRecord["AppointmentID"]]
                )
            ) {
                // Use existing payment record
                $payments[] =
                    $paymentsByAppointment[$appointmentRecord["AppointmentID"]];
            } else {
                // Create pseudo payment record for appointments without payment records
                $payments[] = [
                    "PaymentID" => null,
                    "AppointmentID" => $appointmentRecord["AppointmentID"],
                    "PatientID" => $appointmentRecord["PatientID"],
                    "Status" => "Pending",
                    "UpdatedBy" => null,
                    "UpdatedAt" => $appointmentRecord["CreatedAt"],
                    "Notes" => null,
                    "AppointmentDateTime" => $appointmentRecord["DateTime"],
                    "AppointmentType" => $appointmentRecord["AppointmentType"],
                    "Reason" => $appointmentRecord["Reason"],
                    "DoctorName" =>
                        $appointmentRecord["DoctorFirstName"] .
                        " " .
                        $appointmentRecord["DoctorLastName"],
                    "Specialization" =>
                        $appointmentRecord["Specialization"] ?? "General",
                    "UpdatedByName" => "System",
                    "items" => [],
                    "total_amount" => 0.0,
                ];
            }
        }

        // Sort by appointment date (most recent first)
        usort($payments, function ($a, $b) {
            return strtotime($b["AppointmentDateTime"]) -
                strtotime($a["AppointmentDateTime"]);
        });

        $data = [
            "user" => $user,
            "payments" => $payments,
        ];

        $layoutConfig = [
            "title" => "Payments",
            "hideHeader" => true,
            "hideFooter" => false,
        ];

        $this->view("pages/patient/Payments", $data, $layoutConfig);
    }

    public function dentalchart()
    {
        $this->requireAuth();
        $this->requireRole("Patient");

        $user = $this->getAuthUser();

        require_once "app/models/DentalChart.php";
        require_once "app/models/DentalChartItem.php";
        require_once "app/models/Patient.php";

        $patient = new Patient($this->conn);
        $patientData = $patient->getPatientByUserId($user["id"]);

        if (!$patientData) {
            $_SESSION["error"] = "Patient data not found";
            $this->redirect("/patient/dashboard");
            return;
        }

        $patientID = $patientData["PatientID"];

        $dentalChart = new DentalChart($this->conn);
        if (!$dentalChart->findByPatientID($patientID)) {
            $dentalChart->createForPatient($patientID);
        }

        $dentalChartItem = new DentalChartItem($this->conn);
        $dentalChartItem->initializeAllTeeth($dentalChart->dentalChartID);
        $teethData = $dentalChartItem->getTeethByChartID(
            $dentalChart->dentalChartID
        );

        $teethByNumber = [];
        foreach ($teethData as $tooth) {
            $teethByNumber[$tooth["ToothNumber"]] = $tooth;
        }

        $data = [
            "user" => $this->getAuthUser(),
            "dentalChart" => [
                "DentalChartID" => $dentalChart->dentalChartID,
                "PatientID" => $dentalChart->patientID,
                "DentistID" => $dentalChart->dentistID,
                "CreatedAt" => $dentalChart->createdAt,
            ],
            "teethData" => $teethByNumber,
            "csrf_token" => $this->generateCsrfToken(),
        ];

        $layoutConfig = [
            "title" => "Dental Chart",
            "hideHeader" => true,
            "hideFooter" => false,
        ];

        $this->view("pages/patient/DentalChart", $data, $layoutConfig);
    }

    public function getDentalChartData()
    {
        $this->requireAuth();
        $this->requireRole("Patient");

        header("Content-Type: application/json");

        $user = $this->getAuthUser();

        require_once "app/models/DentalChart.php";
        require_once "app/models/DentalChartItem.php";
        require_once "app/models/Patient.php";

        $patient = new Patient($this->conn);
        $patientData = $patient->getPatientByUserId($user["id"]);

        if (!$patientData) {
            echo json_encode([
                "success" => false,
                "message" => "Patient data not found",
            ]);
            exit();
        }

        $patientID = $patientData["PatientID"];

        // Get or create dental chart
        $dentalChart = new DentalChart($this->conn);
        if (!$dentalChart->findByPatientID($patientID)) {
            $dentalChart->createForPatient($patientID);
        }

        // Get all teeth data
        $dentalChartItem = new DentalChartItem($this->conn);
        $dentalChartItem->initializeAllTeeth($dentalChart->dentalChartID);
        $teethData = $dentalChartItem->getTeethByChartID(
            $dentalChart->dentalChartID
        );

        // Organize teeth data by tooth number
        $teethByNumber = [];
        foreach ($teethData as $tooth) {
            $teethByNumber[$tooth["ToothNumber"]] = $tooth;
        }

        echo json_encode([
            "success" => true,
            "dentalChart" => [
                "DentalChartID" => $dentalChart->dentalChartID,
                "PatientID" => $dentalChart->patientID,
                "DentistID" => $dentalChart->dentistID,
                "CreatedAt" => $dentalChart->createdAt,
            ],
            "teethData" => $teethByNumber,
        ]);
        exit();
    }

    public function updatePaymentStatus()
    {
        $this->requireAuth();
        $this->requireRole("Patient"); // This will be changed to doctor/staff role later

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->redirect("/patient/payments");
            return;
        }

        // Validate CSRF token
        if (!$this->validateCsrfToken($_POST["csrf_token"] ?? "")) {
            $_SESSION["error"] = "Invalid security token";
            $this->redirect("/patient/payments");
            return;
        }

        $paymentID = $_POST["payment_id"] ?? "";
        $status = $_POST["status"] ?? "";
        $notes = $_POST["notes"] ?? "";

        if (empty($paymentID) || empty($status)) {
            $_SESSION["error"] = "Payment ID and status are required";
            $this->redirect("/patient/payments");
            return;
        }

        $user = $this->getAuthUser();
        $payment = new Payment($this->conn);

        if ($payment->updateStatus($paymentID, $status, $user["id"], $notes)) {
            $_SESSION["success"] = "Payment status updated successfully";
        } else {
            $_SESSION["error"] = "Failed to update payment status";
        }

        $this->redirect("/patient/payments");
    }

    public function getPaymentDetails()
    {
        // DEBUG: Log that this method is being called
        error_log(
            "DEBUG: PatientController::getPaymentDetails() method called"
        );

        $this->requireAuth();
        $this->requireRole("Patient");

        header("Content-Type: application/json");

        $paymentID = $_GET["payment_id"] ?? "";

        if (empty($paymentID)) {
            echo json_encode([
                "success" => false,
                "message" => "Payment ID is required",
            ]);
            exit();
        }

        $user = $this->getAuthUser();
        $patient = new Patient($this->conn);
        $patientData = $patient->getPatientByUserId($user["id"]);

        if (!$patientData) {
            echo json_encode([
                "success" => false,
                "message" => "Patient not found",
            ]);
            exit();
        }

        $payment = new Payment($this->conn);
        $paymentDetails = $payment->getPaymentWithBreakdown($paymentID);

        if (
            !$paymentDetails ||
            $paymentDetails["PatientID"] != $patientData["PatientID"]
        ) {
            echo json_encode([
                "success" => false,
                "message" => "Payment not found or access denied",
            ]);
            exit();
        }

        $html = $this->generatePaymentDetailsHTML($paymentDetails);

        echo json_encode([
            "success" => true,
            "html" => $html,
            "payment" => $paymentDetails,
        ]);
        exit();
    }

    public function profile()
    {
        $this->requireAuth();
        $this->requireRole("Patient");

        $user = $this->getAuthUser();
        $userModel = new User($this->conn);
        $userModel->findById($user["id"]);

        // Get patient record
        $patientRecord = new PatientRecord($this->conn);
        $patientRecordData = null;
        if ($patientRecord->findByPatientID($user["id"])) {
            $patientRecordData = [
                "recordID" => $patientRecord->recordID,
                "height" => $patientRecord->height,
                "weight" => $patientRecord->weight,
                "allergies" => $patientRecord->allergies,
                "createdAt" => $patientRecord->createdAt,
                "lastVisit" => $patientRecord->lastVisit,
            ];
        }

        $data = [
            "user" => $user,
            "userDetails" => [
                "firstName" => $userModel->firstName,
                "email" => $userModel->email,
                "userType" => $userModel->userType,
                "createdAt" => $userModel->createdAt,
            ],
            "patientRecord" => $patientRecordData,
            "csrf_token" => $this->generateCsrfToken(),
        ];

        $layoutConfig = [
            "title" => "Profile",
            "hideHeader" => true,
            "hideFooter" => false,
        ];

        $this->view("pages/Profile", $data, $layoutConfig);
    }

    public function updateProfile()
    {
        $this->requireAuth();
        $this->requireRole("Patient");
        $this->validateRequest("POST", true);

        $data = $this->sanitize($_POST);
        $user = $this->getAuthUser();
        $userModel = new User($this->conn);

        // Handle profile update
        if (isset($data["action"]) && $data["action"] === "update_profile") {
            $isValid = $this->validate(
                $data,
                [
                    "first_name" => "required",
                    "email" => "required|email",
                ],
                [
                    "first_name" => "First name is required",
                    "email" => "Please enter a valid email address",
                ]
            );

            if (!$isValid) {
                $this->redirectBack("Please correct the errors below");
            }

            // Check if email exists for other users
            if (
                $userModel->emailExistsForOtherUser($data["email"], $user["id"])
            ) {
                $this->redirectBack("Email already exists");
            }

            if (
                $userModel->updateProfile(
                    $user["id"],
                    $data["first_name"],
                    $data["email"]
                )
            ) {
                // Update session data
                $_SESSION["user_name"] =
                    $data["first_name"] .
                    " " .
                    ($_SESSION["user_name"]
                        ? explode(" ", $_SESSION["user_name"])[1] ?? ""
                        : "");
                $_SESSION["user_email"] = $data["email"];

                $this->redirectBack(null, "Profile updated successfully!");
            } else {
                $this->redirectBack(
                    "Failed to update profile. Please try again."
                );
            }
        }

        // Handle password update
        if (isset($data["action"]) && $data["action"] === "update_password") {
            $isValid = $this->validate(
                $data,
                [
                    "current_password" => "required",
                    "new_password" => "required|min:6",
                    "confirm_password" => "required",
                ],
                [
                    "current_password" => "Current password is required",
                    "new_password" =>
                        "Password must be at least 6 characters long",
                    "confirm_password" => "Please confirm your password",
                ]
            );

            if (!$isValid) {
                $this->redirectBack("Please correct the errors below");
            }

            if ($data["new_password"] !== $data["confirm_password"]) {
                $this->redirectBack("New passwords do not match");
            }

            // Verify current password
            $userModel->findById($user["id"]);
            if (!$userModel->verifyPassword($data["current_password"])) {
                $this->redirectBack("Current password is incorrect");
            }

            $newPasswordHash = $userModel->hashPassword($data["new_password"]);
            if ($userModel->updatePassword($user["id"], $newPasswordHash)) {
                $this->redirectBack(null, "Password updated successfully!");
            } else {
                $this->redirectBack(
                    "Failed to update password. Please try again."
                );
            }
        }

        $this->redirectBack("Invalid action");
    }

    public function storeAppointment()
    {
        $this->requireAuth();
        $this->requireRole("Patient");

        // Debug: Log raw POST data
        error_log("=== APPOINTMENT BOOKING DEBUG ===");
        error_log("Raw POST data: " . print_r($_POST, true));

        $this->validateRequest("POST", true);

        $data = $this->sanitize($_POST);
        $user = $this->getAuthUser();

        // Debug: Log sanitized data
        error_log("Sanitized data: " . print_r($data, true));
        error_log("User data: " . print_r($user, true));

        $isValid = $this->validate(
            $data,
            [
                "doctor_id" => "required",
                "appointment_date" => "required",
                "appointment_time" => "required",
                "appointment_type" => "required",
                "reason" => "required|min:10",
            ],
            [
                "doctor_id" => "Please select a doctor",
                "appointment_date" => "Please select an appointment date",
                "appointment_time" => "Please select an appointment time",
                "appointment_type" => "Please select an appointment type",
                "reason" =>
                    "Please provide a reason for your visit (at least 10 characters)",
            ]
        );

        // Debug: Log validation result
        error_log("Validation result: " . ($isValid ? "PASSED" : "FAILED"));
        if (!$isValid) {
            error_log(
                "Validation errors: " .
                    print_r($_SESSION["validation_errors"] ?? [], true)
            );
        }

        if (!$isValid) {
            $this->redirectBack("Please correct the errors below");
        }

        // future time check
        $appointmentDateTime =
            $data["appointment_date"] . " " . $data["appointment_time"] . ":00";

        error_log("Appointment DateTime: " . $appointmentDateTime);
        error_log("Current time: " . date("Y-m-d H:i:s"));
        error_log(
            "Future check result: " .
                (strtotime($appointmentDateTime) > time() ? "PASSED" : "FAILED")
        );

        if (strtotime($appointmentDateTime) <= time()) {
            error_log("FAILURE: Appointment time is not in the future");
            $this->redirectBack(
                "Appointment date and time must be in the future"
            );
        }

        $appointment = new Appointment($this->conn);

        // Add debugging
        error_log("Creating appointment for user ID: " . $user["id"]);
        error_log("Doctor ID: " . $data["doctor_id"]);
        error_log("DateTime: " . $appointmentDateTime);

        // Check if doctor exists
        $doctorCheck = $this->conn->query(
            "SELECT COUNT(*) as count FROM Doctor WHERE DoctorID = " .
                intval($data["doctor_id"])
        );
        $doctorExists = $doctorCheck->fetch_assoc()["count"] > 0;
        error_log("Doctor exists: " . ($doctorExists ? "YES" : "NO"));

        // Check availability before attempting to create
        $availabilityCheck = $appointment->checkDoctorAvailability(
            $data["doctor_id"],
            $appointmentDateTime
        );
        error_log(
            "Doctor availability check: " .
                ($availabilityCheck ? "AVAILABLE" : "NOT AVAILABLE")
        );

        $success = $appointment->createAppointment(
            $user["id"],
            $data["doctor_id"],
            $appointmentDateTime,
            $data["appointment_type"],
            $data["reason"]
        );

        error_log(
            "Appointment creation result: " . ($success ? "SUCCESS" : "FAILED")
        );

        if ($success) {
            error_log("SUCCESS: Appointment created successfully");
            $_SESSION["success"] = "Appointment booked successfully!";
            $this->redirect(BASE_URL . "/patient/bookings");
        } else {
            $error = $this->conn->error;
            error_log(
                "FAILURE: Appointment creation failed with error: " . $error
            );

            $_SESSION["error"] =
                "Failed to book appointment. The selected time slot may no longer be available.";
            $this->redirectBack();
        }

        error_log("=== END APPOINTMENT BOOKING DEBUG ===");
    }

    public function rescheduleAppointment()
    {
        $this->requireAuth();
        $this->requireRole("Patient");
        $this->validateRequest("POST", true);

        $data = $this->sanitize($_POST);
        $user = $this->getAuthUser();

        $isValid = $this->validate(
            $data,
            [
                "appointment_id" => "required",
                "new_appointment_date" => "required",
                "new_appointment_time" => "required",
            ],
            [
                "appointment_id" => "Invalid appointment",
                "new_appointment_date" =>
                    "Please select a new appointment date",
                "new_appointment_time" =>
                    "Please select a new appointment time",
            ]
        );

        if (!$isValid) {
            $this->redirectBack("Please correct the errors below");
        }

        // Verify the appointment belongs to the current patient
        $appointment = new Appointment($this->conn);
        $appointmentDetails = $appointment->getAppointmentById(
            $data["appointment_id"]
        );

        if (
            !$appointmentDetails ||
            $appointmentDetails["PatientID"] != $user["id"]
        ) {
            $this->redirectBack("Invalid appointment or unauthorized access");
        }

        $newDateTime =
            $data["new_appointment_date"] .
            " " .
            $data["new_appointment_time"] .
            ":00";
        if (strtotime($newDateTime) <= time()) {
            $this->redirectBack(
                "New appointment date and time must be in the future"
            );
        }

        $success = $appointment->rescheduleAppointment(
            $data["appointment_id"],
            $newDateTime
        );

        if ($success) {
            $_SESSION["success"] = "Appointment rescheduled successfully!";
            $this->redirect(BASE_URL . "/patient/bookings");
        } else {
            $this->redirectBack(
                "Failed to reschedule appointment. The selected time slot may not be available."
            );
        }
    }

    public function cancelAppointment()
    {
        $this->requireAuth();
        $this->requireRole("Patient");
        $this->validateRequest("POST", true);

        $data = $this->sanitize($_POST);
        $user = $this->getAuthUser();

        if (!isset($data["appointment_id"])) {
            $this->redirectBack("Invalid appointment");
        }

        // Verify the appointment belongs to the current patient
        $appointment = new Appointment($this->conn);
        $appointmentDetails = $appointment->getAppointmentById(
            $data["appointment_id"]
        );

        if (
            !$appointmentDetails ||
            $appointmentDetails["PatientID"] != $user["id"]
        ) {
            $this->redirectBack("Invalid appointment or unauthorized access");
        }

        // Check if appointment is in the future (can't cancel past appointments)
        if (strtotime($appointmentDetails["DateTime"]) <= time()) {
            $this->redirectBack(
                "Cannot cancel appointments that have already occurred"
            );
        }

        $success = $appointment->cancelAppointment($data["appointment_id"]);

        if ($success) {
            $_SESSION["success"] = "Appointment cancelled successfully!";
            $this->redirect(BASE_URL . "/patient/bookings");
        } else {
            $this->redirectBack(
                "Failed to cancel appointment. Please try again."
            );
        }
    }

    public function appointmentDetail($userId, $appointmentId)
    {
        $this->requireAuth();
        $this->requireRole("Patient");

        $user = $this->getAuthUser();

        // Security check: ensure the user is accessing their own appointment
        if ($user["id"] != $userId) {
            $this->redirect(BASE_URL . "/patient/bookings");
            return;
        }

        // Get appointment details
        $appointment = new Appointment($this->conn);
        $appointmentDetails = $appointment->getAppointmentById($appointmentId);

        if (
            !$appointmentDetails ||
            $appointmentDetails["PatientID"] != $user["id"]
        ) {
            $_SESSION["error"] = "Appointment not found or access denied.";
            $this->redirect(BASE_URL . "/patient/bookings");
            return;
        }

        $appointmentReport = new AppointmentReport($this->conn);
        $reportData = $appointmentReport->getReportByAppointmentID(
            $appointmentId
        );

        $patientRecord = new PatientRecord($this->conn);
        $patientRecordData = null;
        if ($patientRecord->findByPatientID($user["id"])) {
            $patientRecordData = [
                "recordID" => $patientRecord->recordID,
                "height" => $patientRecord->height,
                "weight" => $patientRecord->weight,
                "allergies" => $patientRecord->allergies,
                "lastVisit" => $patientRecord->lastVisit,
            ];
        }

        // Get payment data for this appointment
        $payment = new Payment($this->conn);
        $appointmentPayment = $payment->getPaymentByAppointment($appointmentId);

        // Get payment breakdown if payment exists
        if ($appointmentPayment) {
            $paymentWithBreakdown = $payment->getPaymentWithBreakdown(
                $appointmentPayment["PaymentID"]
            );
            $appointmentPayment["items"] = $paymentWithBreakdown["items"] ?? [];
            $appointmentPayment["total_amount"] =
                $paymentWithBreakdown["total_amount"] ?? 0;
        }

        $data = [
            "user" => $user,
            "appointment" => $appointmentDetails,
            "appointmentReport" => $reportData,
            "patientRecord" => $patientRecordData,
            "appointmentPayment" => $appointmentPayment,
        ];

        $additionalHead =
            '<link rel="stylesheet" href="' .
            BASE_URL .
            '/app/styles/views/BookAppointment.css">';

        $layoutConfig = [
            "title" => "Appointment Details",
            "hideHeader" => true,
            "hideFooter" => false,
            "additionalHead" => $additionalHead,
            "additionalScripts" =>
                '<script>window.BASE_URL = "' .
                BASE_URL .
                '";</script><script src="' .
                BASE_URL .
                '/app/views/scripts/AppointmentPages/RescheduleAppointment.js"></script>',
        ];

        $this->view("pages/patient/AppointmentDetail", $data, $layoutConfig);
    }

    public function debugPayments()
    {
        ini_set("display_errors", 1);
        error_reporting(E_ALL);

        echo "<h1>Debug Payments</h1>";

        try {
            echo "<h2>Step 1: Auth Check</h2>";
            $this->requireAuth();
            $this->requireRole("Patient");
            echo "✅ Auth OK<br>";

            echo "<h2>Step 2: Get User</h2>";
            $user = $this->getAuthUser();
            echo "✅ User: " . json_encode($user) . "<br>";

            echo "<h2>Step 3: Database Connection</h2>";
            echo "✅ DB Connection exists: " .
                ($this->conn ? "Yes" : "No") .
                "<br>";

            echo "<h2>Step 4: Test Patient Model</h2>";
            $patient = new Patient($this->conn);
            echo "✅ Patient model created<br>";

            $patientData = $patient->getPatientByUserId($user["id"]);
            echo "✅ Patient data: " . json_encode($patientData) . "<br>";

            if (!$patientData) {
                echo "❌ No patient data found! This is the issue.<br>";
                return;
            }

            echo "<h2>Step 5: Test Payment Model</h2>";
            $payment = new Payment($this->conn);
            echo "✅ Payment model created<br>";

            echo "<h2>Step 6: Test Payment Query</h2>";
            $payments = $payment->getPaymentsByPatient(
                $patientData["PatientID"]
            );
            echo "✅ Payments retrieved: " . count($payments) . " records<br>";
            echo "Payment data: " . json_encode($payments) . "<br>";

            echo "<h2>Step 7: Success!</h2>";
            echo "✅ All steps completed successfully";
        } catch (Exception $e) {
            echo "❌ ERROR: " . $e->getMessage() . "<br>";
            echo "Trace: " . $e->getTraceAsString();
        }
    }

    public function testRoute()
    {
        // Enable error reporting and logging
        ini_set("display_errors", 1);
        ini_set("display_startup_errors", 1);
        ini_set("log_errors", 1);
        ini_set(
            "error_log",
            "/Applications/XAMPP/xamppfiles/logs/php_error_log"
        );
        error_reporting(E_ALL);

        // Test error logging
        error_log("=== TEST ROUTE DEBUG - " . date("Y-m-d H:i:s") . " ===");

        // Simple test to check database connectivity and tables
        try {
            // Test database connection
            $result = $this->conn->query("SELECT 1");
            echo "Database connection: OK<br>";
            error_log("Database connection: OK");

            // Test if tables exist
            $tables = [
                "USER",
                "PATIENT",
                "Doctor",
                "Appointment",
                "PatientRecord",
                "AppointmentReport",
            ];
            foreach ($tables as $table) {
                $result = $this->conn->query("SHOW TABLES LIKE '$table'");
                $exists = $result->num_rows > 0;
                echo "Table $table: " .
                    ($exists ? "EXISTS" : "MISSING") .
                    "<br>";
                error_log("Table $table: " . ($exists ? "EXISTS" : "MISSING"));
            }

            // Test if current user is a patient
            $user = $this->getAuthUser();
            if ($user) {
                echo "Current user ID: " . $user["id"] . "<br>";
                echo "Current user type: " . $user["type"] . "<br>";
                error_log("Current user ID: " . $user["id"]);
                error_log("Current user type: " . $user["type"]);

                // Check if patient record exists
                $patientRecord = new PatientRecord($this->conn);
                $hasRecord = $patientRecord->findByPatientID($user["id"]);
                echo "Patient record exists: " .
                    ($hasRecord ? "YES" : "NO") .
                    "<br>";
                error_log(
                    "Patient record exists: " . ($hasRecord ? "YES" : "NO")
                );

                if (!$hasRecord) {
                    echo "Attempting to create patient record...<br>";
                    error_log("Attempting to create patient record...");
                    if ($patientRecord->createForPatient($user["id"])) {
                        echo "Patient record created successfully<br>";
                        error_log("Patient record created successfully");
                    } else {
                        echo "Failed to create patient record<br>";
                        error_log("Failed to create patient record");
                    }
                }
            }

            // Test appointment booking components
            echo "<hr><h3>Testing Appointment Booking Components:</h3>";

            $doctorResult = $this->conn->query(
                "SELECT COUNT(*) as count FROM Doctor"
            );
            $doctorCount = $doctorResult->fetch_assoc()["count"];
            echo "Number of doctors: $doctorCount<br>";
            error_log("Number of doctors: $doctorCount");

            if ($doctorCount > 0) {
                $firstDoctor = $this->conn
                    ->query("SELECT DoctorID FROM Doctor LIMIT 1")
                    ->fetch_assoc();
                $doctorId = $firstDoctor["DoctorID"];
                echo "Testing with Doctor ID: $doctorId<br>";
                error_log("Testing with Doctor ID: $doctorId");

                // Test appointment creation (without actually creating)
                $testDate = date("Y-m-d H:i:s", strtotime("+1 day"));
                echo "Test appointment time: $testDate<br>";
                error_log("Test appointment time: $testDate");

                $appointment = new Appointment($this->conn);
                $available = $appointment->checkDoctorAvailability(
                    $doctorId,
                    $testDate
                );
                echo "Doctor availability for test time: " .
                    ($available ? "AVAILABLE" : "NOT AVAILABLE") .
                    "<br>";
                error_log(
                    "Doctor availability for test time: " .
                        ($available ? "AVAILABLE" : "NOT AVAILABLE")
                );
            }

            error_log("=== END TEST ROUTE DEBUG ===");
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            error_log("Error in test route: " . $e->getMessage());
        }
    }

    private function generatePaymentDetailsHTML($payment)
    {
        $statusClass = "";
        $statusText = htmlspecialchars($payment["Status"]);
        switch (strtolower($payment["Status"])) {
            case "paid":
                $statusClass = "bg-green-100/40 text-green-800";
                break;
            case "pending":
                $statusClass = "bg-yellow-100/40 text-yellow-800";
                break;
            case "overdue":
                $statusClass = "bg-red-100/40 text-red-800";
                break;
            default:
                $statusClass = "bg-blue-100/40 text-blue-800";
        }

        $html =
            '
        <div class="space-y-6">
            <!-- Payment Header -->
            <div class="text-center">
                <div class="glass-card bg-blue-100/60 text-blue-800 px-6 py-4 rounded-2xl inline-block mb-4">
                    <span class="text-3xl font-bold font-mono">#' .
            str_pad($payment["PaymentID"], 6, "0", STR_PAD_LEFT) .
            '</span>
                </div>
                <div class="flex justify-center">
                    <span class="inline-block glass-card px-4 py-2 text-sm font-medium rounded-full ' .
            $statusClass .
            '">
                        ' .
            $statusText .
            '
                    </span>
                </div>
            </div>

            <!-- Appointment Information -->
            <div class="glass-card bg-gray-50/50 rounded-xl p-6">
                <h4 class="text-xl font-semibold text-nhd-brown mb-4 font-family-bodoni">Appointment Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Appointment Type</label>
                        <p class="text-lg font-semibold text-gray-900">' .
            htmlspecialchars($payment["AppointmentType"]) .
            '</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Doctor</label>
                        <p class="text-lg text-gray-900">' .
            htmlspecialchars($payment["DoctorName"]) .
            '</p>
                        <p class="text-sm text-gray-500">' .
            htmlspecialchars($payment["Specialization"]) .
            '</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Date & Time</label>
                        <p class="text-lg font-semibold text-gray-900">' .
            date("M j, Y", strtotime($payment["AppointmentDateTime"])) .
            '</p>
                        <p class="text-gray-600">' .
            date("g:i A", strtotime($payment["AppointmentDateTime"])) .
            '</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Appointment ID</label>
                        <p class="text-lg font-mono font-semibold text-gray-900">#' .
            str_pad($payment["AppointmentID"], 6, "0", STR_PAD_LEFT) .
            '</p>
                    </div>
                </div>';

        if (!empty($payment["Reason"])) {
            $html .=
                '
                <div class="mt-4">
                    <label class="text-sm font-medium text-gray-500">Reason for Visit</label>
                    <p class="text-gray-700">' .
                nl2br(htmlspecialchars($payment["Reason"])) .
                '</p>
                </div>';
        }

        $html .= '
            </div>';

        if (!empty($payment["items"])) {
            $html .= '
            <div class="glass-card shadow-none bg-blue-50/50 rounded-xl p-6">
                <h4 class="text-xl font-semibold text-nhd-brown mb-4">Payment Breakdown</h4>
                <div class="space-y-3">';

            foreach ($payment["items"] as $item) {
                $html .=
                    '
                    <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0">
                        <div class="flex-1">
                            <p class="text-gray-900 font-medium">' .
                    htmlspecialchars($item["Description"]) .
                    "</p>";

                if ($item["Quantity"] > 1) {
                    $html .=
                        '
                            <p class="text-sm text-gray-500">
                                ₱' .
                        number_format($item["Amount"], 2) .
                        " × " .
                        $item["Quantity"] .
                        '
                            </p>';
                }

                $html .=
                    '
                        </div>
                        <div class="text-right">
                            <p class="text-gray-900 font-semibold">₱' .
                    number_format($item["Total"], 2) .
                    '</p>
                        </div>
                    </div>';
            }

            $html .=
                '
                    <div class="border-t border-gray-300 pt-4 mt-4">
                        <div class="flex justify-between items-center">
                            <p class="text-xl font-bold text-nhd-brown">Total Amount</p>
                            <p class="text-2xl font-bold text-nhd-brown">₱' .
                number_format($payment["total_amount"], 2) .
                '</p>
                        </div>
                    </div>
                </div>
            </div>';
        }

        // Payment Notes
        if (!empty($payment["Notes"])) {
            $html .=
                '
            <div class="glass-card bg-green-50/50 shadow-none rounded-xl p-6">
                <h4 class="text-lg font-medium text-nhd-brown mb-2">Payment Notes</h4>
                <p class="text-gray-700">' .
                nl2br(htmlspecialchars($payment["Notes"])) .
                '</p>
            </div>';
        }

        // Payment Timeline
        $html .=
            '
            <div class="glass-card bg-gray-50/50 rounded-xl p-6">
                <h4 class="text-lg font-medium text-nhd-brown mb-3">Payment Timeline</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Payment Created:</span>
                        <span class="font-medium">' .
            date("M j, Y g:i A", strtotime($payment["UpdatedAt"])) .
            '</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Last Updated:</span>
                        <span class="font-medium">' .
            date("M j, Y g:i A", strtotime($payment["UpdatedAt"])) .
            '</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="font-medium ' .
            ($payment["Status"] === "paid"
                ? "text-green-600"
                : "text-yellow-600") .
            '">' .
            $statusText .
            '</span>
                    </div>
                </div>
            </div>
        </div>';

        return $html;
    }
}
?> 