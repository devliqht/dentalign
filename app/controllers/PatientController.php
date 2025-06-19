<?php

require_once "app/core/Controller.php";
require_once "app/models/User.php";
require_once "app/models/Appointment.php";
require_once "app/models/Patient.php";

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

        $data = [
            "user" => $this->getAuthUser(),
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

        $data = [
            "user" => $this->getAuthUser(),
            // TODO: Fetch patient's appointments
        ];

        $layoutConfig = [
            "title" => "My Bookings",
            "hideHeader" => true,
            "hideFooter" => false,
        ];

        $this->view("pages/patient/Bookings", $data, $layoutConfig);
    }

    public function bookAppointment()
    {
        $this->requireAuth();
        $this->requireRole("Patient");

        $data = [
            "user" => $this->getAuthUser(),
            // TODO: Fetch available doctors and time slots
        ];

        $layoutConfig = [
            "title" => "Book Appointment",
            "hideHeader" => true,
            "hideFooter" => false,
        ];

        $this->view("pages/patient/BookAppointment", $data, $layoutConfig);
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

    // POST methods for handling form submissions
    public function storeAppointment()
    {
        $this->requireAuth();
        $this->requireRole("Patient");
        $this->validateRequest("POST", true);

        // TODO: Handle appointment booking logic
        $this->redirectBack("Appointment booked successfully!");
    }
}
?> 