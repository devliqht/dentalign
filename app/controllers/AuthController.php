<?php

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

require_once "app/models/User.php";
require_once "app/models/Doctor.php";
require_once "app/models/Patient.php";
require_once "app/core/Controller.php";

class AuthController extends Controller
{
    /*
     *   TBA: Middleware to separate guest and auth methods.
     *
     *
     */
    protected function initializeMiddleware()
    {
        $this->middleware("guest", [
            "only" => ["showLogin", "showSignup", "login", "signup"],
        ]);
        $this->middleware("auth", ["only" => ["home", "logout"]]);
    }

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
            "hideFooter" => false,
            "bodyClass" =>
                "bg-[url('/dentalign/public/low.svg')]",
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

            $this->redirect(BASE_URL . "/patient/dashboard");
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

    private function createDoctor($data)
    {
        $doctor = new Doctor($this->conn);
        $doctor->firstName = $data["first_name"];
        $doctor->lastName = $data["last_name"];
        $doctor->email = $data["email"];
        $doctor->passwordHash = $doctor->hashPassword($data["password"]);
        $doctor->specialization = $data["specialization"] ?? "";

        return $doctor->createDoctor();
    }

    private function createPatient($data)
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
}
?>
