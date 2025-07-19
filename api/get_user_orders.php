<?php
include 'db.php';

$user_id = $_GET['user_id'];

$stmt = $conn->prepare("SELECT * FROM tbl_order WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode($orders);
?>
