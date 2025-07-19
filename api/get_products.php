<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once 'db.php'; // đường dẫn tới db.php

$sql = "SELECT * FROM tbl_product";
$result = $conn->query($sql);

$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

echo json_encode($products);
?>
