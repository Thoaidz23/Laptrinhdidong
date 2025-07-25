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
<?php
define('PAYPAL_CLIENT_ID', 'Af5lqsQmtCOeP8wS_2L5VTg3wMliyzbvnakxrNTy2U--aVBB938BjRc-Hvq3OOSZPyRMhD4e74v5qPu3');
define('PAYPAL_SECRET', 'YOUR_SECRET_HERE'); // <- Copy từ dashboard
define('PAYPAL_API_BASE', 'https://api-m.sandbox.paypal.com');?>
