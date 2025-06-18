<?php
require_once "app/core/Router.php";

$router = new Router();
$router->get("", "AuthController@showLogin");
$router->get("login", "AuthController@showLogin");
$router->post("login", "AuthController@login");
$router->get("signup", "AuthController@showSignup");
$router->post("signup", "AuthController@signup");
$router->get("home", "AuthController@home");
$router->get("logout", "AuthController@logout");

$url = $_GET["url"] ?? "";
$router->handleRequest($url);
?>
