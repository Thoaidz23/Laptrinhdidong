<?php
    include("./connect.php");

    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;

    $sql = "SELECT DATE(date) as order_date, SUM(total_price) as daily_revenue 
            FROM tbl_order 
            WHERE status = 3 ";

    if ($start_date && $end_date) {
        $sql .= " AND DATE(date) BETWEEN '$start_date' AND '$end_date' ";
    }

    $sql .= " GROUP BY DATE(date) ORDER BY DATE(date) ASC";

    $result = mysqli_query($mysqli, $sql);

    $dates = [];
    $revenues = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $dates[] = $row['order_date'];
        $revenues[] = $row['daily_revenue'];
    }
?>


<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>
                    <?php
                    $sql_total_orders = "SELECT COUNT(*) AS total FROM tbl_order";
                    $result_total_orders = mysqli_query($mysqli, $sql_total_orders);
                    $row_total_orders = mysqli_fetch_assoc($result_total_orders);
                    echo $row_total_orders['total'];
                    ?>
                </h3>
                <p>Tổng đơn hàng</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="./admin.php?action=quanlydonhang&query=lietke" class="small-box-footer">
                Xem thêm <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
        <div class="inner">
            <h3>
                <?php
                    $sql_pending_orders = "SELECT COUNT(*) AS pending FROM tbl_order WHERE status = 0";
                    $result_pending_orders = mysqli_query($mysqli, $sql_pending_orders);
                    $row_pending_orders = mysqli_fetch_assoc($result_pending_orders);
                    echo $row_pending_orders['pending'];
                ?>
            </h3>
            <p>Đơn hàng chờ xác nhận</p>
        </div>
        <div class="icon">
            <i class="fas fa-clock"></i>
        </div>
        <a href="http://localhost/tts/admin/admin.php?action=quanlydonhang&query=lietke&status_filter=0" class="small-box-footer">
            Xem thêm <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>
</div>




    <!-- <div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
        <div class="inner">
            <h3>
                <?php
                    $sql_cancel_requests = "SELECT COUNT(*) AS cancel_requests FROM tbl_order WHERE status = 5";
                    $result_cancel_requests = mysqli_query($mysqli, $sql_cancel_requests);
                    $row_cancel_requests = mysqli_fetch_assoc($result_cancel_requests);
                    echo $row_cancel_requests['cancel_requests'];
                ?>
            </h3>
            <p>Đơn hàng yêu cầu hủy</p>
        </div>
        <div class="icon">
            <i class="ion ion-close-circled"></i>
        </div>
        <a href="http://localhost/tts/admin/admin.php?action=quanlydonhang&query=lietke&status_filter=5" class="small-box-footer">
            Xem thêm <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>
</div> -->


<div class="col-lg-3 col-6">
    <div class="small-box" style="background-color: #6f4f28;">
        <div class="inner">
            <h3 style="color: white">
                <?php
                    $sql_low_stock = "SELECT COUNT(*) AS low_stock FROM tbl_product WHERE quantity < 50";
                    $result_low_stock = mysqli_query($mysqli, $sql_low_stock);
                    $row_low_stock = mysqli_fetch_assoc($result_low_stock);
                    echo $row_low_stock['low_stock'];
                ?>
            </h3>
            <p style="color: white">Sản phẩm sắp hết hàng</p>
        </div>
        <div class="icon">
            <i class="fas fa-arrow-down"></i>
        </div>
        <a href="http://localhost/tts/admin/admin.php?action=quanlysanpham&query=lietke&filter=saphethang" class="small-box-footer">
            Xem thêm <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>
</div>

    
</div>

<div class="row">
    <div class="col-lg-12">
    <div class="card">
        <div class="card-header border-0">
            <h3 class="card-title text-center">Thống kê doanh thu</h3>
            <form method="POST" class="form-inline d-flex justify-content-center align-items-center mt-4">
                <label for="start_date" class="mr-2">Từ ngày:</label>
                <input type="date" name="start_date" id="start_date" class="form-control mr-3" value="<?php echo $start_date; ?>">

                <label for="end_date" class="mr-2">Đến ngày:</label>
                <input type="date" name="end_date" id="end_date" class="form-control mr-3" value="<?php echo $end_date; ?>">

                <button type="submit" class="btn btn-primary">Xác nhận</button>
            </form>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-center">
                <canvas id="revenueChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>



<div class="col-lg-6">
    <div class="card" style="min-height: calc(100% - 20px);">
        <div class="card-header border-0">
            <h3 class="card-title">Top sản phẩm bán chạy</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table m-0">
                    <thead class="table-light">
                        <tr class="text-center fw-bold">
                            <th class="text-start" scope="col">Sản phẩm</th>
                            <th class="text-start" scope="col">Đã bán</th>
                            <th class="text-start" scope="col">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                    <?php

                        $sql = "
                            SELECT od.id_product, SUM(od.quantity_product) AS total_sold 
                            FROM tbl_order_detail od
                            JOIN tbl_order o ON od.code_order = o.code_order
                            WHERE o.status = 5
                            GROUP BY od.id_product
                            ORDER BY total_sold DESC
                            LIMIT 10
                        ";


                        $query = mysqli_query($mysqli, $sql);


                        if ($query && mysqli_num_rows($query) > 0) {
                            while ($row = mysqli_fetch_assoc($query)) {
                                $id_sanpham = $row['id_product'];
                                $total_sold = $row['total_sold'];

                                $product_query = mysqli_query($mysqli, "SELECT name, price FROM tbl_product WHERE id_product = '$id_sanpham'");
                                if ($product_query && mysqli_num_rows($product_query) > 0) {
                                    $product_row = mysqli_fetch_assoc($product_query);
                                    $product_name = $product_row['name'];
                                    $product_price = $product_row['price'];

                                    $total_revenue = number_format($total_sold * $product_price, 0, ',', '.') . ' đ';

                                    echo '<tr>';
                                    echo '<td class="overflow-hidden" style="max-width: 200px; text-wrap: nowrap; text-overflow: ellipsis;">';
                                    echo '<p class="ms-2">' . $product_name . '</p>';
                                    echo '</td>';
                                    echo '<td class="overflow-hidden" style="max-width: 200px; text-wrap: nowrap; text-overflow: ellipsis;">' . $total_sold . '</td>';
                                    echo '<td class="overflow-hidden" style="max-width: 200px; text-wrap: nowrap; text-overflow: ellipsis;">' . $total_revenue . '</td>';
                                    echo '</tr>';
                                }
                            }
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<div class="col-lg-6">
    <div class="card" style="height: calc(100% - 20px);">
        <div class="card-header border-0">
            <h3 class="card-title">Top danh mục bán chạy</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table m-0">
                    <thead class="table-light">
                        <tr class="text-center fw-bold">
                            <th class="text-start" scope="col">Danh mục</th>
                            <th class="text-start" scope="col">Số lượng sản phẩm</th>
                            <th class="text-start" scope="col">Đã bán</th>
                            <th class="text-start" scope="col">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                    <?php

                        $sql = "
                            SELECT dm.id_category_product, dm.name, 
                                   COUNT(sp.id_product) AS total_products, 
                                   SUM(od.quantity_product) AS total_sold, 
                                   SUM(od.quantity_product * sp.price) AS total_revenue
                            FROM tbl_category_product dm
                            JOIN tbl_product sp ON dm.id_category_product = sp.id_category_product
                            JOIN tbl_order_detail od ON sp.id_product = od.id_product
                            JOIN tbl_order o ON od.code_order = o.code_order
                            WHERE o.status = 5
                            GROUP BY dm.id_category_product
                            ORDER BY total_sold DESC
                            LIMIT 10
                        ";

                        $query = mysqli_query($mysqli, $sql);

                        if ($query && mysqli_num_rows($query) > 0) {
                            while ($row = mysqli_fetch_assoc($query)) {
                                $category_name = $row['name'];
                                $total_products = $row['total_products'];
                                $total_sold = $row['total_sold'];
                                $total_revenue = number_format($row['total_revenue'], 0, ',', '.') . ' đ';

                                echo '<tr>';
                                echo '<td class="overflow-hidden" style="max-width: 200px; text-wrap: nowrap; text-overflow: ellipsis;">';
                                echo '<p class="ms-2">' . $category_name . '</p>';
                                echo '</td>';
                                echo '<td class="overflow-hidden" style="max-width: 200px; text-wrap: nowrap; text-overflow: ellipsis;">' . $total_products . '</td>';
                                echo '<td class="overflow-hidden" style="max-width: 200px; text-wrap: nowrap; text-overflow: ellipsis;">' . $total_sold . '</td>';
                                echo '<td class="overflow-hidden" style="max-width: 200px; text-wrap: nowrap; text-overflow: ellipsis;">' . $total_revenue . '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4" class="text-center">Không có dữ liệu</td></tr>';
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Doanh thu hàng ngày',
                data: <?php echo json_encode($revenues); ?>,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderWidth: 2,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Ngày'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Doanh thu (VNĐ)'
                    },
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
</script>