<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <?php
            $id = $_GET['id_sanpham'];
            $sql_lietke_sp = "SELECT * FROM tbl_product WHERE id_product = '$id'";
            $query_lietke_sp = mysqli_query($mysqli, $sql_lietke_sp);
            while ($row_sp = mysqli_fetch_array($query_lietke_sp)) {
            ?>
            <div class="row">
                <!-- Thông tin cơ bản -->
                <div class="col-md-6 mb-4">
                    <h5 class="mb-3">Thông tin cơ bản</h5>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Tên sản phẩm:</strong> <?= $row_sp["name"]; ?></li>
                        <li class="list-group-item"><strong>Giá:</strong> <?= number_format($row_sp["price"], 0, ',', '.'); ?> VNĐ</li>
                        <li class="list-group-item"><strong>Số lượng:</strong> <?= $row_sp["quantity"]; ?></li>
                        <?php
                        $sql_danhmuc = "SELECT name FROM tbl_category_product WHERE id_category_product = '" . $row_sp["id_category_product"] . "'";
                        $query_danhmuc = mysqli_query($mysqli, $sql_danhmuc);
                        $row_danhmuc = mysqli_fetch_array($query_danhmuc);
                        ?>
                        <li class="list-group-item"><strong>Danh mục:</strong> <?= $row_danhmuc["name"]; ?></li>
                    </ul>
                </div>

                <!-- Hình ảnh sản phẩm -->
                <div class="col-md-6 mb-4">
                    <h5 class="mb-3">Hình ảnh chính</h5>
                    <div class="text-center">
                        <img src="./quanlysanpham/uploads/<?= $row_sp["image"]; ?>" alt="Ảnh sản phẩm" class="img-fluid rounded border shadow-sm" style="max-height: 300px;">
                    </div>

                    <h6 class="mt-4">Hình ảnh chi tiết</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <?php
                        $sql_images = "SELECT * FROM tbl_product_images WHERE id_product = '$id'";
                        $query_images = mysqli_query($mysqli, $sql_images);
                        while ($row_image = mysqli_fetch_array($query_images)) {
                            echo '<img src="./quanlysanpham/uploads_chitiet/' . $row_image["name"] . '" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Mô tả -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <h5>Mô tả sản phẩm</h5>
                    <div class="p-3 bg-light border rounded" style="font-size: 0.95rem;">
                        <?= nl2br(htmlspecialchars(trim($row_sp["content"]))) ?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
