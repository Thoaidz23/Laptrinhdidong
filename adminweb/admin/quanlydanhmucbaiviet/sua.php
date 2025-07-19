<div class="col-lg-12">
    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">Sửa danh mục bài viết </h3>
            </div>
        </div>
        <div class="card-body">

            <?php
                $sql_lietke_dmbv = "SELECT * FROM tbl_danhmucbaiviet WHERE id_dmbv = '$_GET[id_dmbv]'";
                $query_lietke_dmbv = mysqli_query($mysqli, $sql_lietke_dmbv);
                while($row = mysqli_fetch_array($query_lietke_dmbv)) {
            ?>

            <form method="post" action="quanlydanhmucbaiviet/xuly.php?id_dmbv=<?php echo $_GET['id_dmbv'] ?>" class="p-4">
                <div class="form-group mb-3">
                    <label for="tensp">Tên danh mục bài viết</label>
                    <input type="text" class="form-control" name="ten_dmbv" value="<?php echo $row["ten_dmbv"] ?>">
                </div>
                
                <div class="form-group mb-3">
                    <label for="masp">Thứ tự</label>
                    <input type="text" class="form-control" name="thutu" value="<?php echo $row["thutu"] ?>">
                </div>
                
                <div class="form-group mb-3">
                    <input type="submit" class="btn btn-primary" name="suadmbv" value="Sửa danh mục bài viết">
                </div>
                </form>
                <?php
                    }
                ?>
        </div>
        
    </div>
</div>