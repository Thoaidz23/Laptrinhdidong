<?php
    include("./admin/connect.php");
    session_start();
?>

<?php
if (isset($_POST["taomatkhaumoi"])) {
    $email = $_GET["email"] ?? '';
    $password = $_POST["password"] ?? '';
    $confirm_password = $_POST["confirm_password"] ?? '';

    if (!empty($email) && !empty($password) && $password === $confirm_password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql_update = mysqli_query($mysqli, "UPDATE tbl_user SET password='$hashedPassword' WHERE email='$email'");

        if ($sql_update) {
            header("Location: dangnhap.php");
            exit();
        } else {
            echo "<p style='color: red'>Có lỗi xảy ra khi cập nhật mật khẩu. Vui lòng thử lại.</p>";
        }
    } else {
        echo "<p style='color: red'>Mật khẩu không khớp hoặc dữ liệu không hợp lệ.</p>";
    }
}
?>



<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        .input-container {
            position: relative;
            margin: 10px 0 20px;
        }
        .box-input__main {
            font-size: 16px;
            width: 700px;
            border: none; 
            border-bottom: 1px solid #0097B2; 
            outline: none;  
            padding: 10px 0 10px 10px;
            transition: border-bottom 0.3s;
            margin-bottom:30px;
        }
        .box-input__main:focus {
            border-bottom: 1px solid rgb(15, 210, 181); 
        }
        label {
            position: absolute;
            left: 10px;
            top: 10px;
            font-size: 16px;
            color: #aaa;
            transition: 0.2s ease all;
            pointer-events: none;
        }
        .box-input__main:focus + label,
        .box-input__main:not(:placeholder-shown) + label {
            top: -10px;
            left: 10px;
            font-size: 12px;
            color: #0097B2;
        }
        .dangky {
            width: 700px;
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
        .bcctktdk {
            margin-top: 20px;
        }


        /* Định dạng biểu tượng mắt */
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 30%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 20px;
            color: #555;
        }

        .input-container {
            position: relative; /* Cần có relative để icon mắt có thể đặt ở vị trí tuyệt đối */
        }

        input[type="password"] {
            padding-right: 30px; /* Để có không gian cho biểu tượng mắt */
        }


        /* Ẩn modal */
        .hidden {
            display: none !important ;
        }
        .no-scroll {
            overflow: hidden;
            height: 100vh;
        }
        .blur-background {
            filter: blur(5px);
            pointer-events: none; /* Ngăn tương tác */
        }
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }       

        .payment-modal {
            background: white;
            border-radius: 8px;
            width: 90%;
            max-width: 400px;
            padding: 20px;
        }

        .payment-modal__head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: bold;
            background:#FAFAFA;
        }

        .payment-modal__head em {
            cursor: pointer;
        }

        .payment-modal__body {
            margin-top: 20px;
            margin-bottom:20px;
        }

        .list-payment__item {
            display: flex;
            align-items: center;
            border:1px solid rgba(145, 158, 171, .239);
            border-radius:10px;
            padding:11px;
            height: 70px;
            width: 50%;
            margin:0 25% 0 25%;
            font-size:26px;
            letter-spacing:11.7px;
        }
        .list-payment__item:hover{
            background:#E0E0E0;
        }
        .list-payment__item.active{
            border:1px solid #0092B7 !important;
        }
        .payment-item__img img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }
        /* Khi thêm class này vào <body>, trang sẽ bị khóa cuộn */
        .btn{
            margin: 10px;
            border:1px solid rgba(145, 158, 171, .239);
            border-radius:10px;
            background:#0092B7;
            color:white;
            height:40px;
            width:40%;
            text-align:center !important;
            padding-top:10px;
        }
        .btn:hover{
            background: rgb(15, 210, 181) !important;
        }
    </style>
</head>
<body>
    <?php
        include("includes/header.php");
    ?>
    <form id="registrationForm" action="" method="post" onsubmit="return handleCreatenewpass()">
        <div class='main'>
            <h2 class="login-title">Tạo Mật Khẩu Mới</h2>

            <div class="input-container">
                <input type="password" placeholder=" " maxlength="255" autocomplete="off" name="password" class="box-input__main" id="password">
                <label>Mật khẩu mới</label>
                <span class="toggle-password" id="togglePassword">
                    <i class="fa-solid fa-eye"></i>
                </span>
            </div>

            <div class="input-container">
                <input type="password" placeholder=" " maxlength="255" autocomplete="off" name="confirm_password" class="box-input__main" id="confirm_password">
                <label>Xác nhận mật khẩu</label>
                <span class="toggle-password" id="toggleConfirmPassword">
                    <i class="fa-solid fa-eye"></i>
                </span>
            </div>
            <input type="submit" name="taomatkhaumoi" class='dangky' value="Xác Nhận" style ='text-align:center'></input>    
        </div>
        
    </div>
        </div>
    </form>
    <?php
        include("includes/footer.php");
    ?>

    <script>

        // Nhảy placeholder
        const inputs = document.querySelectorAll('.box-input__main');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.placeholder = '';  
            });
            input.addEventListener('blur', () => {
                if (input.value === '') {
                    input.placeholder = ' '; 
                }
            });
        });


        // Hàm xử lý form
        function handleCreatenewpass() {
            console.log("Hàm handleCreatenewpass đã được gọi");

            if (validateForm()) {
                console.log("Form hợp lệ, cho phép gửi");
                return true;
            } else {
                console.log("Form không hợp lệ, ngừng gửi");
                return false;
            }
        }

        // Hàm kiểm tra form
        function validateForm() {
            console.log("Bắt đầu kiểm tra form");
            let hasError = false;
            const notifications = [];

            // Lấy giá trị mật khẩu
            const passwordInput = document.querySelector("input[name='password']").value.trim();
            const confirmPasswordInput = document.querySelector("input[name='confirm_password']").value.trim();

            // Regex kiểm tra điều kiện mật khẩu
            const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;
            if (!passwordRegex.test(passwordInput)) {
                hasError = true;
                notifications.push({
                    message: "Mật khẩu phải có ít nhất 6 ký tự, bao gồm ít nhất một chữ cái, một số và một ký tự đặc biệt",
                    icon: "&#9888;",
                });
            }

            if (passwordInput !== confirmPasswordInput) {
                hasError = true;
                notifications.push({
                    message: "Mật khẩu xác nhận không trùng khớp",
                    icon: "&#9888;",
                });
            }

            // Hiển thị thông báo nếu có lỗi
            if (hasError) {
                console.log("Form có lỗi, hiển thị thông báo");
                const notificationContainer = document.createElement("div");
                notificationContainer.style.position = "fixed";
                notificationContainer.style.top = "70px";
                notificationContainer.style.left = "50%";
                notificationContainer.style.transform = "translateX(-50%)";
                notificationContainer.style.zIndex = "1000";
                notificationContainer.style.maxWidth = "330px";
                notificationContainer.style.width = "100%";

                notifications.forEach(function (notification) {
                    const notificationDiv = document.createElement("div");
                    notificationDiv.style.backgroundColor = "#FF6347";
                    notificationDiv.style.color = "white";
                    notificationDiv.style.padding = "10px 20px";
                    notificationDiv.style.borderRadius = "5px";
                    notificationDiv.style.display = "flex";
                    notificationDiv.style.alignItems = "center";
                    notificationDiv.style.marginBottom = "10px";
                    notificationDiv.style.textAlign = "center";
                    notificationDiv.style.justifyContent = "center";
                    notificationDiv.style.wordWrap = "break-word";

                    const icon = document.createElement("span");
                    icon.innerHTML = notification.icon;
                    icon.style.marginRight = "10px";
                    icon.style.fontSize = "18px";

                    const message = document.createElement("span");
                    message.innerText = notification.message;

                    notificationDiv.appendChild(icon);
                    notificationDiv.appendChild(message);

                    notificationContainer.appendChild(notificationDiv);
                });

                document.body.appendChild(notificationContainer);

                setTimeout(function () {
                    notificationContainer.remove();
                }, 5000);
            }

            console.log("Kết quả kiểm tra form:", !hasError);
            return !hasError;
        }



    // Chức năng ẩn/hiện mật khẩu
    document.getElementById("togglePassword").addEventListener("click", function () {
        const passwordField = document.getElementById("password");
        const icon = this.querySelector("i");

        // Chuyển đổi giữa mật khẩu ẩn và hiện
        const type = passwordField.type === "password" ? "text" : "password";
        passwordField.type = type;

        // Chuyển đổi biểu tượng mắt
        if (type === "password") {
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    });

    document.getElementById("toggleConfirmPassword").addEventListener("click", function () {
        const confirmPasswordField = document.getElementById("confirm_password");
        const icon = this.querySelector("i");

        // Chuyển đổi giữa mật khẩu ẩn và hiện
        const type = confirmPasswordField.type === "password" ? "text" : "password";
        confirmPasswordField.type = type;

        // Chuyển đổi biểu tượng mắt
        if (type === "password") {
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    });
    </script>
</body>
</html>
