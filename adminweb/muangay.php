<?php
session_start();
include("./admin/connect.php");

if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);
    $user_id = $_SESSION['id_user'] ?? 0;

    if ($user_id > 0) {
        $product_query = "SELECT ten_sanpham, hinhanh, giasp FROM tbl_sanpham WHERE id_sanpham = $product_id";
        $product_result = mysqli_query($mysqli, $product_query);

        if ($product_result && mysqli_num_rows($product_result) > 0) {
            $product = mysqli_fetch_assoc($product_result);
            $ten_sanpham = mysqli_real_escape_string($mysqli, $product['ten_sanpham']);
            $hinhanh = mysqli_real_escape_string($mysqli, $product['hinhanh']);
            $giasp = floatval($product['giasp']);

            $check_query = "SELECT * FROM tbl_giohang WHERE id_user = $user_id AND id_sanpham = $product_id";
            $check_result = mysqli_query($mysqli, $check_query);

            if (mysqli_num_rows($check_result) == 0) {
                $insert_query = "INSERT INTO tbl_giohang (id_user, id_sanpham, tensp, hinhanh, giasp, soluong) 
                                 VALUES ($user_id, $product_id, '$ten_sanpham', '$hinhanh', $giasp, 1)";
                mysqli_query($mysqli, $insert_query);
            }
        } else {
            echo "Không tìm thấy sản phẩm.";
            exit();
        }
    } else {
        echo "Người dùng chưa đăng nhập.";
        exit();
    }
    header("Location: giohang.php");
    exit();
} else {
    echo "Sản phẩm không hợp lệ.";
}
?>
