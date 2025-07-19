<div class="col-lg-12">
    <div class="card">
        <div class="card-body d-flex justify-content-between align-items-center">
            <form class="d-flex" method="GET" action="" style="margin-left: auto;">
                <input type="hidden" name="action" value="quanlydonhang">
                <input type="hidden" name="query" value="lietke">
                <div class="input-group" style="width: 120%;">
                    <input 
                        type="text" 
                        name="keyword" 
                        class="form-control rounded-start-pill" 
                        placeholder="Tìm mã đơn hoặc email" 
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
        <form method="GET" action="" class="d-flex justify-content-center mt-3 mb-3">
            <input type="hidden" name="action" value="quanlydonhang">
            <input type="hidden" name="query" value="lietke">
            <div class="btn-group" role="group">
                <button 
                    type="submit" 
                    class="btn btn-outline-info <?php echo (!isset($_GET['status_filter']) || $_GET['status_filter'] === '') ? 'active' : ''; ?>"
                    name="status_filter" 
                    value=""
                >
                    Tất cả
                    <span class="badge bg-info ms-2">
                        <?php
                            $sql_all = "SELECT COUNT(*) FROM tbl_order";
                            $result_all = mysqli_query($mysqli, $sql_all);
                            $count_all = mysqli_fetch_array($result_all)[0];
                            echo $count_all;
                        ?>
                    </span>
                </button>

                <button 
                    type="submit" 
                    class="btn btn-outline-warning <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == '0') ? 'active' : ''; ?>"
                    name="status_filter" 
                    value="0" 
                >
                    Chờ xác nhận
                    <span class="badge bg-warning ms-2">
                        <?php
                            $sql_pending = "SELECT COUNT(*) FROM tbl_order WHERE status = 0";
                            $result_pending = mysqli_query($mysqli, $sql_pending);
                            $count_pending = mysqli_fetch_array($result_pending)[0];
                            echo $count_pending;
                        ?>
                    </span>
                </button>

                <button 
                    type="submit" 
                    class="btn btn-outline-success <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == '1') ? 'active' : ''; ?>"
                    name="status_filter" 
                    value="1" 
                >
                    Đã xác nhận
                    <span class="badge bg-success ms-2">
                        <?php
                            $sql_confirmed = "SELECT COUNT(*) FROM tbl_order WHERE status = 1";
                            $result_confirmed = mysqli_query($mysqli, $sql_confirmed);
                            $count_confirmed = mysqli_fetch_array($result_confirmed)[0];
                            echo $count_confirmed;
                        ?>
                    </span>
                </button>

                <button 
                    type="submit" 
                    class="btn btn-outline-primary <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == '2') ? 'active' : ''; ?>"
                    name="status_filter" 
                    value="2" 
                >
                    Đang vận chuyển
                    <span class="badge bg-primary ms-2">
                        <?php
                            $sql_shipping = "SELECT COUNT(*) FROM tbl_order WHERE status = 2";
                            $result_shipping = mysqli_query($mysqli, $sql_shipping);
                            $count_shipping = mysqli_fetch_array($result_shipping)[0];
                            echo $count_shipping;
                        ?>
                    </span>
                </button>

                <button 
                    type="submit"
                    class="btn btn-outline-info <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == '3') ? 'active' : ''; ?>"
                    name="status_filter" 
                    value="3" 
                >
                    Đã giao hàng
                    <span class="badge bg-info ms-2">
                        <?php
                            $sql_delivered = "SELECT COUNT(*) FROM tbl_order WHERE status = 3";
                            $result_delivered = mysqli_query($mysqli, $sql_delivered);
                            $count_delivered = mysqli_fetch_array($result_delivered)[0];
                            echo $count_delivered;
                        ?>
                    </span>
                </button>

                <button 
                    type="submit" 
                    class="btn btn-outline-danger <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == '5') ? 'active' : ''; ?>"
                    name="status_filter" 
                    value="5" 
                >
                    Đang chờ hủy
                    <span class="badge bg-danger ms-2">
                        <?php
                            $sql_pending_cancel = "SELECT COUNT(*) FROM tbl_order WHERE status = 5";
                            $result_pending_cancel = mysqli_query($mysqli, $sql_pending_cancel);
                            $count_pending_cancel = mysqli_fetch_array($result_pending_cancel)[0];
                            echo $count_pending_cancel;
                        ?>
                    </span>
                </button>

                <button 
                    type="submit" 
                    class="btn btn-outline-secondary <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == '4') ? 'active' : ''; ?>"
                    name="status_filter" 
                    value="4" 
                >
                    Đã hủy
                    <span class="badge bg-secondary ms-2">
                        <?php
                            $sql_cancelled = "SELECT COUNT(*) FROM tbl_order WHERE status = 4";
                            $result_cancelled = mysqli_query($mysqli, $sql_cancelled);
                            $count_cancelled = mysqli_fetch_array($result_cancelled)[0];
                            echo $count_cancelled;
                        ?>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>





<div class="col-lg-12">
    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">Liệt kê đơn hàng </h3>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover" style="width: 100%">
                <thead>
                    <tr>
                        <th>Stt</th>
                        <th>Mã đơn hàng</th>
                        <th>Email</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                        <th>Quản lý</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
                    $status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';

                    $limit = 10; // số đơn hàng mỗi trang
                    // Tính số trang
                    if ($keyword || $status_filter !== '') {
                        $sql_count = "SELECT COUNT(*) FROM tbl_order, tbl_user WHERE tbl_order.id_user = tbl_user.id_user";
                        if ($keyword) {
                            $sql_count .= " AND (tbl_order.code_order LIKE '%$keyword%' OR tbl_user.email LIKE '%$keyword%')";
                        }
                        if ($status_filter !== '') {
                            $sql_count .= " AND tbl_order.status = '$status_filter'";
                        }
                    } else {
                        $sql_count = "SELECT COUNT(*) FROM tbl_order, tbl_user WHERE tbl_order.id_user = tbl_user.id_user";
                    }
                    $query_count = mysqli_query($mysqli, $sql_count);
                    $row_count = mysqli_fetch_array($query_count);
                    $total_records = $row_count[0];
                    $total_pages = ceil($total_records / $limit); // Số trang
                    $page = isset($_GET['page']) ? $_GET['page'] : 1; // Trang hiện tại
                    $start = ($page - 1) * $limit; // Tính bắt đầu từ đâu

                    // Cập nhật SQL để lấy kết quả theo trang và lọc theo trạng thái nếu có
                    if ($keyword || $status_filter !== '') {
                        $sql_lietke = "SELECT * FROM tbl_order, tbl_user WHERE tbl_order.id_user = tbl_user.id_user";
                        if ($keyword) {
                            $sql_lietke .= " AND (tbl_order.code_order LIKE '%$keyword%' OR tbl_user.email LIKE '%$keyword%')";
                        }
                        if ($status_filter !== '') {
                            $sql_lietke .= " AND tbl_order.status = '$status_filter'";
                        }
                        $sql_lietke .= " ORDER BY tbl_order.id_order DESC LIMIT $start, $limit";
                    } else {
                        $sql_lietke = "SELECT * FROM tbl_order, tbl_user WHERE tbl_order.id_user = tbl_user.id_user ORDER BY tbl_order.id_order DESC LIMIT $start, $limit";
                    }

                    $query_lietke = mysqli_query($mysqli, $sql_lietke);
                    while($row = mysqli_fetch_array($query_lietke)) {
                        $i++;
                    ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo $row["code_order"] ?></td>
                        <td><?php echo $row["email"] ?></td>
                        <td><?php echo $row["date"] ?></td>
                        <td>
                            <?php
                            if ($row["status"] == 0) {
                                echo "Chờ xác nhận";
                            } elseif ($row["status"] == 1) {
                                echo "Đã xác nhận";
                            } elseif ($row["status"] == 2) {
                                echo "Đang vận chuyển";
                            } elseif ($row["status"] == 3) {
                                echo "Đã giao hàng";
                            } elseif ($row["status"] == 4) {
                                echo "Đã hủy";
                            } elseif ($row["status"] == 5) {
                                echo "Đang chờ hủy";
                            }
                            else {
                                echo "Trạng thái không xác định";
                            }
                            ?>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="?action=quanlydonhang&query=xem&id_order=<?php echo $row['id_order']; ?>&code_order=<?php echo $row['code_order'] ?>" class="btn btn-primary me-2">Xem chi tiết</a>
                            </div>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <nav aria-label="Page navigation" class="d-flex justify-content-center mt-4">
                <ul class="pagination">
                    <!-- Previous Page Button -->
                    <li class="page-item <?php if($page == 1) echo 'disabled'; ?>">
                        <a class="page-link" href="?action=quanlydonhang&query=lietke&page=<?php echo $page-1; ?>&keyword=<?php echo $keyword; ?>&status_filter=<?php echo $status_filter; ?>" tabindex="-1">Trước</a>
                    </li>

                    <!-- Page Numbers -->
                    <?php
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '"><a class="page-link" href="?action=quanlydonhang&query=lietke&page=' . $i . '&keyword=' . $keyword . '&status_filter=' . $status_filter . '">' . $i . '</a></li>';
                    }
                    ?>

                    <!-- Next Page Button -->
                    <li class="page-item <?php if($page == $total_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="?action=quanlydonhang&query=lietke&page=<?php echo $page+1; ?>&keyword=<?php echo $keyword; ?>&status_filter=<?php echo $status_filter; ?>">Sau</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
