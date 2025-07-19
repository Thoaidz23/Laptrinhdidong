<?php
session_start();
include("./admin/connect.php");

if (isset($_SESSION["id_user"])) {
    $id_user = $_SESSION["id_user"];
    
    $sql = "SELECT * FROM tbl_user WHERE id_user = '$id_user' LIMIT 1";
    $query = mysqli_query($mysqli, $sql);
    $user_data = mysqli_fetch_array($query);
} else {
    header("location: dangnhap.php");
    exit;
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword = preg_replace('/\s+/', '', trim($_POST['new_password']));
    $confirmPassword = preg_replace('/\s+/', '', trim($_POST['confirm_password']));

    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $response = [
            'status' => 'error'
        ];
    }
    elseif(preg_match('/\s/', $newPassword)) {
        $response = [
            'status' => 'error'
        ];
    } elseif ($newPassword !== $confirmPassword) {
        $response = [
            'status' => 'error'
        ];
    } elseif (strlen($newPassword) < 8) {
        $response = [
            'status' => 'error'
        ];
    }
    elseif (!preg_match('/[0-9]/', $newPassword) && !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $newPassword)) {
    $response = [
        'status' => 'error'
        ];
    }
    elseif (!preg_match('/[0-9]/', $newPassword)) {
        $response = [
            'status' => 'error'
        ];
    } elseif (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $newPassword)) {
        $response = [
            'status' => 'error'
        ];
    } elseif (password_verify($newPassword, $user_data['password'])) {
        $response = [
            'status' => 'error'
        ];
    } else {
        if (!password_verify($currentPassword, $user_data['password'])) {
            $response = [
                'status' => 'error',
                'message' => 'Mật khẩu hiện tại không đúng.'
            ];
        } else {
            $newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            $updateSql = "UPDATE tbl_user SET password = '$newHashedPassword' WHERE id_user = '$id_user'";
            if (mysqli_query($mysqli, $updateSql)) {
                $response = [
                    'status' => 'success',
                    'message' => 'Cập nhật mật khẩu thành công!'
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Đã xảy ra lỗi. Vui lòng thử lại sau.'
                ];
            }
        }
    }
    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Thông tin tài khoản</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">   
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        } 
        .account-info-container {
            width: 80%;
            margin: 0 auto;
            padding:0;
            max-width:1200px;
            width:100%;
        }
        
        #notification-bar {
            display: none;
            position: fixed;
            top: 30%;
            right: 340px;
            background-color: rgb(59, 169, 8) !important;
            color: white;
            padding: 10px 10px;
            border-radius: 10px;
            z-index: 1000;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            max-width: 80%;
           text-align: center;
        }

        #notification-bar.show {
           display: flex;
           justify-content: center;
           align-items: center;
           animation: fade-in-out 3s ease;
        }

        @keyframes fade-in-out {
            0%, 90% {
            opacity: 1;
            }
            100% {
            opacity: 0;
        }
        }

        #notification-message {
            font-size: 15px;
            font-weight: bold;
            line-height: 1.5;
        }

        .account-info-container {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }
        
        .account-info {
            background:white;
            width: 70%;
            padding: 30px;
                padding-bottom:50px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        
        }
        
        .account-info h1 {
            color: #333;
            margin-bottom: 50px;
            font-size: 24px;
            Text-Align:center;
        }
        
        
        label {
            position: absolute;
            left: 10px;
            top: 10px;
            font-size: 16px;
            color: #0097B2;
            transition: 0.2s ease all;
            pointer-events: none;
        }

        .account-actions-box {
            width: 25%;
            background-color:white;
            padding: 2rem;
            height: 320px;
            border-radius: 8px;
        }
        .account-actions-box a{
            text-decoration: none;
        }
        .account-actions-box button {
            display: block;
            width: 100%;
            padding: 0.75rem;
            margin: 0.5rem 0;
            background-color:white;
            border:1px solid rgba(145, 158, 171, .239);
            color:#686868;
            border-radius:10px;
            cursor: pointer;
            font-size: 16px;
            text-align:left;
            padding-left:20px;
        }
        .account-actions-box i{
            margin-right:10px;
        }
        .input-container {
            position: relative;
            margin: 10px 0 20px;
            padding-bottom:10px;
            max-width:500px;
            margin-left:auto;
            margin-right:auto;
            width: auto;

        }
        .box-input__main {
            font-size: 16px;
            width: 100%;
            border: none; 
            border-bottom: 1px solid #0097B2; 
            outline: none;
            padding: 10px 0;
            transition: border-bottom 0.3s;
            margin-bottom:20px;
        }
        .box-input__main:focus {
            border-bottom: 1px solid rgb(15, 210, 181); 
        }
        .box-input__main:focus + label,
        .box-input__main:not(:placeholder-shown) + label {
            top: -10px;
            left: 10px;
            font-size: 12px;
            color: rgb(15, 210, 181);
        }
        .btn-cntt{
            display: block; 
            background-color: #0097B2;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 240px;
            max-width:100%;
            margin:auto;
            height:45px;
        }
        .btn-cntt:hover{
            background-color: #0fd2b5;
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

        
        .error-message {
            color: red;
            font-size: 12px;
            display: none;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0px;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            position: relative;
            background: white;
            margin: 10% auto;
            padding: 20px;
            width: 30%;
            border-radius: 8px;
            text-align: center;
        }

        .modal-actions button,a {
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 40%;
            text-align: center;
            height:50px;

        }

        .modal-actions a{
            background-color: red;
            color: white;
            float:left;
            line-height:50px;
            margin:0 7%;
        }

        .modal-actions button:last-child {
            background-color: gray;
            color: white;

        }
    </style>
</head>
<body>
    <?php
        include("includes/header.php");
    ?>

      <div id="notification-bar">
        <span id="notification-message"></span>
    </div>

    <section class="account-info-container container">
    <div class="account-actions-box">
    <a href="taikhoan.php"><button><i class="fa-solid fa-house"></i>  Thông tin tài khoản</button></a>
                <a href="purchasehistory.php"><button ><i class="fa-solid fa-receipt"></i> Lịch Sử Mua Hàng</button></a>
                <a href="capnhatthongtin.php"><button ><i class="fa-solid fa-pen-nib"></i> Cập nhật thông tin</button></a>  
                <a href="">
                    <button style = 'border:2px solid #0097B2;color:#0097B2;background-color:#E2ECFD'>
                    <i class="fa-solid fa-rotate"></i> Đổi mật khẩu
                    </button>
                </a>
                <a href="#" onclick = "showModal1()"><button ><i class="fa-solid fa-right-to-bracket fa-rotate-180"></i> Đăng xuất</button></a>
                        <div id="logoutModal" class="modal">
                                <div class="modal-content">
                                    <h2>Xác nhận Đăng Xuất</h2> 
                                    <div class="modal-actions">
                                        <a onclick="confirmCancel1()" href="dangxuat.php">Xác nhận</a>
                                        <button onclick="closeModal1()">Hủy</button>
                                    </div>
                                </div>
                            </div>
    </div>

    <div class="account-info">
        <h1>Đổi mật khẩu</h1>

        <form method="POST" action="" id="doimk-form">
            <div class="input-container">
                <input type="password" id="current-pass" name="current_password" placeholder=" "  autocomplete="off" class="box-input__main">
                <label>Nhập mật khẩu hiện tại</label>
                <span class="error-message" id="error-current-pass"></span>
                <span class="toggle-password" id="togglePasswordCurrent">
                    <i class="fa-solid fa-eye"></i> 
                </span>
            </div>

            <div class="input-container">
                <input type="password" id="new-pass" name="new_password" placeholder=" " maxlength="255"  autocomplete="off" class="box-input__main">
                <label>Tạo mật khẩu mới</label>
                <span class="error-message" id="error-new-pass"></span>
                <span class="toggle-password" id="togglePasswordNew">
                    <i class="fa-solid fa-eye"></i> 
                </span>
            </div>

            <div class="input-container">
                <input type="password" id="confirm-pass" name="confirm_password" placeholder=" " maxlength="255"  autocomplete="off" class="box-input__main">
                <label>Xác nhận mật khẩu</label>
                <span class="error-message" id="error-confirm-pass"></span>
                <span class="toggle-password" id="togglePasswordConfirm">
                    <i class="fa-solid fa-eye"></i> 
                </span>
            </div>
            
            <button type="submit" class="btn-cntt">Xác Nhận</button>
        </form>
    </div>
</section>
    <?php
        include("includes/footer.php");
    ?>
</body>
<script>
    // Hàm hiển thị thông báo
    function showNotification(message, isSuccess) {
        const notificationBar = document.getElementById("notification-bar");
        const notificationMessage = document.getElementById("notification-message");
        notificationMessage.textContent = message;

        notificationBar.style.backgroundColor = isSuccess ? "#0fd2b5" : "#f44336";
        notificationBar.classList.add("show");


        setTimeout(() => {
            notificationBar.classList.remove("show");
        }, 2000);
    }

    // Gửi yêu cầu AJAX khi submit form
    document.getElementById('doimk-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const currentPassword = document.getElementById('current-pass').value;
        const newPassword = document.getElementById('new-pass').value;
        const confirmPassword = document.getElementById('confirm-pass').value;

        const formData = new FormData();
        formData.append('current_password', currentPassword);
        formData.append('new_password', newPassword);
        formData.append('confirm_password', confirmPassword);

        fetch('doimk.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showNotification(data.message, true);
                setTimeout(() => {
                    window.location.href = 'taikhoan.php'; 
                }, 2000);
            } else {
                showNotification(data.message, false); 
            }
        })
        .catch(error => {
            showNotification("Đã xảy ra lỗi, vui lòng thử lại.", false);
        });
    });

    // Kiểm tra lỗi phía client trước khi gửi form
    document.getElementById("doimk-form").addEventListener("submit", function (event) {
        let hasError = false;

        document.querySelectorAll(".error-message").forEach(error => error.style.display = "none");

        const currentPassword = document.querySelector("input[name='current_password']").value.trim();
        const newPassword = document.querySelector("input[name='new_password']").value.trim();
        const confirmPassword = document.querySelector("input[name='confirm_password']").value.trim();

        if (!currentPassword) {
            hasError = true;
            const errorSpan = document.getElementById("error-current-pass");
            errorSpan.style.display = "block";
            errorSpan.textContent = "(*) Vui lòng nhập mật khẩu hiện tại.";
        }

        if (!newPassword) {
        hasError = true;
        const errorSpan = document.getElementById("error-new-pass");
        errorSpan.style.display = "block";
        errorSpan.textContent = "(*) Vui lòng nhập mật khẩu mới.";
        
        }
        else if (/\s/.test(newPassword)) {
        hasError = true;
        const errorSpan = document.getElementById("error-new-pass");
        errorSpan.style.display = "block";
        errorSpan.textContent = "(*) Mật khẩu không được chứa khoảng trắng.";
        } else if (newPassword.length < 8) {
            hasError = true;
            const errorSpan = document.getElementById("error-new-pass");
            errorSpan.style.display = "block";
            errorSpan.textContent = "(*) Mật khẩu mới phải có ít nhất 8 ký tự.";
        } else if (!/[0-9]/.test(newPassword) && !/[!@#$%^&*(),.?":{}|<>]/.test(newPassword)) {
            hasError = true;
            const errorSpan = document.getElementById("error-new-pass");
            errorSpan.style.display = "block";
            errorSpan.textContent = "(*) Mật khẩu phải chứa ít nhất 1 chữ số và 1 ký tự đặc biệt.";
        }else if (!/[0-9]/.test(newPassword)) {
            hasError = true;
            const errorSpan = document.getElementById("error-new-pass");
            errorSpan.style.display = "block";
            errorSpan.textContent = "(*) Mật khẩu mới phải chứa ít nhất 1 chữ số.";
        } else if (!/[!@#$%^&*(),.?":{}|<>]/.test(newPassword)) {
            hasError = true;
            const errorSpan = document.getElementById("error-new-pass");
            errorSpan.style.display = "block";
            errorSpan.textContent = "(*) Mật khẩu mới phải chứa ít nhất 1 ký tự đặc biệt.";
        } else if (newPassword === currentPassword) {
            hasError = true;
            const errorSpan = document.getElementById("error-new-pass");
            errorSpan.style.display = "block";
            errorSpan.textContent = "(*) Mật khẩu mới không được trùng với mật khẩu hiện tại.";
        }
        
        if (!confirmPassword) {
            hasError = true;
            const errorSpan = document.getElementById("error-confirm-pass");
            errorSpan.style.display = "block";
            errorSpan.textContent = "(*) Vui lòng xác nhận mật khẩu.";
        } else if (newPassword !== confirmPassword) {
            hasError = true;
            const errorSpan = document.getElementById("error-confirm-pass");
            errorSpan.style.display = "block";
            errorSpan.textContent = "(*) Mật khẩu mới và xác nhận không khớp.";
        }

        if (hasError) {
            event.preventDefault();
        }
    });

    // Hàm để hiển thị/ẩn mật khẩu
    const togglePasswordVisibility = (inputId, toggleId) => {
        document.getElementById(toggleId).addEventListener("click", function () {
            const input = document.getElementById(inputId);
            const icon = this.querySelector("i");
            const type = input.type === "password" ? "text" : "password";
            input.type = type;
            icon.classList.toggle("fa-eye");
            icon.classList.toggle("fa-eye-slash");
        });
    };

    // Kích hoạt chức năng ẩn/hiện mật khẩu
    togglePasswordVisibility("current-pass", "togglePasswordCurrent");
    togglePasswordVisibility("new-pass", "togglePasswordNew");
    togglePasswordVisibility("confirm-pass", "togglePasswordConfirm");
    </script>
    <script>

        function showModal() {
            document.getElementById('cancelModal').style.display = 'block';
        }
        function closeModal() {
            document.getElementById('cancelModal').style.display = 'none';
        }

        function showModal1() {
            document.getElementById('logoutModal').style.display = 'block';
        }
        function closeModal1() {
            document.getElementById('logoutModal').style.display = 'none';
        }

        function confirmCancel() {
            closeModal();
        }
        function confirmCancel() {
            closeModal1();
        }

        window.onclick = function(event) {
            const modal = document.getElementById('cancelModal');
            if (event.target === modal) {
                closeModal();
            }
        };
    </script>
</html>
