<?php

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

require_once "app/models/User.php";
require_once "app/models/Doctor.php";
require_once "app/models/Patient.php";
require_once "app/models/DentalAssistant.php";
require_once "app/core/Controller.php";
require_once "app/services/EmailService.php";

class AuthController extends Controller
{
    public function DisplayLoginPage()
    {
        if ($this->isAuthenticated()) {
            $this->redirect(BASE_URL . "/home");
        }

        $data = [
            "error" => $_SESSION["error"] ?? "",
            "csrf_token" => $this->generateCsrfToken(),
        ];

        $layoutConfig = [
            "title" => "Login",
            "hideHeader" => true,
            "hideSidebar" => true,
            "hideFooter" => false,
            "bodyClass" =>
                "bg-[url('/dentalign/public/low.svg')] bg-size-[100%]",
        ];

        unset($_SESSION["error"]);

        $this->view("pages/Login", $data, $layoutConfig);
    }

    public function LoginUser()
    {
        $this->validateRequest("POST", true);

        $data = $this->sanitize($_POST);

        $isValid = $this->validate(
            $data,
            [
                "email" => "required|email",
                "password" => "required",
            ],
            [
                "email" => "Please enter a valid email address",
                "password" => "Password is required",
            ]
        );

        if (!$isValid) {
            $this->redirectBack("Please correct the errors below");
        }

        $user = new User($this->conn);

        if (
            $user->findByEmail($data["email"]) &&
            $user->verifyPassword($data["password"])
        ) {
            $_SESSION["user_id"] = $user->userID;
            $_SESSION["user_name"] = $user->firstName . " " . $user->lastName;
            $_SESSION["user_type"] = $user->userType;
            $_SESSION["user_email"] = $user->email;

            // Redirect based on user type
            if ($user->userType === "ClinicStaff") {
                $staffQuery = "SELECT StaffType FROM CLINIC_STAFF WHERE ClinicStaffID = ?";
                $stmt = $this->conn->prepare($staffQuery);
                $stmt->bind_param("i", $user->userID);
                $stmt->execute();
                $result = $stmt->get_result();

                if($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $_SESSION["staff_type"] = $row["StaffType"];
                } else {
                    $_SESSION["staff_type"] = NULL;
                }

                if($_SESSION["staff_type"] === "Doctor"){
                    $this->redirect(BASE_URL . "/doctor/dashboard");
                } else if($_SESSION["staff_type"] === "DentalAssistant"){
                    $this->redirect(BASE_URL . "/dentalassistant/dashboard");
                } else {
                    $this->redirect(BASE_URL . "/doctor/dashboard");
                }
            } else {
                $_SESSION["staff_type"] = NULL;
                $this->redirect(BASE_URL . "/patient/dashboard");
            }
        } else {
            $this->redirectBack("Invalid email or password");
        }
    }

    public function DisplaySignupPage()
    {
        if ($this->isAuthenticated()) {
            $this->redirect(BASE_URL . "/home");
        }

        $data = [
            "error" => $_SESSION["error"] ?? "",
            "success" => $_SESSION["success"] ?? "",
            "csrf_token" => $this->generateCsrfToken(),
        ];

        $layoutConfig = [
            "title" => "Sign Up",
            "hideHeader" => true,
            "hideSidebar" => true,
            "hideFooter" => true,
        ];

        unset($_SESSION["error"], $_SESSION["success"]);

        $this->view("pages/Signup", $data, $layoutConfig);
    }

    public function SignupUser()
    {
        $this->validateRequest("POST", true);

        $data = $this->sanitize($_POST);

        $passwordErrors = $this->validatePassword($data["password"] ?? "");
        if (!empty($passwordErrors)) {
            $this->redirectBack(implode(". ", $passwordErrors));
            return;
        }

        if (empty($data["confirm_password"])) {
            $this->redirectBack("Please confirm your password");
            return;
        }

        if ($data["password"] !== $data["confirm_password"]) {
            $this->redirectBack("Passwords do not match");
            return;
        }

        $isValid = $this->validate(
            $data,
            [
                "first_name" => "required",
                "last_name" => "required",
                "email" => "required|email",
                "password" => "required",
                "confirm_password" => "required",
                "user_type" => "required",
            ],
            [
                "first_name" => "First name is required",
                "last_name" => "Last name is required",
                "email" => "Please enter a valid email address",
                "password" => "Password is required",
                "confirm_password" => "Please confirm your password",
                "user_type" => "Please select a user type",
            ]
        );

        if (!$isValid) {
            $this->redirectBack("Please correct the errors below");
            return;
        }

        if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
            $this->redirectBack("Please enter a valid email address");
            return;
        }

        $emailParts = explode("@", $data["email"]);
        if (count($emailParts) !== 2 || empty($emailParts[1])) {
            $this->redirectBack(
                "Please enter a valid email address with a proper domain"
            );
            return;
        }

        $user = new User($this->conn);
        if ($user->emailExists($data["email"])) {
            $this->redirectBack(
                "An account with this email address already exists. Please try logging in instead."
            );
            return;
        }

        $success = false;
        $errorMessage = "";

        try {
            if ($data["user_type"] === "Doctor") {
                $success = $this->createDoctor($data);
                if (!$success) {
                    $errorMessage =
                        "Failed to create doctor account. Please contact support.";
                }
            } elseif ($data["user_type"] === "DentalAssistant") {
                $success = $this->createDentalAssistant($data);
                if (!$success) {
                    $errorMessage =
                        "Failed to create dental assistant account. Please contact support.";
                }
            } else {
                $success = $this->createPatient($data);
                if (!$success) {
                    $errorMessage =
                        "Failed to create patient account. Please try again or contact support.";
                }
            }
        } catch (Exception $e) {
            error_log("Signup error: " . $e->getMessage());
            $errorMessage =
                "An error occurred while creating your account. Please try again.";
        }

        if ($success) {
            $this->redirectBack(
                null,
                "Account created successfully! Please login with your email and password."
            );
        } else {
            $this->redirectBack(
                $errorMessage ?: "Failed to create account. Please try again."
            );
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

    private function createDoctor($data): bool
    {
        $doctor = new Doctor($this->conn);
        $doctor->firstName = $data["first_name"];
        $doctor->lastName = $data["last_name"];
        $doctor->email = $data["email"];
        $doctor->passwordHash = $doctor->hashPassword($data["password"]);
        $doctor->specialization = $data["specialization"] ?? "";

        return $doctor->createDoctor();
    }

    private function createDentalAssistant($data): bool
    {
        $dentalAssistant = new DentalAssistant($this->conn);
        $dentalAssistant->firstName = $data["first_name"];
        $dentalAssistant->lastName = $data["last_name"];
        $dentalAssistant->email = $data["email"];
        $dentalAssistant->passwordHash = $dentalAssistant->hashPassword(
            $data["password"]
        );

        return $dentalAssistant->createDentalAssistant();
    }

    private function createPatient($data): bool
    {
        $patient = new Patient($this->conn);
        $patient->firstName = $data["first_name"];
        $patient->lastName = $data["last_name"];
        $patient->email = $data["email"];
        $patient->passwordHash = $patient->hashPassword($data["password"]);

        return $patient->createPatient();
    }

    public function DisplayHomePage()
    {
        $this->requireAuth();

        $data = [
            "user" => $this->getAuthUser(),
        ];

        $layoutConfig = [
            "title" => "Dashboard",
            "hideHeader" => true,
            "hideFooter" => false,
        ];

        $this->view("pages/Dashboard", $data, $layoutConfig);
    }

    public function LogoutUser()
    {
        session_destroy();
        $this->redirect(BASE_URL . "/login");
    }

    public function requestPasswordReset()
    {
        $this->validateRequest("POST", true);

        $data = $this->sanitize($_POST);

        $isValid = $this->validate(
            $data,
            [
                "email" => "required|email",
            ],
            [
                "email" => "Please enter a valid email address",
            ]
        );

        if (!$isValid) {
            $this->json(
                [
                    "success" => false,
                    "message" => "Please enter a valid email address",
                ],
                400
            );
            return;
        }

        $user = new User($this->conn);
        $emailService = new EmailService();

        $resetToken = $user->generatePasswordResetToken(null, $data["email"]);

        if ($resetToken) {
            $emailSent = $emailService->sendPasswordResetEmail(
                $data["email"],
                $user->firstName,
                $resetToken
            );

            if ($emailSent) {
                $this->json([
                    "success" => true,
                    "message" =>
                        "Password reset link has been sent to your email address. Please check your inbox.",
                ]);
            } else {
                $this->json(
                    [
                        "success" => false,
                        "message" =>
                            "Unable to send email at this time. Please try again later or contact support.",
                    ],
                    500
                );
            }
        } else {
            $this->json([
                "success" => true,
                "message" =>
                    "If an account with that email exists, you will receive a password reset link shortly.",
            ]);
        }
    }

    public function displayPasswordResetForm()
    {
        $token = $_GET["token"] ?? "";
        $isSuccess = isset($_GET["success"]) && $_GET["success"] == "1";

        if (empty($token)) {
            $this->redirectBack("Invalid or missing reset token.");
            return;
        }

        $user = new User($this->conn);

        if ($isSuccess) {
            $userData = $user->getUserByAnyResetToken($token);

            // Verify this is actually a used token (legitimate success)
            if (!$userData || empty($userData["used_at"])) {
                // If success=1 but token wasn't used, something's wrong
                $data = [
                    "error" =>
                        "This password reset link is invalid or has expired. Please request a new one.",
                    "csrf_token" => $this->generateCsrfToken(),
                ];

                $layoutConfig = [
                    "title" => "Invalid Reset Link",
                    "hideHeader" => true,
                    "hideSidebar" => true,
                    "hideFooter" => false,
                ];

                $this->view("pages/PasswordResetError", $data, $layoutConfig);
                return;
            }
        } else {
            // Normal case - only get unused tokens
            $userData = $user->getUserByResetToken($token);

            if (!$userData) {
                $data = [
                    "error" =>
                        "This password reset link is invalid or has expired. Please request a new one.",
                    "csrf_token" => $this->generateCsrfToken(),
                ];

                $layoutConfig = [
                    "title" => "Invalid Reset Link",
                    "hideHeader" => true,
                    "hideSidebar" => true,
                    "hideFooter" => false,
                ];

                $this->view("pages/PasswordResetError", $data, $layoutConfig);
                return;
            }
        }

        $data = [
            "token" => $token,
            "user_email" => $userData["Email"],
            "user_name" => $userData["FirstName"],
            "csrf_token" => $this->generateCsrfToken(),
        ];

        $layoutConfig = [
            "title" => $isSuccess
                ? "Password Reset Successful"
                : "Reset Password",
            "hideHeader" => true,
            "hideSidebar" => true,
            "hideFooter" => false,
        ];

        $this->view("pages/PasswordReset", $data, $layoutConfig);
    }

    public function processPasswordReset()
    {
        $this->validateRequest("POST", true);

        $data = $this->sanitize($_POST);

        $isValid = $this->validate(
            $data,
            [
                "token" => "required",
                "password" => "required",
                "confirm_password" => "required",
            ],
            [
                "token" => "Reset token is required",
                "password" => "Password is required",
                "confirm_password" => "Please confirm your password",
            ]
        );

        if (!$isValid) {
            $this->redirectBack("Please correct the errors below");
            return;
        }

        $passwordErrors = $this->validatePassword($data["password"]);
        if (!empty($passwordErrors)) {
            $this->redirectBack(implode(". ", $passwordErrors));
            return;
        }

        if ($data["password"] !== $data["confirm_password"]) {
            $this->redirectBack("Passwords do not match");
            return;
        }

        $user = new User($this->conn);

        if ($user->resetPasswordWithToken($data["token"], $data["password"])) {
            // Clean up old sessions for security
            session_regenerate_id(true);

            $this->redirect(
                BASE_URL .
                    "/reset-password?token=" .
                    urlencode($data["token"]) .
                    "&success=1"
            );
        } else {
            $this->redirectBack(
                "Invalid or expired reset token. Please request a new password reset."
            );
        }
    }
}
?>
