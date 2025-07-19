<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$id_user = $data['id_user'];
$id_product = $data['id_product'];
$quantity = $data['quantity'];

$sql = "UPDATE tbl_cart SET quantity=? WHERE id_user=? AND id_product=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $quantity, $id_user, $id_product);
echo json_encode(["status" => $stmt->execute()]);
