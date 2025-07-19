<?php
    include("./admin/connect.php");
    session_start();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $product_id = intval($_POST["product_id"]);
        $user_id = $_SESSION["id_user"];

        if (!$user_id) {
            echo "Bạn cần đăng nhập trước khi thêm vào giỏ hàng.";
            exit();
        }


        $product_query = "SELECT * FROM tbl_sanpham WHERE id_sanpham = $product_id LIMIT 1";
        $product_result = mysqli_query($mysqli, $product_query);

        if (mysqli_num_rows($product_result) > 0) {
            $product = mysqli_fetch_assoc($product_result);
            $product_name = $product["ten_sanpham"];
            $product_image = $product["hinhanh"];
            $product_price = $product["giasp"];

            $query = "SELECT * FROM tbl_giohang WHERE id_user = $user_id AND id_sanpham = $product_id";
            $result = mysqli_query($mysqli, $query);

            if (mysqli_num_rows($result) > 0) {
                $update_query = "UPDATE tbl_giohang SET soluong = soluong + 1 WHERE id_user = $user_id AND id_sanpham = $product_id";
                if (mysqli_query($mysqli, $update_query)) {
                    echo "Đã cập nhật số lượng sản phẩm trong giỏ hàng.";
                } else {
                    echo "Lỗi khi cập nhật giỏ hàng.";
                }
            } else {
                $insert_query = "INSERT INTO tbl_giohang (id_user, id_sanpham, tensp, hinhanh, giasp, soluong) VALUES ('$user_id', '$product_id', '$product_name', '$product_image', '$product_price', 1)";
                if (mysqli_query($mysqli, $insert_query)) {
                    echo "Thêm sản phẩm vào giỏ hàng thành công.";
                } else {
                    echo "Lỗi khi thêm vào giỏ hàng.";
                }
            }
        } else {
            echo "Sản phẩm không tồn tại.";
        }
    } else {
        echo "Phương thức không hợp lệ.";
    }
?>