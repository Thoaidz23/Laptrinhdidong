<?php
    include("./admin/connect.php");
    session_start();
    $sql_sanpham = "SELECT * FROM tbl_danhmucsanpham, tbl_sanpham WHERE tbl_danhmucsanpham.id_dmsp = tbl_sanpham.id_dmsp AND id_sanpham = '$_GET[id_sanpham]'";
    $query_sanpham = mysqli_query($mysqli, $sql_sanpham);
    $sanpham = mysqli_fetch_array($query_sanpham);
?>
    
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Khám phá iPhone 15 Plus 512GB với thiết kế tinh tế và công nghệ tiên tiến.">
    <meta name="keywords" content="iPhone 15, iPhone 15 Plus, điện thoại, Apple, smartphone">
    <title><?php echo $sanpham["ten_sanpham"] ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="index.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }
        .thanhdieuhuong {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 63px;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            left: 0;
            width: 100%;
            background-color: #ffffff;
            will-change: transform;
            overflow-x: auto;
            white-space: nowrap !important;
        }
        .trangchu {
            align-items: center;
        }
        .main {
            display: flex;
            margin: 20px 5%;
            padding: 15px;
            background-color: #fff;
        }
        .bleft, .bright {
            padding: 20px;
            display: flex;
            flex-direction: column;
        }
        .bleft {
            flex: 1;
        }
        .bright {
            width: 40%;
        }
        #khunganh {
            width: 100%;
            height: 440px;
            background-color: #fff;
            overflow: hidden;
            position: relative;
            padding-bottom: 80px;
            box-shadow: 0 1px 2px 0 rgba(60, 64, 67, .1), 0 2px 6px 2px rgba(60, 64, 67, .15);
            border-radius: 30px;
        }
        #khunganh:hover .left, #khunganh:hover .right {
            display: block;
        }
        .anh img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .dacdiem {
            margin: 20px 0;
        }
        .khungTSKT {
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            flex: 1;
            background-color: #f2f2f2;
            overflow-y: auto;
            max-height: 305px;
        }
        .value-TSKT {
            background-color: white;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            overflow-y: auto;
            max-height: 220px;
        }
        .khungTSKT h3 {
            font-size: 20px;
            margin-bottom: 20px;
        }
        .spec-item {
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
            margin-bottom: 10px;
            width: 100%;
            border-bottom: 1px solid #f5f5f5;
        }
        .spec-title {
            font-weight: bold;
            color: #333;
            width: 50%;
        }
        .spec-value {
            color: #555;
            text-align: left;
            flex-basis: 50%;
            padding-left: 40px;
        }
        .btn {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            align-items:center;
        }
        .next, .prev {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-size: 18px;
            display: none;
        }
        .prev {
            left: 0px;
            border-top-left-radius: 0;
            border-top-right-radius: 30px;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 30px;
        }
        .prev.active {
            border: 2px solid #707070;
        }
        .next {
            right: 0px;
            border-top-left-radius: 30px;
            border-top-right-radius: 0;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 0;
        }
        #khunganh:hover .prev, #khunganh:hover .next {
            display: block;
        }
        .buy-button, .shop-button {
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .buy-button{
            background-color: #0097B2;
            color: white;
            width: 80%;
            height: 70px;
            font-size:20px;
            font-weight:bold;
            padding-left:26%;
            padding-top:5%;
            display:block;      
        }
        .buy-button:hover {
            background-color: #0fd2b5;
        }
        .shop-button {
            width: 20%;
            height: 70px;
            margin: 0 10px 0 10px;
            background-color: white;
            color: #0097B2;
            border: 2px solid #0097B2;
            font-size: 9px;
        }
        .shop-button:hover {
            background-color: #f2f2f2;
        }
        .khungtintuc {
            margin: 20px 0;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 1px 2px 0 rgba(60, 64, 67, .1), 0 2px 6px 2px rgba(60, 64, 67, .15);
        }
        .tintuc {
            display: flex;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
        }
        .tintuc:focus {
            text-decoration: underline;
        }
        .tintuc:hover {
            text-decoration: underline;
        }
        .anhtintuc img {
            width: 120px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 10px;
        }
        .ndtintuc {
            font-size: 16px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            line-height: 1.5;
            height: 4.5em;
        }
        .tendt {
            border-bottom: 2px solid #e0e0e0;
            margin: 0 76px 0 76px;
            padding-bottom: 8px;
        }
        .thumbnails {
            display: flex;
            justify-content: left;
            margin-top: 10px;
            position: relative;
            z-index: 1;
            width: 100%;
        }
        .thumbnails img {
            box-shadow: 0 1px 2px 0 rgba(60, 64, 67, .1), 0 2px 6px 2px rgba(60, 64, 67, .15);
            width: 60px;
            height: 60px;
            object-fit: cover;
            margin: 0 5px;
            cursor: pointer;
            border-radius: 10px;
            border: 2px solid transparent;
            transition: border 0.3s;
        }
        .thumbnails img:hover {
            border: 2px solid #0097b2;
        }
        .thumbnails img.active {
            border: 2px solid #0097b2;
        }
        .block-breadcrumbs {
            padding: 20px 0;
            top: 50px;
            max-width: 1400px;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
        }
        .ul1 {
            width: 100%;
            list-style: none;
            padding-left: 9%;
            padding-top:0.5%;
            margin-left: auto;
            display: flex;
            align-items: center;
            height: 30px;
        }
        .ul1 > li {
            display: inline-flex;
            align-items: center;
        }
        .ul1 > li a {
            color: #707070;
            text-decoration: none;
            font-size: 12px;
            margin: 0 5px;
            display: flex;
            align-items: center;
        }
        .ul1 > li a:hover {
            color: #707070;
        }
        .ul1 > li a:after {
            content: '';
            margin-left: 5px;
        }
        .ul1 > li:first-child a:after {
            content: '';
        }
        .ul1 > li:last-child a:after {
            content: '';
        }
        .ul1 > li p {
            color: #333;
            font-size: 12px;
        }
        svg {
            width: 15px;
            height: 15px;
            fill: #707070;
            margin: 5px 5px ;
        }
        .button__breadcrumb-item {
            display: flex;
            align-items: center;
        }
        .button__home {
            color: #707070;
        }
        .button__home:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .ul1 {
                flex-direction: row !important;
                align-items: flex-start;
                
            }
            .ul1 > li {
                margin-bottom: 5px;
            }
            .ul1 > li a {
                font-size: 12px;
            }
            .ul1 > li p {
                font-size: 12px;
            }
            .button__home.nuxt-link-active{
                margin-top:5px !important;
            }
        }
        .giatien {
            font-weight: bold;
            margin: 35px 0 0px 20px;
            font-size: 22px;
            color: #0097B2;
        }
        .ul1 {
            padding-bottom: 5px;
        }
        @media (max-width: 768px) {
            body {
                font-size: 14px;
            }
            .main {
                flex-direction: column;
                margin: 10px 5%;
                padding: 10px;
            }
            .bleft, .bright {
                width: 100%;
                padding: 10px;
            }
            .bright {
                width: 100%;
            }
            #khunganh {
                height: 300px;
            }
            .khungTSKT {
                padding: 10px;
                max-height: 200px;
                overflow-y: scroll;
            }
            .spec-item {
                flex-direction: column;
                align-items: flex-start;
            }
            .spec-title {
                width: 100%;
                margin-bottom: 5px;
            }
            .spec-value {
                width: 100%;
                padding-left: 0;
            }
            .next, .prev {
                padding: 8px;
                font-size: 16px;
            }
            .giatien {
                font-size: 18px;
                margin: 25px 0 0px 10px;
            }
            .thumbnails {
                flex-wrap: wrap;
                justify-content: center;
            }
            .thumbnails img {
                width: 50px;
                height: 50px;
                margin: 5px;
            }
            .tintuc {
                flex-direction: row;
                align-items: flex-start;
                padding: 10px;
                margin: 10px 0;
            }
            .anhtintuc img {
                width: 100px;
                height: 70px;
                margin-right: 0;
            }
            .ndtintuc {
                font-size: 14px;
                margin-top: 5px;
            }
            .ul1 {
                flex-direction: column;
                align-items: flex-start;
            }
            .ul1 > li {
                margin-bottom: 5px;
            }
            .ul1 > li a {
                font-size: 12px;
            }
            .ul1 > li p {
                font-size: 12px;
            }
            .buy-button {
                width: 100%;
                height: 60px;
                padding-left:12% !important;
            }
            .shop-button {
                width: 100%;
                height: 60px;
                margin: 10px 0;
            }
        }

        @media (max-width: 480px) {
            .main {
                margin: 5px 2%;
                padding: 5px;
            }
            .bleft, .bright {
                width: 100%;
                padding: 5px;
            }
            .khunganh {
                height: 250px;
            }
            .giatien {
                font-size: 16px;
                margin: 15px 0 0px 5px;
            }
            .thumbnails img {
                width: 40px;
                height: 40px;
            }
            .anhtintuc img {
                width: 80px;
                height: 60px;
            }
            .ndtintuc {
                font-size: 12px;
            }
            .tendt {
                margin: 0 30px 0 30px;
                padding-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    
        <?php
            include("includes/header.php");
        ?>

    <div class="thanhdieuhuong">
        <ul class = 'ul1'>
            <li>
                <div><a href="home.php" class="button__home nuxt-link-active">Trang chủ</a></div> 
            </li>    
            <li ins-init-condition="#LmJyZWFkY3J1bWJzIGxpOm5vdCg6Zmlyc3QsIDpsYXN0KSwgLmJsb2NrLWJyZWFkY3J1bWJzIGxpOm5vdCg6Zmlyc3QsIDpsYXN0KQ=="><div><svg height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z"></path></svg></div> <a href="" class="button__breadcrumb-item">
                <?php echo $sanpham["ten_sanpham"] ?>
            </a></li>
        </ul>
    </div>
    <div class="block-breadcrumbs affix"> 
        <div class="tendt">
            <h2><?php echo $sanpham["ten_sanpham"] ?></h2>
        </div>
        <div class="main">
            <div class="bleft">
                <div id="khunganh">
                    <div class="anh">
                    <?php
                        $sql_hinhanh_chitiet_dautien = "SELECT * FROM tbl_product_images WHERE id_sanpham = '$_GET[id_sanpham]' ORDER BY id_product_images DESC LIMIT 1";
                        $query_hinhanh_chitiet_dautien = mysqli_query($mysqli, $sql_hinhanh_chitiet_dautien);
                        $anhchitiet_dautien = mysqli_fetch_array($query_hinhanh_chitiet_dautien);
                        ?>
                        <img style="height: auto; width: 65%; margin-left: 16%; margin-top: 20px" id ='image' title="Điện thoại iPhone 15 Plus 512GB" src="admin/quanlysanpham/uploads_chitiet/<?php echo $anhchitiet_dautien["name"] ?>">
                    </div> 
                    <button class="prev" onclick="prevImage()">❮</button>
                    <button class="next" onclick="nextImage()">❯</button> 
                </div>
                <div class="thumbnails">
                    <?php
                        $sql_hinhanh_chitiet = "SELECT * FROM tbl_product_images WHERE id_sanpham = '$_GET[id_sanpham]' ORDER BY id_product_images DESC";
                        $query_hinhanh_chitiet = mysqli_query($mysqli, $sql_hinhanh_chitiet);
                        $images = [];
                        $i = 0;
                        while($row = mysqli_fetch_array($query_hinhanh_chitiet)) {
                            $images[] = "admin/quanlysanpham/uploads_chitiet/" . $row['name'];  
                    ?>
                        <img src="admin/quanlysanpham/uploads_chitiet/<?php echo $row["name"] ?>" onclick="changeImage(<?php echo $i ?>)">
                    <?php
                        $i++;
                        }
                    ?>
                    
                </div>
                
                <div class="dacdiem" style="padding-top: 20px;">
                    <p>
                        <?php
                            echo $sanpham["noidung"];
                        ?>
                    </p>
                </div>
            </div>
            <div class="bright">
                <div class="khungTSKT">
                    <h3>Thông số kỹ thuật</h3>
                    <div class='value-TSKT'>
                        <?php
                            $sql_tskt = "SELECT * FROM tbl_thongsokythuat WHERE id_sanpham = '$_GET[id_sanpham]'";
                            $query_tskt = mysqli_query($mysqli, $sql_tskt);
                            while($row_tskt = mysqli_fetch_array($query_tskt)) {
                        ?>
                        <div class="spec-item">
                            <div class="spec-title"><?php echo $row_tskt["thuoctinh"] ?></div>
                            <div class="spec-value"><?php echo $row_tskt["giatri"] ?></div>
                        </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
                <div class= 'giatien'>
                    <?php echo number_format($sanpham["giasp"],0,",",".") ?><sup>đ</sup>
                </div>
                <?php
                    if(isset($_SESSION["id_user"])) {
                ?>
                <div class="btn">
                    <a href="muangay.php?product_id=<?php echo $sanpham['id_sanpham']; ?>" class="buy-button">MUA NGAY</a>
                    <button class="shop-button" aria-label="Thêm điện thoại vào giỏ hàng" id="add-to-cart" data-product-id="<?php echo $sanpham["id_sanpham"] ?>"><i class="fa-solid fa-cart-shopping" style="color: #0097B2; font-size :24px"></i><p>Thêm Vào Giỏ Hàng</p></button>
                </div>
                <?php
                }
                else {
                ?>
                <div class="btn">
                    <a href="dangnhap.php" class="buy-button">MUA NGAY</a>
                    <button class="shop-button" onclick="chuyenTrang()" aria-label="Thêm điện thoại vào giỏ hàng"><i class="fa-solid fa-cart-shopping" style="color: #0097B2; font-size :24px"></i><p>Thêm Vào Giỏ Hàng</p></button>
                </div>
                <?php
            }
            ?>

                <div class="khungtintuc">
                    <div style="margin-left: 100px; font-weight: 700" class="icon">
                        <p style ='margin-left:15%'>Tin tức nổi bật</p>
                    </div>
                <?php
                    $sql_bv = "SELECT * FROM tbl_baiviet ORDER BY id_baiviet LIMIT 5";
                    $query_bv = mysqli_query($mysqli, $sql_bv);
                    while($row = mysqli_fetch_array($query_bv)) {
                ?>
                    <a class="tintuc" href ="chitietbaiviet.php?id_baiviet=<?php echo $row["id_baiviet"] ?>" style="color: black;">
                        <div class="anhtintuc">
                            <img src="./admin/quanlybaiviet/uploads/<?php echo $row["hinhanh"] ?>" alt="CareS - Trung tâm bảo hành ủy quyền Apple chính hãng tại Việt Nam">
                        </div>
                        <div class="ndtintuc">
                            <?php
                                echo $row["tieude"]
                            ?>
                        </div>
                        
                        
                    </a>
                    <?php
                    }
                    ?>
                    
                </div>
            </div>
        </div>
        
    </div> 
    <?php
        include("includes/footer.php");
        ?>
    </body>
    <script>
        <?php
            $image_json = json_encode($images);
        ?>
        const images = <?php echo $image_json; ?>;

        let currentImageIndex = 0;

        function nextImage() {
            currentImageIndex = (currentImageIndex + 1) % images.length;
            document.getElementById("image").src = images[currentImageIndex];
            
            const thumbnails = document.querySelectorAll('.thumbnails img');
            thumbnails.forEach((thumb) => {
                thumb.classList.remove('active');
            });

            thumbnails[currentImageIndex].classList.add('active'); 
        }

        function prevImage() {
            currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
            document.getElementById("image").src = images[currentImageIndex];
            
            const thumbnails = document.querySelectorAll('.thumbnails img');
            thumbnails.forEach((thumb) => {
                thumb.classList.remove('active');
            });

            thumbnails[currentImageIndex].classList.add('active'); 
        }

        function changeImage(index) {
            currentImageIndex = index;
            document.getElementById("image").src = images[currentImageIndex];

            const thumbnails = document.querySelectorAll('.thumbnails img');
            thumbnails.forEach((thumb) => {
                thumb.classList.remove('active');
            });
            thumbnails[index].classList.add('active');  
        }


        // Thêm giỏ hàng Javascript
        document.addEventListener("DOMContentLoaded", function() {
        const thumbnails = document.querySelectorAll('.thumbnails img');
        if (thumbnails.length > 0) {
            thumbnails[0].classList.add('active');
        }});

        document.getElementById("add-to-cart").addEventListener("click", function () {
            // Tạo thông báo
            const notification = document.createElement("div");
            notification.innerText = "Thêm vào giỏ hàng thành công!";
            notification.style.position = "fixed";
            notification.style.top = "100px";
            notification.style.right = "20px";
            notification.style.backgroundColor = "#4CAF50";
            notification.style.color = "white";
            notification.style.padding = "10px 20px";
            notification.style.borderRadius = "5px";
            notification.style.zIndex = "1000";
        
            document.body.appendChild(notification);
        
            setTimeout(function () {
                notification.remove();
            }, 5000);
        });


        // Gửi AJAX đến file themgiohang.php
        document.addEventListener("DOMContentLoaded", function () {
        const button = document.getElementById("add-to-cart");

        button.addEventListener("click", function () {
            const productId = this.getAttribute("data-product-id"); // Lấy ID sản phẩm

            fetch("addtocart.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `product_id=${productId}`,
            })
            .then(response => response.text())
            .then(data => {
                // alert(data);
            })
            .catch(error => {
                console.error("Lỗi:", error);
            });
        });
    });
    function chuyenTrang() {
            window.location.href = "dangnhap.php";
        }
    </script>
</html>