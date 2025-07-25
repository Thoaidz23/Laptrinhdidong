<?php
include 'db.php';

header('Content-Type: application/json');

$orderId = $_GET['code_order'] ?? null;

if (!$orderId) {
    echo json_encode(['status' => false, 'message' => 'Thiếu mã đơn hàng']);
    exit;
}

// Lấy thông tin đơn hàng
$sql_order = "SELECT * FROM tbl_order WHERE code_order = ?";
$stmt = $conn->prepare($sql_order);
$stmt->bind_param("s", $orderId);
$stmt->execute();
$result_order = $stmt->get_result();

if ($result_order->num_rows === 0) {
    echo json_encode(['status' => false, 'message' => 'Không tìm thấy đơn hàng']);
    exit;
}

$order = $result_order->fetch_assoc();

// Lấy chi tiết sản phẩm trong đơn
$sql_details = "
    SELECT od.*, p.name AS name, p.image AS image, p.price AS price
    FROM tbl_order_detail od
    JOIN tbl_product p ON od.id_product = p.id_product
    WHERE od.code_order = ?
";
$stmt_detail = $conn->prepare($sql_details);
$stmt_detail->bind_param("s", $orderId);
$stmt_detail->execute();
$result_details = $stmt_detail->get_result();

$items = [];
while ($row = $result_details->fetch_assoc()) {
    $items[] = [
        'id_product' => $row['id_product'],
        'name' => $row['name'],
        'price' => $row['price'],
        'image' => $row['image'],
        'quantity' => $row['quantity_product'],
    ];
}

// Trả về JSON
echo json_encode([
    'status' => true,
    'order' => [
        'code_order' => $order['code_order'],
        'date' => $order['date'],
        'total_price' => $order['total_price'],
        'status' => (int)$order['status'],
        'paystatus' => (int)$order['paystatus'],
        'method' => (int)$order['method'],
        'name_user' => $order['name_user'],
        'phone' => $order['phone'],
        'address' => $order['address'],
    ],
    'items' => $items,
]);
?>
