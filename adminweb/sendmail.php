<?php
    //Import PHPMailer classes vào không gian tên toàn cầu
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    require 'vendor/autoload.php';

    class Mailer {
        // Phương thức gửi email chung
        private function sendEmail($tieude, $noidung, $maildathang) {
            $mail = new PHPMailer(true);
            $mail->CharSet = "UTF-8";
            
            try {
                // Cấu hình SMTP
                $mail->SMTPDebug = 0; // Tắt debug
                $mail->isSMTP(); // Sử dụng SMTP
                $mail->Host       = 'smtp.gmail.com'; // Địa chỉ server SMTP
                $mail->SMTPAuth   = true; // Kích hoạt xác thực SMTP
                $mail->Username   = 'truongthuong1512@gmail.com'; // Tên đăng nhập
                $mail->Password   = 'lljvbafslfcjbltv'; // Mật khẩu SMTP
                $mail->SMTPSecure = 'tls'; // Bảo mật TLS
                $mail->Port       = 587; // Cổng SMTP

                // Thêm người nhận
                $mail->setFrom('truongthuong@1512gmail.com', 'Mailer');
                $mail->addAddress($maildathang, 'Thanh Thuong'); // Địa chỉ nhận email
                $mail->addCC('truongthuong1512@gmail.com'); // Thêm CC nếu cần

                // Nội dung email
                $mail->isHTML(true); // Định dạng email là HTML
                $mail->Subject = $tieude; // Tiêu đề email
                $mail->Body    = $noidung; // Nội dung email

                // Gửi email
                $mail->send();
                return true; // Gửi thành công
            } catch (Exception $e) {
                return false; // Nếu có lỗi
            }
        }

        // Phương thức gửi email OTP
        public function otp_signup($tieude, $noidung, $maildathang) {
            return $this->sendEmail($tieude, $noidung, $maildathang); // Gọi phương thức sendEmail và trả về kết quả
        }

        // Phương thức gửi email đặt hàng
        public function dathangmail($tieude, $noidung, $maildathang) {
            return $this->sendEmail($tieude, $noidung, $maildathang); // Gọi phương thức sendEmail và trả về kết quả
        }

        public function otp_forgot_password($subject, $message, $email) {
            // Phương thức gửi email OTP cho quên mật khẩu
            return $this->sendEmail($subject, $message, $email);
        }
    }
?>
