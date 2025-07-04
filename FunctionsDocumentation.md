# Dentalign Functions Documentation

## Table of Contents
1. [Base Controller Functions](#base-controller-functions)
2. [Authentication Controller Functions](#authentication-controller-functions)
3. [Doctor Controller Functions](#doctor-controller-functions)
4. [Patient Controller Functions](#patient-controller-functions)
5. [Search Controller Functions](#search-controller-functions)
6. [Model Functions](#model-functions)
   - [User Model](#user-model)
   - [Doctor Model](#doctor-model)
   - [Patient Model](#patient-model)
   - [Appointment Model](#appointment-model)
   - [Payment Model](#payment-model)
   - [PaymentItem Model](#paymentitem-model)
   - [PatientRecord Model](#patientrecord-model)
   - [DentalChart Model](#dentalchart-model)
   - [DentalChartItem Model](#dentalchartitem-model)
   - [AppointmentReport Model](#appointmentreport-model)

---

## Base Controller Functions

### `__construct()`
**Description:** Initializes the controller with database connection and middleware.
**Return Type:** `void`
**Usage:**
```php
$controller = new AuthController(); // Automatically sets up database and middleware
```

### `middleware($middleware, $options = [])`
**Description:** Applies middleware to controller methods for authentication and authorization.
**Parameters:** `string $middleware`, `array $options`
**Return Type:** `$this` (for method chaining)
**Usage:**
```php
$this->middleware("auth", ["only" => ["dashboard", "profile"]]);
```

### `initializeMiddleware()`
**Description:** Abstract method for child controllers to define their specific middleware requirements.
**Return Type:** `void`
**Usage:**
```php
protected function initializeMiddleware() {
    $this->middleware("role:Patient", ["only" => ["*"]]);
}
```

### `requireAuth($redirectTo = "/login")`
**Description:** Ensures user is authenticated before accessing protected routes.
**Parameters:** `string $redirectTo` (optional, default: "/login")
**Return Type:** `void` (exits on failure)
**Usage:**
```php
$this->requireAuth(); // Redirects to login if not authenticated
```

### `isAuthenticated()`
**Description:** Checks if a user session is active.
**Return Type:** `bool` (true if authenticated, false otherwise)
**Usage:**
```php
if ($this->isAuthenticated()) {
    // User is logged in
}
```

### `getAuthUser()`
**Description:** Retrieves current authenticated user's information from session.
**Return Type:** `array|null` - Returns user data array with keys: 'id', 'name', 'email', 'type', or null if not authenticated
**Usage:**
```php
$user = $this->getAuthUser();
echo "Welcome " . $user['name'];
```

### `hasRole($role)`
**Description:** Checks if current user has a specific role.
**Parameters:** `string $role` (e.g., "Patient", "ClinicStaff")
**Return Type:** `bool` (true if user has role, false otherwise)
**Usage:**
```php
if ($this->hasRole("ClinicStaff")) {
    // User is staff member
}
```

### `requireRole($role, $redirectTo = "/login")`
**Description:** Enforces role-based access control.
**Parameters:** `string $role`, `string $redirectTo` (optional)
**Return Type:** `void` (exits on failure)
**Usage:**
```php
$this->requireRole("Doctor", "/unauthorized");
```

### `validate($data, $rules, $messages = [])`
**Description:** Validates input data against defined rules.
**Parameters:** `array $data`, `array $rules`, `array $messages` (optional)
**Return Type:** `bool` (true if valid, false if validation fails)
**Side Effects:** Sets validation errors in session on failure
**Usage:**
```php
$isValid = $this->validate($_POST, [
    "email" => "required|email",
    "password" => "required|min:8"
]);
```

### `getValidationErrors()`
**Description:** Retrieves validation errors from session.
**Return Type:** `array` - Associative array with field names as keys and error messages as values
**Side Effects:** Clears validation errors from session after retrieval
**Usage:**
```php
$errors = $this->getValidationErrors();
foreach ($errors as $field => $error) {
    echo $error;
}
```

### `getOldInput($field = null)`
**Description:** Retrieves previously submitted form data for form repopulation.
**Parameters:** `string|null $field` (optional - specific field name or null for all)
**Return Type:** `string|array` - Field value if $field specified, or array of all old input
**Side Effects:** Clears old input from session after retrieval
**Usage:**
```php
$email = $this->getOldInput('email'); // Repopulate email field
```

### `redirect($url, $statusCode = 302)`
**Description:** Redirects user to specified URL.
**Parameters:** `string $url`, `int $statusCode` (optional, default: 302)
**Return Type:** `void` (exits after redirect)
**Usage:**
```php
$this->redirect(BASE_URL . "/dashboard");
```

### `redirectBack($error = null, $success = null)`
**Description:** Redirects back to previous page with optional messages.
**Parameters:** `string|null $error`, `string|null $success`
**Return Type:** `void` (exits after redirect)
**Side Effects:** Sets error/success messages in session
**Usage:**
```php
$this->redirectBack("Invalid credentials", null);
```

### `view($viewFile, $data = [], $layoutConfig = [])`
**Description:** Renders a view with data and layout configuration.
**Parameters:** `string $viewFile`, `array $data`, `array $layoutConfig`
**Return Type:** `void`
**Side Effects:** Outputs rendered HTML, adds validation errors and user data to view data
**Usage:**
```php
$this->view("pages/Dashboard", ["user" => $user], ["title" => "Dashboard"]);
```

### `json($data, $statusCode = 200)`
**Description:** Returns JSON response for API endpoints.
**Parameters:** `mixed $data`, `int $statusCode` (optional, default: 200)
**Return Type:** `void` (exits after output)
**Side Effects:** Sets JSON content-type header and outputs JSON
**Usage:**
```php
$this->json(["status" => "success", "data" => $appointments]);
```

### `getRequestMethod()`
**Description:** Gets the HTTP request method.
**Return Type:** `string` - HTTP method (GET, POST, PUT, DELETE, etc.)
**Usage:**
```php
if ($this->getRequestMethod() === "POST") {
    // Handle POST request
}
```

### `isPost()`
**Description:** Checks if request is POST method.
**Return Type:** `bool` (true if POST, false otherwise)
**Usage:**
```php
if ($this->isPost()) {
    // Process form submission
}
```

### `isGet()`
**Description:** Checks if request is GET method.
**Return Type:** `bool` (true if GET, false otherwise)
**Usage:**
```php
if ($this->isGet()) {
    // Display page
}
```

### `sanitize($data)`
**Description:** Sanitizes input data to prevent XSS attacks.
**Parameters:** `mixed $data` (string or array)
**Return Type:** `mixed` - Sanitized data (same type as input)
**Usage:**
```php
$cleanData = $this->sanitize($_POST);
```

### `generateCsrfToken()`
**Description:** Generates CSRF token for form security.
**Return Type:** `string` - CSRF token (64-character hex string)
**Side Effects:** Stores token in session if not already set
**Usage:**
```php
$token = $this->generateCsrfToken();
// Include in form: <input type="hidden" name="csrf_token" value="<?= $token ?>">
```

### `validateCsrfToken($token)`
**Description:** Validates CSRF token to prevent cross-site request forgery.
**Parameters:** `string $token`
**Return Type:** `bool` (true if valid, false otherwise)
**Usage:**
```php
if ($this->validateCsrfToken($_POST['csrf_token'])) {
    // Token is valid, process request
}
```

### `validateRequest($requiredMethod = "POST", $checkCsrf = true)`
**Description:** Validates request method and CSRF token.
**Parameters:** `string $requiredMethod`, `bool $checkCsrf`
**Return Type:** `void` (redirects back on failure)
**Side Effects:** May redirect with error message if validation fails
**Usage:**
```php
$this->validateRequest("POST", true); // Ensure POST with valid CSRF
```

---

## Authentication Controller Functions

### `DisplayLoginPage()`
**Description:** Shows the login page with CSRF protection.
**Return Type:** `void`
**Side Effects:** Renders login view, redirects if already authenticated, clears error session data
**Data Passed:** `array` with 'error' message and 'csrf_token'
**Usage:**
```php
// Route: GET /login
// Displays login form with security tokens
```

### `LoginUser()`
**Description:** Processes user login with credentials validation.
**Return Type:** `void` (redirects on success/failure)
**Side Effects:** Creates user session on success, redirects to user-type specific dashboard
**Data Processed:** `$_POST` with 'email', 'password', 'csrf_token'
**Usage:**
```php
// Route: POST /login
// Validates email/password, creates session, redirects based on user type
```

### `DisplaySignupPage()`
**Description:** Shows the registration page for new users.
**Return Type:** `void`
**Side Effects:** Renders signup view, redirects if already authenticated, clears session messages
**Data Passed:** `array` with 'error', 'success' messages and 'csrf_token'
**Usage:**
```php
// Route: GET /signup
// Displays signup form for patients and doctors
```

### `SignupUser()`
**Description:** Processes user registration with comprehensive validation.
**Return Type:** `void` (redirects back with success/error)
**Side Effects:** Creates new user account, validates password strength, checks email uniqueness
**Data Processed:** `$_POST` with 'first_name', 'last_name', 'email', 'password', 'confirm_password', 'user_type', 'specialization'
**Usage:**
```php
// Route: POST /signup
// Creates new patient or doctor account with validation
```

### `validatePassword($password)`
**Description:** Validates password strength requirements.
**Parameters:** `string $password`
**Return Type:** `array` - Array of error messages (empty if valid)
**Requirements:** 8+ chars, uppercase, lowercase, number, special character
**Usage:**
```php
$errors = $this->validatePassword("weak123"); 
// Returns array of password requirement violations
```

### `createDoctor($data)`
**Description:** Creates a new doctor account with clinic staff privileges.
**Parameters:** `array $data` with keys: 'first_name', 'last_name', 'email', 'password', 'specialization'
**Return Type:** `bool` (true on success, false on failure)
**Side Effects:** Creates USER, CLINIC_STAFF, and Doctor records in transaction
**Usage:**
```php
$success = $this->createDoctor([
    "first_name" => "John",
    "last_name" => "Doe",
    "email" => "john@clinic.com",
    "password" => "SecurePass123!",
    "specialization" => "Orthodontist"
]);
```

### `createPatient($data)`
**Description:** Creates a new patient account.
**Parameters:** `array $data` with keys: 'first_name', 'last_name', 'email', 'password'
**Return Type:** `bool` (true on success, false on failure)
**Side Effects:** Creates USER and PATIENT records, triggers PatientRecord creation
**Usage:**
```php
$success = $this->createPatient([
    "first_name" => "Jane",
    "last_name" => "Smith", 
    "email" => "jane@email.com",
    "password" => "SecurePass123!"
]);
```

### `DisplayHomePage()`
**Description:** Shows the main dashboard based on user type.
**Return Type:** `void`
**Side Effects:** Renders dashboard view, requires authentication
**Data Passed:** `array` with 'user' information
**Usage:**
```php
// Route: GET /home
// Redirects to appropriate dashboard (patient/doctor)
```

### `LogoutUser()`
**Description:** Destroys user session and redirects to login.
**Return Type:** `void` (exits after redirect)
**Side Effects:** Destroys session, redirects to login page
**Usage:**
```php
// Route: POST /logout
// Clears session data and redirects to login page
```

---

## Doctor Controller Functions

### `dashboard()`
**Description:** Displays doctor dashboard with overview data.
**Return Type:** `void`
**Side Effects:** Renders doctor dashboard view, requires ClinicStaff role
**Data Passed:** `array` with 'user' information
**Usage:**
```php
// Route: GET /doctor/dashboard
// Shows appointments, patient summary, quick stats
```

### `schedule()`
**Description:** Shows doctor's schedule with calendar views.
**Parameters:** `$_GET['date']` (optional, defaults to today)
**Return Type:** `void`
**Side Effects:** Renders schedule view with calendar components
**Data Passed:** `array` with appointments (today's, upcoming, selected date, weekly), doctor info, date ranges
**Usage:**
```php
// Route: GET /doctor/schedule?date=2024-01-15
// Displays daily/weekly appointments with patient details
```

### `getWeekData()`
**Description:** Returns weekly appointment data for AJAX requests.
**Parameters:** `$_GET['date']` (optional, defaults to today)
**Return Type:** `void` (outputs HTML)
**Side Effects:** Generates and outputs weekly calendar HTML
**Data Returned:** HTML string for weekly appointment grid
**Usage:**
```php
// AJAX Route: GET /doctor/schedule/week?date=2024-01-15
// Returns HTML for weekly calendar view
```

### `appointmentHistory()`
**Description:** Shows past appointments with search and filtering.
**Return Type:** `void`
**Side Effects:** Renders appointment history view
**Data Passed:** `array` with past appointments and patient details
**Usage:**
```php
// Route: GET /doctor/appointment-history
// Displays completed appointments with patient information
```

### `patientRecords()`
**Description:** Displays all patient records accessible to the doctor.
**Return Type:** `void`
**Side Effects:** Renders patient records view
**Data Passed:** `array` with patient list, medical records, appointment statistics
**Usage:**
```php
// Route: GET /doctor/patient-records
// Shows searchable list of patients with basic info
```

### `inbox()`
**Description:** Shows doctor's message inbox (placeholder).
**Return Type:** `void`
**Side Effects:** Renders inbox view (future functionality)
**Data Passed:** Empty array (placeholder for future messages)
**Usage:**
```php
// Route: GET /doctor/inbox
// Future: Display patient messages, appointment requests
```

### `profile()`
**Description:** Shows doctor's profile information.
**Return Type:** `void`
**Side Effects:** Renders profile view with doctor information
**Data Passed:** `array` with user and doctor details
**Usage:**
```php
// Route: GET /doctor/profile
// Displays editable doctor information and settings
```

### `updateProfile()`
**Description:** Processes doctor profile updates.
**Return Type:** `void` (redirects back with success/error)
**Side Effects:** Updates user and doctor records in database
**Data Processed:** `$_POST` with profile fields (name, email, specialization, etc.)
**Usage:**
```php
// Route: POST /doctor/profile
// Updates doctor's personal and professional information
```

### `updateSchedule()`
**Description:** Updates doctor's schedule availability.
**Return Type:** `void` (redirects back with success/error)
**Side Effects:** Modifies doctor's working schedule in database
**Data Processed:** `$_POST` with schedule/availability data
**Usage:**
```php
// Route: POST /doctor/schedule/update
// Modifies working hours, availability slots
```

### `updatePatientRecord()`
**Description:** Updates patient medical record information.
**Return Type:** `void` (redirects back with success/error)
**Side Effects:** Updates PatientRecord table with new medical data
**Data Processed:** `$_POST` with patient record fields
**Usage:**
```php
// Route: POST /doctor/patient-record/update
// Updates height, weight, allergies, medical history
```

### `getPatientDetails()`
**Description:** Retrieves detailed patient information for AJAX requests.
**Parameters:** `$_GET['patientId']`
**Return Type:** `void` (outputs JSON)
**Side Effects:** Returns JSON response with patient data
**Data Returned:** `array` with patient info, appointment history, medical records, payment status
**Usage:**
```php
// AJAX Route: GET /doctor/patient/123/details
// Returns patient data, appointment history, medical records
```

### `updatePatientRecordData()`
**Description:** Processes patient record updates via AJAX.
**Return Type:** `void` (outputs JSON)
**Side Effects:** Updates patient record, returns JSON success/error response
**Data Processed:** JSON input with patient record fields
**Data Returned:** `array` with 'success' boolean and 'message' string
**Usage:**
```php
// AJAX Route: POST /doctor/patient-record/update
// Updates patient medical information asynchronously
```

### `getAppointmentReport()`
**Description:** Retrieves appointment report for editing.
**Parameters:** `$_GET['appointmentId']`
**Return Type:** `void` (outputs JSON)
**Side Effects:** Returns JSON response with appointment report data
**Data Returned:** `array` with appointment report details (diagnosis, notes, patient info)
**Usage:**
```php
// AJAX Route: GET /doctor/appointment/123/report
// Returns diagnosis, notes, treatment details
```

### `updateAppointmentReport()`
**Description:** Updates appointment report with diagnosis and notes.
**Return Type:** `void` (outputs JSON)
**Side Effects:** Updates AppointmentReport table
**Data Processed:** JSON input with 'appointmentId', 'oralNotes', 'diagnosis'
**Data Returned:** `array` with 'success' boolean and 'message' string
**Usage:**
```php
// AJAX Route: POST /doctor/appointment/123/report
// Saves diagnosis, oral notes, treatment plans
```

### `getDentalChart()`
**Description:** Retrieves dental chart data for patient.
**Parameters:** `$_GET['patientId']`
**Return Type:** `void` (outputs JSON)
**Side Effects:** Returns JSON response with dental chart data
**Data Returned:** `array` with dental chart info and tooth records
**Usage:**
```php
// AJAX Route: GET /doctor/patient/123/dental-chart
// Returns tooth status, treatments, dental history
```

### `updateDentalChartItem()`
**Description:** Updates individual tooth information in dental chart.
**Return Type:** `void` (outputs JSON)
**Side Effects:** Updates or creates dental chart item record
**Data Processed:** JSON input with 'patientId', 'toothNumber', 'status', 'notes'
**Data Returned:** `array` with 'success' boolean and 'message' string
**Usage:**
```php
// AJAX Route: POST /doctor/dental-chart/tooth/update
// Updates tooth status, treatments, notes
```

### `dentalChartEdit($patientId)`
**Description:** Shows dental chart editing interface.
**Parameters:** `int $patientId` (from URL parameter)
**Return Type:** `void`
**Side Effects:** Renders dental chart editing view
**Data Passed:** `array` with patient info, dental chart data, tooth records
**Usage:**
```php
// Route: GET /doctor/patient/123/dental-chart/edit
// Displays interactive dental chart for editing
```

### `paymentManagement()`
**Description:** Shows payment management dashboard for staff.
**Sample Use Case:**
```php
// Route: GET /doctor/payment-management
// Displays all payments, pending items, financial overview
```

### `getAllAppointmentsPayments()`
**Description:** Retrieves all appointment payments for management.
**Sample Use Case:**
```php
// AJAX Route: GET /doctor/payments/all
// Returns all payment records with patient/appointment details
```

### `getPaymentDetails()`
**Description:** Gets detailed payment information including line items.
**Sample Use Case:**
```php
// AJAX Route: GET /doctor/payment/123/details
// Returns payment breakdown, items, patient info
```

### `createPayment()`
**Description:** Creates new payment record for appointment.
**Sample Use Case:**
```php
// AJAX Route: POST /doctor/payment/create
// Creates payment with multiple line items for services
```

### `updatePayment()`
**Description:** Updates existing payment information.
**Sample Use Case:**
```php
// AJAX Route: PUT /doctor/payment/123/update
// Modifies payment details, status, notes
```

### `deletePayment()`
**Description:** Removes payment record and associated items.
**Sample Use Case:**
```php
// AJAX Route: DELETE /doctor/payment/123
// Safely removes payment record with validation
```

### `addPaymentItem()`
**Description:** Adds line item to existing payment.
**Sample Use Case:**
```php
// AJAX Route: POST /doctor/payment/123/add-item
// Adds service/procedure to payment breakdown
```

### `updatePaymentItem()`
**Description:** Updates payment line item details.
**Sample Use Case:**
```php
// AJAX Route: PUT /doctor/payment-item/456/update
// Modifies description, amount, quantity of service
```

### `deletePaymentItem()`
**Description:** Removes line item from payment.
**Sample Use Case:**
```php
// AJAX Route: DELETE /doctor/payment-item/456
// Removes specific service from payment
```

### `updatePaymentStatus()`
**Description:** Changes payment status (pending/paid/cancelled).
**Sample Use Case:**
```php
// AJAX Route: POST /doctor/payment/123/status
// Updates payment status with staff authentication
```

---

## Patient Controller Functions

### `dashboard()`
**Description:** Displays patient dashboard with appointments and health overview.
**Sample Use Case:**
```php
// Route: GET /patient/dashboard
// Shows upcoming appointments, payment status, health summary
```

### `bookings()`
**Description:** Shows patient's appointment bookings and history.
**Sample Use Case:**
```php
// Route: GET /patient/bookings
// Displays all appointments with status and payment info
```

### `bookAppointment()`
**Description:** Shows appointment booking form with available doctors.
**Sample Use Case:**
```php
// Route: GET /patient/book-appointment
// Displays booking form with doctor selection and time slots
```

### `getTimeslots()`
**Description:** Retrieves available time slots for specific doctor and date.
**Sample Use Case:**
```php
// AJAX Route: GET /patient/timeslots?doctor=123&date=2024-01-15
// Returns available appointment times
```

### `payments()`
**Description:** Shows patient's payment history and pending bills.
**Sample Use Case:**
```php
// Route: GET /patient/payments
// Displays payment records, invoices, outstanding amounts
```

### `dentalchart()`
**Description:** Shows patient's dental chart view.
**Sample Use Case:**
```php
// Route: GET /patient/dental-chart
// Displays read-only dental chart with treatment history
```

### `getDentalChartData()`
**Description:** Retrieves dental chart data for AJAX display.
**Sample Use Case:**
```php
// AJAX Route: GET /patient/dental-chart/data
// Returns dental chart information for visualization
```

### `updatePaymentStatus()`
**Description:** Allows patients to update payment status (simulation).
**Sample Use Case:**
```php
// AJAX Route: POST /patient/payment/123/status
// Patient marks payment as paid (for demo purposes)
```

### `getPaymentDetails()`
**Description:** Retrieves detailed payment information for patient view.
**Sample Use Case:**
```php
// AJAX Route: GET /patient/payment/123/details
// Returns payment breakdown and invoice details
```

### `profile()`
**Description:** Shows patient profile and personal information.
**Sample Use Case:**
```php
// Route: GET /patient/profile
// Displays editable patient personal information
```

### `updateProfile()`
**Description:** Processes patient profile updates.
**Sample Use Case:**
```php
// Route: POST /patient/profile
// Updates patient personal details and contact information
```

### `storeAppointment()`
**Description:** Creates new appointment booking.
**Sample Use Case:**
```php
// Route: POST /patient/store-appointment
// Books new appointment with doctor validation and conflict checking
```

### `rescheduleAppointment()`
**Description:** Reschedules existing appointment to new time.
**Sample Use Case:**
```php
// AJAX Route: POST /patient/appointment/123/reschedule
// Changes appointment date/time with availability checking
```

### `cancelAppointment()`
**Description:** Cancels patient's appointment.
**Sample Use Case:**
```php
// AJAX Route: POST /patient/appointment/123/cancel
// Cancels appointment and updates related records
```

### `appointmentDetail($userId, $appointmentId)`
**Description:** Shows detailed view of specific appointment.
**Sample Use Case:**
```php
// Route: GET /patient/appointment/123/detail
// Displays appointment details, diagnosis, treatment plan
```

### `debugPayments()`
**Description:** Debug function for payment system testing.
**Sample Use Case:**
```php
// Debug Route: GET /patient/debug-payments
// Developer tool for testing payment functionality
```

### `testRoute()`
**Description:** Test route for development and debugging.
**Sample Use Case:**
```php
// Debug Route: GET /patient/test
// Developer endpoint for testing new features
```

### `generatePaymentDetailsHTML($payment)`
**Description:** Generates HTML for payment details display.
**Sample Use Case:**
```php
$html = $this->generatePaymentDetailsHTML($paymentData);
// Creates formatted payment breakdown for display
```

---

## Search Controller Functions

### `search()`
**Description:** Global search functionality across pages, appointments, patients, and payments.
**Sample Use Case:**
```php
// AJAX Route: POST /search
// Searches across all entities based on user role and query
```

### `searchPages($query, $userType)`
**Description:** Searches through available pages/navigation items.
**Sample Use Case:**
```php
$results = $this->searchPages("dashboard", "Patient");
// Returns matching navigation items and pages
```

### `searchAppointments($query, $user)`
**Description:** Searches through user's appointments.
**Sample Use Case:**
```php
$results = $this->searchAppointments("tooth cleaning", $userData);
// Returns matching appointments based on type, doctor, reason
```

### `searchPatients($query)`
**Description:** Searches through patient records (staff only).
**Sample Use Case:**
```php
$results = $this->searchPatients("john smith");
// Returns matching patients by name, email, phone
```

### `searchPayments($query, $user)`
**Description:** Searches through payment records.
**Sample Use Case:**
```php
$results = $this->searchPayments("pending", $userData);
// Returns matching payments by status, amount, description
```

---

## Model Functions

## User Model

### `__construct($db)`
**Description:** Initializes User model with database connection.
**Sample Use Case:**
```php
$user = new User($db);
```

### `create()`
**Description:** Creates new user record in database.
**Sample Use Case:**
```php
$user->firstName = "John";
$user->lastName = "Doe";
$user->email = "john@example.com";
$user->passwordHash = password_hash("password", PASSWORD_DEFAULT);
$user->userType = "Patient";
$success = $user->create();
```

### `findByEmail($email)`
**Description:** Finds user by email address and loads data into object.
**Sample Use Case:**
```php
if ($user->findByEmail("john@example.com")) {
    echo "User found: " . $user->firstName;
}
```

### `emailExists($email)`
**Description:** Checks if email is already registered.
**Sample Use Case:**
```php
if ($user->emailExists("test@example.com")) {
    echo "Email already taken";
}
```

### `verifyPassword($password)`
**Description:** Verifies password against stored hash.
**Sample Use Case:**
```php
if ($user->verifyPassword("userPassword")) {
    echo "Password correct";
}
```

### `hashPassword($password)`
**Description:** Creates secure password hash.
**Sample Use Case:**
```php
$hashedPassword = $user->hashPassword("newPassword123");
```

### `findById($userId)`
**Description:** Finds user by ID and loads data into object.
**Sample Use Case:**
```php
if ($user->findById(123)) {
    echo "User: " . $user->firstName . " " . $user->lastName;
}
```

### `updateProfile($userId, $firstName, $email)`
**Description:** Updates user's basic profile information.
**Sample Use Case:**
```php
$success = $user->updateProfile(123, "John Updated", "newemail@example.com");
```

### `updatePassword($userId, $newPasswordHash)`
**Description:** Updates user's password hash.
**Sample Use Case:**
```php
$newHash = password_hash("newPassword", PASSWORD_DEFAULT);
$success = $user->updatePassword(123, $newHash);
```

### `emailExistsForOtherUser($email, $userId)`
**Description:** Checks if email exists for users other than specified ID.
**Sample Use Case:**
```php
if ($user->emailExistsForOtherUser("test@example.com", 123)) {
    echo "Email taken by another user";
}
```

---

## Doctor Model

### `createDoctor()`
**Description:** Creates doctor account with user, clinic staff, and doctor records.
**Sample Use Case:**
```php
$doctor = new Doctor($db);
$doctor->firstName = "Dr. John";
$doctor->lastName = "Smith";
$doctor->email = "dr.smith@clinic.com";
$doctor->passwordHash = password_hash("password", PASSWORD_DEFAULT);
$doctor->specialization = "Orthodontist";
$success = $doctor->createDoctor();
```

### `findDoctorByEmail($email)`
**Description:** Finds doctor by email with all related information.
**Sample Use Case:**
```php
if ($doctor->findDoctorByEmail("dr.smith@clinic.com")) {
    echo "Specialization: " . $doctor->specialization;
}
```

### `getAllDoctors()`
**Description:** Retrieves all doctors with their specializations.
**Sample Use Case:**
```php
$doctors = $doctor->getAllDoctors();
foreach ($doctors as $doc) {
    echo $doc['FirstName'] . " - " . $doc['Specialization'];
}
```

### `findById($userID)`
**Description:** Finds doctor by user ID with all related information.
**Sample Use Case:**
```php
if ($doctor->findById(123)) {
    echo "Dr. " . $doctor->lastName . " specializes in " . $doctor->specialization;
}
```

---

## Patient Model

### `createPatient()`
**Description:** Creates patient account with user and patient records.
**Sample Use Case:**
```php
$patient = new Patient($db);
$patient->firstName = "Jane";
$patient->lastName = "Doe";
$patient->email = "jane@example.com";
$patient->passwordHash = password_hash("password", PASSWORD_DEFAULT);
$success = $patient->createPatient();
```

### `findPatientByEmail($email)`
**Description:** Finds patient by email with related information.
**Sample Use Case:**
```php
if ($patient->findPatientByEmail("jane@example.com")) {
    echo "Patient ID: " . $patient->patientID;
}
```

### `getPatientAppointments($patientID)`
**Description:** Retrieves all appointments for a patient.
**Sample Use Case:**
```php
$appointments = $patient->getPatientAppointments(123);
foreach ($appointments as $apt) {
    echo "Appointment on " . $apt['DateTime'];
}
```

### `getPatientByUserId($userId)`
**Description:** Gets patient data by user ID.
**Sample Use Case:**
```php
$patientData = $patient->getPatientByUserId(123);
if ($patientData) {
    echo "Patient: " . $patientData['FirstName'];
}
```

### `getPatientById($patientID)`
**Description:** Gets patient data by patient ID.
**Sample Use Case:**
```php
$patientData = $patient->getPatientById(456);
```

### `getAllPatientsWithRecords()`
**Description:** Gets all patients with their medical records and statistics.
**Sample Use Case:**
```php
$patients = $patient->getAllPatientsWithRecords();
// Returns patients with appointment counts, last visit, etc.
```

### `searchPatients($query)`
**Description:** Searches patients by name, email, or other criteria.
**Sample Use Case:**
```php
$results = $patient->searchPatients("john smith");
```

---

## Appointment Model

### `__construct($db)`
**Description:** Initializes Appointment model with database connection.
**Sample Use Case:**
```php
$appointment = new Appointment($db);
```

### `create($useExistingTransaction = false)`
**Description:** Creates new appointment with transaction support.
**Sample Use Case:**
```php
$appointment->patientID = 123;
$appointment->doctorID = 456;
$appointment->dateTime = "2024-01-15 10:00:00";
$appointment->appointmentType = "Consultation";
$appointment->reason = "Tooth pain";
$success = $appointment->create();
```

### `getAppointmentsByPatient($patientID)`
**Description:** Gets all appointments for a specific patient.
**Sample Use Case:**
```php
$appointments = $appointment->getAppointmentsByPatient(123);
```

### `getUpcomingAppointmentsByPatient($patientID)`
**Description:** Gets future appointments for a patient.
**Sample Use Case:**
```php
$upcoming = $appointment->getUpcomingAppointmentsByPatient(123);
```

### `checkDoctorAvailability($doctorID, $dateTime)`
**Description:** Checks if doctor is available at specific time.
**Sample Use Case:**
```php
if ($appointment->checkDoctorAvailability(456, "2024-01-15 10:00:00")) {
    echo "Doctor is available";
}
```

### `checkDoctorAvailabilityWithLock($doctorID, $dateTime)`
**Description:** Checks doctor availability with database lock to prevent race conditions.
**Sample Use Case:**
```php
// Used during appointment booking to prevent double-booking
$available = $appointment->checkDoctorAvailabilityWithLock(456, "2024-01-15 10:00:00");
```

### `createAppointment($patientID, $doctorID, $dateTime, $appointmentType, $reason)`
**Description:** High-level appointment creation with validation.
**Sample Use Case:**
```php
$success = $appointment->createAppointment(123, 456, "2024-01-15 10:00:00", "Cleaning", "Regular checkup");
```

### `getAvailableTimeSlots($doctorID, $date)`
**Description:** Gets available time slots for doctor on specific date.
**Sample Use Case:**
```php
$slots = $appointment->getAvailableTimeSlots(456, "2024-01-15");
// Returns ["09:00", "10:00", "14:00", ...]
```

### `getAllTimeSlotsWithStatus($doctorID, $date)`
**Description:** Gets all time slots with availability status.
**Sample Use Case:**
```php
$slots = $appointment->getAllTimeSlotsWithStatus(456, "2024-01-15");
// Returns [{"time": "09:00", "available": true}, ...]
```

### `rescheduleAppointment($appointmentID, $newDateTime)`
**Description:** Reschedules appointment to new date/time.
**Sample Use Case:**
```php
$success = $appointment->rescheduleAppointment(789, "2024-01-16 11:00:00");
```

### `cancelAppointment($appointmentID)`
**Description:** Cancels appointment and updates status.
**Sample Use Case:**
```php
$success = $appointment->cancelAppointment(789);
```

### `getCompletedAppointmentsByPatient($patientID)`
**Description:** Gets past completed appointments for patient.
**Sample Use Case:**
```php
$completed = $appointment->getCompletedAppointmentsByPatient(123);
```

### `getAppointmentById($appointmentID)`
**Description:** Gets specific appointment by ID.
**Sample Use Case:**
```php
$appointmentData = $appointment->getAppointmentById(789);
```

### `getAppointmentsByDoctor($doctorID)`
**Description:** Gets all appointments for specific doctor.
**Sample Use Case:**
```php
$doctorAppointments = $appointment->getAppointmentsByDoctor(456);
```

### `getUpcomingAppointmentsByDoctor($doctorID)`
**Description:** Gets future appointments for doctor.
**Sample Use Case:**
```php
$upcoming = $appointment->getUpcomingAppointmentsByDoctor(456);
```

### `getTodaysAppointmentsByDoctor($doctorID)`
**Description:** Gets today's appointments for doctor.
**Sample Use Case:**
```php
$today = $appointment->getTodaysAppointmentsByDoctor(456);
```

### `getAppointmentHistoryByDoctor($doctorID)`
**Description:** Gets past appointments for doctor.
**Sample Use Case:**
```php
$history = $appointment->getAppointmentHistoryByDoctor(456);
```

### `getAppointmentsByDoctorAndDateRange($doctorID, $startDate, $endDate)`
**Description:** Gets appointments for doctor within date range.
**Sample Use Case:**
```php
$weekAppointments = $appointment->getAppointmentsByDoctorAndDateRange(456, "2024-01-15", "2024-01-21");
```

### `getAppointmentsByDoctorAndDate($doctorID, $date)`
**Description:** Gets appointments for doctor on specific date.
**Sample Use Case:**
```php
$dayAppointments = $appointment->getAppointmentsByDoctorAndDate(456, "2024-01-15");
```

### `searchAppointmentsByPatient($patientID, $query)`
**Description:** Searches patient's appointments by keywords.
**Sample Use Case:**
```php
$results = $appointment->searchAppointmentsByPatient(123, "cleaning");
```

---

## Payment Model

### `__construct($db)`
**Description:** Initializes Payment model with database connection.
**Sample Use Case:**
```php
$payment = new Payment($db);
```

### `create()`
**Description:** Creates new payment record.
**Sample Use Case:**
```php
$payment->appointmentID = 789;
$payment->patientID = 123;
$payment->status = "Pending";
$payment->updatedBy = 456; // Staff ID
$payment->notes = "Initial consultation fee";
$success = $payment->create();
```

### `getPaymentsByPatient($patientID)`
**Description:** Gets all payments for a specific patient.
**Sample Use Case:**
```php
$payments = $payment->getPaymentsByPatient(123);
foreach ($payments as $pay) {
    echo "Amount: $" . $pay['total_amount'] . " - Status: " . $pay['Status'];
}
```

### `getPaymentByAppointment($appointmentID)`
**Description:** Gets payment record for specific appointment.
**Sample Use Case:**
```php
$paymentData = $payment->getPaymentByAppointment(789);
```

### `updateStatus($paymentID, $status, $updatedBy, $notes = null)`
**Description:** Updates payment status with audit trail.
**Sample Use Case:**
```php
$success = $payment->updateStatus(123, "Paid", 456, "Payment confirmed by front desk");
```

### `getPaymentWithBreakdown($paymentID)`
**Description:** Gets payment with detailed line items.
**Sample Use Case:**
```php
$paymentDetails = $payment->getPaymentWithBreakdown(123);
// Returns payment info + array of payment items
```

### `getTotalAmount($paymentID)`
**Description:** Calculates total amount from payment items.
**Sample Use Case:**
```php
$total = $payment->getTotalAmount(123);
echo "Total due: $" . $total;
```

### `ensurePaymentItems($paymentID)`
**Description:** Ensures payment has default items if none exist.
**Sample Use Case:**
```php
$payment->ensurePaymentItems(123); // Creates default consultation fee if no items
```

---

## PaymentItem Model

### `__construct($db)`
**Description:** Initializes PaymentItem model with database connection.
**Sample Use Case:**
```php
$paymentItem = new PaymentItem($db);
```

### `create()`
**Description:** Creates new payment line item.
**Sample Use Case:**
```php
$paymentItem->paymentID = 123;
$paymentItem->description = "Tooth Cleaning";
$paymentItem->amount = 75.00;
$paymentItem->quantity = 1;
$success = $paymentItem->create(); // Auto-calculates total
```

### `getItemsByPayment($paymentID)`
**Description:** Gets all line items for a payment.
**Sample Use Case:**
```php
$items = $paymentItem->getItemsByPayment(123);
foreach ($items as $item) {
    echo $item['Description'] . ": $" . $item['Total'];
}
```

### `update($paymentItemID, $description, $amount, $quantity)`
**Description:** Updates existing payment item.
**Sample Use Case:**
```php
$success = $paymentItem->update(456, "Deep Cleaning", 120.00, 1);
```

### `delete($paymentItemID)`
**Description:** Deletes specific payment item.
**Sample Use Case:**
```php
$success = $paymentItem->delete(456);
```

### `deleteByPayment($paymentID)`
**Description:** Deletes all items for a payment.
**Sample Use Case:**
```php
$success = $paymentItem->deleteByPayment(123);
```

### `createMultiple($paymentID, $items)`
**Description:** Creates multiple payment items in single transaction.
**Sample Use Case:**
```php
$items = [
    ["description" => "Consultation", "amount" => 50.00, "quantity" => 1],
    ["description" => "X-Ray", "amount" => 30.00, "quantity" => 2]
];
$success = $paymentItem->createMultiple(123, $items);
```

---

## PatientRecord Model

### `__construct($db)`
**Description:** Initializes PatientRecord model with database connection.
**Sample Use Case:**
```php
$patientRecord = new PatientRecord($db);
```

### `create()`
**Description:** Creates new patient medical record.
**Sample Use Case:**
```php
$patientRecord->patientID = 123;
$patientRecord->height = 175.5; // cm
$patientRecord->weight = 70.2;  // kg
$patientRecord->allergies = "Penicillin, Latex";
$patientRecord->lastVisit = "2024-01-10";
$success = $patientRecord->create();
```

### `createForPatient($patientID)`
**Description:** Creates empty medical record for new patient.
**Sample Use Case:**
```php
$success = $patientRecord->createForPatient(123);
// Creates record with null values to be filled later
```

### `findByPatientID($patientID)`
**Description:** Finds and loads patient record by patient ID.
**Sample Use Case:**
```php
if ($patientRecord->findByPatientID(123)) {
    echo "Height: " . $patientRecord->height . "cm";
    echo "Allergies: " . $patientRecord->allergies;
}
```

### `update()`
**Description:** Updates existing patient record.
**Sample Use Case:**
```php
$patientRecord->height = 176.0;
$patientRecord->weight = 71.0;
$patientRecord->allergies = "Penicillin, Latex, Nuts";
$success = $patientRecord->update();
```

### `updateLastVisit($patientID, $visitDate = null)`
**Description:** Updates last visit date for patient.
**Sample Use Case:**
```php
$patientRecord->updateLastVisit(123); // Sets to today
$patientRecord->updateLastVisit(123, "2024-01-15"); // Sets to specific date
```

### `getRecordByPatientID($patientID)`
**Description:** Gets patient record data as array.
**Sample Use Case:**
```php
$record = $patientRecord->getRecordByPatientID(123);
if ($record) {
    echo "Patient height: " . $record['Height'];
}
```

---

## DentalChart Model

### `__construct($db)`
**Description:** Initializes DentalChart model with database connection.
**Sample Use Case:**
```php
$dentalChart = new DentalChart($db);
```

### `create()`
**Description:** Creates new dental chart for patient.
**Sample Use Case:**
```php
$dentalChart->patientID = 123;
$dentalChart->dentistID = 456; // or null
$success = $dentalChart->create();
```

### `findByPatientID($patientID)`
**Description:** Finds latest dental chart for patient.
**Sample Use Case:**
```php
if ($dentalChart->findByPatientID(123)) {
    echo "Chart ID: " . $dentalChart->dentalChartID;
    echo "Created: " . $dentalChart->createdAt;
}
```

### `findByID($chartID)`
**Description:** Finds dental chart by chart ID.
**Sample Use Case:**
```php
if ($dentalChart->findByID(789)) {
    echo "Patient ID: " . $dentalChart->patientID;
}
```

### `createForPatient($patientID, $dentistID = null)`
**Description:** Creates dental chart for patient if none exists.
**Sample Use Case:**
```php
$success = $dentalChart->createForPatient(123, 456);
// Returns true if chart created or already exists
```

### `getChartsByPatientID($patientID)`
**Description:** Gets all dental charts for patient with dentist information.
**Sample Use Case:**
```php
$charts = $dentalChart->getChartsByPatientID(123);
foreach ($charts as $chart) {
    echo "Chart from " . $chart['CreatedAt'] . " by Dr. " . $chart['FirstName'];
}
```

---

## DentalChartItem Model

### `__construct($db)`
**Description:** Initializes DentalChartItem model with database connection.
**Sample Use Case:**
```php
$dentalChartItem = new DentalChartItem($db);
```

### `create()`
**Description:** Creates new dental chart item for specific tooth.
**Sample Use Case:**
```php
$dentalChartItem->dentalChartID = 789;
$dentalChartItem->toothNumber = "14"; // Upper left first molar
$dentalChartItem->status = "Filled";
$dentalChartItem->notes = "Amalgam filling, no issues";
$success = $dentalChartItem->create();
```

### `update()`
**Description:** Updates existing dental chart item.
**Sample Use Case:**
```php
$dentalChartItem->status = "Crown";
$dentalChartItem->notes = "Porcelain crown placed";
$success = $dentalChartItem->update();
```

### `findByChartAndTooth($chartID, $toothNumber)`
**Description:** Finds specific tooth record in dental chart.
**Sample Use Case:**
```php
if ($dentalChartItem->findByChartAndTooth(789, "14")) {
    echo "Tooth 14 status: " . $dentalChartItem->status;
}
```

### `getTeethByChartID($chartID)`
**Description:** Gets all tooth records for dental chart.
**Sample Use Case:**
```php
$teeth = $dentalChartItem->getTeethByChartID(789);
foreach ($teeth as $tooth) {
    echo "Tooth " . $tooth['ToothNumber'] . ": " . $tooth['Status'];
}
```

### `updateOrCreate($chartID, $toothNumber, $status, $notes)`
**Description:** Updates existing tooth or creates new record.
**Sample Use Case:**
```php
$success = $dentalChartItem->updateOrCreate(789, "14", "Crown", "New porcelain crown");
// Smart method that handles both updates and creation
```

### `initializeAllTeeth($chartID)`
**Description:** Creates records for all 32 adult teeth.
**Sample Use Case:**
```php
$success = $dentalChartItem->initializeAllTeeth(789);
// Creates empty records for teeth 1-32
```

### `getToothName($toothNumber)`
**Description:** Static method to get tooth name from number.
**Sample Use Case:**
```php
$name = DentalChartItem::getToothName("14");
echo $name; // "Upper Left First Molar"
```

---

## AppointmentReport Model

### `__construct($db)`
**Description:** Initializes AppointmentReport model with database connection.
**Sample Use Case:**
```php
$appointmentReport = new AppointmentReport($db);
```

### `create()`
**Description:** Creates new appointment report.
**Sample Use Case:**
```php
$appointmentReport->patientRecordID = 456;
$appointmentReport->appointmentID = 789;
$appointmentReport->oralNotes = "Patient reports tooth sensitivity";
$appointmentReport->diagnosis = "Mild gingivitis";
$appointmentReport->xrayImages = null;
$success = $appointmentReport->create();
```

### `createForAppointment($appointmentID, $patientRecordID)`
**Description:** Creates empty report for appointment.
**Sample Use Case:**
```php
$success = $appointmentReport->createForAppointment(789, 456);
// Creates blank report to be filled later
```

### `findByAppointmentID($appointmentID)`
**Description:** Finds and loads report by appointment ID.
**Sample Use Case:**
```php
if ($appointmentReport->findByAppointmentID(789)) {
    echo "Diagnosis: " . $appointmentReport->diagnosis;
    echo "Notes: " . $appointmentReport->oralNotes;
}
```

### `update()`
**Description:** Updates existing appointment report.
**Sample Use Case:**
```php
$appointmentReport->oralNotes = "Patient shows improvement";
$appointmentReport->diagnosis = "Gingivitis resolved";
$success = $appointmentReport->update();
```

### `getReportByAppointmentID($appointmentID)`
**Description:** Gets report data with patient record information.
**Sample Use Case:**
```php
$report = $appointmentReport->getReportByAppointmentID(789);
if ($report) {
    echo "Patient height: " . $report['Height'];
    echo "Diagnosis: " . $report['Diagnosis'];
}
```

### `getReportsByPatientRecordID($patientRecordID)`
**Description:** Gets all reports for patient record with appointment details.
**Sample Use Case:**
```php
$reports = $appointmentReport->getReportsByPatientRecordID(456);
foreach ($reports as $report) {
    echo "Visit on " . $report['DateTime'] . ": " . $report['Diagnosis'];
}
```

### `deleteByAppointmentID($appointmentID)`
**Description:** Deletes appointment report by appointment ID.
**Sample Use Case:**
```php
$success = $appointmentReport->deleteByAppointmentID(789);
```

---

## Notes

### Empty Model Files
The following model files were found to be empty and likely represent placeholder functionality for future development:
- `Invoice.php` - Future invoice generation functionality
- `Prescription.php` - Future prescription management
- `Staff.php` - Future general staff management beyond doctors
- `UserType.php` - Future user type management system

### Security Features
All models include:
- SQL injection prevention through prepared statements
- XSS protection through data sanitization
- Transaction support for data integrity
- Error logging for debugging and monitoring

### Best Practices Implemented
- Consistent naming conventions
- Comprehensive error handling
- Database transaction management
- Input validation and sanitization
- Separation of concerns between controllers and models
- RESTful API design patterns 