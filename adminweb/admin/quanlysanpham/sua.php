<div class="col-lg-12">
    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">Sửa sản phẩm</h3>
            </div>
        </div>
        <div class="card-body">

            <?php
                $sql_lietke_sp = "SELECT * FROM tbl_product WHERE id_product = '$_GET[id_sanpham]'";
                $query_lietke_sp = mysqli_query($mysqli, $sql_lietke_sp);
                while($row_sp = mysqli_fetch_array($query_lietke_sp)) {
            ?>

            <form method="post" action="quanlysanpham/xuly.php?id_sanpham=<?php echo $_GET['id_sanpham'] ?>" enctype="multipart/form-data" class="p-4">
                
                <!-- Tên sản phẩm -->
                <div class="form-group mb-3">
                    <label for="tensp">Tên sản phẩm</label>
                    <input type="text" class="form-control" id="tensp" name="tensp" value="<?php echo $row_sp["name"] ?>">
                </div>

                <!-- Giá sản phẩm -->
                <div class="form-group mb-3">
                    <label for="giasp">Giá sản phẩm</label>
                    <input type="text" class="form-control" id="giasp" name="giasp" value="<?php echo $row_sp["price"] ?>">
                </div>

                <!-- Số lượng -->
                <div class="form-group mb-3">
                    <label for="soluong">Số lượng</label>
                    <input type="text" class="form-control" id="soluong" name="soluong" value="<?php echo $row_sp["quantity"] ?>">
                </div>

                <!-- Hình ảnh đại diện -->
                <div class="form-group mb-3">
                    <label for="hinhanh">Hình ảnh</label>
                    <input type="file" class="form-control" id="hinhanh" name="hinhanh">
                    <?php if (!empty($row_sp["image"])): ?>
                        <img src="./quanlysanpham/uploads/<?php echo $row_sp["image"] ?>" width="150px" class="mt-3">
                    <?php endif; ?>
                </div>

                <!-- Nội dung -->
                <div class="form-group mb-3">
                    <label for="noidung">Nội dung</label>
                    <textarea rows="5" class="form-control" id="noidung" name="noidung"><?php echo $row_sp["content"] ?></textarea>
                </div>

                <!-- Danh mục sản phẩm -->
                <div class="form-group mb-3">
                    <label for="dmsp">Danh mục sản phẩm</label>
                    <select class="form-control" id="dmsp" name="id_dmsp">
                        <?php
                            $sql_lietke_dmsp = "SELECT * FROM tbl_category_product";
                            $query_lietke_dmsp = mysqli_query($mysqli, $sql_lietke_dmsp);
                            while($row_dmsp = mysqli_fetch_array($query_lietke_dmsp)) {
                                $selected = ($row_dmsp['id_category_product'] == $row_sp['id_category_product']) ? 'selected' : '';
                                echo "<option value='{$row_dmsp['id_category_product']}' $selected>{$row_dmsp['name']}</option>";
                            }
                        ?>
                    </select>
                </div>

                <!-- Hình ảnh chi tiết -->
                <div class="form-group mb-3">
                    <label for="hinhanh">Hình ảnh chi tiết</label><br>
                    <div class="d-flex flex-wrap">
                        <?php
                            $sql_images = "SELECT * FROM tbl_product_images WHERE id_product = '$_GET[id_sanpham]'";
                            $query_images = mysqli_query($mysqli, $sql_images);
                            while ($row_image = mysqli_fetch_array($query_images)) {
                                echo '<div style="margin-right: 20px; text-align: center;">
                                        <img src="./quanlysanpham/uploads_chitiet/' . $row_image["name"] . '" width="100" height="100" class="mt-2">
                                    </div>';
                            }
                        ?>
                    </div>
                    <label class="mt-3">Sửa tất cả hình ảnh</label><br>
                    <input name="file[]" type="file" multiple />
                </div>

                <!-- Nút Sửa -->
                <div class="form-group text-center mt-5">
                    <input type="submit" class="btn btn-primary btn-lg px-5" name="suasanpham" value="Sửa sản phẩm">
                </div>

            </form>

            <?php } // end while ?>
        </div>
    </div>
</div>
