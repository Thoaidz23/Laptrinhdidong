<div class="col-lg-12">
    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">Sửa sản phẩm </h3>
            </div>
        </div>
        <div class="card-body">

            <?php
                $sql_lietke_bv = "SELECT * FROM tbl_baiviet WHERE id_baiviet = '$_GET[id_baiviet]'";
                $query_lietke_bv = mysqli_query($mysqli, $sql_lietke_bv);
                while($row = mysqli_fetch_array($query_lietke_bv)) {
            ?>

            <form method="post" action="quanlybaiviet/xuly.php?id_baiviet=<?php echo $_GET['id_baiviet'] ?>" enctype="multipart/form-data" class="p-4">
                <div class="form-group mb-3">
                    <label>Tiêu đề</label>
                    <input type="text" class="form-control" name="tieude" value="<?php echo $row["tieude"] ?>">
                </div>
                
                <div class="form-group mb-3">
                    <label for="hinhanh">Hình ảnh</label>
                    <input type="file" class="form-control" name="hinhanh">
                    <img src="./quanlybaiviet/uploads/<?php echo $row["hinhanh"] ?>" width="150px" style="margin-top: 15px;">
                </div>
                
                <div class="form-group mb-3">
                    <label for="noidung">Nội dung</label>
                    <textarea rows="5" class="form-control" name="noidung" style="resize: none"><?php echo $row["noidung"] ?></textarea>
                </div>
                
                <div class="form-group mb-3">
                    <label>Danh mục bài viết</label>
                    <select class="form-control" name="id_dmbv">
                    <?php
                        $sql_lietke_dmbv = "SELECT * FROM tbl_danhmucbaiviet";
                        $query_lietke_dmbv = mysqli_query($mysqli, $sql_lietke_dmbv);
                        while($row_dmbv = mysqli_fetch_array($query_lietke_dmbv)) {
                        if($row_dmbv['id_dmbv'] == $row['id_dmbv']) {
                    ?>
                        <option selected value="<?php echo $row_dmbv['id_dmbv']; ?>"><?php echo $row_dmbv['ten_dmbv']; ?></option>
                        <?php
                        }
                        else {
                        ?>
                        <option value="<?php echo $row_dmbv['id_dmbv']; ?>"><?php echo $row_dmbv['ten_dmbv']; ?></option>
                    <?php
                        }
                    }
                    ?>
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label>Tình trạng</label>
                    <select class="form-control" name="tinhtrang">
                    <?php
                        if($row["tinhtrang"] == 1) {
                    ?>
                    <option value="1" selected>Kích hoạt</option>
                    <option value="0">Ẩn</option>
                    <?php
                        }
                        else {
                    ?>
                    <option value="1">Kích hoạt</option>
                    <option value="0" selected>Ẩn</option>
                    <?php
                        }
                    ?>
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <input type="submit" class="btn btn-primary" name="suabaiviet" value="Sửa bài viết">
                </div>
                </form>
                <?php
                    }
                ?>
        </div>
        
    </div>
</div>