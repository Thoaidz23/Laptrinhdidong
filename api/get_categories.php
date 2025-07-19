<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'db.php';

$sql = "SELECT id_category_product, name, icon_name FROM tbl_category_product";
$result = mysqli_query($conn, $sql);

$categories = [];

while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = [
        'id' => $row['id_category_product'],
        'name' => $row['name'],
        'icon_name' => $row['icon_name']
    ];
}

echo json_encode($categories);
