<?php
require 'db.php'; // Bao gồm kết nối DB và các biến PAYPAL_CLIENT_ID, PAYPAL_SECRET

$data = json_decode(file_get_contents("php://input"), true);
$amount_vnd = $data['amount'] ?? 0;

if ($amount_vnd <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "Số tiền không hợp lệ"]);
    exit;
}

// 1. Get access_token từ PayPal
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/oauth2/token");
curl_setopt($ch, CURLOPT_USERPWD, PAYPAL_CLIENT_ID . ":" . PAYPAL_SECRET);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
$response = curl_exec($ch);
if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode(["error" => "Lỗi cURL access_token: " . curl_error($ch)]);
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

// 2. Convert VND -> USD
$rateJson = file_get_contents("https://api.exchangerate.host/convert?from=VND&to=USD&amount=$amount_vnd");
$rateResult = json_decode($rateJson, true);
$amount_usd = round($rateResult['result'] ?? 0, 2);
if ($amount_usd <= 0) {
    http_response_code(500);
    echo json_encode(["error" => "Không lấy được tỷ giá từ exchangerate.host"]);
    exit;
}

// 3. Create PayPal order
$body = [
    "intent" => "CAPTURE",
    "purchase_units" => [[
        "amount" => [
            "currency_code" => "USD",
            "value" => number_format($amount_usd, 2, '.', '')
        ]
    ]],
    "application_context" => [
        "return_url" => "http://yourdomain.com/api/paypal-success.php",
        "cancel_url" => "http://yourdomain.com/api/paypal-cancel.php"
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
    echo json_encode(["error" => "Lỗi cURL tạo đơn hàng: " . curl_error($ch)]);
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
    echo json_encode(["error" => "Không lấy được liên kết phê duyệt từ PayPal"]);
    exit;
}

echo json_encode([
    "status" => true,
    "url" => $approve_url,
    "order_id" => $order['id']
]);
