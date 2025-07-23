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
    "reset-password" => "displayPasswordResetForm",
    "faq" => "DisplayFaq",
    "privacy" => "DisplayPrivacyPolicy",
    "terms" => "DisplayTermsOfService",
    "accessibility" => "DisplayAccessibility",
]);

$router->group("POST", "AuthController", [
    "login" => "LoginUser",
    "signup" => "SignupUser",
    "request-password-reset" => "requestPasswordReset",
    "reset-password" => "processPasswordReset",
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
    "patient/get-treatment-plan" => "getTreatmentPlan",
    "patient/get-treatment-plan-details" => "getTreatmentPlanDetails",
    "patient/get-available-slots" => "getAvailableSlotsForDoctor",
]);

$router->group("POST", "PatientController", [
    "patient/book-appointment" => "storeAppointment",
    "patient/reschedule-appointment" => "rescheduleAppointment",
    "patient/cancel-appointment" => "cancelAppointment",
    "patient/update-payment-status" => "updatePaymentStatus",
    "patient/profile" => "updateProfile",
]);

$router->group("GET", "DentalAssistantController", [
    "dentalassistant/report" => "report",
    "dentalassistant/payment-management" => "paymentManagement",
    "dentalassistant/configuration" => "configuration",
    "dentalassistant/get-all-appointments-payments" =>
        "getAllAppointmentsPayments",
    "dentalassistant/get-payment-details" => "getPaymentDetails",
    "dentalassistant/get-overdue-config" => "getOverdueConfig",
    "dentalassistant/get-service-prices" => "getServicePrices",
    "dentalassistant/appointment-history" => "appointmentHistory",
    "dentalassistant/get-appointment-report" => "getAppointmentReport",
    "dentalassistant/profile" => "profile",
]);

$router->group("POST", "DentalAssistantController", [
    "dentalassistant/create-payment" => "createPayment",
    "dentalassistant/update-payment" => "updatePayment",
    "dentalassistant/delete-payment" => "deletePayment",
    "dentalassistant/add-payment-item" => "addPaymentItem",
    "dentalassistant/update-payment-item" => "updatePaymentItem",
    "dentalassistant/delete-payment-item" => "deletePaymentItem",
    "dentalassistant/update-payment-status" => "updatePaymentStatus",
    "dentalassistant/update-overdue-config" => "updateOverdueConfig",
    "dentalassistant/create-service-price" => "createServicePrice",
    "dentalassistant/update-service-price" => "updateServicePrice",
    "dentalassistant/delete-service-price" => "deleteServicePrice",
    "dentalassistant/toggle-service-price-status" => "toggleServicePriceStatus",
    "dentalassistant/update-appointment-report" => "updateAppointmentReport",
    "dentalassistant/update-appointment-doctor" => "updateAppointmentDoctor",
    "dentalassistant/approve-cancellation" => "approveCancellation",
    "dentalassistant/deny-cancellation" => "denyCancellation",
    "dentalassistant/update-profile" => "updateProfile",
    "dentalassistant/reschedule-appointment" => "rescheduleAppointment",
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
    "doctor/get-patient-treatment-plan" => "getPatientTreatmentPlan",
    "doctor/get-treatment-plan" => "getTreatmentPlan",
    "doctor/get-valid-appointment-reports" => "getValidAppointmentReports",
    "doctor/inbox" => "inbox",
    "staff/profile" => "profile",
    "doctor/sortAppointmentHistory/{sortOption}/{direction}" =>
        "sortAppointmentHistory", // jeane added this
    "doctor/sortAppointmentHistory/{sortOption}/{direction}/{status}" =>
        "sortAppointmentHistory", // Added status parameter
    "doctor/get-availability" => "getAvailabilityForDate",
]);

$router->group("POST", "DoctorController", [
    "doctor/schedule" => "updateSchedule",
    "doctor/patient-records" => "updatePatientRecord",
    "doctor/update-patient-record-data" => "updatePatientRecordData",
    "doctor/update-appointment-report" => "updateAppointmentReport",
    "doctor/update-dental-chart-item" => "updateDentalChartItem",
    "doctor/update-treatment-plan" => "updateTreatmentPlan",
    "doctor/create-treatment-plan" => "createTreatmentPlan",
    "doctor/delete-treatment-plan" => "deleteTreatmentPlan",
    "doctor/add-treatment-plan-item" => "addTreatmentPlanItem",
    "doctor/update-treatment-plan-item" => "updateTreatmentPlanItem",
    "doctor/delete-treatment-plan-item" => "deleteTreatmentPlanItem",
    "doctor/mark-treatment-item-complete" => "markTreatmentItemComplete",
    "doctor/mark-treatment-item-incomplete" => "markTreatmentItemIncomplete",
    "doctor/approve-cancellation" => "approveCancellation",
    "doctor/deny-cancellation" => "denyCancellation",
    "staff/profile" => "updateProfile",
    "doctor/update-blocked-slots" => "updateBlockedSlots", // NEW
]);

// Search functionality
$router->group("POST", "SearchController", [
    "search" => "search",
]);

$url = $_GET["url"] ?? "";
$router->handleRequest($url);
?>
