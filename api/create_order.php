<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$id_user = $data['id_user'];
$name_user = $data['name_user'];
$address = $data['address'];
$phone = $data['phone'];
$method = (int)$data['method']; // 0 = COD, 1 = bank transfer
$cart = $data['cart'];

$code_order = uniqid('ORD');
$total_price = 0;
foreach ($cart as $item) {
    $total_price += $item['quantity'] * $item['price'];
}

// status: 0 = chờ xác nhận
$status = 0;

// paystatus: nếu là ngân hàng (method = 1) => đã thanh toán (1), COD => chưa thanh toán (0)
$paystatus = ($method == 1) ? 1 : 0;

$date = date('Y-m-d H:i:s');

// Thêm vào bảng tbl_order
$sql_order = "INSERT INTO tbl_order (code_order, id_user, total_price, status, paystatus, method, date, name_user, address, phone)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt_order = $conn->prepare($sql_order);
$stmt_order->bind_param("sidiiissss", $code_order, $id_user, $total_price, $status, $paystatus, $method, $date, $name_user, $address, $phone);

if ($stmt_order->execute()) {
    foreach ($cart as $item) {
        $id_product = $item['id_product'];
        $quantity = $item['quantity'];

        $sql_detail = "INSERT INTO tbl_order_detail (code_order, id_product, quantity_product)
                       VALUES (?, ?, ?)";
        $stmt_detail = $conn->prepare($sql_detail);
        $stmt_detail->bind_param("sii", $code_order, $id_product, $quantity);
        $stmt_detail->execute();
    }

    // Xóa giỏ hàng
    $sql_delete_cart = "DELETE FROM tbl_cart WHERE id_user = ?";
    $stmt_del = $conn->prepare($sql_delete_cart);
    $stmt_del->bind_param("i", $id_user);
    $stmt_del->execute();

    echo json_encode(["status" => true, "message" => "Đặt hàng thành công"]);
} else {
    echo json_encode(["status" => false, "message" => "Đặt hàng thất bại"]);
}

$conn->close();
?>
