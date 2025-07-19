<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$id_user = $data['id_user'];
$id_product = $data['id_product'];
$quantity = $data['quantity'];
$price = $data['price'];

$sql = "SELECT * FROM tbl_cart WHERE id_user=? AND id_product=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_user, $id_product);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $sql = "UPDATE tbl_cart SET quantity=quantity + ? WHERE id_user=? AND id_product=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $quantity, $id_user, $id_product);
} else {
    $sql = "INSERT INTO tbl_cart (id_user, id_product, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiid", $id_user, $id_product, $quantity, $price);
}
echo json_encode(["status" => $stmt->execute()]);
