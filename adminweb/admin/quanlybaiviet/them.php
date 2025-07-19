<div class="col-lg-12">
    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">Thêm bài viết </h3>
            </div>
        </div>
        <div class="card-body">
            <form method="post" action="quanlybaiviet/xuly.php" enctype="multipart/form-data" class="p-4">
                <div class="form-group mb-3">
                    <label>Tiêu đề bài viết</label>
                    <input type="text" class="form-control" name="tieude" placeholder="Nhập tiêu đề bài viết">
                </div>

                <div class="form-group mb-3">
                    <label for="hinhanh">Hình ảnh</label>
                    <input type="file" class="form-control" name="hinhanh">
                </div>
                
                <div class="form-group mb-3">
                    <label>Nội dung</label>
                    <textarea rows="5" class="form-control" name="noidung" style="resize: none" placeholder="Nhập nội dung bài viết"></textarea>
                </div>
                
                <div class="form-group mb-3">
                    <label>Danh mục bài viết</label>
                    <select class="form-control" name="dmbv">
                    <?php
                        $sql_lietke_dmbv = "SELECT * FROM tbl_danhmucbaiviet";
                        $query_lietke_dmbv = mysqli_query($mysqli, $sql_lietke_dmbv);
                        while($row_dmbv = mysqli_fetch_array($query_lietke_dmbv)) {
                    ?>
                        <option value="<?php echo $row_dmbv['id_dmbv']; ?>"><?php echo $row_dmbv['ten_dmbv']; ?></option>
                    <?php } ?>
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label>Tình trạng</label>
                    <select class="form-control" name="tinhtrang">
                    <option value="1">Kích hoạt</option>
                    <option value="0">Ẩn</option>
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <input type="submit" class="btn btn-primary" name="thembaiviet" value="Thêm bài viết">
                </div>
                </form>                                      
                                                                                    
        </div>
        
    </div>
</div>