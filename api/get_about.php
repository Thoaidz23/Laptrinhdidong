<?php
include 'db.php';

$sql = "SELECT id_footer, title, content FROM tbl_footer";
$result = mysqli_query($conn, $sql);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode([
    "status" => true,
    "message" => "Lấy dữ liệu thành công",
    "data" => $data
], JSON_UNESCAPED_UNICODE); // giữ dấu tiếng Việt
?>
