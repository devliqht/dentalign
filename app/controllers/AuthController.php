<?php

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

require_once "app/models/User.php";
require_once "app/models/Doctor.php";
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
                "bg-[url('/dentalign/public/bg.png')] bg-opacity-40 bg-cover bg-center",
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

            $this->redirect(BASE_URL . "/home");
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

        $isValid = $this->validate(
            $data,
            [
                "first_name" => "required",
                "last_name" => "required",
                "email" => "required|email",
                "password" => "required|min:6",
                "user_type" => "required",
            ],
            [
                "first_name" => "First name is required",
                "last_name" => "Last name is required",
                "email" => "Please enter a valid email address",
                "password" => "Password must be at least 6 characters long",
                "user_type" => "Please select a user type",
            ]
        );

        if (!$isValid) {
            $this->redirectBack("Please correct the errors below");
        }

        if ($data["password"] !== ($data["confirm_password"] ?? "")) {
            $this->redirectBack("Passwords do not match");
        }

        $user = new User($this->conn);
        if ($user->emailExists($data["email"])) {
            $this->redirectBack("Email already exists");
        }

        $success = false;

        if ($data["user_type"] === "Doctor") {
            $success = $this->createDoctor($data);
        } else {
            $success = $this->createPatient($data);
        }

        if ($success) {
            $this->redirectBack(
                null,
                "Account created successfully! Please login."
            );
        } else {
            $this->redirectBack("Failed to create account. Please try again.");
        }
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
        $user = new User($this->conn);
        $user->firstName = $data["first_name"];
        $user->lastName = $data["last_name"];
        $user->email = $data["email"];
        $user->passwordHash = $user->hashPassword($data["password"]);
        $user->userType = "Patient";

        if ($user->create()) {
            $patientQuery = "INSERT INTO PATIENT (PatientID) VALUES (?)";
            $stmt = $this->conn->prepare($patientQuery);
            $stmt->bind_param("i", $user->userID);
            return $stmt->execute();
        }

        return false;
    }

    public function DisplayHomePage()
    {
        $this->requireAuth();

        $data = [
            "user" => $this->getAuthUser(),
        ];

        $layoutConfig = [
            "title" => "Home",
            "hideHeader" => false,
            "hideFooter" => false,
        ];

        $this->view("pages/Home", $data, $layoutConfig);
    }

    public function LogoutUser()
    {
        session_destroy();
        $this->redirect(BASE_URL . "/login");
    }
}
?>
