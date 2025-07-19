<?php
    include("./admin/connect.php");
    session_start();

    $successMessage = ""; // Biến lưu thông báo thành công
    $errorMessage = "";   // Biến lưu thông báo lỗi

    if (isset($_POST['xacnhan'])) {
        $email = $_GET['email'] ?? '';
        $otp = $_POST['otp'] ?? '';

        if (!empty($email) && !empty($otp)) {
            $query = "SELECT * FROM tbl_otp_signup WHERE email = '$email' AND otp = '$otp' AND expired_at > NOW()";
            $result = mysqli_query($mysqli, $query);

            if (mysqli_num_rows($result) > 0) {
                $userData = mysqli_fetch_assoc($result);
                $hashedPassword = $userData['password'];
                $name = $userData['name'];
                $phone = $userData['phone'];
                $address = $userData['address'];

                $insertQuery = "INSERT INTO tbl_user (email, name, phone, address, password, role) 
                                VALUES ('$email', '$name', '$phone', '$address', '$hashedPassword', 2)";

                if (mysqli_query($mysqli, $insertQuery)) {
                    $deleteQuery = "DELETE FROM tbl_otp_signup WHERE email = '$email'";
                    mysqli_query($mysqli, $deleteQuery);

                    $successMessage = "Đăng ký thành công! Bạn sẽ được chuyển hướng đến trang đăng nhập.";
                    echo "<script>
                            setTimeout(function() {
                                window.location.href = 'dangnhap.php';
                            }, 2000);
                          </script>";
                } else {
                    $errorMessage = "Lỗi khi lưu thông tin người dùng vào cơ sở dữ liệu.";
                }
            } else {
                $errorMessage = "OTP không hợp lệ hoặc đã hết hạn.";
            }
        } else {
            $errorMessage = "Vui lòng nhập đầy đủ thông tin.";
        }
    }
?>




<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Nhận OTP</title>
    <link rel="stylesheet" href="assets/css/index.css">
    <style>
        body {
            font-family: Arial, sans-serif;     
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: white;
        }
        .main {
            background: white;
            padding: 100px 0;
            text-align: center;
            max-width: 700px;
            width: 100%;
            align-items: center;
            margin: auto;
        }
        .login-title {
            font-size: 25px;
            margin-bottom: 40px;
        }
        .dangky {
            width: 300px;
            padding: 10px;
            background-color: #0097B2;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;   
        }
        .dangky:hover {
            background-color: rgb(15, 210, 181);
        }
        .inputotp {
            height: 50px;
            border-radius: 10px;
            width: 300px;
            font-size: 30px;
            padding: 20px;
            letter-spacing: 26px;
            margin-bottom: 30px;
        }
        .error-message {
            color: red;
            font-size: 18px;
            margin-top: 20px;
        }
        .success-message {
            color: green;
            font-size: 18px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include("includes/header.php"); ?>
    <form id="otpForm" action="" method="post">
        <div class="main">
            <h2 class="login-title">Xác Nhận OTP</h2>
            <input 
                type="text" 
                class="inputotp" 
                name="otp" 
                maxlength="6" 
                pattern="\d{6}" 
                required 
                oninput="validateInput(this)"
            ><br>
            <input type="submit" class="dangky" name="xacnhan" value="Xác Nhận">
            
            <?php if (!empty($errorMessage)): ?>
                <div class="error-message"><?php echo $errorMessage; ?></div>
            <?php endif; ?>

            <?php if (!empty($successMessage)): ?>
                <div class="success-message"><?php echo $successMessage; ?></div>
            <?php endif; ?>
        </div>
    </form>
    <?php include("includes/footer.php"); ?>
    <script>
        function validateInput(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
        }
    </script>
</body>
</html>
