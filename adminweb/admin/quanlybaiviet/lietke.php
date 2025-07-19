<div class="col-lg-12">
    <div class="card">
        <div class="card-body d-flex justify-content-between align-items-center">
            <a href="http://localhost/tts/admin/admin.php?action=quanlybaiviet&query=them" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Thêm bài viết mới
            </a>
            <form class="d-flex" method="GET" action="" style="margin-left: auto;">
                <input type="hidden" name="action" value="quanlybaiviet">
                <input type="hidden" name="query" value="lietke">
                <div class="input-group" style="width: 120%;">
                    <input 
                        type="text" 
                        name="keyword" 
                        class="form-control rounded-start-pill" 
                        placeholder="Tìm tiêu đề bài viết" 
                        value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>"
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
        <div class="card-header border-0">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">Liệt kê bài viết </h3>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover" style="width: 100%">
                <thead>
                    <tr>
                        <th>Stt</th>
                        <th>Tiêu đề</th>
                        <th>Hình ảnh</th>
                        <th>Danh mục</th>
                        <th>Trạng thái</th>
                        <th>Quản lý</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
                    $limit = 10;
                    $sql_count = "SELECT COUNT(*) FROM tbl_baiviet, tbl_danhmucbaiviet 
                                  WHERE tbl_danhmucbaiviet.id_dmbv = tbl_baiviet.id_dmbv";
                    if (!empty($keyword)) {
                        $sql_count .= " AND (tbl_baiviet.tieude LIKE '%$keyword%' OR tbl_baiviet.id_baiviet LIKE '%$keyword%')";
                    }
                    $query_count = mysqli_query($mysqli, $sql_count);
                    $row_count = mysqli_fetch_array($query_count);
                    $total_records = $row_count[0];
                    $total_pages = ceil($total_records / $limit);
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $start = ($page - 1) * $limit;
                    
                    $sql_lietke_bv = "
                        SELECT * FROM tbl_baiviet, tbl_danhmucbaiviet 
                        WHERE tbl_danhmucbaiviet.id_dmbv = tbl_baiviet.id_dmbv
                    ";
                    if (!empty($keyword)) {
                        $sql_lietke_bv .= " AND (tbl_baiviet.tieude LIKE '%$keyword%' OR tbl_baiviet.id_baiviet LIKE '%$keyword%')";
                    }
                    $sql_lietke_bv .= " ORDER BY tbl_baiviet.id_baiviet DESC LIMIT $start, $limit";
                    $query_lietke_bv = mysqli_query($mysqli, $sql_lietke_bv);

                    $i = $start;
                    while ($row = mysqli_fetch_array($query_lietke_bv)) {
                        $i++;
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row['tieude']; ?></td>
                        <td><img src="./quanlybaiviet/uploads/<?php echo $row['hinhanh']; ?>" width="150px"></td>
                        <td><?php echo $row['ten_dmbv']; ?></td>
                        <td>
                            <?php echo $row['tinhtrang'] == 1 ? 'Kích hoạt' : 'Ẩn'; ?>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="?action=quanlybaiviet&query=xem&id_baiviet=<?php echo $row['id_baiviet']; ?>" class="btn btn-primary me-2">Xem</a>
                                <a href="?action=quanlybaiviet&query=sua&id_baiviet=<?php echo $row['id_baiviet']; ?>" class="btn btn-warning me-2">Sửa</a>
                                <a href="quanlybaiviet/xuly.php?id_baiviet=<?php echo $row['id_baiviet']; ?>" class="btn btn-danger me-2">Xóa</a>
                            </div>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
            </table>

            <!-- Phân trang -->
            <nav aria-label="Page navigation" class="d-flex justify-content-center mt-4">
                <ul class="pagination">
                    <li class="page-item <?php if($page == 1) echo 'disabled'; ?>">
                        <a class="page-link" href="?action=quanlybaiviet&query=lietke&page=<?php echo $page-1; ?>&keyword=<?php echo $keyword; ?>" tabindex="-1">Trước</a>
                    </li>
                    <?php
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '"><a class="page-link" href="?action=quanlybaiviet&query=lietke&page=' . $i . '&keyword=' . $keyword . '">' . $i . '</a></li>';
                    }
                    ?>
                    <li class="page-item <?php if($page == $total_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="?action=quanlybaiviet&query=lietke&page=<?php echo $page+1; ?>&keyword=<?php echo $keyword; ?>">Sau</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
