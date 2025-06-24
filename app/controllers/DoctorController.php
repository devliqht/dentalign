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
}
?> 