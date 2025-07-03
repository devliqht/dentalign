<?php

require_once "app/core/Controller.php";
require_once "app/models/Appointment.php";
require_once "app/models/Patient.php";
require_once "app/models/Payment.php";

class SearchController extends Controller
{
    protected function initializeMiddleware()
    {
        $this->middleware("auth", ["only" => ["*"]]);
    }

    public function search()
    {
        $this->requireAuth();

        $input = json_decode(file_get_contents("php://input"), true);
        $query = $input["query"] ?? "";

        if (empty($query) || strlen($query) < 2) {
            $this->json(["results" => []]);
            return;
        }

        $user = $this->getAuthUser();
        $results = [];

        // Search for pages/navigation items
        $pageResults = $this->searchPages($query, $user["type"]);
        $results = array_merge($results, $pageResults);

        // Search for appointments (enhanced search for patients)
        $appointmentResults = $this->searchAppointments($query, $user);
        $results = array_merge($results, $appointmentResults);

        // Search for patients (for staff only)
        if ($user["type"] === "ClinicStaff") {
            $patientResults = $this->searchPatients($query);
            $results = array_merge($results, $patientResults);
        }

        // Search for payments
        $paymentResults = $this->searchPayments($query, $user);
        $results = array_merge($results, $paymentResults);

        // Limit results and sort by relevance
        $results = array_slice($results, 0, 10);

        $this->json(["results" => $results]);
    }

    private function searchPages($query, $userType)
    {
        $pages = [];

        if ($userType === "Patient") {
            $pages = [
                [
                    "title" => "Dashboard",
                    "description" => "Your patient dashboard",
                    "url" => BASE_URL . "/patient/dashboard",
                    "keywords" => ["dashboard", "home", "overview", "main"],
                ],
                [
                    "title" => "My Bookings",
                    "description" => "View and manage your appointments",
                    "url" => BASE_URL . "/patient/bookings",
                    "keywords" => [
                        "bookings",
                        "appointments",
                        "schedule",
                        "calendar",
                    ],
                ],
                [
                    "title" => "Book Appointment",
                    "description" => "Schedule a new appointment",
                    "url" => BASE_URL . "/patient/book-appointment",
                    "keywords" => [
                        "book",
                        "schedule",
                        "appointment",
                        "new",
                        "create",
                    ],
                ],
                [
                    "title" => "Payments",
                    "description" => "View payment history and invoices",
                    "url" => BASE_URL . "/patient/payments",
                    "keywords" => [
                        "payments",
                        "bills",
                        "invoices",
                        "money",
                        "cost",
                    ],
                ],
                [
                    "title" => "Results",
                    "description" => "View test results and prescriptions",
                    "url" => BASE_URL . "/patient/results",
                    "keywords" => [
                        "results",
                        "tests",
                        "prescriptions",
                        "medical",
                        "reports",
                    ],
                ],
                [
                    "title" => "Profile",
                    "description" => "Manage your profile settings",
                    "url" => BASE_URL . "/patient/profile",
                    "keywords" => [
                        "profile",
                        "settings",
                        "account",
                        "personal",
                        "info",
                    ],
                ],
            ];
        } elseif ($userType === "ClinicStaff") {
            $pages = [
                [
                    "title" => "Dashboard",
                    "description" => "Staff dashboard",
                    "url" => BASE_URL . "/doctor/dashboard",
                    "keywords" => ["dashboard", "home", "overview", "main"],
                ],
                [
                    "title" => "Schedule",
                    "description" => "Manage appointments and schedule",
                    "url" => BASE_URL . "/doctor/schedule",
                    "keywords" => [
                        "schedule",
                        "calendar",
                        "appointments",
                        "time",
                    ],
                ],
                [
                    "title" => "Appointment History",
                    "description" => "View past appointments",
                    "url" => BASE_URL . "/doctor/appointment-history",
                    "keywords" => [
                        "history",
                        "past",
                        "appointments",
                        "previous",
                    ],
                ],
                [
                    "title" => "Patient Records",
                    "description" => "Access patient medical records",
                    "url" => BASE_URL . "/doctor/patient-records",
                    "keywords" => [
                        "patients",
                        "records",
                        "medical",
                        "files",
                        "database",
                    ],
                ],
                [
                    "title" => "Profile",
                    "description" => "Manage your profile settings",
                    "url" => BASE_URL . "/staff/profile",
                    "keywords" => [
                        "profile",
                        "settings",
                        "account",
                        "personal",
                        "info",
                    ],
                ],
            ];
        }

        $results = [];
        $query = strtolower($query);

        foreach ($pages as $page) {
            $score = 0;

            if (stripos($page["title"], $query) !== false) {
                $score += 10;
            }

            if (stripos($page["description"], $query) !== false) {
                $score += 5;
            }

            foreach ($page["keywords"] as $keyword) {
                if (stripos($keyword, $query) !== false) {
                    $score += 3;
                }
            }

            if ($score > 0) {
                $results[] = [
                    "title" => $page["title"],
                    "description" => $page["description"],
                    "url" => $page["url"],
                    "type" => "page",
                    "score" => $score,
                ];
            }
        }

        // Sort by score (descending)
        usort($results, function ($a, $b) {
            return $b["score"] <=> $a["score"];
        });

        return $results;
    }

    private function searchAppointments($query, $user)
    {
        $results = [];
        $appointment = new Appointment($this->conn);

        try {
            if ($user["type"] === "Patient") {
                $appointments = $appointment->searchAppointmentsByPatient(
                    $user["id"],
                    $query
                );

                foreach ($appointments as $appt) {
                    $formattedDate = date(
                        "M j, Y",
                        strtotime($appt["DateTime"])
                    );
                    $formattedTime = date(
                        "g:i A",
                        strtotime($appt["DateTime"])
                    );

                    $title = "Appointment #{$appt["AppointmentID"]}";
                    if (!empty($appt["AppointmentType"])) {
                        $title .= " - " . ucfirst($appt["AppointmentType"]);
                    }

                    $description = "{$formattedDate} at {$formattedTime}";
                    if (
                        !empty($appt["DoctorFirstName"]) &&
                        !empty($appt["DoctorLastName"])
                    ) {
                        $description .= " with Dr. {$appt["DoctorFirstName"]} {$appt["DoctorLastName"]}";
                    }
                    if (!empty($appt["Specialization"])) {
                        $description .= " ({$appt["Specialization"]})";
                    }

                    $results[] = [
                        "title" => $title,
                        "description" => $description,
                        "url" =>
                            BASE_URL .
                            "/patient/bookings/{$user["id"]}/{$appt["AppointmentID"]}",
                        "type" => "appointment",
                        "score" => 9,
                    ];
                }
            } elseif ($user["type"] === "ClinicStaff") {
                // For staff, search by appointment ID if numeric
                if (is_numeric($query)) {
                    $appointmentData = $appointment->getAppointmentById($query);

                    if ($appointmentData) {
                        $formattedDate = date(
                            "M j, Y",
                            strtotime($appointmentData["DateTime"])
                        );
                        $formattedTime = date(
                            "g:i A",
                            strtotime($appointmentData["DateTime"])
                        );

                        $results[] = [
                            "title" => "Appointment #{$appointmentData["AppointmentID"]}",
                            "description" => "Patient: {$appointmentData["PatientFirstName"]} {$appointmentData["PatientLastName"]} | {$formattedDate} at {$formattedTime}",
                            "url" => BASE_URL . "/doctor/schedule",
                            "type" => "appointment",
                            "score" => 10,
                        ];
                    }
                }
            }
        } catch (Exception $e) {
            // Log error but don't break search
        }

        return $results;
    }

    private function searchPatients($query)
    {
        $results = [];
        $patient = new Patient($this->conn);

        try {
            $patients = $patient->searchPatients($query);

            foreach ($patients as $patientData) {
                $results[] = [
                    "title" =>
                        $patientData["FirstName"] .
                        " " .
                        $patientData["LastName"],
                    "description" => $patientData["Email"],
                    "url" => BASE_URL . "/doctor/patient-records",
                    "type" => "patient",
                    "score" => 8,
                ];
            }
        } catch (Exception $e) {
            error_log("Search patients error: " . $e->getMessage());
        }

        return $results;
    }

    private function searchPayments($query, $user)
    {
        $results = [];

        if (
            stripos("payments", $query) !== false ||
            stripos("bills", $query) !== false ||
            stripos("invoices", $query) !== false ||
            stripos("money", $query) !== false
        ) {
            $url =
                $user["type"] === "Patient"
                    ? BASE_URL . "/patient/payments"
                    : BASE_URL . "/doctor/dashboard"; // Staff might view payments differently

            $results[] = [
                "title" => "Payments",
                "description" => "View payment history and invoices",
                "url" => $url,
                "type" => "payment",
                "score" => 6,
            ];
        }

        return $results;
    }
}
?> 