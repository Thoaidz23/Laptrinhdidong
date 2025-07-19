<?php
include 'db.php'; // Kết nối CSDL

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

$data = json_decode(file_get_contents("php://input"));

$email = $data->email ?? '';
$password = $data->password ?? '';

if (empty($email) || empty($password)) {
    echo json_encode([
        'status' => false,
        'message' => 'Email hoặc mật khẩu không được để trống',
    ]);
    exit;
}

$sql = "SELECT * FROM tbl_user WHERE email = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode([
        'status' => true,
        'message' => 'Đăng nhập thành công',
        'user' => $user
    ]);
} else {
    echo json_encode([
        'status' => false,
        'message' => 'Email hoặc mật khẩu không đúng'
    ]);
}
?>
