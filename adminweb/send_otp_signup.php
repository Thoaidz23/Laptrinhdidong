<?php
    session_start();
    include("./admin/connect.php");

    require("sendmail.php");

    date_default_timezone_set('Asia/Ho_Chi_Minh');

    if (isset($_POST['dangky'])) {
        $email = mysqli_real_escape_string($mysqli, $_POST['email'] ?? '');
        $name = mysqli_real_escape_string($mysqli, $_POST['name'] ?? '');
        $phone = mysqli_real_escape_string($mysqli, $_POST['phone'] ?? '');
        $address = mysqli_real_escape_string($mysqli, $_POST['address'] ?? '');
        $password = mysqli_real_escape_string($mysqli, $_POST['password'] ?? '');

        if (!empty($email) && !empty($name) && !empty($phone) && !empty($address) && !empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $otp = rand(100000, 999999);

            $expired_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

            $query = "INSERT INTO tbl_otp_signup (email, name, phone, address, password, otp, expired_at) 
                    VALUES ('$email', '$name', '$phone', '$address', '$hashedPassword', '$otp', '$expired_at')";

            if (mysqli_query($mysqli, $query)) {
                $subject = 'Mã OTP đăng ký';
                $message = "Mã OTP của bạn là: $otp\nHạn sử dụng: 5 phút.";

                $mailer = new Mailer();
                $result = $mailer->otp_signup($subject, $message, $email);

                if ($result) {
                    header('Location: otp_signup.php?email=' . urlencode($email));
                    exit();
                } else {
                    echo 'Không thể gửi email';
                }
            } else {
                echo 'Lỗi lưu thông tin vào CSDL';
            }
        } else {
            echo 'Dữ liệu không hợp lệ';
        }
    } else {
        echo 'Form chưa được gửi';
    }

?>

