<div class="col-lg-12">
    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">Sửa banner </h3>
            </div>
        </div>
        <div class="card-body">

            <?php
                $sql_lietke_banner = "SELECT * FROM tbl_banner WHERE id_banner = '$_GET[id_banner]'";
                $query_lietke_banner = mysqli_query($mysqli, $sql_lietke_banner);
                while($row = mysqli_fetch_array($query_lietke_banner)) {
            ?>

            <form method="post" action="quanlybanner/xuly.php?id_banner=<?php echo $_GET['id_banner'] ?>" enctype="multipart/form-data" class="p-4">
                <div class="form-group mb-3">
                    <label>Tên banner</label>
                    <input type="text" class="form-control" name="tenbanner" value="<?php echo $row["name"] ?>">
                </div>
                
                <div class="form-group mb-3">
                    <label for="hinhanh">Hình ảnh</label>
                    <input type="file" class="form-control" id="hinhanh" name="hinhanh">
                    <img src="./quanlybanner/uploads/<?php echo $row["image"] ?>" width="350px" height="150px" style="margin-top: 15px;">
                </div>
                
                <div class="form-group mb-3">
                    <input type="submit" class="btn btn-primary" name="suabanner" value="Sửa banner">
                </div>
                </form>
                <?php
                    }
                ?>
        </div>
        
    </div>
</div>