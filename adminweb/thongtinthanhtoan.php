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

    $id_user = $_SESSION["id_user"];
    $sql_sanpham = "SELECT * FROM tbl_giohang WHERE id_user = '$id_user'";
    $query_sanpham = mysqli_query($mysqli, $sql_sanpham);
?>

<!DOCTYPE html>
    <html lang="vi">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán</title>
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
        font-family: Arial, sans-serif;
        }
        .containerr{
        max-width: 1200px;
        margin:auto;
        z-index: 999;
        }
      
        .cart {
        background-color: white;
        padding: 20px;
        border: 1px solid #ddd;
        box-shadow: 0 1px 2px 0 rgba(60, 64, 67, .1), 0 2px 6px 2px rgba(60, 64, 67, .15);
        border-radius:10px;
        max-width:800px;
        margin:auto;
        margin-bottom:40px;
        margin-top:10px;
        display:block;
        }
        .sanpham {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
            position: relative;
        }
        .sanpham:last-child {
        border-bottom: none;
        }
        .anhtintuc img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
        }
        .info-container {
        flex-grow: 1;
        padding-left: 10px;
        }
        .product__price--show {
        font-size: 18px;
        color: #333;
        color:red;
        padding-top:5px;
        margin-left: 10px;
        }
        .action {
            display: flex;
            margin-top:50px; 
        }
        .minus, .plus {
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #ddd;
            cursor: pointer;
            background-color: #f3F3F3;
        }
        .quantity {
        width: 40px;
        text-align: center;
        border: 1px solid #ddd;
        height:30px;
        }
        .cart-total {
            font-size: 18px;    
            font-weight: bold;
            width: 100%;
            margin-top:20px;
            padding: 0 10px 20px;
            height:50px;
        }   
        .tamtinh{
            float:left;
            align-items: center;
            display:flex;
            height:50px;
        }
        .checkout{
            width: 50%;
            float:right;
            margin-right:16%;
        }
        .checkout button {
            background-color: #0097B2;
            color: white;
            border-radius:10px;
            float:right;
            height:50px;
            width: 30%;
            border:none;
            cursor: pointer;
        }
        .checkout button:hover {
            background-color: #0fd2b5;
        }
        .ndsanpham{
            font-size:18px;
            margin:-15px 0 20px 10px;
            font-weight: bold;
            color:black;
        }
        .ndsanpham span:hover{
            border-bottom: 1px solid #3a3a3a;
        }
        .icon{
            position: absolute;
            top: 10px;
            right: 10px; 
            font-size: 20px; 
            cursor: pointer;
        }

        .tamtinh{
            margin-left:200px;
        }
        .modal {
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }       

        .payment-modal {
            background: white;
            border-radius: 8px;
            width: 100%;
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
        }

        .list-payment__item {
            display: flex;
            align-items: center;
            margin: 10px;
            border:1px solid rgba(145, 158, 171, .239);
            border-radius:10px;
            padding:10px;
            height: 70px;
        }
        .list-payment__item:hover{
            background:#E0E0E0;
        }
        .list-payment__item.active{
            border:2px solid #0092B7 !important;
        }
        .payment-item__img img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }
        /* Khi thêm class này vào <body>, trang sẽ bị khóa cuộn */
        .btn{
            display: flex;
            align-items: center;
            margin: 10px;
            border:1px solid rgba(145, 158, 171, .239);
            border-radius:10px;
            padding:10px;
            height: 70px;
        }
        .btn:hover{
            background: rgb(15, 210, 181) !important;
        }
        

        @media (max-width: 1024px) {
            .cart {
                max-width: 90%; /* Co giãn để phù hợp màn hình */
                margin: 20px auto; /* Giảm khoảng cách để cân đối */
                padding: 15px; /* Tối ưu padding */
            }

            .sanpham {
                flex-wrap: wrap; /* Cho phép xuống dòng nếu thiếu chỗ */
                justify-content: center; /* Căn giữa các phần tử */
            }

            .anhtintuc img {
                width: 100px;
                height: 100px; /* Giảm kích thước ảnh */
            }

            .info-container {
                padding-left: 5px; /* Giảm padding để tiết kiệm không gian */
            }

            .action {
                margin-top: 20px; /* Giảm khoảng cách cho gọn */
                justify-content: center;
            }

            .checkout button {
                margin: 0 0 0 50%  ;
                padding : 10px 40px;
                width: 100%;
                text-align: center;
            }
            
        }
    </style>
    </head>
    <body>
        <?php
            include("includes/header.php");
        ?>
    <div class="containerr">
        <h3 style ='width:800px;border-bottom:1px solid #e5e5e5;margin-left:17%;font-size: 24px;margin-bottom:20px;padding-bottom:5px'><i class="fa-solid fa-arrow-left" style = 'padding-right:37%;color:#6c757d'></i>Thông Tin</h3>
        <div class="cart">
            <?php
                $total_price = 0;
                while($row = mysqli_fetch_array($query_sanpham)) {
                    $item_total = $row["giasp"] * $row["soluong"];
                    $total_price += $item_total;
            ?>
            <div class="sanpham">
                <div class="anhtintuc">
                    <img src="admin/quanlysanpham/uploads/<?php echo $row["hinhanh"] ?>">
                </div>
                <div class="info-container">
                    <a href="chitietsanpham.php?id_sanpham=<?php echo $row['id_sanpham']; ?>"><div class="ndsanpham"><span><?php echo $row["tensp"] ?></span></div></a>
                    <div class="product__price--show"><?php echo number_format($row["giasp"],0,",",".") ?><sup>đ</sup></div>
                </div>
                <div class="action">
                    Số lượng: <span style = 'color:red;margin-left:3px;'><?php echo $row["soluong"] ?></span>
                </div>
            </div>
            <?php
                }
            ?>
        </div>
        <p style = 'margin-left:200px;font-size:20px;'>THÔNG TIN KHÁCH HÀNG</p>
        <div class = 'cart'>
            <div style = 'margin-bottom:30px'><?php echo $user_data["name"] ?> <span style= 'float:right'><?php echo $user_data["phone"] ?></span></div>
            <div style = 'margin-bottom:30px'>Email: <?php echo $user_data["email"] ?></div>
            <div style = 'margin-bottom:10px;'><a href = "capnhatthongtin.php"><i class="fa-solid fa-pen-to-square" style = 'float:right'></i></a>Địa chỉ : <?php echo $user_data["address"] ?></div>
        </div>
        
        <p style = 'margin-left:200px;font-size:20px;'>PHƯƠNG THỨC THANH TOÁN</p>
        <div class ='cart'>
            <div id="payment-modal" class="modal">      
                <div class="payment-modal" >
                <div class="payment-modal__body">
                    <div class="list-payment__item" onclick="addBorder(this)">
                        <div class="payment-item__img">
                            <i class="fa-solid fa-house-user" style="color: #0097b2;font-size:40px;margin-right:10px;"></i>
                        </div>  
                        <div class="payment-item__title">
                            <p>Thanh toán khi nhận hàng </p>
                        </div>
                    </div>
                    <div data-v-05e59da4="" class="list-payment__item" onclick="addBorder(this)"><div data-v-05e59da4="" class="payment-item__img"><img data-v-05e59da4="" src="https://cdn2.cellphones.com.vn/x/media/logo/gw2/momo_vi.png" alt="payment method"></div> <div data-v-05e59da4="" class="payment-item__title" style ='margin-left:5px'><p data-v-05e59da4="">Ví MoMo</p> <!----> <!----> <!----></div> <!----> <div data-v-05e59da4="" class="payment-item__tick"><svg data-v-05e59da4="" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"></svg></div></div>
                    <div data-v-05e59da4="" class="list-payment__item" onclick="addBorder(this)"><div data-v-05e59da4="" class="payment-item__img"><img data-v-05e59da4="" src="https://cdn2.cellphones.com.vn/x400,webp,q100/media/wysiwyg/QRCode.png" alt="payment method"></div> <div data-v-05e59da4="" class="payment-item__title" style ='margin-left:5px'><p data-v-05e59da4="">Chuyển khoản ngân hàng qua mã QR</p> <!----> <!----> <!----></div> <!----> <div data-v-05e59da4="" class="payment-item__tick"><svg data-v-05e59da4="" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"></svg></div></div>
                    <div id="alert" style="display: none; color: red; text-align:center">Bạn chưa chọn phương thức thanh toán!</div>
                </div>
            </div>
        </div>
    </div>
    <div class="cart-total">
        <div class = 'tamtinh'>
            Tạm tính:<span style="margin-left: 5px;color:red;" id="cart-total"><?php echo number_format($total_price, 0, ",", ".") ?>₫</span>
        </div>   
        <div class="checkout">
        <a style="color: white"><button onclick="confirmSelection(event)">Thanh toán</button></a>

        </div>
    </div>
</div>
<div id="success-message" style="display: none; position: fixed; top: 20%; left: 50%; transform: translate(-50%, -50%); background: #d4edda; color: #155724; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); text-align: center; z-index: 9999;">
    <p>Thanh toán thành công! Đang chuyển hướng...</p>
</div>

    <?php
        include("includes/footer.php");
    ?>
<script>

    document.addEventListener('DOMContentLoaded', () => {
    const openModalBtn = document.querySelector('#payment-selector');
    const closeModalBtn = document.querySelector('#close-modal-btn');
    const modal = document.getElementById('payment-modal');
    const body = document.body;

    function openModal() {
        const modal = document.getElementById('payment-modal');
        const body = document.body;
        modal.classList.remove('hidden');
        body.classList.add('no-scroll');

        const container = document.querySelector('.containerr');
        container.classList.add('blur-background');
        
    }

    function closeModal() { 
        const modal = document.getElementById('payment-modal');
        const body = document.body;
        modal.classList.add('hidden');
        body.classList.remove('no-scroll');

        // Gỡ hiệu ứng mờ
        const container = document.querySelector('.containerr');
        container.classList.remove('blur-background');
        
    }

    // Gắn sự kiện cho các nút
    if (openModalBtn) {
        openModalBtn.addEventListener('click', openModal);
    }
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeModal);
    }
    
    // Đóng modal khi nhấp ra ngoài modal
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Đóng modal khi nhấn phím ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
});
function addBorder(selectedDiv) {
  document.getElementById("alert").style.display = "none";
  const boxes = document.querySelectorAll('.list-payment__item');
  boxes.forEach(box => box.classList.remove('active'));

  selectedDiv.classList.add('active');
}

function confirmSelection(event) {
    // Kiểm tra xem có phương thức thanh toán được chọn chưa
    const selectedDiv = document.querySelector('.list-payment__item.active');

    if (!selectedDiv) {
        document.getElementById("alert").style.display = "block";
        event.preventDefault();
    } else {
        const successMessage = document.getElementById("success-message");
        successMessage.style.display = "block";

        const modal = document.getElementById('payment-modal');
        const body = document.body;
        modal.classList.add('hidden');
        body.classList.remove('no-scroll');

        setTimeout(() => {
            window.location.href = "thanhtoan.php";
        }, 2000);
    }
}
</script>

</body>
</html>