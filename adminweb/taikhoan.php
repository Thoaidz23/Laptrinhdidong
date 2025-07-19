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
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
    
        .account-info-container {
            width: 80%;
            margin: 0 auto;
            flex:1;
        }
    
        .account-info-container {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            max-width:1200px;
                width:100%;
        }
    
        .account-info {
            width: 70%;
            padding: 30px;
            border-radius: 8px;
            background:white;
            margin-bottom:30px;
        }
    
        .account-info h1 {
            color: #333;
            margin-bottom: 50px;
            font-size: 24px;
            Text-Align:center;
        }
    
        .account-detail .info-item {
            margin-bottom:40px;
            padding-bottom:10px;
            max-width:500px;
            margin-left:auto;
            margin-right:auto;
            width: auto;
            border-bottom:1px solid #ccc;
        }
    
        .account-detail label {
            font-weight: bold;
            color: #0097B2;
        }
    
        .account-detail span {
            margin: 0.5rem 0 0;
            font-size: 18px;
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
                <a href="">
                    <button style = 'border:2px solid #0097B2;color:#0097B2;background-color:#E2ECFD'>
                        <i class="fa-solid fa-house"></i> Thông tin tài khoản
                    </button>
                </a>
                <a href="purchasehistory.php"><button><i class="fa-solid fa-receipt"></i> Lịch Sử Mua Hàng</button></a>
                <a href="capnhatthongtin.php"><button ><i class="fa-solid fa-pen-nib"></i> Cập nhật thông tin</button></a>
                <a href="doimk.php"><button ><i class="fa-solid fa-rotate"></i> Đổi mật khẩu</button></a>
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
            <div class="account-detail">
                <div class="info-item">
                    <label for="username">Họ và tên:</label>
                    <span id="username"><?php echo $user_data["name"] ?></span>
                </div>

                <div class="info-item">
                    <label for="email">Email:</label>
                    <span id="email"><?php echo $user_data["email"] ?></span>
                </div>

                <div class="info-item">
                    <label for="phone">Số điện thoại:</label>
                    <span id="phone"><?php echo $user_data["phone"] ?></span>
                </div>

                <div class="info-item">
                    <label for="gender">Địa chỉ:</label>
                    <span id="gender"><?php echo $user_data["address"] ?></span>
                </div>

            </div>
        </div>
    </section>
    <?php
        include("includes/footer.php");
    ?>
</body>
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
