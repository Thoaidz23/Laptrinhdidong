<?php
    session_start();
    include("./admin/connect.php");

    require("sendmail.php");

    date_default_timezone_set('Asia/Ho_Chi_Minh');

    $id_user = $_SESSION["id_user"];
    function generateRandomString($length = 9) {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';

        if ($length < 9) {
            return '';
        }
        $randomString = '';
        // Tạo 3 ký tự đầu là chữ
        for ($i = 0; $i < 3; $i++) {
            $randomString .= $letters[rand(0, strlen($letters) - 1)];
        }
        // Tạo 6 ký tự sau là số
        for ($i = 0; $i < 6; $i++) {
            $randomString .= $numbers[rand(0, strlen($numbers) - 1)];
        }
        return $randomString;
    }    
    $code_order = generateRandomString(12);
    $now = date('Y-m-d H:i:s');
    $insert_order = "INSERT INTO tbl_order(id_user, code_order, status, date) VALUE('$id_user', '$code_order', '0', '$now')";
    $order_query = mysqli_query($mysqli, $insert_order);

    if ($order_query) {
        // Lấy các sản phẩm trong giỏ hàng từ cơ sở dữ liệu
        $sql_cart = "SELECT * FROM tbl_giohang WHERE id_user = '$id_user'";
        $result_cart = mysqli_query($mysqli, $sql_cart);

        // Khởi tạo tổng tiền
        $total_price = 0;
    
        // Khởi tạo tiêu đề và nội dung email
        $tieude = "Đặt hàng website điện tử TST thành công";
        $noidung = "<p>Cảm ơn quý khách đã đặt hàng của chúng tôi với mã đơn hàng: " . $code_order . "</p>";
        $noidung .= "<h4>Đơn hàng đặt bao gồm: </h4>";
    
        // Duyệt qua các sản phẩm trong giỏ hàng
        while ($row = mysqli_fetch_array($result_cart)) {
            $id_sanpham = $row["id_sanpham"];
            $soluong = $row["soluong"];
            $giasp = $row["giasp"];

            // Tính tổng tiền sản phẩm
            $product_total = $soluong * $giasp;
            $total_price += $product_total;
    
            // Thêm chi tiết sản phẩm vào bảng `tbl_cart_details`
            $insert_order_detail = "
                INSERT INTO tbl_order_detail (id_sanpham, code_order, soluongmua) 
                VALUES ('$id_sanpham', '$code_order', '$soluong')
            ";
            mysqli_query($mysqli, $insert_order_detail);
    
            // Tạo nội dung email
            $noidung .= "
                <ul style='border: 1px solid blue; margin: 10px; padding: 10px'>
                    <li><strong>Tên sản phẩm:</strong> " . $row["tensp"] . "</li>
                    <li><strong>Số lượng:</strong> " . $row["soluong"] . "</li>
                    <li><strong>Giá:</strong> " . number_format($row["giasp"], 0, ',', '.') . " đ</li>
                </ul>
            ";
        }

        // Cập nhật tổng giá trị đơn hàng vào `tbl_order`
            $update_order_total = "
            UPDATE tbl_order 
            SET total_price = '$total_price' 
            WHERE code_order = '$code_order'
        ";
        mysqli_query($mysqli, $update_order_total);
            
        $maildathang = $_SESSION["email"];
        $mail = new Mailer();
        $mail->dathangmail($tieude, $noidung, $maildathang);
    
        // Xóa giỏ hàng sau khi đặt hàng thành công
        $delete_cart = "DELETE FROM tbl_giohang WHERE id_user = '$id_user'";
        mysqli_query($mysqli, $delete_cart);
    }
    
    header("location: purchasehistory.php");
?>