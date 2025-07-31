<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require 'db.php'; // PAYPAL_CLIENT_ID, PAYPAL_SECRET

$data = json_decode(file_get_contents("php://input"), true);
$amount_usd = $data['amount_usd'] ?? 0;

if ($amount_usd <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "Số tiền USD không hợp lệ"]);
    exit;
}

// 1. Lấy access_token từ PayPal
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/oauth2/token");
curl_setopt($ch, CURLOPT_USERPWD, PAYPAL_CLIENT_ID . ":" . PAYPAL_SECRET);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
$response = curl_exec($ch);
if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode(["error" => "Lỗi lấy access_token: " . curl_error($ch)]);
    curl_close($ch);
    exit;
}
curl_close($ch);

$result = json_decode($response, true);
$access_token = $result['access_token'] ?? null;
if (!$access_token) {
    http_response_code(500);
    echo json_encode(["error" => "Không lấy được access_token"]);
    exit;
}

// 2. Tạo đơn hàng
$body = [
    "intent" => "CAPTURE",
    "purchase_units" => [[
        "amount" => [
            "currency_code" => "USD",
            "value" => number_format($amount_usd, 2, '.', '')
        ]
    ]],
    "application_context" => [
        "return_url" => "https://example.com/success",
        "cancel_url" => "https://example.com/cancel"
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v2/checkout/orders");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $access_token"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
$response = curl_exec($ch);
if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode(["error" => "Lỗi tạo đơn hàng: " . curl_error($ch)]);
    curl_close($ch);
    exit;
}
curl_close($ch);

$order = json_decode($response, true);
$approve_url = "";
if (isset($order['links'])) {
    foreach ($order['links'] as $link) {
        if ($link['rel'] == 'approve') {
            $approve_url = $link['href'];
            break;
        }
    }
}

if (!$approve_url) {
    http_response_code(500);
    echo json_encode(["error" => "Không lấy được link phê duyệt từ PayPal"]);
    exit;
}

echo json_encode([
    "status" => true,
    "url" => $approve_url,
    "order_id" => $order['id']
]);
