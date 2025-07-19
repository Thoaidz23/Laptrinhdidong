<?php
    include("./admin/connect.php");
    session_start();
?>

<?php
    if (isset($_POST['xacnhan'])) {
        $email = $_GET['email'] ?? '';

        $otp = $_POST['otp'] ?? '';

        if (!empty($email) && !empty($otp)) {
            $query = "SELECT * FROM tbl_otp_forgot_password 
                    WHERE email = '$email' AND otp = '$otp' AND expired_at > NOW()";
            $result = mysqli_query($mysqli, $query);

            if (mysqli_num_rows($result) > 0) {
                $deleteQuery = "DELETE FROM tbl_otp_forgot_password WHERE email = '$email'";
                mysqli_query($mysqli, $deleteQuery);

                header('Location: taolaimatkhau.php?email=' . urlencode($email));
                exit();
            } else {
                $error = 'OTP không hợp lệ hoặc đã hết hạn.';
            }
        } else {
            $error = 'Vui lòng nhập đầy đủ thông tin.';
        }
    }

    if (!empty($error)) {
        echo '<p style="color: red; text-align: center;">' . $error . '</p>';
    }
?>




<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Thực OTP - Quên Mật Khẩu</title>
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            padding: 100px 0 100px 0;
            text-align: center;
            max-width: 700px;
            width: 100%;
            align-items: center;
            margin:auto;
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
        .inputotp{
            height:50px;
            border-radius:10px;
            width: 300px;
            font-size:30px;
            padding:20px;
            letter-spacing:26px;
            margin-bottom:30px;
        }
 
    </style>
</head>
<body>
    <?php
        include("includes/header.php");
    ?>
    <form id="otpForm" action="" method="post">
        <div class='main'>
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
            <input type="submit" class='dangky' name="xacnhan" value="Xác Nhận"></input>    
            
        </div>
    </form>
    <div>
        
    <?php
        include("includes/footer.php");
    ?>
</body>
<script>
        function validateInput(input) {
            // Loại bỏ ký tự không phải số
            input.value = input.value.replace(/[^0-9]/g, '');
        }
    </script>
</html>
