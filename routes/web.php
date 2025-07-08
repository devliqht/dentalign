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
    "patient/bookings/{user_id}/{appointment_id}" => "appointmentDetail",
    "patient/book-appointment" => "bookAppointment",
    "patient/get-timeslots" => "getTimeslots",
    "patient/get-payment-details" => "getPaymentDetails",
    "patient/get-dental-chart-data" => "getDentalChartData",
    "patient/test-route" => "testRoute",
    "patient/debug-payments" => "debugPayments",
    "patient/payments" => "payments",
    "patient/dental-chart" => "dentalchart",
    "patient/profile" => "profile",
    "patient/get-treatment-plan" => "getTreatmentPlan"
]);

$router->group("POST", "PatientController", [
    "patient/book-appointment" => "storeAppointment",
    "patient/reschedule-appointment" => "rescheduleAppointment",
    "patient/cancel-appointment" => "cancelAppointment",
    "patient/update-payment-status" => "updatePaymentStatus",
    "patient/profile" => "updateProfile",
]);

$router->group("GET", "DoctorController", [
    "doctor/dashboard" => "dashboard",
    "doctor/schedule" => "schedule",
    "doctor/get-week-data" => "getWeekData",
    "doctor/appointment-history" => "appointmentHistory",
    "doctor/patient-records" => "patientRecords",
    "doctor/get-patient-details" => "getPatientDetails",
    "doctor/get-appointment-report" => "getAppointmentReport",
    "doctor/get-dental-chart" => "getDentalChart",
    "doctor/dental-chart-edit/{patient_id}" => "dentalChartEdit",
    "doctor/payment-management" => "paymentManagement",
    "doctor/get-payment-details" => "getPaymentDetails",
    "doctor/get-all-appointments-payments" => "getAllAppointmentsPayments",
    "doctor/get-patient-treatment-plan" => "getPatientTreatmentPlan",
    "patient/get-treatment-plan/{patient_id}" => "getTreatmentPlan",
    "doctor/inbox" => "inbox",
    "staff/profile" => "profile",
]);

$router->group("POST", "DoctorController", [
    "doctor/schedule" => "updateSchedule",
    "doctor/patient-records" => "updatePatientRecord",
    "doctor/update-patient-record-data" => "updatePatientRecordData",
    "doctor/update-appointment-report" => "updateAppointmentReport",
    "doctor/update-dental-chart-item" => "updateDentalChartItem",
    "doctor/create-payment" => "createPayment",
    "doctor/update-payment" => "updatePayment",
    "doctor/delete-payment" => "deletePayment",
    "doctor/add-payment-item" => "addPaymentItem",
    "doctor/update-payment-item" => "updatePaymentItem",
    "doctor/delete-payment-item" => "deletePaymentItem",
    "doctor/update-payment-status" => "updatePaymentStatus",
    "doctor/update-treatment-plan" => "updateTreatmentPlan",
    "staff/profile" => "updateProfile",
]);

// Search functionality
$router->group("POST", "SearchController", [
    "search" => "search",
]);

$url = $_GET["url"] ?? "";
$router->handleRequest($url);
?>
