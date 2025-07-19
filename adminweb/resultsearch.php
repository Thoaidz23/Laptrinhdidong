<?php
    include("./admin/connect.php");

    $tukhoa = isset($_GET["query"]) ? mysqli_real_escape_string($mysqli, $_GET["query"]) : '';
    $id_dmsp = isset($_GET['id_dmsp']) ? mysqli_real_escape_string($mysqli, $_GET['id_dmsp']) : '';

    // Cấu hình phân trang
    $products_per_page = 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $page = max($page, 1);
    $offset = ($page - 1) * $products_per_page;

    // Truy vấn lấy sản phẩm
    if (!empty($id_dmsp)) {
        $sql_total_products = "SELECT COUNT(*) as total FROM tbl_sanpham WHERE id_dmsp = '$id_dmsp' AND ten_sanpham LIKE '%$tukhoa%'";
        $sql_sanpham = "SELECT * FROM tbl_sanpham WHERE id_dmsp = '$id_dmsp' AND ten_sanpham LIKE '%$tukhoa%' ORDER BY id_sanpham DESC LIMIT $products_per_page OFFSET $offset";
    } else {
        $sql_total_products = "SELECT COUNT(*) as total FROM tbl_sanpham WHERE ten_sanpham LIKE '%$tukhoa%'";
        $sql_sanpham = "SELECT * FROM tbl_sanpham WHERE ten_sanpham LIKE '%$tukhoa%' ORDER BY id_sanpham DESC LIMIT $products_per_page OFFSET $offset";
    }

    $query_total_products = mysqli_query($mysqli, $sql_total_products);
    $total_products = mysqli_fetch_assoc($query_total_products)['total'];
    $query_sanpham = mysqli_query($mysqli, $sql_sanpham);
    $total_pages = ceil($total_products / $products_per_page);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm</title>
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/phone.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
    <style>
        .pagination {
            padding-left:35%;       
            margin-top: 20px;
        }
        .pagination a {
            margin: 0 5px;
            padding: 8px 12px;
            background-color: #f0f0f0;
            text-decoration: none;
            color: #333;
            border-radius: 4px;
        }
        .pagination a.active {
            background-color: #333;
            color: #fff;
        }
        .pagination a:hover {
            background-color: #ddd;
        }
    </style>
<body>
<?php include("includes/header.php"); include("includes/menubar.php"); ?>

<div class="container">
    <div class="main-phone">
        <a href="home.php">Trang chủ</a>
        <i class="fas fa-chevron-right"></i>
        <li>Kết quả tìm kiếm cho: <?php echo htmlspecialchars($tukhoa, ENT_QUOTES, 'UTF-8'); ?></li>
    </div>
    <section class="product-gallery-one">
        <div class="container">
            <div class="product-gallery-one-content">
                <div class="product-gallery-one-content-product">
                    <?php if (mysqli_num_rows($query_sanpham) > 0): ?>
                        <?php while ($row = mysqli_fetch_array($query_sanpham)): ?>
                            <div class="product-gallery-one-content-product-item">
                            <a href="chitietsanpham.php?id_sanpham=<?php echo $row['id_sanpham']; ?>">
                                <img src="<?php echo !empty($row['hinhanh']) ? 'admin/quanlysanpham/uploads/' . $row['hinhanh'] : 'path/to/default-image.jpg'; ?>" 
                                     alt="<?php echo htmlspecialchars($row['ten_sanpham'], ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="product-gallery-one-content-product-item-text">
                                    <li><?php echo htmlspecialchars($row['ten_sanpham'], ENT_QUOTES, 'UTF-8'); ?></li>
                                    <li>Online giá rẻ</li>
                                    <li><?php echo number_format((float)$row['giasp'], 0, ",", ".") ?><sup>đ</sup></li>
                                </div>
                                </a>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>Không có sản phẩm nào phù hợp với từ khóa tìm kiếm của bạn.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?id_dmsp=<?php echo $_GET['id_dmsp']; ?>&page=<?php echo $page - 1; ?>">&#171; Trang trước</a>
            <?php else: ?> <a href="" style ="opacity:0;margin:47px;">.</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?query=<?php echo urlencode($tukhoa); ?>&id_dmsp=<?php echo $id_dmsp; ?>&page=<?php echo $i; ?>" 
                   class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            
            <?php if ($page < $total_pages): ?>
                <a href="?query=<?php echo urlencode($tukhoa); ?>&id_dmsp=<?php echo $id_dmsp; ?>&page=<?php echo $page + 1; ?>">Trang sau &#187;</a>
            <?php endif; ?>
        </div>       
    </section>
</div>
<?php include("includes/footer.php"); ?>
</body>
</html>
