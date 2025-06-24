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
        $upcomingAppointments = $appointment->getUpcomingAppointmentsByPatient(
            $user["id"]
        );

        $data = [
            "user" => $user,
            "upcomingAppointments" => $upcomingAppointments,
        ];

        $layoutConfig = [
            "title" => "Patient Dashboard",
            "hideHeader" => true,
            "hideFooter" => false,
            "bodyClass" =>
                "bg-white bg-[radial-gradient(#e4e9f5_1px,transparent_2px)] [background-size:16px_16px]",
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

        // Get payment data for appointments
        $payment = new Payment($this->conn);
        $patient = new Patient($this->conn);
        $patientData = $patient->getPatientByUserId($user["id"]);

        $appointmentPayments = [];
        if ($patientData) {
            $payments = $payment->getPaymentsByPatient(
                $patientData["PatientID"]
            );
            // Index payments by AppointmentID and get breakdown for each payment
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
                '/app/views/scripts/Bookings.js"></script>',
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
                '/app/views/scripts/BookAppointments.js"></script>',
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
        // Enable error reporting for debugging
        ini_set("display_errors", 1);
        ini_set("display_startup_errors", 1);
        error_reporting(E_ALL);

        // DEBUG: Log that this method is being called
        error_log("DEBUG: PatientController::payments() method called");

        try {
            error_log("DEBUG: Step 1 - Starting payments method");

            $this->requireAuth();
            error_log("DEBUG: Step 2 - Auth required passed");

            $this->requireRole("Patient");
            error_log("DEBUG: Step 3 - Role check passed");

            $user = $this->getAuthUser();
            error_log("DEBUG: Step 4 - Got user: " . json_encode($user));

            $payment = new Payment($this->conn);
            error_log("DEBUG: Step 5 - Created Payment model");

            // Get patient ID from user
            $patient = new Patient($this->conn);
            error_log("DEBUG: Step 6 - Created Patient model");

            $patientData = $patient->getPatientByUserId($user["id"]);
            error_log(
                "DEBUG: Step 7 - Got patient data: " . json_encode($patientData)
            );

            if (!$patientData) {
                error_log("DEBUG: Step 8 - No patient data, redirecting");
                $this->redirect("/patient/dashboard");
                return;
            }

            error_log(
                "DEBUG: Step 9 - Getting payments for patient ID: " .
                    $patientData["PatientID"]
            );
            $payments = $payment->getPaymentsByPatient(
                $patientData["PatientID"]
            );
            error_log(
                "DEBUG: Step 10 - Got payments: " .
                    count($payments) .
                    " records"
            );

            // Get breakdown for each payment
            foreach ($payments as &$paymentRecord) {
                $paymentWithBreakdown = $payment->getPaymentWithBreakdown(
                    $paymentRecord["PaymentID"]
                );
                $paymentRecord["items"] = $paymentWithBreakdown["items"] ?? [];
                $paymentRecord["total_amount"] =
                    $paymentWithBreakdown["total_amount"] ?? 0;
            }
            error_log("DEBUG: Step 11 - Processed payment breakdowns");

            $data = [
                "user" => $user,
                "payments" => $payments,
            ];

            $layoutConfig = [
                "title" => "Payments",
                "hideHeader" => true,
                "hideFooter" => false,
            ];

            error_log("DEBUG: Step 12 - About to render view");
            $this->view("pages/patient/Payments", $data, $layoutConfig);
            error_log("DEBUG: Step 13 - View rendered successfully");
        } catch (Exception $e) {
            error_log("DEBUG: EXCEPTION in payments(): " . $e->getMessage());
            error_log("DEBUG: EXCEPTION trace: " . $e->getTraceAsString());
            echo "Error in payments method: " . $e->getMessage();
        }
    }

    public function results()
    {
        $this->requireAuth();
        $this->requireRole("Patient");

        $data = [
            "user" => $this->getAuthUser(),
            // TODO: Fetch test results and prescriptions
        ];

        $layoutConfig = [
            "title" => "Results",
            "hideHeader" => true,
            "hideFooter" => false,
        ];

        $this->view("pages/patient/Results", $data, $layoutConfig);
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
        $this->validateRequest("POST", true);

        $data = $this->sanitize($_POST);
        $user = $this->getAuthUser();

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

        if (!$isValid) {
            $this->redirectBack("Please correct the errors below");
        }

        // future time check
        $appointmentDateTime =
            $data["appointment_date"] . " " . $data["appointment_time"] . ":00";
        if (strtotime($appointmentDateTime) <= time()) {
            $this->redirectBack(
                "Appointment date and time must be in the future"
            );
        }

        $appointment = new Appointment($this->conn);
        $success = $appointment->createAppointment(
            $user["id"],
            $data["doctor_id"],
            $appointmentDateTime,
            $data["appointment_type"],
            $data["reason"]
        );

        if ($success) {
            $_SESSION["success"] = "Appointment booked successfully!";
            $this->redirect(BASE_URL . "/patient/bookings");
        } else {
            $this->redirectBack(
                "Failed to book appointment. The selected time slot may no longer be available."
            );
        }
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
                '/app/views/scripts/RescheduleAppointment.js"></script>',
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
        echo "TEST ROUTE WORKING - PatientController::testRoute() called";
        exit();
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
            <div class="glass-card bg-blue-50/50 rounded-xl p-6">
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
                                $' .
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
                            <p class="text-gray-900 font-semibold">$' .
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
                            <p class="text-2xl font-bold text-nhd-brown">$' .
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
            <div class="glass-card bg-green-50/50 rounded-xl p-6">
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