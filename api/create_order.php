<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$user_id = $data["user_id"];
$items = $data["items"];
$total = $data["total_price"];

$stmt = $conn->prepare("INSERT INTO tbl_order (user_id, total_price, order_date, status) VALUES (?, ?, NOW(), 0)");
$stmt->bind_param("id", $user_id, $total);

if ($stmt->execute()) {
    $order_id = $stmt->insert_id;

    $detail_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
    foreach ($items as $item) {
        $detail_stmt->bind_param("iii", $order_id, $item["product_id"], $item["quantity"]);
        $detail_stmt->execute();
    }

    echo json_encode(["status" => "success", "order_id" => $order_id]);
} else {
    echo json_encode(["status" => "error"]);
}
?>
