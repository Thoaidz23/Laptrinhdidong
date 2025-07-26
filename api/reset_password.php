<?php
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'];
$newPass = password_hash($data['new_password'], PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE tbl_user SET password = ?, lock_account = 0 WHERE email = ?");
$stmt->bind_param("ss", $newPass, $email);

if ($stmt->execute()) {
    echo json_encode(["status" => true, "message" => "Đổi mật khẩu thành công"]);
} else {
    echo json_encode(["status" => false, "message" => "Không thể đổi mật khẩu"]);
}
