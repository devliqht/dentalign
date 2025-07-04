<?php
session_start();

require_once "config/DB_Connect.php";
require_once "config/Base_Url.php";
require_once "routes/web.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
