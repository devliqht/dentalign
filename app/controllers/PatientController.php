<?php

require_once "app/core/Controller.php";
require_once "app/models/User.php";
require_once "app/models/Appointment.php";
require_once "app/models/Patient.php";
require_once "app/models/Doctor.php";

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
        $appointments = $appointment->getAppointmentsByPatient($user["id"]);

        $data = [
            "user" => $user,
            "appointments" => $appointments,
        ];

      
        $layoutConfig = [
            "title" => "My Bookings",
            "hideHeader" => true,
            "hideFooter" => false
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

        $additionalHead = '<link rel="stylesheet" href="' . BASE_URL . '/app/styles/views/BookAppointment.css">';

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
            "additionalHead" => $additionalHead
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

        $data = [
            "user" => $this->getAuthUser(),
            // TODO: Fetch payment history
        ];

        $layoutConfig = [
            "title" => "Payments",
            "hideHeader" => true,
            "hideFooter" => false,
        ];

        $this->view("pages/patient/Payments", $data, $layoutConfig);
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
            $this->redirect(
                BASE_URL . "/patient/bookings",
                "Appointment booked successfully!"
            );
        } else {
            $this->redirectBack(
                "Failed to book appointment. The selected time slot may no longer be available."
            );
        }
    }
}
?> 