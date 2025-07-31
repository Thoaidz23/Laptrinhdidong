<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
  http_response_code(400);
  echo json_encode(["error" => "Invalid JSON or no input"]);
  exit;
}
if (!$data || !isset($data['orderId'], $data['requestId'], $data['orderInfo'], $data['amount'])) {
    echo json_encode(["error" => "Invalid JSON or missing required fields"]);
    http_response_code(400);
    exit;
}
file_put_contents("debug.log", print_r($data, true));
$endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
$partnerCode = 'MOMO';
$accessKey = 'F8BBA842ECF85';
$secretKey = 'K951B6PE1waDMi640xX08PD3vg6EkVlz';

$orderId = $data['code_order'];
$requestId = $orderId;
$orderInfo = $data['orderInfo'];
$amount = $data['amount'];
$returnUrl = "ttsfood://order-history"; // dùng để redirect về app
$notifyUrl = "https://80b24e207785.ngrok-free.app/api/momo_ipn";

$requestType = "payWithMethod";
$extraData = base64_encode(json_encode($data['userData']));
$paymentCode = $data['paymentCode'] ?? "";

$rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$notifyUrl&orderId=$orderId"
    . "&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$returnUrl"
    . "&requestId=$requestId&requestType=$requestType";

$signature = hash_hmac("sha256", $rawHash, $secretKey);

$requestData = array(
    'partnerCode' => $partnerCode,
    'partnerName' => "TTSShop",
    'storeId' => "TTSStore",
    'requestId' => $requestId,
    'amount' => "$amount",
    'orderId' => $orderId,
    'orderInfo' => $orderInfo,
    'redirectUrl' => $returnUrl,
    'ipnUrl' => $notifyUrl,
    'lang' => 'vi',
    'requestType' => $requestType,
    'autoCapture' => true,
    'extraData' => $extraData,
    'orderGroupId' => '',
    'signature' => $signature,
    'paymentCode' => $paymentCode
);

$ch = curl_init($endpoint);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen(json_encode($requestData))
));

$response = curl_exec($ch);
curl_close($ch);

// Trả về response từ MoMo
echo $response;
