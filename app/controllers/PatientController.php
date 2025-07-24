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
require_once "app/models/TreatmentPlan.php";
require_once "app/models/TreatmentPlanItem.php";

class PatientController extends Controller
{
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

        $cancelledAppointments = $appointment->getCancelledAppointmentsByPatient(
            $user["id"]
        );
        $pendingCancellationAppointments = $appointment->getPendingCancellationsByPatient(
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
        $deadlinePayments = [];
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

                // Copy overdue calculation results from getPaymentWithBreakdown
                $paymentRecord["original_amount"] =
                    $paymentWithBreakdown["original_amount"] ??
                    $paymentRecord["total_amount"];
                $paymentRecord["is_overdue"] =
                    $paymentWithBreakdown["is_overdue"] ?? false;
                $paymentRecord["overdue_amount"] =
                    $paymentWithBreakdown["overdue_amount"] ?? 0;

                $appointmentPayments[
                    $paymentRecord["AppointmentID"]
                ] = $paymentRecord;

                // Track pending payments
                if (
                    strtolower($paymentRecord["Status"]) === "pending" ||
                    strtolower($paymentRecord["Status"]) === "overdue"
                ) {
                    $pendingPayments[] = $paymentRecord;
                    $totalPendingAmount += $paymentRecord["total_amount"];
                } elseif (strtolower($paymentRecord["Status"]) === "paid") {
                    $totalPaidAmount += $paymentRecord["total_amount"];
                }
            }

            $deadlinePayments = $payment->getPaymentsByDeadline(
                $patientData["PatientID"],
                5
            );
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

        // Load real treatment plan data instead of mock data
        $currentTreatments = [];
        if ($patientData) {
            $treatmentPlan = new TreatmentPlan($this->conn);
            $treatmentPlanItem = new TreatmentPlanItem($this->conn);

            $patientTreatmentPlans = $treatmentPlan->getTreatmentPlansByPatientID(
                $patientData["PatientID"]
            );

            foreach ($patientTreatmentPlans as $plan) {
                $progress = $treatmentPlan->calculateProgress(
                    $plan["TreatmentPlanID"]
                );
                $items = $treatmentPlanItem->findByTreatmentPlanID(
                    $plan["TreatmentPlanID"]
                );

                // Get next appointment date from incomplete items
                $nextAppointment = null;
                foreach ($items as $item) {
                    if (
                        empty($item["CompletedAt"]) &&
                        !empty($item["ScheduledDate"])
                    ) {
                        if (
                            !$nextAppointment ||
                            $item["ScheduledDate"] < $nextAppointment
                        ) {
                            $nextAppointment = $item["ScheduledDate"];
                        }
                    }
                }

                // Determine status based on progress
                $status = "In Progress";
                if ($progress >= 100) {
                    $status = "Completed";
                } elseif ($progress >= 80) {
                    $status = "Final Stage";
                }

                $currentTreatments[] = [
                    "id" => $plan["TreatmentPlanID"],
                    "name" => "Treatment Plan #" . $plan["TreatmentPlanID"],
                    "doctor" =>
                        $plan["DoctorName"] ?? "Dr. " . $plan["DentistID"],
                    "specialization" =>
                        $plan["DoctorSpecialization"] ?? "General Dentist",
                    "progress" => round($progress, 1),
                    "next_appointment" => $nextAppointment ?: "TBD",
                    "status" => $status,
                    "description" =>
                        $plan["DentistNotes"] ?:
                        "Comprehensive dental treatment plan",
                    "appointment_report_id" => $plan["AppointmentReportID"],
                ];
            }
        }

        $data = [
            "user" => $user,
            "patientData" => $patientData,
            "upcomingAppointments" => $upcomingAppointments,
            "completedAppointments" => $completedAppointments,
            "cancelledAppointments" => $cancelledAppointments,
            "pendingCancellationAppointments" => $pendingCancellationAppointments,
            "allAppointments" => $allAppointments,
            "appointmentPayments" => $appointmentPayments,
            "pendingPayments" => $pendingPayments,
            "deadlinePayments" => $deadlinePayments,
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

    // In app/controllers/PatientController.php

// Add this new method
    public function getAvailableSlotsForDoctor()
    {
        // This will be our API endpoint, so it should return JSON
        header('Content-Type: application/json');
        $this->requireAuth(); // Ensure user is logged in

        $doctorId = $_GET['doctor_id'] ?? null;
        $date = $_GET['date'] ?? null;

        if (!$doctorId || !$date) {
            echo json_encode(['success' => false, 'message' => 'Doctor ID and date are required.']);
            return;
        }

        // Use your already-updated model method!
        $appointmentModel = new Appointment($this->conn);
        $availableSlots = $appointmentModel->getAvailableTimeSlots($doctorId, $date);

        echo json_encode(['success' => true, 'timeSlots' => $availableSlots]);
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
        $cancelledAppointments = $appointment->getCancelledAppointmentsByPatient(
            $user["id"]
        );
        $pendingCancellationAppointments = $appointment->getPendingCancellationsByPatient(
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

                // Copy overdue calculation results from getPaymentWithBreakdown
                $paymentRecord["original_amount"] =
                    $paymentWithBreakdown["original_amount"] ??
                    $paymentRecord["total_amount"];
                $paymentRecord["is_overdue"] =
                    $paymentWithBreakdown["is_overdue"] ?? false;
                $paymentRecord["overdue_amount"] =
                    $paymentWithBreakdown["overdue_amount"] ?? 0;

                $appointmentPayments[
                    $paymentRecord["AppointmentID"]
                ] = $paymentRecord;
            }
        }

        $data = [
            "user" => $user,
            "upcomingAppointments" => $upcomingAppointments,
            "completedAppointments" => $completedAppointments,
            "cancelledAppointments" => $cancelledAppointments,
            "pendingCancellationAppointments" => $pendingCancellationAppointments,
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

        // Get active service types for appointment types
        require_once "app/models/ServicePrice.php";
        $servicePrice = new ServicePrice($this->conn);
        $serviceTypes = $servicePrice->getActiveServices();

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
            "serviceTypes" => $serviceTypes,
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

        // Load treatment plan data for this patient
        $treatmentPlans = [];
        $treatmentPlan = new TreatmentPlan($this->conn);
        $treatmentPlanItem = new TreatmentPlanItem($this->conn);

        $patientTreatmentPlans = $treatmentPlan->getTreatmentPlansByPatientID(
            $patientID
        );

        foreach ($patientTreatmentPlans as $plan) {
            $progress = $treatmentPlan->calculateProgress(
                $plan["TreatmentPlanID"]
            );
            $items = $treatmentPlanItem->findByTreatmentPlanID(
                $plan["TreatmentPlanID"]
            );

            $completedItems = array_filter($items, function ($item) {
                return !empty($item["CompletedAt"]);
            });

            $treatmentPlans[] = [
                "TreatmentPlanID" => $plan["TreatmentPlanID"],
                "Status" => $plan["Status"],
                "DentistNotes" => $plan["DentistNotes"],
                "AssignedAt" => $plan["AssignedAt"],
                "DoctorName" => $plan["DoctorName"] ?? "Unknown Doctor",
                "AppointmentDate" => $plan["AppointmentDate"] ?? null,
                "progress" => round($progress, 1),
                "totalItems" => count($items),
                "completedItems" => count($completedItems),
                "items" => $items,
            ];
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
            "treatmentPlans" => $treatmentPlans,
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
            $passwordErrors = $this->validatePassword(
                $data["new_password"] ?? ""
            );
            if (!empty($passwordErrors)) {
                $this->redirectBack(implode(". ", $passwordErrors));
                return;
            }

            if (empty($data["confirm_password"])) {
                $this->redirectBack("Please confirm your password");
                return;
            }

            if ($data["new_password"] !== $data["confirm_password"]) {
                $this->redirectBack("New passwords do not match");
                return;
            }

            $isValid = $this->validate(
                $data,
                [
                    "current_password" => "required",
                    "new_password" => "required",
                    "confirm_password" => "required",
                ],
                [
                    "current_password" => "Current password is required",
                    "new_password" => "Password is required",
                    "confirm_password" => "Please confirm your password",
                ]
            );

            if (!$isValid) {
                $this->redirectBack("Please correct the errors below");
                return;
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

        // Create appointment datetime
        $appointmentTime = $data["appointment_time"];
        // Check if time already includes seconds, if not add them
        if (substr_count($appointmentTime, ':') === 1) {
            $appointmentTime .= ':00';
        }
        $appointmentDateTime = $data["appointment_date"] . " " . $appointmentTime;

        error_log("Appointment DateTime: " . $appointmentDateTime);

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

        // Check if appointment is within 24 hours
        if ($appointment->isWithin24Hours($data["appointment_id"])) {
            $this->redirectBack(
                "Cannot reschedule appointment within 24 hours of the scheduled time. Please contact the clinic for reschedule assistance."
            );
        }

        $newAppointmentTime = $data["new_appointment_time"];
        // Check if time already includes seconds, if not add them
        if (substr_count($newAppointmentTime, ':') === 1) {
            $newAppointmentTime .= ':00';
        }
        $newDateTime = $data["new_appointment_date"] . " " . $newAppointmentTime;

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

        // Check if appointment has paid payments
        if ($appointment->hasPaidPayments($data["appointment_id"])) {
            $this->redirectBack(
                "Cannot cancel appointment that has been paid. Please contact the clinic for assistance."
            );
        }

        // Check if appointment is within 24 hours
        if ($appointment->isWithin24Hours($data["appointment_id"])) {
            $this->redirectBack(
                "Cannot cancel appointment within 24 hours of the scheduled time. Please contact the clinic for assistance."
            );
        }

        $success = $appointment->cancelAppointment($data["appointment_id"]);

        if ($success) {
            $_SESSION["success"] =
                "Cancellation request submitted successfully! Your appointment is now pending cancellation approval from the doctor.";
            $this->redirect(BASE_URL . "/patient/bookings");
        } else {
            $this->redirectBack(
                "Failed to submit cancellation request. Please try again."
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

            // Copy overdue calculation results from getPaymentWithBreakdown
            $appointmentPayment["original_amount"] =
                $paymentWithBreakdown["original_amount"] ??
                $appointmentPayment["total_amount"];
            $appointmentPayment["is_overdue"] =
                $paymentWithBreakdown["is_overdue"] ?? false;
            $appointmentPayment["overdue_amount"] =
                $paymentWithBreakdown["overdue_amount"] ?? 0;
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
        $statusIcon = "";
        $deadlineInfo = "";
        $urgencyClass = "";

        switch (strtolower($payment["Status"])) {
            case "paid":
                $statusClass = "bg-green-100 text-green-800 border-green-200";
                $statusIcon =
                    '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
                break;
            case "pending":
                $statusClass =
                    "bg-yellow-100 text-yellow-800 border-yellow-200";
                $statusIcon =
                    '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>';
                break;
            case "overdue":
                $statusClass = "bg-red-100 text-red-800 border-red-200";
                $statusIcon =
                    '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
                break;
            case "cancelled":
                $statusClass = "bg-gray-100 text-gray-800 border-gray-200";
                $statusIcon =
                    '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>';
                break;
            case "failed":
                $statusClass = "bg-red-100 text-red-800 border-red-200";
                $statusIcon =
                    '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
                break;
            case "refunded":
                $statusClass = "bg-blue-100 text-blue-800 border-blue-200";
                $statusIcon =
                    '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>';
                break;
            default:
                $statusClass = "bg-blue-100 text-blue-800 border-blue-200";
                $statusIcon =
                    '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>';
        }

        if (!empty($payment["DeadlineDate"])) {
            $deadline = strtotime($payment["DeadlineDate"]);
            $today = strtotime(date("Y-m-d"));
            $daysLeft = ($deadline - $today) / (60 * 60 * 24);

            if ($daysLeft < 0) {
                $deadlineInfo =
                    '<span class="text-red-600 font-bold">⚠️ Overdue by ' .
                    abs(round($daysLeft)) .
                    " days</span>";
                $urgencyClass = "border-l-4 border-red-500 bg-red-50/50";
            } elseif ($daysLeft == 0) {
                $deadlineInfo =
                    '<span class="text-orange-600 font-bold">⏰ Due today</span>';
                $urgencyClass = "border-l-4 border-orange-500 bg-orange-50/50";
            } elseif ($daysLeft <= 7) {
                $deadlineInfo =
                    '<span class="text-orange-600 font-bold">📅 Due in ' .
                    round($daysLeft) .
                    " days</span>";
                $urgencyClass = "border-l-4 border-orange-500 bg-orange-50/50";
            } else {
                $deadlineInfo =
                    '<span class="text-gray-600">📅 Due in ' .
                    round($daysLeft) .
                    " days</span>";
                $urgencyClass = "border-l-4 border-blue-500 bg-blue-50/50";
            }
        }

        $html =
            '
        <div class="space-y-6">
            <!-- Payment Header -->
            <div class="glass-card shadow-sm border-1 border-gray-200 rounded-2xl p-6">
                <div class="flex flex-row justify-between items-center">
                    <!-- Left Side: Payment Info, Status, and Deadline -->
                    <div class="flex flex-row items-center space-x-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full">
                            <svg class="w-8 h-8 text-nhd-blue" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>

                        <div class="flex flex-col space-y-3">
                            <div class="text-3xl font-bold font-mono text-nhd-blue">#' .
            str_pad($payment["PaymentID"], 6, "0", STR_PAD_LEFT) .
            '</div>

                            <div class="flex flex-col space-y-2">
                                <span class="inline-flex items-center w-fit glass-card shadow-none px-4 py-2 text-base font-bold rounded-full border-2 ' .
            $statusClass .
            '">
                                    ' .
            $statusIcon .
            $statusText .
            '
                                </span>
                        ' .
            '
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Total Amount -->
                    <div class="text-right">';

        if (
            isset($payment["is_overdue"]) &&
            $payment["is_overdue"] &&
            isset($payment["overdue_amount"]) &&
            $payment["overdue_amount"] > 0
        ) {
            $html .=
                '
                        <div class="bg-red-50 border-2 border-red-200 rounded-xl p-4">
                            <div class="text-4xl font-bold text-red-600 mb-2">₱' .
                number_format($payment["total_amount"], 2) .
                '</div>
                            <div class="text-sm text-red-700 space-y-1">
                                <div class="flex justify-between items-center">
                                    <span>Original:</span>
                                    <span class="font-semibold">₱' .
                number_format($payment["original_amount"] ?? 0, 2) .
                '</span>
                                </div>
                                <div class="flex justify-between items-center text-red-800">
                                    <span>Overdue Fee:</span>
                                    <span class="font-bold">+₱' .
                number_format($payment["overdue_amount"] ?? 0, 2) .
                '</span>
                                </div>
                            </div>
                        </div>';
        } else {
            $html .=
                '
                        <div class="bg-white border-2 border-gray-200 rounded-xl p-4">
                            <div class="text-4xl font-bold text-black mb-2">₱' .
                number_format($payment["total_amount"], 2) .
                '</div>

                        </div>';
        }

        $html .=
            '
                    </div>
                </div>
            </div>

            <!-- Appointment Information -->
            <div class="glass-card bg-gray-50/50 rounded-xl p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-nhd-brown mb-4 font-family-sans flex items-center">
                    <svg class="w-6 h-6 mr-2 text-nhd-blue" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                    </svg>
                    Appointment Information
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500 block mb-1">Appointment Type</label>
                            <p class="text-lg font-semibold text-gray-900 bg-white px-3 py-2 rounded-lg border-1 border-gray-200">' .
            htmlspecialchars($payment["AppointmentType"]) .
            '</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 block mb-1">Doctor</label>
                            <div class="bg-white px-3 py-2 rounded-lg border-1 border-gray-200">
                                <p class="text-lg font-semibold text-gray-900">Dr. ' .
            htmlspecialchars($payment["DoctorName"]) .
            '</p>
                                <p class="text-sm text-gray-500">' .
            htmlspecialchars($payment["Specialization"]) .
            '</p>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 block mb-1">Reference Number</label>
                            <p class="text-lg font-semibold text-gray-900 bg-white px-3 py-2 rounded-lg border-1 border-gray-200">' .
            (!empty($payment["ProofOfPayment"]) ? htmlspecialchars($payment["ProofOfPayment"]) : "None applies")  .
            '</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500 block mb-1">Date & Time</label>
                            <div class="bg-white px-3 py-2 rounded-lg border-1 border-gray-200">
                                <p class="text-lg font-semibold text-gray-900">' .
            date("M j, Y", strtotime($payment["AppointmentDateTime"])) .
            '</p>
                                <p class="text-gray-600">' .
            date("g:i A", strtotime($payment["AppointmentDateTime"])) .
            '</p>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 block mb-1">Appointment ID</label>
                            <p class="text-lg font-mono font-semibold text-gray-900 bg-white px-3 py-2 rounded-lg border-1 border-gray-200">#' .
            str_pad($payment["AppointmentID"], 6, "0", STR_PAD_LEFT) .
            '</p>
                        </div>
                    </div>
                </div>';

        // Add payment details section
        $html .=
            '
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h5 class="text-xl font-semibold text-nhd-brown mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-nhd-blue" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                        </svg>
                        Payment Details
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500 block mb-1">Payment Method</label>
                            <div class="bg-white px-3 py-2 rounded-lg border-1 border-gray-200">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    ' .
            htmlspecialchars($payment["PaymentMethod"] ?? "Cash") .
            '
                                </span>
                            </div>
                        </div>';

        if (!empty($payment["DeadlineDate"])) {
            $html .=
                '
                        <div>
                            <label class="text-sm font-medium text-gray-500 block mb-1">Payment Deadline</label>
                            <div class="bg-white px-3 py-2 rounded-lg border-1 border-gray-200">
                                <p class="text-lg font-semibold text-gray-900">' .
                date("M j, Y", strtotime($payment["DeadlineDate"])) .
                '</p>
                                ' .
                (!empty($deadlineInfo)
                    ? '<div class="text-sm">' . $deadlineInfo . "</div>"
                    : "") .
                '
                            </div>
                        </div>';
        }

        $html .= '
                    </div>
                </div>';

        if (!empty($payment["Reason"])) {
            $html .=
                '
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <label class="text-lg font-semibold text-nhd-brown mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-nhd-blue" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Reason for Visit
                    </label>
                    <div class="bg-white px-4 py-3 rounded-lg border-1 border-gray-200">
                        <p class="text-gray-700">' .
                nl2br(htmlspecialchars($payment["Reason"])) .
                '</p>
                    </div>
                </div>';
        }

        $html .= '
            </div>';

        if (!empty($payment["items"])) {
            $html .= '
            <div class="glass-card bg-white rounded-xl shadow-sm p-6 border-2 border-gray-200">
                <h4 class="text-xl font-semibold text-nhd-brown mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-nhd-blue" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                    Payment Breakdown
                </h4>
                <div class="bg-white rounded-lg overflow-hidden">
                    <div class="divide-y divide-gray-200">';

            foreach ($payment["items"] as $item) {
                $html .=
                    '
                        <div class="flex justify-between items-center py-3 hover:bg-gray-50 transition-colors">
                            <div class="flex-1">
                                <p class="text-gray-900 font-medium">' .
                    htmlspecialchars($item["Description"]) .
                    "</p>";

                if ($item["Quantity"] > 1) {
                    $html .=
                        '
                                <p class="text-sm text-gray-500 mt-1">
                                    ₱' .
                        number_format($item["Amount"], 2) .
                        " × " .
                        $item["Quantity"] .
                        ' items
                                </p>';
                }

                $html .=
                    '
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-nhd-brown">₱' .
                    number_format($item["Total"], 2) .
                    '</p>
                            </div>
                        </div>';
            }

            $html .=
                '
                    </div>
                    <div class="py-4 border-t-1 border-nhd-blue">
                        <div class="flex justify-between items-center">
                            <p class="text-xl font-bold text-nhd-brown flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                Total Amount
                            </p>
                            <p class="text-3xl font-bold text-nhd-brown">₱' .
                number_format($payment["total_amount"], 2) .
                '</p>
                        </div>';

            // Add overdue breakdown if applicable
            if (
                isset($payment["is_overdue"]) &&
                $payment["is_overdue"] &&
                isset($payment["overdue_amount"]) &&
                $payment["overdue_amount"] > 0
            ) {
                $html .=
                    '
                        <div class="mt-3 pt-3 border-t border-red-200 bg-red-50 rounded-lg p-3">
                            <div class="text-sm text-red-700 space-y-1">
                                <div class="flex justify-between">
                                    <span>Subtotal:</span>
                                    <span class="font-semibold">₱' .
                    number_format($payment["original_amount"] ?? 0, 2) .
                    '</span>
                                </div>
                                <div class="flex justify-between text-red-800">
                                    <span>Overdue Fee:</span>
                                    <span class="font-bold">+ ₱' .
                    number_format($payment["overdue_amount"] ?? 0, 2) .
                    '</span>
                                </div>
                            </div>
                        </div>';
            }

            $html .= '
                    </div>
                </div>
            </div>';
        }

        // Payment Notes
        if (!empty($payment["Notes"])) {
            $html .=
                '
            <div class="glass-card bg-green-50/50 shadow-none rounded-xl p-6 border-l-4 border-green-400">
                <h4 class="text-lg font-semibold text-nhd-brown mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    Payment Notes
                </h4>
                <div class="bg-white rounded-lg p-4 border border-green-200">
                    <p class="text-gray-700">' .
                nl2br(htmlspecialchars($payment["Notes"])) .
                '</p>
                </div>
            </div>';
        }

        // Payment Timeline
        $html .=
            '
            <div class="glass-card bg-gray-50/50 rounded-xl p-6">
                <h4 class="text-lg font-semibold text-nhd-brown mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-nhd-blue" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    Payment Timeline
                </h4>
                <div class="bg-white rounded-lg border-1 border-gray-200 p-4 space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Payment Created:</span>
                        <span class="font-semibold text-gray-900">' .
            date("M j, Y g:i A", strtotime($payment["UpdatedAt"])) .
            '</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Last Updated:</span>
                        <span class="font-semibold text-gray-900">' .
            date("M j, Y g:i A", strtotime($payment["UpdatedAt"])) .
            '</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 font-medium">Status:</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold border ' .
            $statusClass .
            '">' .
            $statusIcon .
            $statusText .
            '</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6">
                <button onclick="window.print()"
                        class="flex-1 px-6 py-3 glass-card bg-nhd-blue/85 text-white rounded-2xl hover:bg-nhd-blue transition-colors font-semibold flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zM5 14H4v-3h1v3zm1 0v2h6v-2H6zm8 0h1v-3h-1v3z" clip-rule="evenodd"></path>
                    </svg>
                    Print Invoice
                </button>
                <button onclick="closePaymentModal()"
                        class="flex-1 px-6 py-3 glass-card bg-gray-500/85 text-white rounded-2xl hover:bg-gray-600 transition-colors font-semibold flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    Close
                </button>
            </div>
        </div>';

        return $html;
    }

    public function getTreatmentPlan()
    {
        $this->requireAuth();
        $this->requireRole("Patient");

        header("Content-Type: application/json");

        $user = $this->getAuthUser();
        $patientID = $_GET["patient_id"] ?? null;

        // For patients, they can only access their own treatment plans
        $patient = new Patient($this->conn);
        $patientData = $patient->getPatientByUserId($user["id"]);

        if (
            !$patientData ||
            ($patientID && $patientData["PatientID"] != $patientID)
        ) {
            echo json_encode([
                "success" => false,
                "message" => "Access denied",
            ]);
            exit();
        }

        try {
            $treatmentPlan = new TreatmentPlan($this->conn);
            $treatmentPlanItem = new TreatmentPlanItem($this->conn);

            $patientTreatmentPlans = $treatmentPlan->getTreatmentPlansByPatientID(
                $patientData["PatientID"]
            );
            $treatmentPlans = [];

            foreach ($patientTreatmentPlans as $plan) {
                $progress = $treatmentPlan->calculateProgress(
                    $plan["TreatmentPlanID"]
                );
                $items = $treatmentPlanItem->findByTreatmentPlanID(
                    $plan["TreatmentPlanID"]
                );

                $treatmentPlans[] = [
                    "TreatmentPlanID" => $plan["TreatmentPlanID"],
                    "Status" => $plan["Status"],
                    "DentistNotes" => $plan["DentistNotes"],
                    "AssignedAt" => $plan["AssignedAt"],
                    "DoctorName" => $plan["DoctorName"],
                    "AppointmentDate" => $plan["AppointmentDate"],
                    "progress" => round($progress, 1),
                    "totalItems" => count($items),
                    "completedItems" => count(
                        array_filter($items, function ($item) {
                            return !empty($item["CompletedAt"]);
                        })
                    ),
                    "items" => $items,
                ];
            }

            echo json_encode([
                "success" => true,
                "treatmentPlans" => $treatmentPlans,
            ]);
        } catch (Exception $e) {
            error_log(
                "Error fetching patient treatment plans: " . $e->getMessage()
            );
            echo json_encode([
                "success" => false,
                "message" => "An error occurred while fetching treatment plans",
            ]);
        }
        exit();
    }

    public function getTreatmentPlanDetails()
    {
        $this->requireAuth();
        $this->requireRole("Patient");

        header("Content-Type: application/json");

        $user = $this->getAuthUser();
        $treatmentPlanID = $_GET["treatment_plan_id"] ?? null;

        if (!$treatmentPlanID) {
            echo json_encode([
                "success" => false,
                "message" => "Treatment Plan ID is required",
            ]);
            exit();
        }

        try {
            $patient = new Patient($this->conn);
            $patientData = $patient->getPatientByUserId($user["id"]);

            if (!$patientData) {
                echo json_encode([
                    "success" => false,
                    "message" => "Patient data not found",
                ]);
                exit();
            }

            require_once "app/models/TreatmentPlan.php";
            require_once "app/models/TreatmentPlanItem.php";

            $treatmentPlan = new TreatmentPlan($this->conn);
            $treatmentPlanDetails = $treatmentPlan->getTreatmentPlanWithDetails(
                $treatmentPlanID
            );

            if (!$treatmentPlanDetails) {
                echo json_encode([
                    "success" => false,
                    "message" => "Treatment plan not found",
                ]);
                exit();
            }

            // Verify that this treatment plan belongs to the logged-in patient
            $appointmentQuery =
                "SELECT PatientID FROM Appointment WHERE AppointmentID = ?";
            $stmt = $this->conn->prepare($appointmentQuery);
            $stmt->bind_param("i", $treatmentPlanDetails["AppointmentID"]);
            $stmt->execute();
            $appointmentResult = $stmt->get_result()->fetch_assoc();

            if (
                !$appointmentResult ||
                $appointmentResult["PatientID"] != $patientData["PatientID"]
            ) {
                echo json_encode([
                    "success" => false,
                    "message" => "Access denied",
                ]);
                exit();
            }

            // Get treatment plan items with charge status
            $treatmentPlanItem = new TreatmentPlanItem($this->conn);
            $items = $treatmentPlanItem->findByTreatmentPlanID(
                $treatmentPlanID
            );

            // Get progress
            $progress = $treatmentPlan->calculateProgress($treatmentPlanID);

            // Get appointment details
            $appointmentDetailsQuery = "
                SELECT
                    a.AppointmentID,
                    a.DateTime as AppointmentDate,
                    a.AppointmentType,
                    CONCAT(u.FirstName, ' ', u.LastName) as DoctorName,
                    d.Specialization
                FROM Appointment a
                INNER JOIN Doctor d ON a.DoctorID = d.DoctorID
                INNER JOIN USER u ON d.DoctorID = u.UserID
                WHERE a.AppointmentID = ?
            ";
            $stmt = $this->conn->prepare($appointmentDetailsQuery);
            $stmt->bind_param("i", $treatmentPlanDetails["AppointmentID"]);
            $stmt->execute();
            $appointmentDetails = $stmt->get_result()->fetch_assoc();

            $response = [
                "TreatmentPlanID" => $treatmentPlanDetails["TreatmentPlanID"],
                "Status" => $treatmentPlanDetails["Status"],
                "DentistNotes" => $treatmentPlanDetails["DentistNotes"],
                "AssignedAt" => $treatmentPlanDetails["AssignedAt"],
                "DoctorName" => $treatmentPlanDetails["DoctorName"],
                "progress" => round($progress, 1),
                "items" => $items,
                "AppointmentID" => $appointmentDetails["AppointmentID"],
                "AppointmentDate" => $appointmentDetails["AppointmentDate"],
                "AppointmentType" => $appointmentDetails["AppointmentType"],
                "Specialization" => $appointmentDetails["Specialization"],
            ];

            echo json_encode([
                "success" => true,
                "treatmentPlan" => $response,
            ]);
        } catch (Exception $e) {
            error_log(
                "Error fetching treatment plan details: " . $e->getMessage()
            );
            echo json_encode([
                "success" => false,
                "message" =>
                    "An error occurred while fetching treatment plan details",
            ]);
        }
        exit();
    }

    private function validatePassword($password)
    {
        $errors = [];

        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long";
        }

        if (!preg_match("/[A-Z]/", $password)) {
            $errors[] = "Password must contain at least one uppercase letter";
        }

        if (!preg_match("/[a-z]/", $password)) {
            $errors[] = "Password must contain at least one lowercase letter";
        }

        if (!preg_match("/[0-9]/", $password)) {
            $errors[] = "Password must contain at least one number";
        }

        if (!preg_match("/[^A-Za-z0-9]/", $password)) {
            $errors[] =
                "Password must contain at least one special character (!@#$%^&*()_+-=[]{}|;:,.<>?)";
        }

        return $errors;
    }
}
?>
