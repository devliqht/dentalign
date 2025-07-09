<?php

require_once "app/core/Controller.php";
require_once "app/models/User.php";
require_once "app/models/Doctor.php";
require_once "app/models/Appointment.php";
require_once "app/models/PatientRecord.php";
require_once "app/models/Patient.php";
require_once "app/models/AppointmentReport.php";
require_once "app/models/Payment.php";
require_once "app/models/PaymentItem.php";

class DoctorController extends Controller
{
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

    public function getWeekData()
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

        // Return only the week grid HTML
        $daysOfWeek = [
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
            "Sunday",
        ];

        $html = "";
        for ($i = 0; $i < 7; $i++) {
            $currentDate = date(
                "Y-m-d",
                strtotime($startOfWeek . " +" . $i . " days")
            );
            $dayAppointments = array_filter($weekAppointments, function (
                $app
            ) use ($currentDate) {
                return date("Y-m-d", strtotime($app["DateTime"])) ===
                    $currentDate;
            });
            $isToday = $currentDate === date("Y-m-d");

            $html .=
                '<div class="glass-card rounded-2xl shadow-sm p-4 ' .
                ($isToday
                    ? "bg-nhd-blue/10 border-1 border-nhd-blue/30"
                    : "bg-white/60 border-gray-200 border-1") .
                '">';
            $html .= '<div class="text-center mb-3">';
            $html .=
                '<h4 class="font-semibold text-gray-900 ' .
                ($isToday ? "text-nhd-blue" : "") .
                '">' .
                $daysOfWeek[$i] .
                "</h4>";
            $html .=
                '<p class="text-sm text-gray-600 ' .
                ($isToday ? "text-nhd-blue/80" : "") .
                '">' .
                date("M j", strtotime($currentDate));
            if ($isToday) {
                $html .= '<span class="text-xs">(Today)</span>';
            }
            $html .= "</p></div>";

            if (!empty($dayAppointments)) {
                $html .= '<div class="space-y-2">';
                foreach ($dayAppointments as $appointment) {
                    $html .=
                        '<div class="glass-card bg-white/40 border-gray-200 border-1 shadow-sm p-3 rounded-xl text-xs">';
                    $html .=
                        '<div class="font-semibold text-nhd-blue">' .
                        date("g:i A", strtotime($appointment["DateTime"])) .
                        "</div>";
                    $html .=
                        '<div class="text-gray-900 font-medium">' .
                        htmlspecialchars(
                            $appointment["PatientFirstName"] .
                                " " .
                                $appointment["PatientLastName"]
                        ) .
                        "</div>";
                    $html .=
                        '<div class="text-gray-600 truncate">' .
                        htmlspecialchars($appointment["AppointmentType"]) .
                        "</div>";
                    $html .= "</div>";
                }
                $html .= "</div>";
            } else {
                $html .=
                    '<div class="text-center text-gray-400 text-xs py-4">No appointments</div>';
            }
            $html .= "</div>";
        }

        // Return JSON response with the HTML and date range
        header("Content-Type: application/json");
        echo json_encode([
            "html" => $html,
            "dateRange" =>
                date("M j", strtotime($startOfWeek)) .
                " - " .
                date("M j, Y", strtotime($endOfWeek)),
        ]);
        exit();
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
            $patient = new Patient($this->conn);
            $patientData = $patient->getPatientById($patientId);

            if (!$patientData) {
                echo json_encode([
                    "success" => false,
                    "message" => "Patient not found",
                ]);
                exit();
            }

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

            $appointment = new Appointment($this->conn);
            $appointments = $appointment->getAppointmentsByPatient($patientId);

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

            $appointmentReport = new AppointmentReport($this->conn);
            $reportData = $appointmentReport->getReportByAppointmentID(
                $appointmentId
            );

            if (!$reportData) {
                $reportData = [
                    "AppointmentReportID" => null,
                    "PatientRecordID" => null,
                    "AppointmentID" => $appointmentId,
                    "OralNotes" => "",
                    "Diagnosis" => "",
                    "XrayImages" => "",
                    "CreatedAt" => null,
                    "Height" => "",
                    "Weight" => "",
                    "Allergies" => "",
                ];
            }

            // Transform field names to match JavaScript expectations
            $transformedReport = [
                "appointmentReportID" =>
                    $reportData["AppointmentReportID"] ?? null,
                "patientRecordID" => $reportData["PatientRecordID"] ?? null,
                "appointmentID" =>
                    $reportData["AppointmentID"] ?? $appointmentId,
                "oralNotes" => $reportData["OralNotes"] ?? "",
                "diagnosis" => $reportData["Diagnosis"] ?? "",
                "xrayImages" => $reportData["XrayImages"] ?? "",
                "createdAt" => $reportData["CreatedAt"] ?? null,
                "height" => $reportData["Height"] ?? "",
                "weight" => $reportData["Weight"] ?? "",
                "allergies" => $reportData["Allergies"] ?? "",
            ];

            $response = [
                "success" => true,
                "appointment" => $appointmentData,
                "report" => $transformedReport,
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
            $oralNotes = $data["oralNotes"] ?? "";
            $diagnosis = $data["diagnosis"] ?? "";
            $xrayImages = $data["xrayImages"] ?? "";
            $status = $data["status"] ?? "";

            if (!$appointmentId) {
                echo json_encode([
                    "success" => false,
                    "message" => "Appointment ID is required",
                ]);
                exit();
            }
            $appointment = new Appointment($this->conn);
            $appointmentReport = new AppointmentReport($this->conn);

            $appointmentData = $appointment->getAppointmentById($appointmentId);
            if (!$appointmentData || empty($appointmentData)) {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to find appointment.",
                ]);
                exit();
            }

            if ($appointmentReport->findByAppointmentID($appointmentId)) {
                $appointment->status = $status;
                $appointmentReport->oralNotes = $oralNotes;
                $appointmentReport->diagnosis = $diagnosis;
                $appointmentReport->xrayImages = $xrayImages;

                if ($appointmentReport->update() && $appointment->updateAppointmentStatus($appointmentId)) {
                    echo json_encode([
                        "success" => true,
                        "message" => "Appointment and Appointment report updated successfully",
                    ]);
                } else {
                    echo json_encode([
                        "success" => false,
                        "message" => "Failed to update appointment and appointment report",
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
                    $patientRecord->patientID = $appointmentData["PatientID"];
                    $patientRecord->height = null;
                    $patientRecord->weight = null;
                    $patientRecord->allergies = null;
                    $patientRecord->lastVisit = null;
                    $patientRecord->create();
                }

                $appointmentReport->appointmentID = $appointmentId;
                $appointmentReport->patientRecordID = $patientRecord->recordID;
                $appointmentReport->oralNotes = $oralNotes;
                $appointmentReport->diagnosis = $diagnosis;
                $appointmentReport->xrayImages = $xrayImages;

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

    public function getDentalChart()
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
            require_once "app/models/DentalChart.php";
            require_once "app/models/DentalChartItem.php";

            // Get or create dental chart
            $dentalChart = new DentalChart($this->conn);
            if (!$dentalChart->findByPatientID($patientId)) {
                if (!$dentalChart->createForPatient($patientId)) {
                    echo json_encode([
                        "success" => false,
                        "message" =>
                            "Failed to create dental chart for patient",
                    ]);
                    exit();
                }
            }

            // Initialize all teeth if needed
            $dentalChartItem = new DentalChartItem($this->conn);
            if (
                !$dentalChartItem->initializeAllTeeth(
                    $dentalChart->dentalChartID
                )
            ) {
                error_log(
                    "Warning: Failed to initialize some teeth for dental chart ID: " .
                        $dentalChart->dentalChartID
                );
            }

            // Get all teeth data
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
        } catch (Exception $e) {
            error_log("Error in getDentalChart: " . $e->getMessage());
            echo json_encode([
                "success" => false,
                "message" => "Error fetching dental chart: " . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function updateDentalChartItem()
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
            $patientId = $data["patientId"] ?? null;
            $toothNumber = $data["toothNumber"] ?? null;
            $status = $data["status"] ?? "";
            $notes = $data["notes"] ?? "";

            if (!$patientId || !$toothNumber) {
                echo json_encode([
                    "success" => false,
                    "message" => "Patient ID and tooth number are required",
                ]);
                exit();
            }

            require_once "app/models/DentalChart.php";
            require_once "app/models/DentalChartItem.php";

            // Get or create dental chart
            $dentalChart = new DentalChart($this->conn);
            if (!$dentalChart->findByPatientID($patientId)) {
                // Get current user (dentist) ID
                $user = $this->getAuthUser();

                // For doctors, the UserID is the same as DoctorID
                if (!$dentalChart->createForPatient($patientId, $user["id"])) {
                    echo json_encode([
                        "success" => false,
                        "message" =>
                            "Failed to create dental chart for patient",
                    ]);
                    exit();
                }
            }

            // Update or create tooth record
            $dentalChartItem = new DentalChartItem($this->conn);

            if (
                $dentalChartItem->updateOrCreate(
                    $dentalChart->dentalChartID,
                    $toothNumber,
                    $status,
                    $notes
                )
            ) {
                echo json_encode([
                    "success" => true,
                    "message" => "Dental chart item updated successfully",
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" =>
                        "Failed to update dental chart item - database error",
                ]);
            }
        } catch (Exception $e) {
            error_log("Error in updateDentalChartItem: " . $e->getMessage());
            echo json_encode([
                "success" => false,
                "message" => "Error processing request: " . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function dentalChartEdit($patientId)
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");

        if (empty($patientId) || !is_numeric($patientId)) {
            $_SESSION["error"] = "Invalid patient ID";
            $this->redirect("/doctor/patient-records");
            return;
        }

        // Check if patient exists
        $patient = new Patient($this->conn);
        $patientData = $patient->getPatientById($patientId);

        if (!$patientData) {
            $_SESSION["error"] = "Patient not found";
            $this->redirect("/doctor/patient-records");
            return;
        }

        $user = $this->getAuthUser();

        require_once "app/models/DentalChart.php";
        require_once "app/models/DentalChartItem.php";

        $data = [
            "user" => $user,
            "patientId" => $patientId,
            "patient" => $patientData,
        ];

        $layoutConfig = [
            "title" =>
                "Edit Dental Chart - " .
                $patientData["FirstName"] .
                " " .
                $patientData["LastName"],
            "hideHeader" => false,
            "hideFooter" => false,
        ];

        $this->view("pages/staff/doctor/DentalChartEdit", $data, $layoutConfig);
    }

    public function paymentManagement()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");

        $user = $this->getAuthUser();

        $data = [
            "user" => $user,
        ];

        $layoutConfig = [
            "title" => "Payment Management",
            "hideHeader" => false,
            "hideFooter" => false,
            "additionalHead" =>
                '<link rel="stylesheet" href="' .
                BASE_URL .
                '/app/styles/views/PaymentManagement.css">',
            "additionalScripts" =>
                '<script src="' .
                BASE_URL .
                '/app/views/scripts/PaymentManagement/PaymentManagement.js"></script>',
        ];

        $this->view(
            "pages/staff/doctor/PaymentManagement",
            $data,
            $layoutConfig
        );
    }

    public function getAllAppointmentsPayments()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");

        header("Content-Type: application/json");

        try {
            // Get all appointments with payment information
            $query = "
                SELECT 
                    a.AppointmentID,
                    a.DateTime,
                    a.AppointmentType,
                    a.Reason,
                    'Confirmed' as AppointmentStatus,
                    CONCAT(p_user.FirstName, ' ', p_user.LastName) as PatientName,
                    p_user.Email as PatientEmail,
                    CONCAT(d_user.FirstName, ' ', d_user.LastName) as DoctorName,
                    doc.Specialization,
                    pay.PaymentID,
                    pay.Status as PaymentStatus,
                    pay.UpdatedAt as PaymentUpdatedAt,
                    pay.Notes as PaymentNotes,
                    COALESCE(
                        (SELECT SUM(Total) FROM PaymentItems WHERE PaymentID = pay.PaymentID), 
                        0
                    ) as TotalAmount
                FROM Appointment a
                LEFT JOIN PATIENT pat ON a.PatientID = pat.PatientID
                LEFT JOIN USER p_user ON pat.PatientID = p_user.UserID
                LEFT JOIN Doctor doc ON a.DoctorID = doc.DoctorID
                LEFT JOIN USER d_user ON doc.DoctorID = d_user.UserID
                LEFT JOIN Payments pay ON a.AppointmentID = pay.AppointmentID
                ORDER BY a.DateTime DESC
            ";

            $result = $this->conn->query($query);
            $appointments = $result->fetch_all(MYSQLI_ASSOC);

            echo json_encode([
                "success" => true,
                "appointments" => $appointments,
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error fetching appointments: " . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function getPaymentDetails()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");

        header("Content-Type: application/json");

        $appointmentId = $_GET["appointment_id"] ?? "";
        $paymentId = $_GET["payment_id"] ?? "";

        if (empty($appointmentId) && empty($paymentId)) {
            echo json_encode([
                "success" => false,
                "message" => "Appointment ID or Payment ID is required",
            ]);
            exit();
        }

        try {
            require_once "app/models/Payment.php";
            require_once "app/models/PaymentItem.php";

            $payment = new Payment($this->conn);

            if (!empty($paymentId)) {
                $paymentData = $payment->getPaymentWithBreakdown($paymentId);
            } else {
                $paymentData = $payment->getPaymentByAppointment(
                    $appointmentId
                );
                if ($paymentData) {
                    $paymentData = $payment->getPaymentWithBreakdown(
                        $paymentData["PaymentID"]
                    );
                }
            }

            if (!$paymentData) {
                // Get appointment details for creating new payment
                $appointmentQuery = "
                    SELECT 
                        a.AppointmentID,
                        a.DateTime,
                        a.AppointmentType,
                        a.Reason,
                        a.PatientID,
                        CONCAT(p_user.FirstName, ' ', p_user.LastName) as PatientName,
                        CONCAT(d_user.FirstName, ' ', d_user.LastName) as DoctorName
                    FROM Appointment a
                    LEFT JOIN PATIENT pat ON a.PatientID = pat.PatientID
                    LEFT JOIN USER p_user ON pat.PatientID = p_user.UserID
                    LEFT JOIN Doctor doc ON a.DoctorID = doc.DoctorID
                    LEFT JOIN USER d_user ON doc.DoctorID = d_user.UserID
                    WHERE a.AppointmentID = ?
                ";

                $stmt = $this->conn->prepare($appointmentQuery);
                $stmt->bind_param("i", $appointmentId);
                $stmt->execute();
                $appointmentData = $stmt->get_result()->fetch_assoc();

                if (!$appointmentData) {
                    echo json_encode([
                        "success" => false,
                        "message" => "Appointment not found",
                    ]);
                    exit();
                }

                echo json_encode([
                    "success" => true,
                    "payment" => null,
                    "appointment" => $appointmentData,
                ]);
            } else {
                echo json_encode([
                    "success" => true,
                    "payment" => $paymentData,
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" =>
                    "Error fetching payment details: " . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function createPayment()
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
            $patientId = $data["patientId"] ?? null;
            $status = $data["status"] ?? "Pending";
            $notes = $data["notes"] ?? "";
            $items = $data["items"] ?? [];

            if (!$appointmentId || !$patientId) {
                echo json_encode([
                    "success" => false,
                    "message" => "Appointment ID and Patient ID are required",
                ]);
                exit();
            }

            require_once "app/models/Payment.php";
            require_once "app/models/PaymentItem.php";

            $user = $this->getAuthUser();

            $payment = new Payment($this->conn);
            $payment->appointmentID = $appointmentId;
            $payment->patientID = $patientId;
            $payment->status = $status;
            $payment->updatedBy = $user["id"];
            $payment->notes = $notes;

            if ($payment->create()) {
                // Add payment items
                if (!empty($items)) {
                    $paymentItem = new PaymentItem($this->conn);
                    foreach ($items as $item) {
                        $paymentItem->paymentID = $payment->paymentID;
                        $paymentItem->description = $item["description"];
                        $paymentItem->amount = $item["amount"];
                        $paymentItem->quantity = $item["quantity"] ?? 1;
                        $paymentItem->create();
                    }
                }

                echo json_encode([
                    "success" => true,
                    "message" => "Payment created successfully",
                    "paymentId" => $payment->paymentID,
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to create payment",
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error creating payment: " . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function updatePayment()
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
            $paymentId = $data["paymentId"] ?? null;
            $status = $data["status"] ?? "";
            $notes = $data["notes"] ?? "";

            if (!$paymentId) {
                echo json_encode([
                    "success" => false,
                    "message" => "Payment ID is required",
                ]);
                exit();
            }

            require_once "app/models/Payment.php";
            $user = $this->getAuthUser();

            $payment = new Payment($this->conn);
            if (
                $payment->updateStatus($paymentId, $status, $user["id"], $notes)
            ) {
                echo json_encode([
                    "success" => true,
                    "message" => "Payment updated successfully",
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to update payment",
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error updating payment: " . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function deletePayment()
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
            $paymentId = $data["paymentId"] ?? null;

            if (!$paymentId) {
                echo json_encode([
                    "success" => false,
                    "message" => "Payment ID is required",
                ]);
                exit();
            }

            require_once "app/models/PaymentItem.php";

            // Delete payment items first
            $paymentItem = new PaymentItem($this->conn);
            $paymentItem->deleteByPayment($paymentId);

            // Delete payment
            $deleteQuery = "DELETE FROM Payments WHERE PaymentID = ?";
            $stmt = $this->conn->prepare($deleteQuery);
            $stmt->bind_param("i", $paymentId);

            if ($stmt->execute()) {
                echo json_encode([
                    "success" => true,
                    "message" => "Payment deleted successfully",
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to delete payment",
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error deleting payment: " . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function addPaymentItem()
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
            $paymentId = $data["paymentId"] ?? null;
            $description = $data["description"] ?? "";
            $amount = $data["amount"] ?? 0;
            $quantity = $data["quantity"] ?? 1;

            if (!$paymentId || empty($description) || $amount <= 0) {
                echo json_encode([
                    "success" => false,
                    "message" =>
                        "Payment ID, description, and valid amount are required",
                ]);
                exit();
            }

            require_once "app/models/PaymentItem.php";

            $paymentItem = new PaymentItem($this->conn);
            $paymentItem->paymentID = $paymentId;
            $paymentItem->description = $description;
            $paymentItem->amount = $amount;
            $paymentItem->quantity = $quantity;

            if ($paymentItem->create()) {
                echo json_encode([
                    "success" => true,
                    "message" => "Payment item added successfully",
                    "itemId" => $paymentItem->paymentItemID,
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to add payment item",
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error adding payment item: " . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function updatePaymentItem()
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
            $itemId = $data["itemId"] ?? null;
            $description = $data["description"] ?? "";
            $amount = $data["amount"] ?? 0;
            $quantity = $data["quantity"] ?? 1;

            if (!$itemId || empty($description) || $amount <= 0) {
                echo json_encode([
                    "success" => false,
                    "message" =>
                        "Item ID, description, and valid amount are required",
                ]);
                exit();
            }

            require_once "app/models/PaymentItem.php";

            $paymentItem = new PaymentItem($this->conn);
            if (
                $paymentItem->update($itemId, $description, $amount, $quantity)
            ) {
                echo json_encode([
                    "success" => true,
                    "message" => "Payment item updated successfully",
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to update payment item",
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error updating payment item: " . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function deletePaymentItem()
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
            $itemId = $data["itemId"] ?? null;

            if (!$itemId) {
                echo json_encode([
                    "success" => false,
                    "message" => "Item ID is required",
                ]);
                exit();
            }

            require_once "app/models/PaymentItem.php";

            $paymentItem = new PaymentItem($this->conn);
            if ($paymentItem->delete($itemId)) {
                echo json_encode([
                    "success" => true,
                    "message" => "Payment item deleted successfully",
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to delete payment item",
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error deleting payment item: " . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function updatePaymentStatus()
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
            $paymentId = $data["paymentId"] ?? null;
            $status = $data["status"] ?? "";

            if (!$paymentId || empty($status)) {
                echo json_encode([
                    "success" => false,
                    "message" => "Payment ID and status are required",
                ]);
                exit();
            }

            require_once "app/models/Payment.php";
            $user = $this->getAuthUser();

            $payment = new Payment($this->conn);
            if ($payment->updateStatus($paymentId, $status, $user["id"])) {
                echo json_encode([
                    "success" => true,
                    "message" => "Payment status updated successfully",
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to update payment status",
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" =>
                    "Error updating payment status: " . $e->getMessage(),
            ]);
        }
        exit();
    }
}
?> 