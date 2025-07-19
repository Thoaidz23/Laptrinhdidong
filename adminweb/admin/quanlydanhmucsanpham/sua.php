<div class="col-lg-12">
    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">Sửa danh mục sản phẩm </h3>
            </div>
        </div>
        <div class="card-body">

            <?php
                $sql_lietke_dmsp = "SELECT * FROM tbl_category_product WHERE id_category_product = '$_GET[id_category_product]'";
                $query_lietke_dmsp = mysqli_query($mysqli, $sql_lietke_dmsp);
                while($row = mysqli_fetch_array($query_lietke_dmsp)) {
            ?>

            <form method="post" action="quanlydanhmucsanpham/xuly.php?id_category_product=<?php echo $_GET['id_category_product'] ?>" class="p-4">
                <div class="form-group mb-3">
                    <label for="tensp">Tên danh mục sản phẩm</label>
                    <input type="text" class="form-control" name="ten_dmsp" value="<?php echo $row["name"] ?>">
                </div>
                
                <div class="form-group mb-3">
                    <input type="submit" class="btn btn-primary" name="suadmsp" value="Sửa danh mục sản phẩm">
                </div>
                </form>
                <?php
                    }
                ?>
        </div>
        
    </div>
</div>