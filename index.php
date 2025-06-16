<?php
session_start();

// Define base URL configuration
define('BASE_URL', '/dentalign');

// Include the database connection
require_once 'config/DB_Connect.php';

// Include the router
require_once 'app/core/Router.php';

// Initialize the router
$router = new Router();

// Define routes
$router->get('', 'AuthController@showLogin');
$router->get('login', 'AuthController@showLogin');
$router->post('login', 'AuthController@login');
$router->get('signup', 'AuthController@showSignup');
$router->post('signup', 'AuthController@signup');
$router->get('home', 'AuthController@home');
$router->get('logout', 'AuthController@logout');

// Handle the request
$url = $_GET['url'] ?? '';
$router->handleRequest($url);
?>
