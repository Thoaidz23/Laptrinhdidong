<?php
include 'db.php'; // Kết nối cơ sở dữ liệu

// Thiết lập header để cho phép gọi từ Flutter hoặc frontend
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

// Nhận dữ liệu từ Flutter gửi lên (JSON)
$data = json_decode(file_get_contents("php://input"));

$email = $data->email ?? '';
$password = $data->password ?? '';

// Kiểm tra dữ liệu rỗng
if (empty($email) || empty($password)) {
    echo json_encode([
        'status' => false,
        'message' => 'Email hoặc mật khẩu không được để trống',
    ]);
    exit;
}

// Truy vấn CSDL để kiểm tra đăng nhập
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
        'user' => [
            'id_user' => (int)$user['id_user'],
            'name' => $user['name'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'address' => $user['address'],
        ]
    ]);
} else {
    echo json_encode([
        'status' => false,
        'message' => 'Email hoặc mật khẩu không đúng'
    ]);
}
?>
