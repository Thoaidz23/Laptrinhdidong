<?php
header('Content-Type: application/json; charset=utf-8');
include 'db.php';

$id_user = $_GET['user_id'] ?? 0;

$stmt = $conn->prepare("
    SELECT 
        o.code_order,
        o.total_price,
        o.status,
        o.date,
        o.paystatus,
        p.image,
        GROUP_CONCAT(p.name SEPARATOR ', ') AS item
    FROM tbl_order o
    JOIN tbl_order_detail od ON o.code_order = od.code_order
    JOIN tbl_product p ON od.id_product = p.id_product
    WHERE o.id_user = ?
    GROUP BY o.code_order
    ORDER BY o.date DESC
");

$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode($orders);
?>
