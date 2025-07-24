<?php
include 'db.php'; // Kết nối DB
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

$data = json_decode(file_get_contents("php://input"));
$id_user = $data->id_user ?? 0;

// Debug: Kiểm tra ID user
error_log("Received id_user: " . $id_user);

if (!$id_user) {
    echo json_encode([
        'status' => false,
        'message' => 'Thiếu ID người dùng',
    ]);
    exit;
}

// Truy vấn danh sách đơn hàng theo id_user
$sql = "SELECT * FROM tbl_order WHERE id_user = ? ORDER BY date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];

while ($row = $result->fetch_assoc()) {
    $code_order = $row['code_order'];

    // Debug: log đơn hàng
    error_log("Found order: " . $code_order);

    // Truy vấn các sản phẩm trong đơn hàng
    $details = [];
    $image = '';

    $detailQuery = "SELECT od.quantity_product, p.name, p.image 
                    FROM tbl_order_detail od 
                    JOIN tbl_product p ON od.id_product = p.id_product 
                    WHERE od.code_order = ?";
    $detailStmt = $conn->prepare($detailQuery);
    $detailStmt->bind_param("s", $code_order);
    $detailStmt->execute();
    $detailResult = $detailStmt->get_result();

    while ($d = $detailResult->fetch_assoc()) {
        $details[] = $d['name'];
        if (!$image) $image = $d['image']; // Lấy ảnh sản phẩm đầu tiên
    }

    $orders[] = [
        'id' => $code_order,
        'status' => $row['status'],
        'date' => date('d/m/Y', strtotime($row['date'])),
        'time' => date('H:i', strtotime($row['date'])),
        'total' => (int)$row['total_price'],
        'item' => implode(', ', $details),
        'image' => $image,
    ];
}

// Debug: Log tổng số đơn hàng lấy được
error_log("Total orders found: " . count($orders));

// Trả kết quả về Flutter
echo json_encode([
    'status' => true,
    'orders' => $orders,
]);
?>
