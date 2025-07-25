<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"));
$name = $data->name;
$email = $data->email;
$password = password_hash($data->password, PASSWORD_DEFAULT);
$phone = $data->phone ?? '';
$address = $data->address ?? '';

$check = "SELECT * FROM tbl_user WHERE email = '$email'";
$result = $conn->query($check);

if ($result->num_rows > 0) {
    echo json_encode([
        'status' => false,
        'message' => 'Email đã tồn tại'
    ]);
} else {
    $sql = "INSERT INTO tbl_user (name, email, password, phone, address, role)
            VALUES ('$name', '$email', '$password', '$phone', '$address', 3)";
    if ($conn->query($sql) === TRUE) {
        echo json_encode([
            'status' => true,
            'message' => 'Đăng ký thành công'
        ]);
    } else {
        echo json_encode([
            'status' => false,
            'message' => 'Lỗi server: ' . $conn->error
        ]);
    }
}
?>
