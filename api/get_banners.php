<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'db.php';

$sql = "SELECT id_banner, image FROM tbl_banner";
$result = mysqli_query($conn, $sql);

$banners = [];

while ($row = mysqli_fetch_assoc($result)) {
    $banners[] = [
        'id_banner' => $row['id_banner'],
        'image' => $row['image']
    ];
}

echo json_encode($banners);
