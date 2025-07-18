<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'ttsfood';

$conn = new mysqli($host, $user, $pass, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
