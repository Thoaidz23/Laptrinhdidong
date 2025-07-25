<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"));
$id_user = $data->id_user;
$currentPassword = $data->current_password;
$newPassword = $data->new_password;

// Lấy user hiện tại từ DB
$sql = "SELECT * FROM tbl_user WHERE id_user = $id_user";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo json_encode(['status' => false, 'message' => 'Người dùng không tồn tại']);
    exit;
}

$user = $result->fetch_assoc();

// Kiểm tra mật khẩu hiện tại có đúng không
if (!password_verify($currentPassword, $user['password'])) {
    echo json_encode(['status' => false, 'message' => 'Mật khẩu hiện tại không đúng']);
    exit;
}

// Cập nhật mật khẩu mới
$newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);
$update = "UPDATE tbl_user SET password = '$newPasswordHashed' WHERE id_user = $id_user";

if ($conn->query($update) === TRUE) {
    echo json_encode(['status' => true, 'message' => 'Đổi mật khẩu thành công']);
} else {
    echo json_encode(['status' => false, 'message' => 'Đổi mật khẩu thất bại']);
}
?>
