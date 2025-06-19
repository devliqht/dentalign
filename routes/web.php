<?php
require_once "app/core/Router.php";

$router = new Router();

/*
 *   The routes follow a format:
 *   'route_name' => 'controller_function'
 *   e.g 'login' (which corresponds to /login) will active DisplayLoginPage() from the AuthController class
 *
 */
$router->group("GET", "AuthController", [
    "" => "DisplayLoginPage",
    "login" => "DisplayLoginPage",
    "signup" => "DisplaySignupPage",
    "dashboard" => "DisplayHomePage",
    "logout" => "LogoutUser",
]);

$router->group("POST", "AuthController", [
    "login" => "LoginUser",
    "signup" => "SignupUser",
]);

$router->group("GET", "PatientController", [
    "patient/dashboard" => "dashboard",
    "patient/bookings" => "bookings",
    "patient/book-appointment" => "bookAppointment",
    "patient/payments" => "payments",
    "patient/results" => "results",
]);

$router->group("POST", "PatientController", [
    "patient/book-appointment" => "storeAppointment",
]);

$router->group("GET", "DoctorController", [
    "doctor/dashboard" => "dashboard",
    "doctor/schedule" => "schedule",
    "doctor/appointment-history" => "appointmentHistory",
    "doctor/patient-records" => "patientRecords",
    "doctor/inbox" => "inbox",
]);

$router->group("POST", "DoctorController", [
    "doctor/schedule" => "updateSchedule",
    "doctor/patient-records" => "updatePatientRecord",
]);

$url = $_GET["url"] ?? "";
$router->handleRequest($url);
?>
