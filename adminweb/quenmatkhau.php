<?php
    include("./admin/connect.php");
    session_start();
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
    <form id="registrationForm" action="send_otp_qmk.php" method="post">
        <div class='main'>
            <h2 class="login-title">Quên mật khẩu</h2>


            <div class="input-container">
                <input type="text" placeholder=" " maxlength="255"  autocomplete="off" name="email" class="box-input__main">
                <label>Email</label>
            </div>

            <input type="submit" class='dangky' value="Xác nhận" name="quenmatkhau" onclick="return handleForgotPassword()"></input> 
        </div>
        
        <div id="payment-modal" class="modal hidden">      
            <div class="payment-modal">
                <div class="payment-modal__head">
                    <p style="cursor: default; font-size: 24px; text-align: center; margin: 0; padding-left: 50px">Xác thực email của bạn</p>
                    <em id="close-modal-btn" onclick="closeModal()">✖</em>
                </div>
                <div class="payment-modal__body" style="cursor: default">
                    <p>Nhập mã OTP được gửi qua email</p>                  
                </div>
                <input class="list-payment__item" type="text" name="otp" required>
                <button class="btn xacnhan" name="dangky" type="submit" style="float:left; display: flex; justify-content: center; align-items: center; width: 150px; height: 40px; background-color: #0092B7; color: white; border: none; padding: 10px; cursor: pointer;">
                    <span style="font-size: 18px;">Xác Nhận</span>
                </button>

                <div class="btn" style="float:right; cursor: pointer;"><span style="font-size: 18px;">Gửi Lại</span></div>
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

        function handleForgotPassword() {
        const emailInput = document.querySelector("input[name='email']").value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

        if (!emailRegex.test(emailInput)) {
            showNotification("Vui lòng nhập email hợp lệ", "&#9888;");
            return false;
        }

        // Tạo AJAX để kiểm tra email
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "checkmail_qmk.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);

                    if (response.exists) {
                        document.getElementById("registrationForm").submit();
                    } else {
                        showNotification("Email không tồn tại, vui lòng kiểm tra lại.", "&#9888;");
                    }
                } catch (e) {
                    console.error("Lỗi JSON từ server:", e);
                    showNotification("Có lỗi xảy ra, vui lòng thử lại.", "&#9888;");
                }
            }
        };

        xhr.send("email=" + encodeURIComponent(emailInput));

        return false;
    }


        // Hàm hiển thị thông báo
        function showNotification(message, icon) {
            const notificationDiv = document.createElement("div");
            notificationDiv.style.position = "fixed";
            notificationDiv.style.top = "70px";
            notificationDiv.style.left = "50%";
            notificationDiv.style.transform = "translateX(-50%)";
            notificationDiv.style.zIndex = "1000";
            notificationDiv.style.maxWidth = "330px";
            notificationDiv.style.width = "100%";
            notificationDiv.style.backgroundColor = "#FF6347";
            notificationDiv.style.color = "white";
            notificationDiv.style.padding = "10px 20px";
            notificationDiv.style.borderRadius = "5px";
            notificationDiv.style.textAlign = "center";
            notificationDiv.style.wordWrap = "break-word";
            notificationDiv.innerHTML = `${icon} ${message}`;

            document.body.appendChild(notificationDiv);

            setTimeout(() => {
                notificationDiv.remove();
            }, 5000);
        }

        // Hàm hiển thị thông báo
        function showNotification(message, icon) {
            const notificationContainer = document.createElement("div");
            notificationContainer.style.position = "fixed";
            notificationContainer.style.top = "70px";
            notificationContainer.style.left = "50%";
            notificationContainer.style.transform = "translateX(-50%)";
            notificationContainer.style.zIndex = "1000";
            notificationContainer.style.maxWidth = "330px";
            notificationContainer.style.width = "100%";

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

            const iconElement = document.createElement("span");
            iconElement.innerHTML = icon;
            iconElement.style.marginRight = "10px";
            iconElement.style.fontSize = "18px";

            const messageElement = document.createElement("span");
            messageElement.innerText = message;

            notificationDiv.appendChild(iconElement);
            notificationDiv.appendChild(messageElement);
            notificationContainer.appendChild(notificationDiv);

            document.body.appendChild(notificationContainer);

            setTimeout(function () {
                notificationContainer.remove();
            }, 5000);
        }

    </script>
</body>
</html>
