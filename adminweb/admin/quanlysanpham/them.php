<div class="col-lg-12">
    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">Thêm sản phẩm </h3>
            </div>
        </div>
        <div class="card-body">
            <form method="post" action="quanlysanpham/xuly.php" enctype="multipart/form-data" class="p-4">
                <div class="form-group mb-3">
                    <label for="tensp">Tên sản phẩm</label>
                    <input type="text" class="form-control" id="tensp" name="tensp" placeholder="Nhập tên sản phẩm">
                </div>
                
                <div class="form-group mb-3">
                    <label for="giasp">Giá sản phẩm</label>
                    <input type="text" class="form-control" id="giasp" name="giasp" placeholder="Nhập giá sản phẩm">
                </div>
                
                <div class="form-group mb-3">
                    <label for="soluong">Số lượng</label>
                    <input type="text" class="form-control" id="soluong" name="soluong" placeholder="Nhập số lượng">
                </div>
                
                <div class="form-group mb-3">
                    <label for="hinhanh">Hình ảnh</label>
                    <input type="file" class="form-control" id="hinhanh" name="hinhanh">
                </div>
                
                <div class="form-group mb-3">
                    <label for="noidung">Nội dung</label>
                    <textarea rows="5" class="form-control" id="noidung" name="noidung" style="resize: none" placeholder="Nhập nội dung sản phẩm"></textarea>
                </div>
                
                <div class="form-group mb-3">
                    <label for="dmsp">Danh mục sản phẩm</label>
                    <select class="form-control" id="dmsp" name="dmsp">
                    <?php
                        $sql_lietke_dmsp = "SELECT * FROM tbl_category_product";
                        $query_lietke_dmsp = mysqli_query($mysqli, $sql_lietke_dmsp);
                        while($row_dmsp = mysqli_fetch_array($query_lietke_dmsp)) {
                    ?>
                        <option value="<?php echo $row_dmsp['id_category_product']; ?>"><?php echo $row_dmsp['name']; ?></option>
                    <?php } ?>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="file">Chọn ảnh chi tiết</label>
                    <input name="file[]" type="file" class="form-control" multiple />
                </div>

                <div class="form-group mb-3" style="text-align: center">
                    <input type="submit" class="btn btn-primary" name="themsanpham" value="Thêm sản phẩm">
                </div>
                </form>
                                                                                    
        </div>
        
    </div>
</div>