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
define('PAYPAL_CLIENT_ID', 'AY1zJ3XwQM_5tDP7xJB9RJmPiOqQ5QYT1Z2jmP7PxmB7SsQ4GoDdDBdX3gCaVcGjm1-6mrAoV_0gs9Ab');
define('PAYPAL_SECRET', 'EFr43D-Uaoo24EPjuUN_4lMaNzA6CAqKVyOD0bdJt9Ov2vDh86Ry3k2VACp4_Me0Y10K1163nHxFumxU'); // <- Copy từ dashboard
define('PAYPAL_API_BASE', 'https://api-m.sandbox.paypal.com');?>
