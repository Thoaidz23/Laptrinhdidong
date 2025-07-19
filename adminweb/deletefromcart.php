<?php
    session_start();
    include("./admin/connect.php");

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id_sanpham = intval($_POST["id_sanpham"]);
        $id_khachhang = $_SESSION["id_user"];

        if (!$id_sanpham || !$id_khachhang) {
            echo "error";
            exit();
        }

        $delete_query = "DELETE FROM tbl_giohang WHERE id_user = $id_khachhang AND id_sanpham = $id_sanpham";
        if (mysqli_query($mysqli, $delete_query)) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        echo "invalid_request";
    }
?>
