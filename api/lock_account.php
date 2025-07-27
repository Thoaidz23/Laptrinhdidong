<?php
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$id_user = $data['id_user'] ?? null;

if (!$id_user) {
    echo json_encode(["status" => false, "message" => "Thiếu ID người dùng"]);
    exit;
}

// Cập nhật cột lock = 1
$stmt = $conn->prepare("UPDATE tbl_user SET lock_account = 1 WHERE id_user = ?");
$stmt->bind_param("i", $id_user);

if ($stmt->execute()) {
    echo json_encode(["status" => true, "message" => "Tài khoản đã bị khóa"]);
} else {
    echo json_encode(["status" => false, "message" => "Không thể khóa tài khoản"]);
}
?>
