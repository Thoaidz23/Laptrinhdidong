<?php
    session_start();
    include("./admin/connect.php");
?>

<!DOCTYPE html>
<html lang="enul">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/img/avatar.png" type="image/x-icon"/ class="circle-favicon">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>TTS - Cửa Hàng Điện Tử </title>
</head>
<body>
    <?php
        include("includes/header.php");
        include("includes/menubar.php");
    ?>
    <!--------------Slider------------->
    <section class="slider">
        <div class="container">
            <div class="slider-content">
                    <div class="slider-content-top-container">
                        <div class="slider-content-top">
                            <?php
                                $sql_slider = "SELECT * FROM tbl_banner ORDER BY sort_order";
                                $query_slider = mysqli_query($mysqli, $sql_slider);
                                while($row = mysqli_fetch_array($query_slider)) {

                            ?>
                            <a href=""><img src="admin/quanlybanner/uploads/<?php echo $row["image"] ?>"></a>
                            <?php
                                }
                            ?>
                        </div>
                        <div class="slider-content-top-btn">
                            <i class="fas fa-chevron-left" onclick="moveSlide(-1)"></i>
                            <i class="fas fa-chevron-right" onclick="moveSlide(1)"></i>
                        </div>
                    </div>      
                    </div>
            </div>
        </div>
    </section>
    <!--------------------------------section-product--------------------------->
    <section class="section-product-one">
        <div class="container">
            <div class="section-product-one-content">
                <div class="section-product-one-content-title">
                    <h2>Sản phẩm mới nhất</h2>
                    <div class="section-product-one-content-item-btn">
                        <i class="fas fa-chevron-left" id="prev-btn"></i>
                        <i class="fas fa-chevron-right" id="next-btn"></i>
                    </div>  
                </div>
                <div class="section-product-one-content-container">
                    <div class="section-product-one-content-items-content">
                        <div class="section-product-one-content-items" id="product-list" >
                            <?php
                                $sql_pro_hot = "SELECT * FROM tbl_sanpham ORDER BY id_sanpham DESC LIMIT 20";
                                $query_pro_hot = mysqli_query($mysqli, $sql_pro_hot);
                                while($row = mysqli_fetch_array($query_pro_hot)) {
                            ?>
                            <div class="section-product-one-content-item">
                                <a href="chitietsanpham.php?id_sanpham=<?php echo $row['id_sanpham']; ?>">
                                    <img src="admin/quanlysanpham/uploads/<?php echo $row["hinhanh"] ?>">
                                    <div class="section-product-one-content-item-text">
                                        <ul>
                                            <li style="color: black"><?php echo $row["ten_sanpham"] ?></li>
                                            <li style="color: black">Online giá rẻ</li>
                                            <li><?php echo number_format($row["giasp"],0,",",".") ?><sup>đ</sup></li>
                                        </ul>
                                    </div>
                                </a>
                            </div>

                            <?php
                                }
                            ?>
                        </div>
                    </div> 
            </div>
        </div>
    </section>

    <?php
    $sql_dmsp = "SELECT * FROM tbl_danhmucsanpham ORDER BY thutu";
    $query_dmsp = mysqli_query($mysqli, $sql_dmsp);

    while($row_dmsp = mysqli_fetch_array($query_dmsp)) {
?>
<section class="product-gallery-one">
    <div class="container">
        <div class="product-gallery-one-content">
            <div class="product-gallery-one-content-title">
                <h2><?php echo $row_dmsp["ten_dmsp"] ?> Nổi Bật Nhất</h2>
            </div>
            <div class="product-gallery-one-content-product">
                <?php
                    $id_dmsp = $row_dmsp["id_dmsp"];
                    $sql_sanpham = "SELECT * FROM tbl_sanpham WHERE id_dmsp = $id_dmsp ORDER BY id_sanpham LIMIT 10";
                    $query_sanpham = mysqli_query($mysqli, $sql_sanpham);

                    while($row_sanpham = mysqli_fetch_array($query_sanpham)) {
                ?>
                <div class="product-gallery-one-content-product-item">
                    <a href="chitietsanpham.php?id_sanpham=<?php echo $row_sanpham['id_sanpham']; ?>">
                        <img src="admin/quanlysanpham/uploads/<?php echo $row_sanpham["hinhanh"] ?>">
                        <div class="product-gallery-one-content-product-item-text">
                            <ul>
                                <li style="color: black"><?php echo $row_sanpham["ten_sanpham"] ?></li>
                                <li style="color: black">Online giá rẻ</li>
                                <li><?php echo number_format($row_sanpham["giasp"], 0, ",", ".") ?><sup>đ</sup></li>
                            </ul>
                        </div>
                    </a>
                </div>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>
</section>
<?php
    }
?>

     <!-----------------------Product news----------------------->
     <section class="product-news">
        <div class="container">
            <div class="product-news-content">
                <div class="product-gallery-one-content-title">
                    <h2>Bài tin</h2>
                </div>
                <div class="product-news-content-product" id="newsItems">
                <i class="fas fa-chevron-left" id="prevbtn"></i>
                <i class="fas fa-chevron-right" id="nextbtn"></i>
                    <?php
                        $sql_baiviet_hot = "SELECT * FROM tbl_baiviet ORDER BY id_baiviet LIMIT 10";
                        $query_baiviet_hot = mysqli_query($mysqli, $sql_baiviet_hot);
                        while($row = mysqli_fetch_array($query_baiviet_hot)) {
                    ?>
                    <div class="product-news-content-product-item">
                        <a href="chitietbaiviet.php?id_baiviet=<?php echo $row["id_baiviet"] ?>"><img src="admin/quanlybaiviet/uploads/<?php echo $row["hinhanh"] ?>">
                        <div class="product-news-content-product-item-text">
                            <p><?php echo $row["tieude"] ?></p>
                        </div>

                    </a>
                    </div>
                    <?php
                        }
                    ?>
                    
                    
                    
        </div>
        <div class="seemore-news">
            <?php
                $sql_min_id = "SELECT MIN(id_dmbv) AS min_id FROM tbl_danhmucbaiviet";
                $query_min_id = mysqli_query($mysqli, $sql_min_id);
                $row_min_id = mysqli_fetch_assoc($query_min_id);
                $min_id = $row_min_id['min_id'];
            ?>
            <a href="danhmucbaiviet.php?id_dmbv=<?php echo $min_id ?>">Xem thêm bài tin<i class="fas fa-chevron-right"></i></a>
        </div>
    </section>

    <?php
        include("includes/footer.php");
    ?>
   
    <script src="assets/js/stript.js"></script>
</body>
</html>
