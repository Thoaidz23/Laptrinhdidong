<?php
    include("./admin/connect.php");
    session_start();
    $id_user = $_SESSION["id_user"];
    $sql_sanpham = "SELECT * FROM tbl_giohang WHERE id_user = '$id_user'";
    $query_sanpham = mysqli_query($mysqli, $sql_sanpham);

    $count_sanpham = mysqli_num_rows($query_sanpham);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="assets/css/index.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        .container {
        max-width: 1200px;   
        flex: 1;    
        }
        .cart {
        background-color: white;
        padding: 20px;
        border: 1px solid #ddd;
        box-shadow: 0 1px 2px 0 rgba(60, 64, 67, .1), 0 2px 6px 2px rgba(60, 64, 67, .15);
        border-radius:10px;
        max-width:800px;
        margin:auto;
        display:block;
        width: 1200px;
        margin-bottom: 30px !important;
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
            width: 50%;
            float:left;
            align-items: center;
            display:flex;
            height:50px;
        }
        .checkout{
            width: 50%;
            float:right;
        }
        .checkout button {
            background-color: #0097B2;
            color: white;
            border-radius:10px;
            float:right;
            height:50px;
            width: 30%;
            border:none;
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
        @media (max-width: 1024px) {
            .cart {
                max-width: 90%;
                margin: 20px auto;
                padding: 15px;
            }

            .sanpham {
                flex-wrap: wrap;
                justify-content: center;
            }

            .anhtintuc img {
                width: 100px;
                height: 100px;
            }

            .info-container {
                padding-left: 5px;
            }

            .action {
                margin-top: 20px;
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
    <div class="container">
    <h3 style ='border-bottom:1px solid #e5e5e5;margin-bottom:25px;padding-bottom:5px;font-size: 24px'>
        <a id="backButton" style="cursor: pointer">
            <i class="fa-solid fa-arrow-left" style = 'padding-right:35%;color:#6c757d; font-size: 24px'></i>
        </a>
        Giỏ hàng của bạn</h3>
    <div class="cart">
    <?php if ($count_sanpham > 0): ?>
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
                <a href=""><div class="ndsanpham"><span><?php echo $row["tensp"] ?></span></div></a>
                <div class="product__price--show"><?php echo number_format($row["giasp"], 0, ",", ".") ?><sup>đ</sup></div>
            </div>
            <div class="icon">
                <i class="fa-solid fa-trash-can delete-item" data-id="<?php echo $row["id_sanpham"]; ?>"></i>
            </div>
            <div class="action">
                <span class="minus" data-id="<?php echo $row["id_sanpham"]; ?>" data-price="<?php echo $row["giasp"]; ?>">-</span>
                <input class="quantity" type="text" value="<?php echo $row["soluong"] ?>" readonly>
                <span class="plus" data-id="<?php echo $row["id_sanpham"]; ?>" data-price="<?php echo $row["giasp"]; ?>">+</span>
            </div>
        </div>
        <?php } ?>
        <div class="cart-total">
            <div class="tamtinh">
                Tạm tính:<span style="margin-left: 5px;color:red;" id="cart-total"><?php echo number_format($total_price, 0, ",", ".") ?>₫</span>
            </div>   
            <div class="checkout">
                <button><a href="thongtinthanhtoan.php" style="color: white">Mua Ngay</a></button>
            </div>
        </div>
    <?php else: ?>
        <img style="margin-left: 130px; width: 500px; height: 300px" src="https://cdn.tgdd.vn/mwgcart/v2/vue-pro/img/empty-cart.ae25f7ba3c224d5125553b3d9.png" alt="">
        <h2 style="margin-top: 20px;margin-left: 297px">Giỏ hàng trống</h2>
        <p style="text-align: center; color: #999; padding: 20px;">Không có sản phẩm nào trong giỏ hàng</p>
    <?php endif; ?>
</div>
        
    </div>
        <?php
        include("includes/footer.php");
        ?>
    
        
</body>
<script>
   // Nút xóa khỏi giỏ hàng
    document.addEventListener("DOMContentLoaded", function () {
    const cartTotalElement = document.getElementById("cart-total");

    // Gắn sự kiện click vào các biểu tượng thùng rác
    document.querySelectorAll(".delete-item").forEach((icon) => {
        icon.addEventListener("click", function () {
            const productId = this.getAttribute("data-id");
            const parentElement = this.closest(".sanpham");
            const productPrice = parseFloat(
                parentElement.querySelector(".product__price--show").textContent.replace(/\./g, "").replace("₫", "")
            );
            const productQuantity = parseInt(
                parentElement.querySelector(".quantity").value
            );
            const productTotal = productPrice * productQuantity;

            // Gửi yêu cầu xóa sản phẩm mà không có xác nhận
            fetch("deletefromcart.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `id_sanpham=${productId}`,
            })
            .then((response) => response.text())
            .then((data) => {
                if (data.trim() === "success") {
                    // Xóa sản phẩm khỏi giao diện
                    parentElement.remove();

                    // Tính lại tổng giá
                    const currentTotal = parseFloat(
                        cartTotalElement.textContent.replace(/\./g, "").replace("₫", "")
                    );
                    const newTotal = currentTotal - productTotal;

                    cartTotalElement.textContent = newTotal.toLocaleString("vi-VN") + "₫";

                    // Kiểm tra số lượng sản phẩm còn lại
                    const remainingProducts = document.querySelectorAll(".sanpham").length;
                    if (remainingProducts === 0) {
                        // Tải lại trang nếu giỏ hàng trống
                        window.location.reload();
                    }
                } else {
                    alert("Lỗi khi xóa sản phẩm. Vui lòng thử lại!");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("Lỗi khi gửi yêu cầu!");
            });
        });
    });
});



//Cập nhật số lượng
document.addEventListener("DOMContentLoaded", function () {
     document.querySelectorAll(".plus").forEach((plusBtn) => {
         plusBtn.addEventListener("click", function () {
             const productId = this.dataset.id;
             const productPrice = parseFloat(this.dataset.price);
             updateQuantity(this, productId, 1, productPrice); // Tăng số lượng
         });
     });

     document.querySelectorAll(".minus").forEach((minusBtn) => {
         minusBtn.addEventListener("click", function () {
             const productId = this.dataset.id;
             const productPrice = parseFloat(this.dataset.price);
             updateQuantity(this, productId, -1, productPrice); // Giảm số lượng
         });
     });

     // Hàm cập nhật số lượng
     function updateQuantity(button, productId, change, productPrice) {
         const quantityInput = button.closest(".action").querySelector(".quantity");

         if (!quantityInput) {
             console.error("Không tìm thấy phần tử quantity!");
             return;
         }

         const currentQuantity = parseInt(quantityInput.value);
         const newQuantity = currentQuantity + change;

         if (newQuantity < 1) {
             alert("Số lượng không được nhỏ hơn 1.");
             return;
         }

         fetch("updatecart.php", {
             method: "POST",
             headers: {
                 "Content-Type": "application/x-www-form-urlencoded",
             },
             body: `id_sanpham=${productId}&change=${change}`,
         })
             .then((response) => response.text())
             .then((data) => {
                 if (data.trim() === "success") {
                     quantityInput.value = newQuantity;

                     const cartTotalElement = document.getElementById("cart-total");
                     const currentTotal = parseFloat(
                         cartTotalElement.textContent.replace(/\./g, "").replace("₫", "")
                     );
                     const newTotal = currentTotal + change * productPrice;

                     cartTotalElement.textContent = newTotal.toLocaleString("vi-VN") + "₫";
                 } else {
                     alert("Lỗi khi cập nhật giỏ hàng. Vui lòng thử lại!");
                 }
             })
             .catch((error) => {
                 console.error("Error:", error);
                 alert("Lỗi khi gửi yêu cầu!");
             });
     }
 });


        document.getElementById('backButton').addEventListener('click', function () {
            window.history.back();
        });



</script>
</html>