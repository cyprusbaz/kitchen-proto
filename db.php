<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "baldugamyba";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Nepavyko prisijungti: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
