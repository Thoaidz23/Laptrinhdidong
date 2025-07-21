<?php
header('Content-Type: application/json');
include 'db.php';

if (!isset($_GET['id'])) {
    echo json_encode(['status' => false, 'message' => 'Missing ID']);
    exit;
}

$id = intval($_GET['id']);

$query = "SELECT * FROM tbl_user WHERE id_user = $id";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    echo json_encode($user);
} else {
    echo json_encode(['status' => false, 'message' => 'User not found']);
}
