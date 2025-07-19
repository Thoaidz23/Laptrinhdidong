<?php

    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
    $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $limit = 10;
    $start = ($page - 1) * $limit;

    $sql_base = "FROM tbl_product, tbl_category_product WHERE tbl_category_product.id_category_product = tbl_product.id_category_product";

    $sql_saphethang = "SELECT COUNT(*) FROM tbl_product WHERE quantity < 50";
    $query_saphethang = mysqli_query($mysqli, $sql_saphethang);
    $row_saphethang = mysqli_fetch_array($query_saphethang);
    $count_saphethang = $row_saphethang[0];

    if ($filter == 'saphethang') {
        $sql_base .= " AND tbl_product.quantity < 50";
    }

    if (!empty($keyword)) {
        $sql_base .= " AND (tbl_product.name LIKE '%$keyword%' OR tbl_product.id_product LIKE '%$keyword%')";
    }

    $sql_count = "SELECT COUNT(*) " . $sql_base;
    $query_count = mysqli_query($mysqli, $sql_count);
    $row_count = mysqli_fetch_array($query_count);
    $total_records = $row_count[0];
    $total_pages = ceil($total_records / $limit);

    $sql_lietke_sp = "SELECT 
        tbl_product.id_product, 
        tbl_product.name AS product_name,
        tbl_product.image, 
        tbl_product.price, 
        tbl_product.quantity,
        tbl_category_product.name AS category_name
    " . $sql_base . " 
    ORDER BY tbl_product.id_product DESC 
    LIMIT $start, $limit";
    $query_lietke_sp = mysqli_query($mysqli, $sql_lietke_sp);

?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body d-flex justify-content-between align-items-center">

            <a href="http://localhost/ttsfood/adminweb/admin/admin.php?action=quanlysanpham&query=them" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Thêm sản phẩm mới
            </a>

            <form class="d-flex" method="GET" action="" style="margin-left: auto;">
                <input type="hidden" name="action" value="quanlysanpham">
                <input type="hidden" name="query" value="lietke">
                <div class="input-group" style="width: 120%;">
                    <input 
                        type="text" 
                        name="keyword" 
                        class="form-control rounded-start-pill" 
                        placeholder="Tìm tên hoặc mã sản phẩm" 
                        value="<?php echo $keyword; ?>"
                    >
                    <button 
                        class="btn btn-success rounded-end-pill px-4" 
                        type="submit"
                        style="display: flex; align-items: center; gap: 8px;" 
                    >
                        <i class="fas fa-search"></i> <span>Tìm</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="card">

        <div class="card-body" style="text-align: right;">
            <a href="?action=quanlysanpham&query=lietke&filter=<?php echo $filter == 'saphethang' ? '' : 'saphethang'; ?>" 
               class="btn btn-outline-warning <?php echo ($filter == 'saphethang') ? 'active' : ''; ?>" 
               style="padding: 6px 12px; font-size: 14px; font-weight: bold; border-radius: 5px; transition: all 0.3s ease;">
                Sản phẩm sắp hết hàng
                <span class="badge bg-warning ms-2">
                    <?php echo $count_saphethang; ?>
                </span>
            </a>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">Liệt kê sản phẩm </h3>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover" style="width: 100%">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên sản phẩm</th>
                        <th>Hình ảnh</th>
                        <th>Giá sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Danh mục</th>
                        <th>Quản lý</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = $start;
                    while ($row_sp = mysqli_fetch_array($query_lietke_sp)) {
                        $i++;
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row_sp["product_name"]; ?></td>
                        <td><img src="./quanlysanpham/uploads/<?php echo $row_sp["image"]; ?>" width="150px"></td>
                        <td><?php echo number_format($row_sp["price"], 0, ",", "."); ?><sup>đ</sup></td>
                        <td><?php echo $row_sp["quantity"]; ?></td>
                        <td><?php echo $row_sp["category_name"]; ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="?action=quanlysanpham&query=xem&id_sanpham=<?php echo $row_sp['id_product']; ?>" class="btn btn-primary me-2">Xem</a>
                                <a href="?action=quanlysanpham&query=sua&id_sanpham=<?php echo $row_sp['id_product']; ?>" class="btn btn-warning me-2">Sửa</a>
                                <a href="quanlysanpham/xuly.php?query=xoa&id_sanpham=<?php echo $row_sp['id_product']; ?>" class="btn btn-danger me-2">Xóa</a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <nav aria-label="Page navigation" class="d-flex justify-content-center mt-4">
                <ul class="pagination">

                    <li class="page-item <?php if ($page == 1) echo 'disabled'; ?>">
                        <a class="page-link" href="?action=quanlysanpham&query=lietke&page=<?php echo $page - 1; ?>&keyword=<?php echo $keyword; ?>&filter=<?php echo $filter; ?>" tabindex="-1">Trước</a>
                    </li>

                    <?php
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '"><a class="page-link" href="?action=quanlysanpham&query=lietke&page=' . $i . '&keyword=' . $keyword . '&filter=' . $filter . '">' . $i . '</a></li>';
                    }
                    ?>

                    <li class="page-item <?php if ($page == $total_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="?action=quanlysanpham&query=lietke&page=<?php echo $page + 1; ?>&keyword=<?php echo $keyword; ?>&filter=<?php echo $filter; ?>">Sau</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
