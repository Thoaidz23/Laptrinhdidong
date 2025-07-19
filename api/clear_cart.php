<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"));
$id_user = $data->id_user;

$conn->query("DELETE FROM tbl_cart WHERE id_user = $id_user");

echo json_encode(["success" => true]);
?>
