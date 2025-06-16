<?php

require_once 'app/models/User.php';
require_once 'app/models/Doctor.php';

class AuthController {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function showLogin() {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/home');
            exit();
        }
        
        $error = $_SESSION['error'] ?? '';
        unset($_SESSION['error']);
        
        include 'app/views/Login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit();
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Please fill in all fields';
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        $user = new User($this->conn);
        
        if ($user->findByEmail($email) && $user->verifyPassword($password)) {
            $_SESSION['user_id'] = $user->userID;
            $_SESSION['user_name'] = $user->firstName . ' ' . $user->lastName;
            $_SESSION['user_type'] = $user->userType;
            $_SESSION['user_email'] = $user->email;
            
            header('Location: ' . BASE_URL . '/home');
            exit();
        } else {
            $_SESSION['error'] = 'Invalid email or password';
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
    }

    public function showSignup() {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/home');
            exit();
        }
        
        $error = $_SESSION['error'] ?? '';
        $success = $_SESSION['success'] ?? '';
        unset($_SESSION['error'], $_SESSION['success']);
        
        include 'app/views/SignUp.php';
    }

    public function signup() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/signup');
            exit();
        }

        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $userType = $_POST['user_type'] ?? '';
        $specialization = $_POST['specialization'] ?? '';

        if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($userType)) {
            $_SESSION['error'] = 'Please fill in all required fields';
            header('Location: ' . BASE_URL . '/signup');
            exit();
        }

        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Passwords do not match';
            header('Location: ' . BASE_URL . '/signup');
            exit();
        }

        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Password must be at least 6 characters long';
            header('Location: ' . BASE_URL . '/signup');
            exit();
        }

        $user = new User($this->conn);
        if ($user->emailExists($email)) {
            $_SESSION['error'] = 'Email already exists';
            header('Location: ' . BASE_URL . '/signup');
            exit();
        }

        if ($userType === 'Doctor') {
            $doctor = new Doctor($this->conn);
            $doctor->firstName = $firstName;
            $doctor->lastName = $lastName;
            $doctor->email = $email;
            $doctor->passwordHash = $doctor->hashPassword($password);
            $doctor->specialization = $specialization;
            
            if ($doctor->createDoctor()) {
                $_SESSION['success'] = 'Doctor account created successfully! Please login.';
            } else {
                $_SESSION['error'] = 'Failed to create doctor account';
            }
        } else {
            // Create regular user (Patient)
            $user->firstName = $firstName;
            $user->lastName = $lastName;
            $user->email = $email;
            $user->passwordHash = $user->hashPassword($password);
            $user->userType = 'Patient';
            
            if ($user->create()) {
                $patientQuery = "INSERT INTO PATIENT (PatientID) VALUES (?)";
                $stmt = $this->conn->prepare($patientQuery);
                $stmt->bind_param("i", $user->userID);
                $stmt->execute();
                
                $_SESSION['success'] = 'Account created successfully! Please login.';
            } else {
                $_SESSION['error'] = 'Failed to create account';
            }
        }

        header('Location: ' . BASE_URL . '/signup');
        exit();
    }

    public function home() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
        
        include 'app/views/Home.php';
    }

    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
        exit();
    }
}
?>
