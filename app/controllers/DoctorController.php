<?php

require_once "app/core/Controller.php";
require_once "app/models/User.php";
require_once "app/models/Doctor.php";
require_once "app/models/Appointment.php";
require_once "app/models/PatientRecord.php";

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

        $data = [
            "user" => $this->getAuthUser(),
            // TODO: Fetch doctor's schedule and appointments
        ];

        $layoutConfig = [
            "title" => "Schedule",
            "hideHeader" => false,
            "hideFooter" => false,
        ];

        $this->view("pages/staff/doctor/Schedule", $data, $layoutConfig);
    }

    public function appointmentHistory()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");

        $data = [
            "user" => $this->getAuthUser(),
            // TODO: Fetch appointment history
        ];

        $layoutConfig = [
            "title" => "Appointment History",
            "hideHeader" => false,
            "hideFooter" => false,
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

        $data = [
            "user" => $this->getAuthUser(),
            // TODO: Fetch patient records accessible to this doctor
        ];

        $layoutConfig = [
            "title" => "Patient Records",
            "hideHeader" => false,
            "hideFooter" => false,
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
}
?> 