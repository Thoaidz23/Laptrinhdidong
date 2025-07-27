<?php
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'];
$otp = $data['otp'];
$purpose = $data['purpose'] ?? 'forgot_password';

$stmt = $conn->prepare("SELECT * FROM tbl_otp_requests 
                        WHERE email = ? AND otp_code = ? AND purpose = ? AND is_used = 0 
                        ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("sss", $email, $otp, $purpose);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(["status" => false, "message" => "OTP không đúng hoặc đã dùng"]);
    exit;
}

$row = $result->fetch_assoc();
if (strtotime($row['expires_at']) < time()) {
    echo json_encode(["status" => false, "message" => "OTP đã hết hạn"]);
    exit;
}

// Đánh dấu đã dùng
$update = $conn->prepare("UPDATE tbl_otp_requests SET is_used = 1 WHERE id = ?");
$update->bind_param("i", $row['id']);
$update->execute();

echo json_encode(["status" => true, "message" => "Xác thực OTP thành công"]);
