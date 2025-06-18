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
    "home" => "DisplayHomePage",
    "logout" => "LogoutUser",
]);

$router->group("POST", "AuthController", [
    "login" => "LoginUser",
    "signup" => "SignupUser",
]);

$url = $_GET["url"] ?? "";
$router->handleRequest($url);
?>
