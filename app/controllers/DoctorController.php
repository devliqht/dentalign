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
require_once "app/models/TreatmentPlan.php";
require_once "app/models/TreatmentPlanItem.php";
require_once 'app/models/BlockedSlot.php';

class DoctorController extends Controller
{
    public function dashboard()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("Doctor");

        $user = $this->getAuthUser();
        $doctorID = $user["id"];

        // Initialize models
        $appointmentModel = new Appointment($this->conn);
        $doctorModel = new Doctor($this->conn);
        $patientModel = new Patient($this->conn);

        // Get doctor information
        $doctorModel->findById($doctorID);

        // Get appointment statistics
        $allAppointments = $appointmentModel->getAppointmentsByDoctor(
            $doctorID
        );
        $upcomingAppointments = $appointmentModel->getUpcomingAppointmentsByDoctor(
            $doctorID
        );
        $todaysAppointments = $appointmentModel->getTodaysAppointmentsByDoctor(
            $doctorID
        );
        $appointmentHistory = $appointmentModel->getAppointmentHistoryByDoctor(
            $doctorID
        );
        $pendingCancellations = $appointmentModel->getPendingCancellationsByDoctor(
            $doctorID
        );

        // Calculate stats
        $appointmentStats = [
            "total" => count($allAppointments),
            "upcoming" => count($upcomingAppointments),
            "today" => count($todaysAppointments),
            "completed" => count(
                array_filter($appointmentHistory, function ($app) {
                    return $app["Status"] === "Completed";
                })
            ),
            "cancellation_requests" => count($pendingCancellations),
        ];

        // Get recent patient visits (from appointment history, last 10)
        $recentPatientVisits = array_slice($appointmentHistory, 0, 10);

        // Get this week's appointments for mini calendar
        $startOfWeek = date("Y-m-d", strtotime("monday this week"));
        $endOfWeek = date("Y-m-d", strtotime("sunday this week"));
        $weekAppointments = $appointmentModel->getAppointmentsByDoctorAndDateRange(
            $doctorID,
            $startOfWeek,
            $endOfWeek
        );

        $data = [
            "user" => $user,
            "doctor" => [
                "specialization" =>
                    $doctorModel->specialization ?? "General Practice",
                "firstName" => $doctorModel->firstName,
                "lastName" => $doctorModel->lastName,
                "email" => $doctorModel->email,
            ],
            "appointmentStats" => $appointmentStats,
            "upcomingAppointments" => array_slice($upcomingAppointments, 0, 5),
            "todaysAppointments" => $todaysAppointments,
            "recentlyCompletedAppointments" => array_slice(
                array_filter($appointmentHistory, function ($app) {
                    return $app["Status"] === "Completed";
                }),
                0,
                5
            ),
            "recentPatientVisits" => $recentPatientVisits,
            "pendingCancellations" => array_slice($pendingCancellations, 0, 5),
            "weekAppointments" => $weekAppointments,
            "startOfWeek" => $startOfWeek,
            "endOfWeek" => $endOfWeek,
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
        $this->requireStaffType("Doctor");

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
        $this->requireStaffType("Doctor");

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
                        '<div class="glass-card bg-white/40 border-gray-200 border-1 shadow-sm p-3 rounded-xl text-xs cursor-pointer hover:bg-white/60 transition-colors group" ' .
                        'onclick="openAppointmentDetailsModal(' . $appointment['AppointmentID'] . ')" ' .
                        'title="Click to view appointment details">';
                    $html .= '<div class="flex items-center justify-between mb-1">';
                    $html .=
                        '<div class="font-semibold text-nhd-blue group-hover:text-nhd-blue/80">' .
                        date("g:i A", strtotime($appointment["DateTime"])) .
                        "</div>";
                    $html .=
                        '<div class="text-xs text-gray-400 group-hover:text-gray-500">' .
                        '#' . str_pad($appointment["AppointmentID"], 4, "0", STR_PAD_LEFT) .
                        "</div>";
                    $html .= '</div>';
                    $html .=
                        '<div class="text-gray-900 font-medium group-hover:text-gray-700">' .
                        htmlspecialchars(
                            $appointment["PatientFirstName"] .
                                " " .
                                $appointment["PatientLastName"]
                        ) .
                        "</div>";
                    $html .=
                        '<div class="text-gray-600 truncate group-hover:text-gray-500">' .
                        htmlspecialchars($appointment["AppointmentType"]) .
                        "</div>";
                    $html .=
                        '<div class="mt-2 text-xs text-gray-400 group-hover:text-gray-500 opacity-0 group-hover:opacity-100 transition-opacity">' .
                        'Click for details →' .
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
        $this->requireStaffType("Doctor");

        $user = $this->getAuthUser();
        $doctorID = $user["id"];

        $appointmentModel = new Appointment($this->conn);
        $appointmentHistory = $appointmentModel->getAppointmentHistoryByDoctor(
            $doctorID
        );
        $pendingCancellations = $appointmentModel->getPendingCancellationsByDoctor(
            $doctorID
        );

        $appointmentsByStatus = [
            "Pending" => [],
            "Approved" => [],
            "Rescheduled" => [],
            "Completed" => [],
            "Declined" => [],
            "Cancelled" => [],
        ];

        foreach ($appointmentHistory as $appointment) {
            $status = $appointment["Status"];
            if (isset($appointmentsByStatus[$status])) {
                $appointmentsByStatus[$status][] = $appointment;
            }
        }

        $data = [
            "user" => $user,
            "appointmentHistory" => $appointmentsByStatus,
            "pendingCancellations" => $pendingCancellations,
            "csrf_token" => $this->generateCsrfToken(),
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
        $this->requireStaffType("Doctor");

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
        $this->requireStaffType("Doctor");

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
        $this->requireStaffType("Doctor");

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
        $this->requireStaffType("Doctor");

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

    // POST methods for handling form submissions
    public function updateSchedule()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("Doctor");
        $this->validateRequest("POST", true);

        // TODO: Handle schedule update logic
        $this->redirectBack("Schedule updated successfully!");
    }

    public function updatePatientRecord()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("Doctor");
        $this->validateRequest("POST", true);

        // TODO: Handle patient record update logic
        $this->redirectBack("Patient record updated successfully!");
    }

    public function getPatientDetails()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("Doctor");

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
        $this->requireStaffType("Doctor");

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
        $this->requireStaffType("Doctor");

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
        $this->requireStaffType("Doctor");

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

                if (
                    $appointmentReport->update() &&
                    $appointment->updateAppointmentStatus($appointmentId)
                ) {
                    echo json_encode([
                        "success" => true,
                        "message" =>
                            "Appointment and Appointment report updated successfully",
                    ]);
                } else {
                    echo json_encode([
                        "success" => false,
                        "message" =>
                            "Failed to update appointment and appointment report",
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
        $this->requireStaffType("Doctor");

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
        $this->requireStaffType("Doctor");

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
        $this->requireStaffType("Doctor");

        if (empty($patientId) || !is_numeric($patientId)) {
            $_SESSION["error"] = "Invalid patient ID";
            $this->redirect("/doctor/patient-records");
            return;
        }

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
    /**
     * Sort appointment history data
     */
    public function sortAppointmentHistory(
        $sortOption,
        $direction,
        $status = ""
    ) {
        $this->requireAuth();
        $this->hasRole("ClinicStaff");
        $this->requireStaffType("Doctor");

        $user = $this->getAuthUser();
        $appointmentModel = new Appointment($this->conn);
        $appointments = $appointmentModel->getAppointmentHistoryByDoctor(
            $user["id"]
        );

        // Filter by status if provided and not empty
        if (!empty($status)) {
            $appointments = array_filter($appointments, function (
                $appointment
            ) use ($status) {
                return $appointment["Status"] === $status;
            });
            // Re-index the array after filtering
            $appointments = array_values($appointments);
        }

        $direction = strtolower($direction) === "desc" ? "desc" : "asc";

        if (!empty($appointments) && isset($appointments[0][$sortOption])) {
            usort($appointments, function ($a, $b) use (
                $sortOption,
                $direction
            ) {
                $valueA = $a[$sortOption];
                $valueB = $b[$sortOption];

                // Handle different data types
                if (is_string($valueA) && is_string($valueB)) {
                    // For date/time fields, convert to timestamp for proper sorting
                    if ($sortOption === "DateTime") {
                        $valueA = strtotime($valueA);
                        $valueB = strtotime($valueB);
                    } else {
                        // Case-insensitive string comparison
                        $valueA = strtolower($valueA);
                        $valueB = strtolower($valueB);
                    }
                }

                if ($direction === "asc") {
                    return $valueA <=> $valueB;
                } else {
                    return $valueB <=> $valueA;
                }
            });
        }

        header("Content-Type: application/json");
        echo json_encode($appointments);
        exit();
    }

    public function approveCancellation()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("Doctor");

        $this->validateRequest("POST", true);

        $data = $this->sanitize($_POST);

        if (empty($data["appointment_id"])) {
            $this->redirectBack("Appointment ID is required.");
            return;
        }

        $appointment = new Appointment($this->conn);
        $success = $appointment->approveCancellation($data["appointment_id"]);

        if ($success) {
            $_SESSION["success"] =
                "Cancellation request approved successfully. The appointment has been cancelled.";
            $this->redirect(BASE_URL . "/doctor/appointment-history");
        } else {
            $this->redirectBack(
                "Failed to approve cancellation request. Please try again."
            );
        }
    }

    public function denyCancellation()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("Doctor");

        $this->validateRequest("POST", true);

        $data = $this->sanitize($_POST);

        if (empty($data["appointment_id"]) || empty($data["new_status"])) {
            $this->redirectBack("Appointment ID and new status are required.");
            return;
        }

        $appointment = new Appointment($this->conn);
        $success = $appointment->denyCancellation(
            $data["appointment_id"],
            $data["new_status"]
        );

        if ($success) {
            $_SESSION["success"] =
                "Cancellation request denied successfully. The appointment status has been restored.";
            $this->redirect(BASE_URL . "/doctor/appointment-history");
        } else {
            $this->redirectBack(
                "Failed to deny cancellation request. Please try again."
            );
        }
    }

    // ===== TREATMENT PLAN METHODS =====

    public function createTreatmentPlan()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("Doctor");

        header("Content-Type: application/json");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode([
                "success" => false,
                "message" => "Invalid request method",
            ]);
            exit();
        }

        try {
            $input = json_decode(file_get_contents("php://input"), true);

            if (!$input || !isset($input["appointmentReportID"])) {
                echo json_encode([
                    "success" => false,
                    "message" => "Appointment Report ID is required",
                ]);
                exit();
            }

            $treatmentPlan = new TreatmentPlan($this->conn);
            $treatmentPlan->appointmentReportID = $input["appointmentReportID"];
            $treatmentPlan->status = $input["status"] ?? "pending";
            $treatmentPlan->dentistNotes = $input["dentistNotes"] ?? "";
            $treatmentPlan->assignedAt = date("Y-m-d H:i:s");

            if ($treatmentPlan->create()) {
                if (isset($input["items"]) && is_array($input["items"])) {
                    $treatmentPlanItem = new TreatmentPlanItem($this->conn);

                    foreach ($input["items"] as $item) {
                        $treatmentPlanItem->treatmentPlanID =
                            $treatmentPlan->treatmentPlanID;
                        $treatmentPlanItem->toothNumber =
                            $item["toothNumber"] ?? "";
                        $treatmentPlanItem->procedureCode =
                            $item["procedureCode"] ?? "";
                        $treatmentPlanItem->description =
                            $item["description"] ?? "";
                        $treatmentPlanItem->cost = $item["cost"] ?? 0;
                        $treatmentPlanItem->scheduledDate =
                            $item["scheduledDate"] ?? null;
                        $treatmentPlanItem->completedAt =
                            isset($item["completedAt"]) &&
                            !empty($item["completedAt"])
                                ? $item["completedAt"]
                                : null;

                        $treatmentPlanItem->create();
                    }
                }

                echo json_encode([
                    "success" => true,
                    "message" => "Treatment plan created successfully",
                    "treatmentPlanID" => $treatmentPlan->treatmentPlanID,
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to create treatment plan",
                ]);
            }
        } catch (Exception $e) {
            error_log("Error creating treatment plan: " . $e->getMessage());
            echo json_encode([
                "success" => false,
                "message" =>
                    "An error occurred while creating the treatment plan",
            ]);
        }
        exit();
    }

    public function getTreatmentPlan()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("Doctor");

        header("Content-Type: application/json");

        $treatmentPlanID = $_GET["treatment_plan_id"] ?? null;

        if (!$treatmentPlanID) {
            echo json_encode([
                "success" => false,
                "message" => "Treatment Plan ID is required",
            ]);
            exit();
        }

        try {
            $treatmentPlan = new TreatmentPlan($this->conn);
            $treatmentPlanItem = new TreatmentPlanItem($this->conn);

            $planDetails = $treatmentPlan->getTreatmentPlanWithDetails(
                $treatmentPlanID
            );
            $planItems = $treatmentPlanItem->findByTreatmentPlanID(
                $treatmentPlanID
            );

            if ($planDetails) {
                echo json_encode([
                    "success" => true,
                    "treatmentPlan" => $planDetails,
                    "items" => $planItems,
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Treatment plan not found",
                ]);
            }
        } catch (Exception $e) {
            error_log("Error fetching treatment plan: " . $e->getMessage());
            echo json_encode([
                "success" => false,
                "message" =>
                    "An error occurred while fetching the treatment plan",
            ]);
        }
        exit();
    }

    public function updateTreatmentPlan()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("Doctor");

        header("Content-Type: application/json");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode([
                "success" => false,
                "message" => "Invalid request method",
            ]);
            exit();
        }

        try {
            $input = json_decode(file_get_contents("php://input"), true);

            if (!$input || !isset($input["treatmentPlanID"])) {
                echo json_encode([
                    "success" => false,
                    "message" => "Treatment Plan ID is required",
                ]);
                exit();
            }

            $treatmentPlan = new TreatmentPlan($this->conn);

            if ($treatmentPlan->findByID($input["treatmentPlanID"])) {
                $treatmentPlan->status =
                    $input["status"] ?? $treatmentPlan->status;
                $treatmentPlan->dentistNotes =
                    $input["dentistNotes"] ?? $treatmentPlan->dentistNotes;

                if ($treatmentPlan->update()) {
                    // Update treatment plan items if provided
                    if (isset($input["items"]) && is_array($input["items"])) {
                        $treatmentPlanItem = new TreatmentPlanItem($this->conn);

                        foreach ($input["items"] as $item) {
                            if (
                                isset($item["treatmentItemID"]) &&
                                !empty($item["treatmentItemID"])
                            ) {
                                // Update existing item
                                if (
                                    $treatmentPlanItem->findByID(
                                        $item["treatmentItemID"]
                                    )
                                ) {
                                    $treatmentPlanItem->toothNumber =
                                        $item["toothNumber"] ??
                                        $treatmentPlanItem->toothNumber;
                                    $treatmentPlanItem->procedureCode =
                                        $item["procedureCode"] ??
                                        $treatmentPlanItem->procedureCode;
                                    $treatmentPlanItem->description =
                                        $item["description"] ??
                                        $treatmentPlanItem->description;
                                    $treatmentPlanItem->cost =
                                        $item["cost"] ??
                                        $treatmentPlanItem->cost;
                                    $treatmentPlanItem->scheduledDate =
                                        $item["scheduledDate"] ??
                                        $treatmentPlanItem->scheduledDate;
                                    $treatmentPlanItem->completedAt =
                                        isset($item["completedAt"]) &&
                                        !empty($item["completedAt"])
                                            ? $item["completedAt"]
                                            : $treatmentPlanItem->completedAt;

                                    $treatmentPlanItem->update();
                                }
                            } else {
                                // Create new item
                                $treatmentPlanItem->treatmentPlanID =
                                    $treatmentPlan->treatmentPlanID;
                                $treatmentPlanItem->toothNumber =
                                    $item["toothNumber"] ?? "";
                                $treatmentPlanItem->procedureCode =
                                    $item["procedureCode"] ?? "";
                                $treatmentPlanItem->description =
                                    $item["description"] ?? "";
                                $treatmentPlanItem->cost = $item["cost"] ?? 0;
                                $treatmentPlanItem->scheduledDate =
                                    $item["scheduledDate"] ?? null;
                                $treatmentPlanItem->completedAt =
                                    isset($item["completedAt"]) &&
                                    !empty($item["completedAt"])
                                        ? $item["completedAt"]
                                        : null;

                                $treatmentPlanItem->create();
                            }
                        }
                    }

                    echo json_encode([
                        "success" => true,
                        "message" => "Treatment plan updated successfully",
                    ]);
                } else {
                    echo json_encode([
                        "success" => false,
                        "message" => "Failed to update treatment plan",
                    ]);
                }
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Treatment plan not found",
                ]);
            }
        } catch (Exception $e) {
            error_log("Error updating treatment plan: " . $e->getMessage());
            echo json_encode([
                "success" => false,
                "message" =>
                    "An error occurred while updating the treatment plan",
            ]);
        }
        exit();
    }

    public function deleteTreatmentPlan()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("Doctor");

        header("Content-Type: application/json");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode([
                "success" => false,
                "message" => "Invalid request method",
            ]);
            exit();
        }

        try {
            $input = json_decode(file_get_contents("php://input"), true);

            if (!$input || !isset($input["treatmentPlanID"])) {
                echo json_encode([
                    "success" => false,
                    "message" => "Treatment Plan ID is required",
                ]);
                exit();
            }

            $treatmentPlan = new TreatmentPlan($this->conn);

            if ($treatmentPlan->delete($input["treatmentPlanID"])) {
                echo json_encode([
                    "success" => true,
                    "message" => "Treatment plan deleted successfully",
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to delete treatment plan",
                ]);
            }
        } catch (Exception $e) {
            error_log("Error deleting treatment plan: " . $e->getMessage());
            echo json_encode([
                "success" => false,
                "message" =>
                    "An error occurred while deleting the treatment plan",
            ]);
        }
        exit();
    }

    public function getValidAppointmentReports()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("Doctor");

        header("Content-Type: application/json");

        $patientID = $_GET["patient_id"] ?? null;

        if (!$patientID) {
            echo json_encode([
                "success" => false,
                "message" => "Patient ID is required",
            ]);
            exit();
        }

        try {
            $treatmentPlan = new TreatmentPlan($this->conn);
            $validReports = $treatmentPlan->getValidAppointmentReportsForTreatmentPlan(
                $patientID
            );

            echo json_encode([
                "success" => true,
                "reports" => $validReports,
            ]);
        } catch (Exception $e) {
            error_log(
                "Error fetching valid appointment reports: " . $e->getMessage()
            );
            echo json_encode([
                "success" => false,
                "message" =>
                    "An error occurred while fetching appointment reports",
            ]);
        }
        exit();
    }

    public function addTreatmentPlanItem()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("Doctor");

        header("Content-Type: application/json");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode([
                "success" => false,
                "message" => "Invalid request method",
            ]);
            exit();
        }

        try {
            $input = json_decode(file_get_contents("php://input"), true);

            if (!$input || !isset($input["treatmentPlanID"])) {
                echo json_encode([
                    "success" => false,
                    "message" => "Treatment Plan ID is required",
                ]);
                exit();
            }

            $treatmentPlanItem = new TreatmentPlanItem($this->conn);
            $treatmentPlanItem->treatmentPlanID = $input["treatmentPlanID"];
            $treatmentPlanItem->toothNumber = $input["toothNumber"] ?? "";
            $treatmentPlanItem->procedureCode = $input["procedureCode"] ?? "";
            $treatmentPlanItem->description = $input["description"] ?? "";
            $treatmentPlanItem->cost = $input["cost"] ?? 0;
            $treatmentPlanItem->scheduledDate = $input["scheduledDate"] ?? null;
            $treatmentPlanItem->completedAt =
                isset($input["completedAt"]) && !empty($input["completedAt"])
                    ? $input["completedAt"]
                    : null;

            if ($treatmentPlanItem->create()) {
                echo json_encode([
                    "success" => true,
                    "message" => "Treatment plan item added successfully",
                    "itemID" => $treatmentPlanItem->treatmentItemID,
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to add treatment plan item",
                ]);
            }
        } catch (Exception $e) {
            error_log("Error adding treatment plan item: " . $e->getMessage());
            echo json_encode([
                "success" => false,
                "message" =>
                    "An error occurred while adding the treatment plan item",
            ]);
        }
        exit();
    }

    public function updateTreatmentPlanItem()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("Doctor");

        header("Content-Type: application/json");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode([
                "success" => false,
                "message" => "Invalid request method",
            ]);
            exit();
        }

        try {
            $input = json_decode(file_get_contents("php://input"), true);

            if (!$input || !isset($input["treatmentItemID"])) {
                echo json_encode([
                    "success" => false,
                    "message" => "Treatment Item ID is required",
                ]);
                exit();
            }

            $treatmentPlanItem = new TreatmentPlanItem($this->conn);

            if ($treatmentPlanItem->findByID($input["treatmentItemID"])) {
                $treatmentPlanItem->toothNumber =
                    $input["toothNumber"] ?? $treatmentPlanItem->toothNumber;
                $treatmentPlanItem->procedureCode =
                    $input["procedureCode"] ??
                    $treatmentPlanItem->procedureCode;
                $treatmentPlanItem->description =
                    $input["description"] ?? $treatmentPlanItem->description;
                $treatmentPlanItem->cost =
                    $input["cost"] ?? $treatmentPlanItem->cost;
                $treatmentPlanItem->scheduledDate =
                    $input["scheduledDate"] ??
                    $treatmentPlanItem->scheduledDate;
                $treatmentPlanItem->completedAt =
                    isset($input["completedAt"]) &&
                    !empty($input["completedAt"])
                        ? $input["completedAt"]
                        : $treatmentPlanItem->completedAt;

                if ($treatmentPlanItem->update()) {
                    echo json_encode([
                        "success" => true,
                        "message" => "Treatment plan item updated successfully",
                    ]);
                } else {
                    echo json_encode([
                        "success" => false,
                        "message" => "Failed to update treatment plan item",
                    ]);
                }
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Treatment plan item not found",
                ]);
            }
        } catch (Exception $e) {
            error_log(
                "Error updating treatment plan item: " . $e->getMessage()
            );
            echo json_encode([
                "success" => false,
                "message" =>
                    "An error occurred while updating the treatment plan item",
            ]);
        }
        exit();
    }

    public function deleteTreatmentPlanItem()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("Doctor");

        header("Content-Type: application/json");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode([
                "success" => false,
                "message" => "Invalid request method",
            ]);
            exit();
        }

        try {
            $input = json_decode(file_get_contents("php://input"), true);

            if (!$input || !isset($input["treatmentItemID"])) {
                echo json_encode([
                    "success" => false,
                    "message" => "Treatment Item ID is required",
                ]);
                exit();
            }

            $treatmentPlanItem = new TreatmentPlanItem($this->conn);

            if ($treatmentPlanItem->delete($input["treatmentItemID"])) {
                echo json_encode([
                    "success" => true,
                    "message" => "Treatment plan item deleted successfully",
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to delete treatment plan item",
                ]);
            }
        } catch (Exception $e) {
            error_log(
                "Error deleting treatment plan item: " . $e->getMessage()
            );
            echo json_encode([
                "success" => false,
                "message" =>
                    "An error occurred while deleting the treatment plan item",
            ]);
        }
        exit();
    }

    public function markTreatmentItemComplete()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("Doctor");

        header("Content-Type: application/json");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode([
                "success" => false,
                "message" => "Invalid request method",
            ]);
            exit();
        }

        try {
            $input = json_decode(file_get_contents("php://input"), true);

            if (!$input || !isset($input["treatmentItemID"])) {
                echo json_encode([
                    "success" => false,
                    "message" => "Treatment Item ID is required",
                ]);
                exit();
            }

            $treatmentPlanItem = new TreatmentPlanItem($this->conn);
            $completedAt = $input["completedAt"] ?? null;

            if (
                $treatmentPlanItem->markAsCompleted(
                    $input["treatmentItemID"],
                    $completedAt
                )
            ) {
                echo json_encode([
                    "success" => true,
                    "message" => "Treatment item marked as completed",
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to mark treatment item as completed",
                ]);
            }
        } catch (Exception $e) {
            error_log(
                "Error marking treatment item complete: " . $e->getMessage()
            );
            echo json_encode([
                "success" => false,
                "message" =>
                    "An error occurred while marking the treatment item as completed",
            ]);
        }
        exit();
    }

    public function markTreatmentItemIncomplete()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("Doctor");

        header("Content-Type: application/json");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode([
                "success" => false,
                "message" => "Invalid request method",
            ]);
            exit();
        }

        try {
            $input = json_decode(file_get_contents("php://input"), true);

            if (!$input || !isset($input["treatmentItemID"])) {
                echo json_encode([
                    "success" => false,
                    "message" => "Treatment Item ID is required",
                ]);
                exit();
            }

            $treatmentPlanItem = new TreatmentPlanItem($this->conn);

            if (
                $treatmentPlanItem->markAsIncomplete($input["treatmentItemID"])
            ) {
                echo json_encode([
                    "success" => true,
                    "message" => "Treatment item marked as incomplete",
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to mark treatment item as incomplete",
                ]);
            }
        } catch (Exception $e) {
            error_log(
                "Error marking treatment item incomplete: " . $e->getMessage()
            );
            echo json_encode([
                "success" => false,
                "message" =>
                    "An error occurred while marking the treatment item as incomplete",
            ]);
        }
        exit();
    }

    public function getPatientTreatmentPlan()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("Doctor");

        header("Content-Type: application/json");

        $patientID = $_GET["patient_id"] ?? null;

        if (!$patientID) {
            echo json_encode([
                "success" => false,
                "message" => "Patient ID is required",
            ]);
            exit();
        }

        try {
            $treatmentPlan = new TreatmentPlan($this->conn);
            $treatmentPlanItem = new TreatmentPlanItem($this->conn);

            $patientTreatmentPlans = $treatmentPlan->getTreatmentPlansByPatientID(
                $patientID
            );
            $treatmentPlans = [];

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

    public function getAvailabilityForDate() {
        header('Content-Type: application/json');
        $this->requireAuth();
        $user = $this->getAuthUser();
        $doctorId = $user["id"];
        $date = $_GET['date'] ?? date('Y-m-d');

        // Get already blocked slots
        $blockedSlotModel = new BlockedSlot($this->conn);
        $blockedTimes = $blockedSlotModel->getByDoctorAndDate($doctorId, $date);
        
        // Get already booked appointments
        $appointmentModel = new Appointment($this->conn);
        $appointments = $appointmentModel->getAppointmentsByDoctorAndDate($doctorId, $date);
        $bookedTimes = array_map(function($appt) {
            return date("H:i:s", strtotime($appt['DateTime']));
        }, $appointments);

        echo json_encode([
            'success' => true,
            'blocked_times' => $blockedTimes,
            'booked_times' => $bookedTimes
        ]);
    }
    public function updateBlockedSlots() {
        header('Content-Type: application/json');
        $this->requireAuth();
        $user = $this->getAuthUser();
        $doctorId = $user["id"];

        $input = json_decode(file_get_contents('php://input'), true);
        $date = $input['date'] ?? null;
        $times = $input['times'] ?? [];

        if (!$date) {
            echo json_encode(['success' => false, 'message' => 'Date is required.']);
            return;
        }

        $blockedSlotModel = new BlockedSlot($this->conn);
        $success = $blockedSlotModel->updateForDoctor($doctorId, $date, $times);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Availability updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update availability.']);
        }
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