<?php
include 'db.php';

header('Content-Type: application/json'); // ✅ THÊM DÒNG NÀY

$id_user = $_GET['id_user'] ?? null;

if (!$id_user) {
    echo json_encode(["status" => false, "message" => "Thiếu id_user"]);
    exit;
}

$sql = "SELECT c.*, p.name, p.image, p.price, p.quantity as maxQuantity
        FROM tbl_cart c
        JOIN tbl_product p ON c.id_product = p.id_product
        WHERE c.id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

$cart = [];
while ($row = $result->fetch_assoc()) {
    $cart[] = $row;
}

echo json_encode($cart);
?>
