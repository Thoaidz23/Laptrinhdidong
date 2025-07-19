<div class="col-lg-12">
    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">Sửa footer </h3>
            </div>
        </div>
        <div class="card-body">

            <?php
                $sql_lietke = "SELECT * FROM tbl_footer WHERE id_footer = '$_GET[id_footer]'";
                $query_lietke = mysqli_query($mysqli, $sql_lietke);
                while($row = mysqli_fetch_array($query_lietke)) {
            ?>

            <form method="post" action="quanlyfooter/xuly.php?id_footer=<?php echo $_GET['id_footer'] ?>" class="p-4">
                <div class="form-group mb-3">
                    <label for="tensp">Tiêu đề</label>
                    <input type="text" class="form-control" name="tieude" value="<?php echo $row["title"] ?>">
                </div>
                
                <div class="form-group mb-3">
                    <label for="noidung">Nội dung</label>
                    <textarea rows="5" class="form-control" name="noidung" style="resize: none"><?php echo $row["content"] ?></textarea>
                </div>
                
                <div class="form-group mb-3 d-flex justify-content-center">
                    <input type="submit" class="btn btn-primary" name="suafooter" value="Sửa">
                </div>


                </form>
                <?php
                    }
                ?>
        </div>
        
    </div>
</div>