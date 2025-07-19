<?php
    session_start();
    include("./admin/connect.php");
    require("sendmail.php");

    date_default_timezone_set('Asia/Ho_Chi_Minh');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = mysqli_real_escape_string($mysqli, $_POST['email'] ?? '');

        if (!empty($email)) {
            $otp = rand(100000, 999999);

            $created_at = date('Y-m-d H:i:s');

            $expired_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

            $query = "INSERT INTO tbl_otp_forgot_password (email, otp, created_at, expired_at) 
                    VALUES ('$email', '$otp', '$created_at', '$expired_at')";

            if (mysqli_query($mysqli, $query)) {
                $subject = 'Mã OTP khôi phục mật khẩu';
                $message = "Xin chào,\n\nBạn đã yêu cầu khôi phục mật khẩu. Đây là mã OTP của bạn:\n\n$otp\n\nMã này có hiệu lực trong vòng 5 phút.\n\nNếu bạn không yêu cầu khôi phục mật khẩu, vui lòng bỏ qua email này.";

                $mailer = new Mailer();
                $result = $mailer->otp_forgot_password($subject, $message, $email);

                if ($result) {
                    header('Location: otp_qmk.php?email=' . urlencode($email));
                    exit();
                } else {
                    echo 'Không thể gửi email. Vui lòng thử lại sau.';
                }
            } else {
                echo 'Lỗi lưu thông tin OTP vào cơ sở dữ liệu. Vui lòng thử lại sau.';
            }
        } else {
            echo 'Email không được để trống.';
        }
    } else {
        echo 'Form chưa được gửi.';
    }
?>
