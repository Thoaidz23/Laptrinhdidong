<?php
// create-payment.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Include PayPal credentials
require 'db.php'; // PAYPAL_CLIENT_ID, PAYPAL_SECRET, PAYPAL_API_BASE

$data = json_decode(file_get_contents("php://input"), true);
$amount = $data['amount'] ?? 0;

if ($amount <= 0) {
    echo json_encode(['error' => 'Invalid amount']);
    exit;
}

// === 1. Get Access Token ===
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, PAYPAL_API_BASE . "/v1/oauth2/token");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, PAYPAL_CLIENT_ID . ":" . PAYPAL_SECRET);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Accept: application/json",
    "Accept-Language: en_US"
]);
$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo json_encode(['error' => 'Curl error: ' . curl_error($ch)]);
    exit;
}
curl_close($ch);

$result = json_decode($response, true);
$access_token = $result['access_token'] ?? null;
if (!$access_token) {
    echo json_encode(['error' => 'Failed to get access token']);
    exit;
}

// === 2. Create Order ===
$orderData = [
    "intent" => "CAPTURE",
    "purchase_units" => [[
        "amount" => [
            "currency_code" => "USD",
            "value" => number_format($amount, 2, '.', '')
        ]
    ]],
    "application_context" => [
        "return_url" => "https://example.com/success", // You can handle this in Flutter WebView
        "cancel_url" => "https://example.com/cancel"
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, PAYPAL_API_BASE . "/v2/checkout/orders");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $access_token"
]);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo json_encode(['error' => 'Curl error: ' . curl_error($ch)]);
    exit;
}
curl_close($ch);

$result = json_decode($response, true);

if (isset($result['id'])) {
    // Trả về link approval để redirect trong WebView
    foreach ($result['links'] as $link) {
        if ($link['rel'] === 'approve') {
            echo json_encode([
                'orderID' => $result['id'],
                'approvalUrl' => $link['href']
            ]);
            exit;
        }
    }
    echo json_encode(['error' => 'Approval link not found']);
} else {
    echo json_encode(['error' => 'Failed to create PayPal order']);
}
