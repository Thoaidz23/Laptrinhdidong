<div class="col-lg-12">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white text-center">
            <h4 class="mb-0">Mã đơn hàng: <?php echo $_GET["code_order"] ?></h4>
        </div>
    </div>
</div>


<div class="col-lg-12">
    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">Thông tin khách hàng </h3>
            </div>
        </div>
            <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Ngày đặt</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_lietke = "SELECT * FROM tbl_order, tbl_user WHERE tbl_order.id_user = tbl_user.id_user AND tbl_order.id_order = $_GET[id_order] ORDER BY tbl_order.id_order DESC";
                    $query_lietke = mysqli_query($mysqli, $sql_lietke);
                    while ($row = mysqli_fetch_array($query_lietke)) {
                    ?>
                        <tr>
                            <td><i class="fas fa-user"></i> <?php echo $row["name"]; ?></td>
                            <td><i class="fas fa-envelope"></i> <?php echo $row["email"]; ?></td>
                            <td><i class="fas fa-phone"></i> <?php echo $row["phone"]; ?></td>
                            <td><i class="fas fa-map-marker-alt"></i> <?php echo $row["address"]; ?></td>
                            <td><i class="fas fa-calendar-alt"></i> <?php echo $row["date"]; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">Chi tiết đơn hàng </h3>
            </div>
        </div>
        <div class="card-body">
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>STT</th>
                    <th>Tên sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_get_code = "SELECT code_order FROM tbl_order WHERE id_order = $_GET[id_order]";
                $result_get_code = mysqli_query($mysqli, $sql_get_code);
                $total_price = 0;
                $i = 1;

                if ($row_code = mysqli_fetch_array($result_get_code)) {
                    $code_order = $row_code['code_order'];
                    $sql = "SELECT * FROM tbl_order_detail, tbl_sanpham WHERE tbl_order_detail.id_sanpham = tbl_sanpham.id_sanpham AND tbl_order_detail.code_order = '$code_order' ORDER BY tbl_sanpham.giasp DESC";
                    $query = mysqli_query($mysqli, $sql);
                    while ($row = mysqli_fetch_array($query)) {
                        $total_price += $row["giasp"] * $row["soluongmua"];
                ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $row["ten_sanpham"]; ?></td>
                        <td><img src="./quanlysanpham/uploads/<?php echo $row["hinhanh"]; ?>" class="img-fluid" width="150px" alt="Product Image"></td>
                        <td><?php echo $row["soluongmua"]; ?></td>
                        <td><?php echo number_format($row["giasp"], 0, ',', '.'); ?>đ</td>
                        <td><?php echo number_format($row["giasp"] * $row["soluongmua"], 0, ',', '.'); ?>đ</td>
                    </tr>
                <?php
                    }
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right"><strong>Tổng tiền</strong></td>
                    <td><strong><?php echo number_format($total_price, 0, ',', '.'); ?>đ</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>



    </div>
</div>



<div class="col-lg-12">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white text-center">
            <h4 class="mb-0">Cập nhật trạng thái đơn hàng</h4>
        </div>
        <div class="card-body">
            <form action="quanlydonhang/xuly.php?id_order=<?php echo $_GET['id_order'] ?>" method="POST">
                <div class="form-group">
                    <label for="orderStatus">Chọn trạng thái đơn hàng</label>
                    <?php
                        $sql_lietke = "SELECT * FROM tbl_order WHERE id_order = $_GET[id_order]";
                        $query_lietke = mysqli_query($mysqli, $sql_lietke);
                        while ($row = mysqli_fetch_array($query_lietke)) {
                    ?>
                    <select class="form-control" id="orderStatus" name="status">
                        <option value="0" <?php if($row["status"] == 0) echo "selected"; ?>>Chờ xác nhận</option>
                        <option value="1" <?php if($row["status"] == 1) echo "selected"; ?>>Đã xác nhận</option>
                        <option value="2" <?php if($row["status"] == 2) echo "selected"; ?>>Đang vận chuyển</option>
                        <option value="3" <?php if($row["status"] == 3) echo "selected"; ?>>Đã giao hàng</option>
                        <option value="5" <?php if($row["status"] == 5) echo "selected"; ?>>Đang chờ hủy</option>
                        <option value="4" <?php if($row["status"] == 4) echo "selected"; ?>>Đã hủy</option>
                    </select>
                        <?php
                        }
                        ?>
                </div>
                <div class="form-group text-center">
                    <button type="submit" name="xulydonhang" class="btn btn-success">Xử lý</button>
                </div>
            </form>
        </div>
    </div>
</div>
