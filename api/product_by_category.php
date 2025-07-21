<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'db.php';

$id = $_GET['id'];

$sql = "SELECT * FROM tbl_product WHERE id_category_product = $id";
$result = mysqli_query($conn, $sql);

$products = [];

while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

echo json_encode($products);
