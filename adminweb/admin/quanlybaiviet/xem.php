<div class="col-lg-12">
    <div class="card">
        <div class="card-header border-0">
        </div>
        <div class="card-body">
            <?php
                $sql_lietke_bv = "SELECT * FROM tbl_baiviet WHERE id_baiviet = '$_GET[id_baiviet]'";
                $query_lietke_bv = mysqli_query($mysqli, $sql_lietke_bv);
                while($row = mysqli_fetch_array($query_lietke_bv)) {
            ?>
            <div class="row">
                <div class="col-md-8 mb-4">
                    <div class="mb-2">
                        <strong>Tiêu đề:</strong> <?php echo $row["tieude"]?>
                    </div>

                    <div class="mb-2">
                        <strong>Danh mục:</strong> 
                        <?php
                            $sql_lietke_dmbv = "SELECT * FROM tbl_danhmucbaiviet WHERE id_dmbv = '" . $row['id_dmbv'] . "'";
                            $query_lietke_dmbv = mysqli_query($mysqli, $sql_lietke_dmbv);
                            $row_dmbv = mysqli_fetch_array($query_lietke_dmbv);
                            echo $row_dmbv['ten_dmbv'];
                        ?>
                    </div>

                    <div class="mb-2">
                        <strong>Tình trạng:</strong> <?php echo $row["tinhtrang"] == 1 ? "Kích hoạt" : "Ẩn"; ?>
                    </div>
                </div>

                <div class="col-md-4 mb-4 text-center">
                    <img src="./quanlybaiviet/uploads/<?php echo $row["hinhanh"] ?>" class="img-thumbnail" alt="Hình ảnh bài viết" width="300">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="p-3 bg-light border rounded" style="font-size: 0.9rem; line-height: 1.6; margin-top: 20px;">
                        <?php
                            $noidung = $row["noidung"];
                            $noidung = preg_replace('/(\r\n|\n|\r)/', ' ', $noidung);
                            $noidung = preg_replace('/\s+/', ' ', $noidung);
                            $noidung = trim($noidung);
                            echo nl2br($noidung);
                        ?>
                    </div>
                </div>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
</div>
