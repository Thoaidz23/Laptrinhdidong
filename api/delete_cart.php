<?php
include 'db.php';

header('Content-Type: application/json');

// Lấy dữ liệu từ Flutter gửi lên
$data = json_decode(file_get_contents("php://input"), true);

$id_user = $data['id_user'] ?? null;
$id_product = $data['id_product'] ?? null;

$response = [];

if ($id_user && $id_product) {
    $sql = "DELETE FROM tbl_cart WHERE id_user = ? AND id_product = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_user, $id_product);

    if ($stmt->execute()) {
        $response['status'] = true;
        $response['message'] = 'Xóa sản phẩm thành công';
    } else {
        $response['status'] = false;
        $response['message'] = 'Xóa thất bại: ' . $stmt->error;
    }

    $stmt->close();
} else {
    $response['status'] = false;
    $response['message'] = 'Thiếu tham số id_user hoặc id_product';
}

$conn->close();
echo json_encode($response);
?>
