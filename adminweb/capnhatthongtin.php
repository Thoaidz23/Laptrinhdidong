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

    $error_message = "";
    $name_err = $phone_err = $address_err = "";
    $success_message = "";

    if (isset($_POST["capnhatthongtin"])) {

        $name = mysqli_real_escape_string($mysqli, $_POST['name']);
        $phone = mysqli_real_escape_string($mysqli, $_POST['phone']);
        $address = mysqli_real_escape_string($mysqli, $_POST['address']);
        $email = $_POST['email'];


        if (empty($name) || empty($phone) || empty($address)) {
            if (empty($name)) {
                $name_err = "(*) Vui lòng nhập tên";
            }
            if (empty($phone)) {
                $phone_err = "(*) Vui lòng nhập số điện thoại";
            }
            if (empty($address)) {
                $address_err = "(*) Vui lòng nhập địa chỉ";
            }
        } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {

            $phone_err = "(*) Số điện thoại không hợp lệ";
        } else {

            $sql_update = "UPDATE tbl_user SET name = '$name', phone = '$phone', address = '$address' WHERE id_user = $id_user";
            $_SESSION["name"] = $name;
            mysqli_query($mysqli, $sql_update);

            $success_message = "Cập nhật thông tin thành công";
        }
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
        }
        .account-info-container {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            max-width:1200px;
            width:100%;
        }
        .account-info {
            background:white;
            width: 70%;
            padding: 30px;
            padding-bottom:50px;
            border-radius: 8px;
        }
        .account-info h1 {
            color: #333;
            margin-bottom: 50px;
            font-size: 24px;
            text-align: center;
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
            padding: 10px 0 10px 10px;
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
        .btn-cntt {
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
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .success-message {
            color: white;
            background-color: rgb(59, 169, 8);
            font-size: 18px;
            padding: 10px 20px;
            margin-left: 240px;
            margin-bottom: 20px;
            text-align: center;
            border-radius: 5px;
            display: inline-block;
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
    <section class="account-info-container">
        <div class="account-actions-box">
            <a href="taikhoan.php"><button><i class="fa-solid fa-house"></i> Thông tin tài khoản</button></a>
            <a href="purchasehistory.php"><button><i class="fa-solid fa-receipt"></i> Lịch sử mua hàng</button></a>
            <a href="#">
                <button style='border:2px solid #0097B2;color:#0097B2;background-color:#E2ECFD'>
                    <i class="fa-solid fa-pen-nib"></i> Cập nhật thông tin
                </button>
            </a>
            <a href="doimk.php"><button><i class="fa-solid fa-rotate"></i> Đổi mật khẩu</button></a>
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
            <h1>Thông tin tài khoản</h1>

            <?php if (!empty($success_message)): ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="input-container">
                    <input type="text" placeholder=" " required autocomplete="off" class="box-input__main" value="<?php echo $user_data["name"] ?>" name="name">
                    <label>Họ và tên</label>
                    <?php if (!empty($name_err)): ?>
                        <span class="error-message"><?php echo $name_err; ?></span>
                    <?php endif; ?>
                </div>

                <div class="input-container">
                    <input style="cursor: default" type="email" placeholder=" " maxlength="255" required autocomplete="off" class="box-input__main" value="<?php echo $user_data["email"] ?>" name="email" readonly>
                    <label>Email</label>
                </div>

                <div class="input-container">
                    <input type="tel" placeholder=" " maxlength="10" required autocomplete="off" class="box-input__main" value="<?php echo $user_data["phone"] ?>" name="phone">
                    <label>Số điện thoại</label>
                    <?php if (!empty($phone_err)): ?>
                        <span style="margin-left: 10px" class="error-message"><?php echo $phone_err; ?></span>
                    <?php endif; ?>
                </div>

                <div class="input-container">
                    <input type="text" placeholder=" " maxlength="255" required autocomplete="off" class="box-input__main" value="<?php echo $user_data["address"] ?>" name="address">
                    <label>Địa chỉ</label>
                    <?php if (!empty($address_err)): ?>
                        <span class="error-message"><?php echo $address_err; ?></span>
                    <?php endif; ?>
                </div>

                <button type="submit" name="capnhatthongtin" class="btn-cntt">Cập nhật</button>
            </form>
        </div>
    </section>
    <?php
        include("includes/footer.php");
    ?>
</body>
<script>
        <?php if (!empty($success_message)): ?>
            setTimeout(function() {
                window.location.href = "taikhoan.php";
            }, 3000);
        <?php endif; ?>
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
