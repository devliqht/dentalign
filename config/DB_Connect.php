<?php
// define("DB_HOST", "localhost");
// define("DB_USER", "root");
// define("DB_PASS", "");
// define("DB_NAME", "dentalign");
// define("DB_SOCKET", "/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock");

define("DB_HOST", "localhost:3306");
define("DB_USER", "s22103604_dentalign");
define("DB_PASS", "sonicdravice");
define("DB_NAME", "s22103604_dentalign");
define("DB_SOCKET", "/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock");

$conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, 3306, DB_SOCKET);

if ($conn->connect_error) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, 3306);
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
