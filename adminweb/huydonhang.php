<?php
    session_start();
    include("./admin/connect.php");

    if (isset($_GET['code_order'])) {
        $code_order = $_GET['code_order'];

        $sql = "UPDATE tbl_order SET status = 5 WHERE code_order = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $code_order);

        if ($stmt->execute()) {
            // Thành công
            // echo "<script>alert('Đơn hàng đã được hủy thành công.'); window.location.href='danhsachdonhang.php';</script>";
        } else {
            // Thất bại
            // echo "<script>alert('Có lỗi xảy ra khi hủy đơn hàng.'); window.history.back();</script>";
        }

        $stmt->close();
    } else {
        // Không có mã đơn hàng
        // echo "<script>alert('Mã đơn hàng không hợp lệ.'); window.history.back();</script>";
    }
    header("location: purchasehistory.php");

    $mysqli->close();
?>
