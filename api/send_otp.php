<?php

require 'PHPMailer/PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer/PHPMailer-master/src/SMTP.php';
require 'PHPMailer/PHPMailer-master/src/Exception.php';

require 'db.php';

use PHPMailer\PHPMailer\PHPMailer;

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';
$purpose = $data['purpose'] ?? 'forgot_password';

if (empty($email)) {
    echo json_encode(["status" => false, "message" => "Email không hợp lệ"]);
    exit;
}

// Tạo mã OTP
$otp = rand(100000, 999999);
$created_at = date("Y-m-d H:i:s");
$expires_at = date("Y-m-d H:i:s", strtotime("+5 minutes"));

// Lưu vào bảng tbl_otp_requests
$stmt = $conn->prepare("INSERT INTO tbl_otp_requests (email, otp_code, purpose, created_at, expires_at, is_used)
                        VALUES (?, ?, ?, ?, ?, 0)");
$stmt->bind_param("sssss", $email, $otp, $purpose, $created_at, $expires_at);
$stmt->execute();

// Gửi mail
$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'truongthuong1512@gmail.com';
$mail->Password = 'lljvbafslfcjbltv';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('truongthuong1512@gmail.com', 'TTS Food');
$mail->addAddress($email);
$mail->Subject = 'Mã xác nhận đặt lại mật khẩu';
$mail->Body = "Mã OTP của bạn là: $otp. Mã có hiệu lực trong 5 phút.";

if ($mail->send()) {
    echo json_encode(["status" => true, "message" => "Đã gửi OTP"]);
} else {
    echo json_encode(["status" => false, "message" => "Không thể gửi email"]);
}
?>
