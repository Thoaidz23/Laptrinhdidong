<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$order_id = $data['order_id'] ?? '';

if (!$order_id) {
    echo json_encode(["status" => false, "error" => "Thiếu order_id"]);
    exit;
}

// Lấy access token
$ch = curl_init("https://api-m.sandbox.paypal.com/v1/oauth2/token");
curl_setopt_array($ch, [
    CURLOPT_USERPWD => PAYPAL_CLIENT_ID . ":" . PAYPAL_SECRET,
    CURLOPT_POSTFIELDS => "grant_type=client_credentials",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ["Accept: application/json"]
]);
$response = curl_exec($ch);
curl_close($ch);
$tokenData = json_decode($response, true);
$access_token = $tokenData['access_token'] ?? null;

if (!$access_token) {
    echo json_encode(["status" => false, "error" => "Không lấy được access token"]);
    exit;
}

// Capture đơn hàng
$ch = curl_init("https://api-m.sandbox.paypal.com/v2/checkout/orders/$order_id/capture");
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $access_token"
    ]
]);
$response = curl_exec($ch);
curl_close($ch);
$result = json_decode($response, true);

if (isset($result['status']) && $result['status'] === 'COMPLETED') {
    echo json_encode(["status" => true, "message" => "Thanh toán thành công"]);
} else {
    echo json_encode(["status" => false, "error" => "Capture thất bại", "paypal_response" => $result]);
}
