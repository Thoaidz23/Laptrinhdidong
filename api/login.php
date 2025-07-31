<?php


include 'db.php'; // Kết nối cơ sở dữ liệu
ini_set('display_errors', 1);
error_reporting(E_ALL);


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
$sql = "SELECT * FROM tbl_user WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // ✅ Kiểm tra nếu tài khoản bị khóa
    if ((int)$user['lock_account'] === 1) {
        echo json_encode([
            'status' => false,
            'message' => 'Tài khoản của bạn đã bị khóa. Vui lòng sử dụng chức năng Quên mật khẩu để mở lại.',
        ]);
        exit;
    }

    // ✅ Kiểm tra mật khẩu
    if (password_verify($password, $user['password'])) {
        echo json_encode([
            'status' => true,
            'message' => 'Đăng nhập thành công',
            'user' => [
                'id_user' => (int)$user['id_user'],
                'name' => $user['name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'address' => $user['address'],
                'lock_account' => (int)$user['lock_account'], // <-- Truyền về luôn cho Flutter dùng
            ]
        ]);
    } else {
        echo json_encode([
            'status' => false,
            'message' => 'Email hoặc mật khẩu không đúng'
        ]);
    }
} else {
    echo json_encode([
        'status' => false,
        'message' => 'Email hoặc mật khẩu không đúng'
    ]);
}
?>
