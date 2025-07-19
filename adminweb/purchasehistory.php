<?php
    session_start();
    include("./admin/connect.php");

    $id_user = $_SESSION['id_user'];

    $sql_user = "SELECT name, email FROM tbl_user WHERE id_user = $id_user";
    $query_user = mysqli_query($mysqli, $sql_user);
    $user_info = mysqli_fetch_array($query_user);

    $sql_orders = "SELECT * FROM tbl_order WHERE id_user = $id_user ORDER BY date DESC";
    $query_orders = mysqli_query($mysqli, $sql_orders);
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
        .html,body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
        }
        .account-info-container {
            width: 80%;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            max-width: 1200px;
            width: 100%;
            margin-bottom:40px;
        }
        .account-info {
            width: 70%;
            padding: 30px;
            border-radius: 8px;
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
        .PurchaseHistory-container{
            width: 70%;
        }
        .Name-infor{
            background-color:whitesmoke;
        }
        .Name-infor h2{
            color: #0097B2;
            font-size: 28px;
        }
        .Name-infor li {
            color: #555;
        }
        .Name-infor li i {
            margin-left: 4px;
        }
        .total-bill {
            display: flex;
            align-items: center;
            justify-content: space-around;
            width: 100%;
            margin: 20px auto;
            padding: 20px;
            border-radius: 5px;
            background: white;
        }

        .left-section, .right-section {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .left-section .top, .right-section .top {
            font-size: 24px;
            font-weight: bold;
            color: #000101;
        }

        .left-section .bottom, .right-section .bottom {
            font-size: 16px;
            color: #555;
        }
        .divider {
            width: 2px;
            height: 80px;
            background-color: #0c0b0b;
            align-self: center;
            margin-left: 84px;
        }
        /*--------------------------Hóa đơn sản phẩm----------------------*/
        .product-bill {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 10px;
            border-radius: 5px;
            background-color: white;
            margin: 10px 0;
            position: relative;
        }

        .product-bill-left img {
            width: 150px;
            height: auto;
            border-radius: 8px;
        }

        .product-bill-right {
            margin-left: 20px;
            margin-bottom: 50px;
        }
        .product-bill-right p:nth-child(1){
            color: black;
            font-size: 20px;
        }
        .product-bill-right p:nth-child(2){
            color: rgb(89, 89, 89);
            font-size: 12px;
        }
        .product-bill-right p:nth-child(3){
            color: rgb(0, 151, 0);
            font-size: 15px;
            border: #007967;
            padding: 5px 5px 5px 5px;
            background-color: rgba(134, 170, 165, 0.5); 
            width: 100px;
        }
        .product-bill-right p:nth-child(4){
            color: red;
            font-size: 18px;
            font-weight: bold;
        }
        .product-bill-right p {
            margin: 5px 0;
            font-size: 16px;
            color: #333;
        }
        .check-infor {
            position: absolute;
            bottom: 10px;
            right: 10px;
            display: flex;
            gap: 10px;
        }

        .see-bill, .detail-bill {
            font-size: 15px;
            color: red;
            border: 1px solid red;
            border-radius: 5px;
            padding: 5px;
        }

        .see-bill a, .detail-bill a {
            text-decoration: none;
            border: none;
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
        @media (max-width: 480px) {
            .product-bill {
                flex-direction: column;
                align-items: flex-start;
                padding: 15px;
            }

            .product-bill-left img {
                width: 100%;
                max-width: 300px;
                margin-bottom: 15px;
            }

            .product-bill-right {
                margin-left: 0;
                margin-bottom: 10px;
                width: 100%;
            }

            .product-bill-right p {
                font-size: 14px;
                margin-bottom: 8px;
                word-break: break-word;
            }

            .product-bill-right p:nth-child(1) {
                font-size: 16px;
                font-weight: bold;
            }
            .check-infor {
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: flex-end;
            width: 100%;
            position: relative;
            }
            .see-bill, .detail-bill {
                font-size: 12px;
                width: 50%;
                max-width: 200px;
                padding: 8px;
                text-align: center;
                border-radius: 5px;
                box-sizing: border-box;
            }
            .see-bill {
                position: relative;
            }
            .detail-bill {
                position: relative;
            }
        }
    </style>
</head>
<body>
    <?php
        include("includes/header.php");
    ?>
    <div class="container" style ='padding-top:0 !important'>
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
            <section  class="PurchaseHistory-container">
            <div class="Name-infor">
                <h2><?php echo $user_info["name"] ?></h2>
                <li style ='margin:5px 0 20px 10px;'><?php echo $user_info["email"] ?></li>
            </div>
            <?php
            
            while ($row = mysqli_fetch_assoc($query_orders)) {
                $code_order = $row['code_order'];
                
                // Truy vấn các sản phẩm trong đơn hàng này
                $sql_order_details = "SELECT * FROM tbl_order_detail WHERE code_order = '$code_order'";
                $query_order_details = mysqli_query($mysqli, $sql_order_details);
                
                // Khởi tạo biến để lưu sản phẩm đắt nhất
                $max_price_product = null;
                $max_price = 0;
                
                // Khởi tạo biến đếm số loại sản phẩm
                $product_count = 0;
                
                // Lặp qua từng sản phẩm trong đơn hàng để tìm sản phẩm đắt nhất và đếm số loại sản phẩm
                while ($order_detail = mysqli_fetch_assoc($query_order_details)) {
                    $id_sanpham = intval($order_detail['id_sanpham']);
                    
                    // Truy vấn thông tin sản phẩm từ bảng tbl_sanpham
                    $sql_product = "SELECT * FROM tbl_sanpham WHERE id_sanpham = $id_sanpham";
                    $query_product = mysqli_query($mysqli, $sql_product);
                    $product = mysqli_fetch_assoc($query_product);
                    
                    // Kiểm tra nếu sản phẩm có giá cao hơn sản phẩm đắt nhất hiện tại
                    if ($product && $product['giasp'] > $max_price) {
                        $max_price = $product['giasp'];
                        $max_price_product = $product;
                    }
            
                    // Đếm số lượng sản phẩm khác nhau
                    $product_count++;
                }
                
                // Trừ đi 1 để loại trừ sản phẩm đắt nhất
                $other_product_count = $product_count - 1;
                
                // Hiển thị thông tin của đơn hàng và sản phẩm đắt nhất
                if ($max_price_product) {
                    $product_image = $max_price_product['hinhanh'];
                    $product_name = $max_price_product['ten_sanpham'];
                } else {
                    $product_image = '';
                    $product_name = 'Chưa có sản phẩm';
                }
            ?>
            <div class="product-bill">
                <div class="product-bill-left">
                    <p><img src="admin/quanlysanpham/uploads/<?php echo $product_image; ?>" alt=""></p>
                </div>
                <div class="product-bill-right">
                    <p><?php echo $row["code_order"] ?></p>
                    <p><?php echo $product_name; ?>
            <?php if ($other_product_count > 0) { ?>
                và <?php echo $other_product_count; ?> sản phẩm khác
            <?php } ?>
            </p>
            <?php
                if ($row['status'] == 0) {
                    echo '<p style="display: inline; margin: 0; padding: 4px;background-color: rgba(210, 244, 56, 0.2);color: rgb(219, 227, 60); ">Chờ xác nhận</p>';
                } elseif ($row['status'] == 1) {
                    echo '<p style="display: inline; margin: 0; padding: 4px;background-color:  rgba(3, 139, 78, 0.4);color: rgb(2, 82, 15);">Đã xác nhận</p>';
                } elseif ($row['status'] == 2) {
                    echo '<p style="display: inline; margin: 0; padding: 4px;background-color: rgba(141, 170, 240, 0.5);color: rgb(1, 65, 204);">Đang vận chuyển</p>';
                } elseif ($row['status'] == 3) {
                    echo '<p style="display: inline; margin: 0; padding: 4px;background-color:  rgba(166, 254, 194, 0.886); color: rgb(9, 182, 38);">Đã giao hàng</p>';
                }
                elseif ($row['status'] == 5) {
                    echo '<p style="display: inline; margin: 0; padding: 4px;background-color:  rgba(255, 113, 113, 0.601); color: rgb(207, 8, 5);">Đang chờ hủy</p>';
                }
                else {
                    echo '<p style="display: inline; margin: 0; padding: 4px;background-color:  rgba(255, 113, 113, 0.601); color: rgb(207, 8, 5);">Đã hủy</p>';
                }
                ?>

                    <p><?php echo number_format($row["total_price"],0,",",".") ?><sup>đ</sup></p>
                </div>  
                <div class="check-infor">
                        <?php if ($row['status'] == 0): ?>
                            <div class="see-bill">
                                <a class="see-bill" href="#" onclick="showModal()">Yêu cầu hủy đơn</a>
                            </div>
                            <!-- Modal -->
                            <div id="cancelModal" class="modal">
                                <div class="modal-content">
                                    <h2>Xác nhận hủy đơn</h2>
                                    <p style = 'margin-top:10px;'>Bạn có chắc chắn muốn yêu cầu hủy đơn này?</p>   
                                    <div class="modal-actions">
                                        <a onclick="confirmCancel()" href="huydonhang.php?code_order=<?php echo $row['code_order']; ?>">Xác nhận</a>
                                        <button onclick="closeModal()">Hủy</button>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="detail-bill">
                            <a class="detail-bill" href="chitietdonhang.php?code_order=<?php echo $row['code_order']; ?>">Chi tiết hóa đơn</a>
                        </div>              
                    </div>
      
            </div> 
            <?php
                }
            ?>
        </section>
    </div>
    <br>
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
