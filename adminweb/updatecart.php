<?php
    session_start();
    include("./admin/connect.php");

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id_user = $_SESSION["id_user"];
        $id_sanpham = intval($_POST["id_sanpham"]);
        $change = intval($_POST["change"]);

        if (!$id_user || !$id_sanpham || !$change) {
            echo "error";
            exit();
        }

        // Lấy số lượng sản phẩm hiện có trong bảng sản phẩm
        $query_product = "SELECT soluong FROM tbl_sanpham WHERE id_sanpham = $id_sanpham";
        $result = mysqli_query($mysqli, $query_product);
        $product = mysqli_fetch_assoc($result);

        if ($product) {
            $stock_quantity = $product['soluong'];
            $query_cart = "SELECT soluong FROM tbl_giohang WHERE id_user = $id_user AND id_sanpham = $id_sanpham";
            $result_cart = mysqli_query($mysqli, $query_cart);
            $cart_item = mysqli_fetch_assoc($result_cart);
            
            if ($cart_item) {
                $current_quantity = $cart_item['soluong'];
                $new_quantity = $current_quantity + $change;

                if ($new_quantity > $stock_quantity) {
                    echo "exceed_stock";
                    exit();
                }
            }

            $query = "UPDATE tbl_giohang SET soluong = soluong + $change WHERE id_user = $id_user AND id_sanpham = $id_sanpham";
            if (mysqli_query($mysqli, $query)) {
                echo "success";
            } else {
                echo "error";
            }
        } else {
            echo "product_not_found";
        }
    } else {
        echo "invalid_request";
    }
?>
