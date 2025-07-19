<?php
    session_start();
    include("./admin/connect.php");

    $products_per_page = 5;

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $page = $page > 0 ? $page : 1;

    $id_dmbv = isset($_GET['id_dmbv']) ? $_GET['id_dmbv'] : null;
    if (!$id_dmbv) {
        die("Không tìm thấy danh mục bài viết.");
    }

    $offset = ($page - 1) * $products_per_page;

    $stmt_danhmuc = $mysqli->prepare("SELECT * FROM tbl_danhmucbaiviet WHERE id_dmbv = ?");
    $stmt_danhmuc->bind_param("s", $id_dmbv);
    $stmt_danhmuc->execute();
    $result_danhmuc = $stmt_danhmuc->get_result();
    $row_danhmucsanpham = $result_danhmuc->fetch_assoc();

    if (!$row_danhmucsanpham) {
        die("Danh mục bài viết không tồn tại.");
    }

    $stmt_total = $mysqli->prepare("SELECT COUNT(*) as total FROM tbl_baiviet WHERE id_dmbv = ?");
    $stmt_total->bind_param("s", $id_dmbv);
    $stmt_total->execute();
    $total_products = $stmt_total->get_result()->fetch_assoc()['total'];

    $stmt_baiviet = $mysqli->prepare(
        "SELECT * FROM tbl_baiviet WHERE id_dmbv = ? ORDER BY id_baiviet DESC LIMIT ? OFFSET ?"
    );
    $stmt_baiviet->bind_param("sii", $id_dmbv, $products_per_page, $offset);
    $stmt_baiviet->execute();
    $query_sanpham = $stmt_baiviet->get_result();

    $total_pages = ceil($total_products / $products_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $row_danhmucsanpham['ten_dmbv']; ?> - Tin công nghệ cập nhật 24H</title>
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/Product_catelog.css">
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
    <div>
        <?php
            include("includes/header.php");
            include("includes/menubar.php");
        ?>
        <div class="container">
            <div class="product-catelog">
                <?php
                    $query_danhmucbaiviet = $mysqli->query("SELECT * FROM tbl_danhmucbaiviet ORDER BY id_dmbv");
                    while ($row_danhmucbaiviet = $query_danhmucbaiviet->fetch_assoc()) {
                        $active_class = ($row_danhmucbaiviet['id_dmbv'] == $id_dmbv) ? 'active' : '';
                ?>
                        <a href="danhmucbaiviet.php?id_dmbv=<?php echo $row_danhmucbaiviet['id_dmbv']; ?>" class="<?php echo $active_class; ?>">
                            <?php echo $row_danhmucbaiviet['ten_dmbv']; ?>
                        </a>
                <?php } ?>
            </div>
        </div>
        <section class="left-content">
            <div class="container">
                <?php
                    if ($query_sanpham->num_rows > 0) {
                        while ($row = $query_sanpham->fetch_assoc()) {
                ?>
                        <a href="chitietbaiviet.php?id_baiviet=<?php echo $row['id_baiviet']; ?>">
                            <img style="width: 300px; height: 150px; object-fit: cover; object-position: center" src="./admin/quanlybaiviet/uploads/<?php echo $row['hinhanh']; ?>" alt="<?php echo $row['tieude']; ?>">
                            <div class="event">
                                <h2><?php echo $row['tieude']; ?></h2>
                            </div>
                        </a>
                <?php
                        }
                    } else {
                        echo "<p>Không có bài viết nào trong danh mục này.</p>";
                    }
                ?>
            </div>
        </section>

        
       <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?id_dmbv=<?php echo $_GET['id_dmbv']; ?>&page=<?php echo $page - 1; ?>">&#171; Trang trước</a>
            <?php else: ?> <a href="" style ="opacity:0;margin:47px;">.</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?id_dmbv=<?php echo $id_dmbv; ?>&page=<?php echo $i; ?>" 
                class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?id_dmbv=<?php echo $_GET['id_dmbv']; ?>&page=<?php echo $page + 1; ?>">Trang sau &#187;</a>
            <?php endif; ?>
        </div>  
    </div>
    <?php include("includes/footer.php"); ?>
    <script src="assets/js/product_c.js"></script>
</body>
</html>
