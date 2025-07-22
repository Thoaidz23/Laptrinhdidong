<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$id_user = $data['id_user'];
$id_product = $data['id_product'];
$quantity = $data['quantity'];

$response = [];

if (isset($id_user, $id_product, $quantity)) {
    $sql = "UPDATE tbl_cart SET quantity=? WHERE id_user=? AND id_product=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $quantity, $id_user, $id_product);

    if ($stmt->execute()) {
        $response['status'] = true;
    } else {
        $response['status'] = false;
        $response['error'] = $stmt->error;
    }

    $stmt->close();
} else {
    $response['status'] = false;
    $response['error'] = 'Missing parameters';
}

$conn->close();
echo json_encode($response);
