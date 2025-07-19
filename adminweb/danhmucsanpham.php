<?php
    session_start();
    include("./admin/connect.php");

    $products_per_page = 10;

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $page = $page > 0 ? $page : 1;

    $id_dmsp = isset($_GET['id_dmsp']) ? (int)$_GET['id_dmsp'] : 0;

    $offset = ($page - 1) * $products_per_page;

    $sql_danhmucsanpham = "SELECT * FROM tbl_danhmucsanpham WHERE id_dmsp = ?";
    $stmt = $mysqli->prepare($sql_danhmucsanpham);
    $stmt->bind_param("i", $id_dmsp);
    $stmt->execute();
    $result_danhmucsanpham = $stmt->get_result();
    $row_danhmucsanpham = $result_danhmucsanpham->fetch_assoc();

    if (!$row_danhmucsanpham) {
        echo "Danh mục không tồn tại.";
        exit;
    }

    $sql_total_products = "SELECT COUNT(*) as total FROM tbl_sanpham WHERE id_dmsp = ?";
    $stmt = $mysqli->prepare($sql_total_products);
    $stmt->bind_param("i", $id_dmsp);
    $stmt->execute();
    $result_total_products = $stmt->get_result();
    $total_products = $result_total_products->fetch_assoc()['total'];

    $sql_sanpham = "SELECT * FROM tbl_sanpham WHERE id_dmsp = ? ORDER BY id_sanpham DESC LIMIT ? OFFSET ?";
    $stmt = $mysqli->prepare($sql_sanpham);
    $stmt->bind_param("iii", $id_dmsp, $products_per_page, $offset);
    $stmt->execute();
    $query_sanpham = $stmt->get_result();

    $total_pages = ceil($total_products / $products_per_page);
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row_danhmucsanpham["ten_dmsp"]); ?></title>
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/phone.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .pagination {
            padding-left:40%;       
            margin-top: 60px;
            margin-bottom: 25px;
        }
        .pagination a {
            margin: 0 5px;
            padding: 8px 12px;
            background-color: #f0f0f0;
            text-decoration: none;
            color: #333;
            border-radius: 4px;
            font-weight: normal;
        }

        .pagination a.active {
            background-color: #333;
            color: #fff;
            font-weight: bold;
        }

        .pagination a:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <?php
        include("includes/header.php");
        include("includes/menubar.php");
    ?>
    
    <div class="container">
        <div class="main-phone">
            <a href="home.php">Trang chủ</a>
            <i class="fas fa-chevron-right"></i>
            <li><?php echo htmlspecialchars($row_danhmucsanpham["ten_dmsp"]); ?></li>
        </div>
        
        <section class="product-gallery-one">
            <div class="container">
                <div class="product-gallery-one-content">
                    <div class="product-gallery-one-content-product">
                        <?php while($row_sanpham = $query_sanpham->fetch_assoc()) { ?>
                        <div class="product-gallery-one-content-product-item">
                            <a href="chitietsanpham.php?id_sanpham=<?php echo $row_sanpham['id_sanpham']; ?>">
                                <img src="./admin/quanlysanpham/uploads/<?php echo htmlspecialchars($row_sanpham["hinhanh"]); ?>" 
                                     alt="<?php echo htmlspecialchars($row_sanpham["ten_sanpham"]); ?>">
                                <div class="product-gallery-one-content-product-item-text">
                                    <li><?php echo htmlspecialchars($row_sanpham["ten_sanpham"]); ?></li>
                                    <li>Online giá rẻ</li>
                                    <li><?php echo number_format($row_sanpham["giasp"], 0, ",", "."); ?><sup>đ</sup></li>
                                </div>
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pagination -->
        <div class="pagination">
        <?php if ($page > 1): ?>
                <a href="?id_dmsp=<?php echo $_GET['id_dmsp']; ?>&page=<?php echo $page - 1; ?>">&#171; Trang trước</a>
            <?php else: ?> <a href="" style ="opacity:0;margin:47px;">.</a> 
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?id_dmsp=<?php echo $id_dmsp; ?>&page=<?php echo $i; ?>" 
                   class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?id_dmsp=<?php echo $id_dmsp; ?>&page=<?php echo $page + 1; ?>">Trang sau &#187;</a>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include("includes/footer.php"); ?>
</body>
</html>
