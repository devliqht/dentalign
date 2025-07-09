<?php

abstract class Controller
{
    protected $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    /**
     * Check if user is authenticated
     */
    protected function requireAuth($redirectTo = "/login")
    {
        if (!$this->isAuthenticated()) {
            $this->redirect(BASE_URL . $redirectTo);
            exit();
        }
    }

    protected function isAuthenticated()
    {
        return isset($_SESSION["user_id"]);
    }

    /**
     * Get current authenticated user
     */
    protected function getAuthUser()
    {
        if ($this->isAuthenticated()) {
            return [
                "id" => $_SESSION["user_id"],
                "name" => $_SESSION["user_name"] ?? "",
                "email" => $_SESSION["user_email"] ?? "",
                "type" => $_SESSION["user_type"] ?? "",
                "staff_type" => $_SESSION["staff_type"] ?? NULL,
            ];
        }
        return null;
    }

    /**
     * Gets employee type (Doctor/DentalAssistant)
     */
    protected function hasStaffType($staffType)
    {
        return ($this->getAuthUser()["staff_type"] ?? "") === $staffType;
    }

    /**
     * Require specific employee type
     */

    protected function requireStaffType($staffType, $redirectTo = "/login")
    {
        if (!$this->hasStaffType($staffType)){
            $this->redirect(BASE_URL . $redirectTo);
            exit();
        }
    }

    /**
     * Check if user has specific role
     */
    protected function hasRole($role)
    {
        return ($this->getAuthUser()["type"] ?? "") === $role;
    }

    /**
     * Require specific role
     */
    protected function requireRole($role, $redirectTo = "/login")
    {
        if (!$this->hasRole($role)) {
            $this->redirect(BASE_URL . $redirectTo);
            exit();
        }
    }

    /**
     * Validate request data
     */
    protected function validate($data, $rules, $messages = [])
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $rules_array = explode("|", $rule);

            foreach ($rules_array as $single_rule) {
                if ($single_rule === "required" && empty($data[$field])) {
                    $errors[$field] =
                        $messages[$field] ?? "The {$field} field is required.";
                    break;
                }

                if (strpos($single_rule, "min:") === 0) {
                    $min_length = (int) substr($single_rule, 4);
                    if (strlen($data[$field] ?? "") < $min_length) {
                        $errors[$field] =
                            $messages[$field] ??
                            "The {$field} field must be at least {$min_length} characters.";
                        break;
                    }
                }

                if (
                    $single_rule === "email" &&
                    !filter_var($data[$field] ?? "", FILTER_VALIDATE_EMAIL)
                ) {
                    $errors[$field] =
                        $messages[$field] ??
                        "The {$field} field must be a valid email address.";
                    break;
                }
            }
        }

        if (!empty($errors)) {
            $_SESSION["validation_errors"] = $errors;
            $_SESSION["old_input"] = $data;
            return false;
        }

        return true;
    }

    /**
     * Get validation errors
     */
    protected function getValidationErrors()
    {
        $errors = $_SESSION["validation_errors"] ?? [];
        unset($_SESSION["validation_errors"]);
        return $errors;
    }

    /**
     * Get old input data
     */
    protected function getOldInput($field = null)
    {
        $oldInput = $_SESSION["old_input"] ?? [];

        if ($field) {
            $value = $oldInput[$field] ?? "";
            if (isset($_SESSION["old_input"])) {
                unset($_SESSION["old_input"][$field]);
                if (empty($_SESSION["old_input"])) {
                    unset($_SESSION["old_input"]);
                }
            }
            return $value;
        }

        unset($_SESSION["old_input"]);
        return $oldInput;
    }

    /**
     * Redirect helper
     */
    protected function redirect($url, $statusCode = 302)
    {
        http_response_code($statusCode);
        header("Location: " . $url);
        exit();
    }

    /**
     * Redirect back with error
     */
    protected function redirectBack($error = null, $success = null)
    {
        if ($error) {
            $_SESSION["error"] = $error;
        }
        if ($success) {
            $_SESSION["success"] = $success;
        }

        $referer = $_SERVER["HTTP_REFERER"] ?? BASE_URL;
        $this->redirect($referer);
    }

    /**
     * Render view using LayoutHelper
     */
    protected function view($viewFile, $data = [], $layoutConfig = [])
    {
        require_once __DIR__ . "/../helpers/LayoutHelper.php";

        $data["errors"] = $this->getValidationErrors();
        $data["old"] = $this->getOldInput();
        $data["auth"] = $this->getAuthUser();

        LayoutHelper::render($viewFile, $data, $layoutConfig);
    }

    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header("Content-Type: application/json");
        echo json_encode($data);
        exit();
    }

    /**
     * Get request method
     */
    protected function getRequestMethod()
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    /**
     * Check if request is POST
     */
    protected function isPost()
    {
        return $this->getRequestMethod() === "POST";
    }

    /**
     * Check if request is GET
     */
    protected function isGet()
    {
        return $this->getRequestMethod() === "GET";
    }

    /**
     * Sanitize input data
     */
    protected function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([$this, "sanitize"], $data);
        }

        return htmlspecialchars(strip_tags($data), ENT_QUOTES, "UTF-8");
    }

    /**
     * CSRF token generation and validation
     */
    protected function generateCsrfToken()
    {
        if (!isset($_SESSION["csrf_token"])) {
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        }
        return $_SESSION["csrf_token"];
    }

    /**
     * Validate CSRF token
     */
    protected function validateCsrfToken($token)
    {
        return hash_equals($_SESSION["csrf_token"] ?? "", $token);
    }

    /**
     * Check method type and CSRF for forms
     */
    protected function validateRequest(
        $requiredMethod = "POST",
        $checkCsrf = true
    ) {
        if ($this->getRequestMethod() !== $requiredMethod) {
            $this->redirectBack("Invalid request method");
        }

        if ($checkCsrf && $requiredMethod === "POST") {
            $token = $_POST["csrf_token"] ?? "";
            if (!$this->validateCsrfToken($token)) {
                $this->redirectBack("Invalid security token");
            }
        }
    }
}
