<?php

require_once "app/core/Controller.php";
require_once "app/models/User.php";
require_once "app/models/Doctor.php";
require_once "app/models/Appointment.php";
require_once "app/models/PatientRecord.php";
require_once "app/models/Patient.php";
require_once "app/models/AppointmentReport.php";

class DoctorController extends Controller
{
    protected function initializeMiddleware()
    {
        $this->middleware("auth", ["only" => ["*"]]);
        $this->middleware("role:ClinicStaff", ["only" => ["*"]]);
    }

    public function dashboard()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");

        $data = [
            "user" => $this->getAuthUser(),
            // TODO: Fetch today's appointments, recent patients, etc.
        ];

        $layoutConfig = [
            "title" => "Doctor Dashboard",
            "hideHeader" => false,
            "hideFooter" => false,
        ];

        $this->view("pages/staff/doctor/Dashboard", $data, $layoutConfig);
    }

    public function schedule()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");

        $user = $this->getAuthUser();
        $doctorID = $user["id"];

        $appointmentModel = new Appointment($this->conn);

        $selectedDate = isset($_GET["date"]) ? $_GET["date"] : date("Y-m-d");

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
            $selectedDate = date("Y-m-d");
        }

        $todaysAppointments = $appointmentModel->getTodaysAppointmentsByDoctor(
            $doctorID
        );
        $upcomingAppointments = $appointmentModel->getUpcomingAppointmentsByDoctor(
            $doctorID
        );
        $selectedDateAppointments = $appointmentModel->getAppointmentsByDoctorAndDate(
            $doctorID,
            $selectedDate
        );

        $startOfWeek = date(
            "Y-m-d",
            strtotime("monday this week", strtotime($selectedDate))
        );
        $endOfWeek = date(
            "Y-m-d",
            strtotime("sunday this week", strtotime($selectedDate))
        );
        $weekAppointments = $appointmentModel->getAppointmentsByDoctorAndDateRange(
            $doctorID,
            $startOfWeek,
            $endOfWeek
        );

        $doctorModel = new Doctor($this->conn);
        $doctorModel->findById($doctorID);

        $data = [
            "user" => $user,
            "doctor" => [
                "specialization" =>
                    $doctorModel->specialization ?? "General Practice",
                "firstName" => $user["user_name"]
                    ? explode(" ", $user["user_name"])[0]
                    : "",
                "lastName" => $user["user_name"]
                    ? explode(" ", $user["user_name"])[1] ?? ""
                    : "",
            ],
            "selectedDate" => $selectedDate,
            "todaysAppointments" => $todaysAppointments,
            "upcomingAppointments" => $upcomingAppointments,
            "selectedDateAppointments" => $selectedDateAppointments,
            "weekAppointments" => $weekAppointments,
            "startOfWeek" => $startOfWeek,
            "endOfWeek" => $endOfWeek,
        ];

        $additionalHead =
            '<link rel="stylesheet" href="' .
            BASE_URL .
            '/app/styles/views/Bookings.css">';

        $layoutConfig = [
            "title" => "Schedule",
            "hideHeader" => true,
            "hideFooter" => false,
            "additionalScripts" =>
                '<script src="' .
                BASE_URL .
                '/app/views/scripts/SchedulePage/ScheduleLogic.js"></script>' .
                '<script src="' .
                BASE_URL .
                '/app/views/scripts/SchedulePage/AppointmentDetailsModal.js"></script>',
            "additionalHead" => $additionalHead,
        ];

        $this->view("pages/staff/doctor/Schedule", $data, $layoutConfig);
    }

    public function appointmentHistory()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");

        $user = $this->getAuthUser();
        $doctorID = $user["id"];

        $appointmentModel = new Appointment($this->conn);
        $appointmentHistory = $appointmentModel->getAppointmentHistoryByDoctor(
            $doctorID
        );

        $doctorModel = new Doctor($this->conn);
        $doctorModel->findById($doctorID);

        $data = [
            "user" => $user,
            "doctor" => [
                "specialization" =>
                    $doctorModel->specialization ?? "General Practice",
                "firstName" => $user["user_name"]
                    ? explode(" ", $user["user_name"])[0]
                    : "",
                "lastName" => $user["user_name"]
                    ? explode(" ", $user["user_name"])[1] ?? ""
                    : "",
            ],
            "appointmentHistory" => $appointmentHistory,
        ];

        $additionalHead =
            '<link rel="stylesheet" href="' .
            BASE_URL .
            '/app/styles/views/Bookings.css">';

        $layoutConfig = [
            "title" => "Appointment History",
            "hideHeader" => true,
            "hideFooter" => false,
            "additionalScripts" =>
                '<script src="' .
                BASE_URL .
                '/app/views/scripts/SchedulePage/AppointmentDetailsModal.js"></script>',
            "additionalHead" => $additionalHead,
        ];

        $this->view(
            "pages/staff/doctor/AppointmentHistory",
            $data,
            $layoutConfig
        );
    }

    public function patientRecords()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");

        $user = $this->getAuthUser();

        // Get all patients with their records
        $patient = new Patient($this->conn);
        $patientsWithRecords = $patient->getAllPatientsWithRecords();

        $data = [
            "user" => $user,
            "patients" => $patientsWithRecords,
        ];

        $layoutConfig = [
            "title" => "Patient Records",
            "hideHeader" => false,
            "hideFooter" => false,
            "additionalScripts" =>
                '<script src="' .
                BASE_URL .
                '/app/views/scripts/PatientRecords/PatientRecordLogic.js"></script>',
        ];

        $this->view("pages/staff/doctor/PatientRecords", $data, $layoutConfig);
    }

    public function inbox()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");

        $data = [
            "user" => $this->getAuthUser(),
            // TODO: Fetch messages/notifications
        ];

        $layoutConfig = [
            "title" => "Inbox",
            "hideHeader" => false,
            "hideFooter" => false,
        ];

        $this->view("pages/staff/doctor/Inbox", $data, $layoutConfig);
    }

    public function profile()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");

        $user = $this->getAuthUser();
        $userModel = new User($this->conn);
        $userModel->findById($user["id"]);

        $data = [
            "user" => $user,
            "userDetails" => [
                "firstName" => $userModel->firstName,
                "email" => $userModel->email,
                "userType" => $userModel->userType,
                "createdAt" => $userModel->createdAt,
            ],
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
        $this->requireRole("ClinicStaff");
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

    // POST methods for handling form submissions
    public function updateSchedule()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->validateRequest("POST", true);

        // TODO: Handle schedule update logic
        $this->redirectBack("Schedule updated successfully!");
    }

    public function updatePatientRecord()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->validateRequest("POST", true);

        // TODO: Handle patient record update logic
        $this->redirectBack("Patient record updated successfully!");
    }

    public function getPatientDetails()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");

        header("Content-Type: application/json");

        $patientId = $_GET["patient_id"] ?? "";

        if (empty($patientId)) {
            echo json_encode([
                "success" => false,
                "message" => "Patient ID is required",
            ]);
            exit();
        }

        try {
            // Get patient basic info
            $patient = new Patient($this->conn);
            $patientData = $patient->getPatientById($patientId);

            if (!$patientData) {
                echo json_encode([
                    "success" => false,
                    "message" => "Patient not found",
                ]);
                exit();
            }

            // Get patient record
            $patientRecord = new PatientRecord($this->conn);
            $patientRecordData = null;
            if ($patientRecord->findByPatientID($patientId)) {
                $patientRecordData = [
                    "recordID" => $patientRecord->recordID,
                    "height" => $patientRecord->height,
                    "weight" => $patientRecord->weight,
                    "allergies" => $patientRecord->allergies,
                    "createdAt" => $patientRecord->createdAt,
                    "lastVisit" => $patientRecord->lastVisit,
                ];
            }

            // Get appointments
            $appointment = new Appointment($this->conn);
            $appointments = $appointment->getAppointmentsByPatient($patientId);

            // Get appointment reports for each appointment
            $appointmentReport = new AppointmentReport($this->conn);
            foreach ($appointments as &$apt) {
                $reportData = $appointmentReport->getReportByAppointmentID(
                    $apt["AppointmentID"]
                );
                $apt["report"] = $reportData;
            }

            $response = [
                "success" => true,
                "patient" => $patientData,
                "patientRecord" => $patientRecordData,
                "appointments" => $appointments,
            ];

            echo json_encode($response);
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" =>
                    "Error fetching patient details: " . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function updatePatientRecordData()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");

        // Don't validate CSRF for JSON API requests
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("HTTP/1.1 405 Method Not Allowed");
            echo json_encode([
                "success" => false,
                "message" => "Method not allowed",
            ]);
            exit();
        }

        header("Content-Type: application/json");

        // Enable error logging for debugging
        error_log("=== UPDATE PATIENT RECORD DEBUG START ===");

        $rawInput = file_get_contents("php://input");
        error_log("Raw input: " . $rawInput);

        $data = json_decode($rawInput, true);
        error_log("Decoded data: " . json_encode($data));

        if (!$data) {
            error_log(
                "JSON decode failed. JSON error: " . json_last_error_msg()
            );
            echo json_encode([
                "success" => false,
                "message" => "Invalid JSON data: " . json_last_error_msg(),
            ]);
            exit();
        }

        try {
            $recordId = $data["recordId"] ?? null;
            $patientId = $data["patientId"] ?? null;
            $height = $data["height"] ?? null;
            $weight = $data["weight"] ?? null;
            $allergies = $data["allergies"] ?? null;

            error_log(
                "Processing - RecordId: $recordId, PatientId: $patientId, Height: $height, Weight: $weight"
            );

            if (!$patientId) {
                error_log("Patient ID missing");
                echo json_encode([
                    "success" => false,
                    "message" => "Patient ID is required",
                ]);
                exit();
            }

            $patientRecord = new PatientRecord($this->conn);
            error_log("PatientRecord model created");

            if ($recordId) {
                // Update existing record
                error_log("Updating existing record with ID: $recordId");
                if ($patientRecord->findByPatientID($patientId)) {
                    error_log("Patient record found, updating...");
                    $patientRecord->height = $height;
                    $patientRecord->weight = $weight;
                    $patientRecord->allergies = $allergies;

                    if ($patientRecord->update()) {
                        error_log("Update successful");
                        echo json_encode([
                            "success" => true,
                            "message" => "Patient record updated successfully",
                        ]);
                    } else {
                        error_log("Update failed");
                        echo json_encode([
                            "success" => false,
                            "message" => "Failed to update patient record",
                        ]);
                    }
                } else {
                    error_log(
                        "Patient record not found for patient ID: $patientId"
                    );
                    echo json_encode([
                        "success" => false,
                        "message" => "Patient record not found",
                    ]);
                }
            } else {
                // Create new record
                error_log("Creating new record for patient ID: $patientId");
                $patientRecord->patientID = $patientId;
                $patientRecord->height = $height;
                $patientRecord->weight = $weight;
                $patientRecord->allergies = $allergies;
                $patientRecord->lastVisit = null;

                if ($patientRecord->create()) {
                    error_log("Create successful");
                    echo json_encode([
                        "success" => true,
                        "message" => "Patient record created successfully",
                    ]);
                } else {
                    error_log("Create failed");
                    echo json_encode([
                        "success" => false,
                        "message" => "Failed to create patient record",
                    ]);
                }
            }
        } catch (Exception $e) {
            error_log("Exception caught: " . $e->getMessage());
            error_log("Exception trace: " . $e->getTraceAsString());
            echo json_encode([
                "success" => false,
                "message" => "Error processing request: " . $e->getMessage(),
            ]);
        }

        error_log("=== UPDATE PATIENT RECORD DEBUG END ===");
        exit();
    }

    public function getAppointmentReport()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");

        header("Content-Type: application/json");

        $appointmentId = $_GET["appointment_id"] ?? "";

        if (empty($appointmentId)) {
            echo json_encode([
                "success" => false,
                "message" => "Appointment ID is required",
            ]);
            exit();
        }

        try {
            // Get appointment details
            $appointment = new Appointment($this->conn);
            $appointmentData = $appointment->getAppointmentById($appointmentId);

            if (!$appointmentData) {
                echo json_encode([
                    "success" => false,
                    "message" => "Appointment not found",
                ]);
                exit();
            }

            // Get appointment report
            $appointmentReport = new AppointmentReport($this->conn);
            $reportData = $appointmentReport->getReportByAppointmentID(
                $appointmentId
            );

            if (!$reportData) {
                // Create empty report if none exists
                $reportData = [
                    "AppointmentReportID" => null,
                    "PatientRecordID" => null,
                    "AppointmentID" => $appointmentId,
                    "BloodPressure" => "",
                    "PulseRate" => "",
                    "Temperature" => "",
                    "RespiratoryRate" => "",
                    "GeneralAppearance" => "",
                    "CreatedAt" => null,
                    "Height" => "",
                    "Weight" => "",
                    "Allergies" => "",
                ];
            }

            $response = [
                "success" => true,
                "appointment" => $appointmentData,
                "report" => $reportData,
            ];

            echo json_encode($response);
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" =>
                    "Error fetching appointment report: " . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function updateAppointmentReport()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("HTTP/1.1 405 Method Not Allowed");
            echo json_encode([
                "success" => false,
                "message" => "Method not allowed",
            ]);
            exit();
        }

        header("Content-Type: application/json");

        $rawInput = file_get_contents("php://input");
        $data = json_decode($rawInput, true);

        if (!$data) {
            echo json_encode([
                "success" => false,
                "message" => "Invalid JSON data: " . json_last_error_msg(),
            ]);
            exit();
        }

        try {
            $appointmentId = $data["appointmentId"] ?? null;
            $bloodPressure = $data["bloodPressure"] ?? "";
            $pulseRate = $data["pulseRate"] ?? "";
            $temperature = $data["temperature"] ?? "";
            $respiratoryRate = $data["respiratoryRate"] ?? "";
            $generalAppearance = $data["generalAppearance"] ?? "";

            if (!$appointmentId) {
                echo json_encode([
                    "success" => false,
                    "message" => "Appointment ID is required",
                ]);
                exit();
            }

            $appointmentReport = new AppointmentReport($this->conn);

            // Check if report already exists
            if ($appointmentReport->findByAppointmentID($appointmentId)) {
                // Update existing report
                $appointmentReport->bloodPressure = $bloodPressure;
                $appointmentReport->pulseRate = $pulseRate;
                $appointmentReport->temperature = $temperature;
                $appointmentReport->respiratoryRate = $respiratoryRate;
                $appointmentReport->generalAppearance = $generalAppearance;

                if ($appointmentReport->update()) {
                    echo json_encode([
                        "success" => true,
                        "message" => "Appointment report updated successfully",
                    ]);
                } else {
                    echo json_encode([
                        "success" => false,
                        "message" => "Failed to update appointment report",
                    ]);
                }
            } else {
                // Create new report
                // First get patient record ID
                $appointment = new Appointment($this->conn);
                $appointmentData = $appointment->getAppointmentById(
                    $appointmentId
                );

                if (!$appointmentData) {
                    echo json_encode([
                        "success" => false,
                        "message" => "Appointment not found",
                    ]);
                    exit();
                }

                $patientRecord = new PatientRecord($this->conn);
                if (
                    !$patientRecord->findByPatientID(
                        $appointmentData["PatientID"]
                    )
                ) {
                    // Create patient record if it doesn't exist
                    $patientRecord->patientID = $appointmentData["PatientID"];
                    $patientRecord->height = null;
                    $patientRecord->weight = null;
                    $patientRecord->allergies = null;
                    $patientRecord->lastVisit = null;
                    $patientRecord->create();
                }

                $appointmentReport->appointmentID = $appointmentId;
                $appointmentReport->patientRecordID = $patientRecord->recordID;
                $appointmentReport->bloodPressure = $bloodPressure;
                $appointmentReport->pulseRate = $pulseRate;
                $appointmentReport->temperature = $temperature;
                $appointmentReport->respiratoryRate = $respiratoryRate;
                $appointmentReport->generalAppearance = $generalAppearance;

                if ($appointmentReport->create()) {
                    echo json_encode([
                        "success" => true,
                        "message" => "Appointment report created successfully",
                    ]);
                } else {
                    echo json_encode([
                        "success" => false,
                        "message" => "Failed to create appointment report",
                    ]);
                }
            }
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error processing request: " . $e->getMessage(),
            ]);
        }
        exit();
    }
}
?> 