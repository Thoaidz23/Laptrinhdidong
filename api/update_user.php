<?php
header('Content-Type: application/json');
include 'db.php';

$id_user = $_POST['id_user'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';

if (!$id_user) {
    echo json_encode(['status' => false, 'message' => 'Missing user ID']);
    exit;
}

$query = "UPDATE tbl_user SET name='$name', email='$email', phone='$phone', address='$address' WHERE id_user = $id_user";
$result = mysqli_query($conn, $query);

if ($result) {
    echo json_encode(['status' => true, 'message' => 'User updated successfully']);
} else {
    echo json_encode(['status' => false, 'message' => 'Failed to update user']);
}
?>
