<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$ip = gethostbyname(gethostname());

echo json_encode([
    "ip" => $ip
]);
