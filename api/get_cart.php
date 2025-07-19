<?php
include 'db.php';

$id_user = $_GET['id_user'];
$sql = "SELECT c.*, p.name, p.image, p.price FROM tbl_cart c
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
