<?php
    session_start();
    include("./admin/connect.php");

    $id_user = $_SESSION['id_user'];

    $sql_user = "SELECT name, email FROM tbl_user WHERE id_user = $id_user";
    $query_user = mysqli_query($mysqli, $sql_user);
    $user_info = mysqli_fetch_array($query_user);

    $sql_orders = "SELECT * FROM tbl_order WHERE code_order = '$_GET[code_order]'";
    $query_orders = mysqli_query($mysqli, $sql_orders);
    $order_info = mysqli_fetch_assoc($query_orders);


    $sql_order_detail = "SELECT * FROM tbl_order_detail, tbl_sanpham WHERE tbl_order_detail.id_sanpham = tbl_sanpham.id_sanpham AND tbl_order_detail.code_order = '$_GET[code_order]'";
    $query_order_detail = mysqli_query($mysqli, $sql_order_detail);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Lịch sử mua hàng</title>
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
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
        max-width: 1200px;
        width: 100%;
        }

        .account-info {
        width: 70%;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        background: white;
        }

        .account-info h1 {
        color: #333;
        margin-bottom: 50px;
        font-size: 24px;
        text-align: center;
        }

        .account-detail .info-item {
        margin-bottom: 40px;
        padding-bottom: 10px;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
        width: auto;
        border-bottom: 1px solid #ccc;
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

        .detail-bill-container{
        width: 70%;
        background-color: white;
        border-radius: 5px;  
        position: relative;
        }
        .title-div-detailBill i {
            position: absolute;
            left: 20px;
        }
        .title-div-detailBill{
            padding: 10px 0px 5px 0px;
        }
        .title-div-detailBill h2 {
            text-align: center;
            color: black;
        }
        .title-div-detailBill a i {
            font-size: 25px;
            margin-top: 4px;
        }
        .detail-bill-container span{
            font-weight: bold;

        }
        .total-product{
        display: flex;
        position: relative;
        }
        .detail-product span:nth-child(1){
            font-size: 25px;
        }
        .detail-product p:nth-child(4){
            position: absolute;
            right: 20px;
            top: 68px;
        }
        .detail-product p:nth-child(4) span{
            color: rgb(2, 82, 15);
            font-size: 18px;
            padding: 5px 5px 0px 5px;
        }
        .detail-product p {
            line-height: 1.5;
            font-size: 18px;
            margin-left: 20px;
        }
        .detail-product i {
        margin-right: 10px;
        color: rgb(188, 188, 188,1);
        font-size: 25px;
        padding-left: 5px;
        }

        .total-product-left img {
        margin-top: 20px;
        width: 150px;
        height: auto;
        border-radius: 8px;
        }
        .total-product-right p:nth-child(1){
            margin-top: 30px;
        }
        .total-product-right p {
        color: #3f4db7;
        font-size: 18px;
        line-height: 1.5;
        }   
        .total-product-right span {
        color: red;
        }

        .divider {
        border-top: 1px solid #ccc;
        margin: 10px 20px;
        }
        .pay-container{
        width: 70%;
        margin-left: 30%;
        background-color: white;
        border-radius: 5px;
        padding-top: 20px;
        }
        .total-pay i {
        margin-left: 10px;
        color: #0097B2;
        margin-right: 5px;
        }
        .total-pay-content {
        display: flex;
        justify-content: space-between;
        }

        .total-pay-left, .total-pay-right {
        display: flex; 
        flex-direction: column;
        }

        .total-pay-left p, .total-pay-right p {
        font-size: 18px;
        margin: 0;
        line-height: 1.5;
        }
        .total-pay-right p {
        margin-right: 10px;
        }
        .total-pay-left p{
        margin-left: 10px;
        }
        .total-pay-content span {
        font-weight: bold;
        }
        .total-pay-content p:nth-child(2) span {
        color: rgb(42, 189, 52);
        }


        .infor-pay-container{
        width: 70%;
        margin-left: 30%;
        background-color: white;
        border-radius: 5px;
        padding-top: 20px;
        }
        .detail-inforGuest h2{
        font-size: 25px;
        margin-bottom: 10px;
        }
        .detail-inforGuest h2 i{
        padding-right: 10px;
        }
        .detail-inforGuest p {
        font-size: 18px;
        line-height: 2;
        }
        .detail-inforGuest i {
        padding-left: 10px;
        padding-right: 20px;
        color: #0097B2;
        }

    </style>
</head>
<body>
    <?php
        include("includes/header.php");
    ?>
    <div class="container">
        <section class="account-info-container">
            <div class="account-actions-box">
                <a href="taikhoan.php"><button><i class="fa-solid fa-house"></i>  Thông tin tài khoản</button></a>
                    <a href="">
                        <button style = 'border:2px solid #0097B2;color:#0097B2;background-color:#E2ECFD'>
                        <i class="fa-solid fa-receipt"></i> Lịch Sử Mua Hàng
                        </button>
                    </a>
                    <a href="capnhatthongtin.php"><button ><i class="fa-solid fa-pen-nib"></i> Cập nhật thông tin</button></a>
                    <a href="doimk.php"><button ><i class="fa-solid fa-rotate"></i> Đổi mật khẩu</button></a>
                    <a href="dangxuat.php"><button ><i class="fa-solid fa-right-to-bracket fa-rotate-180"></i> Đăng xuất</button></a>
                </div>
            
            <div class="detail-bill-container">
                <div class="title-div-detailBill">
                    <h2><a id="backButton" style="cursor: pointer"><i class="fa-solid fa-arrow-left"></i></a></i>Chi Tiết Đơn Hàng</h2>
                </div>
                <div class="divider"></div>
                <div class="detail-product">
                    <p>Mã đơn hàng: <span><?php echo $_GET["code_order"] ?></span></p>
                    <?php
                        $order_date = $order_info['date'];
                        $date_obj = new DateTime($order_date);
                        $date = $date_obj->format('d/m/Y');
                        $time = $date_obj->format('H:i');

                        $status = $order_info['status'];
                        
                    ?>
                    <p>Ngày mua: <?php echo $date ?></p>
                    <p>Thời gian: <?php echo $time ?></p>
                    <?php
                if ($status == 0) {
                    echo '<p style="display: inline; margin: 0; padding: 4px;background-color: rgba(210, 244, 56, 0.2);color: rgb(219, 227, 60); ">Chờ xác nhận</p>';
                } elseif ($status == 1) {
                    echo '<p style="display: inline; margin: 0; padding: 4px;background-color:  rgba(3, 139, 78, 0.4);color: rgb(2, 82, 15);">Đã xác nhận</p>';
                } elseif ($status == 2) {
                    echo '<p style="display: inline; margin: 0; padding: 4px;background-color: rgba(141, 170, 240, 0.5);color: rgb(1, 65, 204);">Đang vận chuyển</p>';
                } elseif ($status == 3) {
                    echo '<p style="display: inline; margin: 0; padding: 4px;background-color:  rgba(166, 254, 194, 0.886); color: rgb(9, 182, 38);">Đã giao hàng</p>';
                }
                elseif ($status == 5) {
                    echo '<p style="display: inline; margin: 0; padding: 4px;background-color:  rgba(255, 113, 113, 0.601); color: rgb(207, 8, 5);">Đang chờ hủy</p>';
                }
                else {
                    echo '<p style="display: inline; margin: 0; padding: 4px;background-color:  rgba(255, 113, 113, 0.601); color: rgb(207, 8, 5);">Đã hủy</p>';
                }
                ?>

                </div>
                <?php
                    while($row = mysqli_fetch_array($query_order_detail)) {
                ?>
                <div class="total-product">
                    <div class="total-product-left">
                        <p><img src="admin/quanlysanpham/uploads/<?php echo $row['hinhanh']; ?>" alt=""></p>
                    </div>
                    <div class="total-product-right">
                        <p><?php echo $row["ten_sanpham"] ?></p>
                        <p>Giá : <span><?php echo number_format($row["giasp"],0,",",".") ?><sup>đ</sup></span></p>
                        <p>Số lượng: <span><?php echo $row["soluongmua"] ?></span></p>
                    </div>
                  
                </div>
                <div class="divider"></div>
                <?php
                    }
                ?>
                <br>
            </div>                
    </div>
    <br>
    <div class="container">
        <div class="pay-container">
            <div class="total-pay">
                <h2><i class="fa-regular fa-credit-card"></i>Thông tin thanh toán</h2>
                <br>
                <div class="total-pay-content">
                    <div class="total-pay-left">
                        <p>Tổng tiền sản phẩm:</p>
                        <p>Phí vận chuyển:</p>
                    </div>
                    <div class="total-pay-right">
                        <p><?php echo number_format($order_info["total_price"],0,",",".") ?><sup>đ</sup></p>
                        <p>Miễn phí</p>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="total-pay-content">
                    <div class="total-pay-left">
                        <p>Phải thanh toán:</p>
                        <p>Đã thanh toán:</p>
                    </div>
                    <div class="total-pay-right">
                        <p><span><?php echo number_format($order_info["total_price"],0,",",".") ?><sup>đ</sup></span></p>
                        <p><span><?php echo number_format($order_info["total_price"],0,",",".") ?><sup>đ</sup></span></p>
                    </div>
                </div>
            </div> 
            <br>
        </div>
        
        <br>
       <div class="infor-pay-container">
        
        <div class="detail-inforGuest">
            <h2><i class="fa-solid fa-circle-info"></i>Thông tin khách hàng</h2>
            <p><i class="fa-solid fa-user"></i><?php echo $user_info["name"] ?></p>
            <p><i class="fa-solid fa-phone"></i><?php echo $user_info["email"] ?></p>
            <?php
                $user_id = $_SESSION['id_user'];
                $sql_user = "SELECT name, email, address FROM tbl_user WHERE id_user = $user_id";
                $query_user = mysqli_query($mysqli, $sql_user);
                $user_info = mysqli_fetch_assoc($query_user);
            ?>
            <p><i class="fa-solid fa-address-book"></i><?php echo $user_info["address"] ?></p>
        </div> 
        <br>
         

        </div>
       </div>
    </div>
    
    <?php
        include("includes/footer.php");
    ?>
   

   <script>
        document.getElementById('backButton').addEventListener('click', function () {
            window.history.back();
        });
    </script>
    
</body>
</html>
