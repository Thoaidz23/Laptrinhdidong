<div class="col-lg-12">
    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">Liệt kê banner </h3>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover" style="width: 100%">
                <thead>
                    <tr>
                    <th>STT</th>
                    <th>Tên banner</th>
                    <th>Hình ảnh</th>
                    <th>Quản lý</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    $sql_lietke_banner = "SELECT * FROM tbl_banner";
                    $query_lietke_banner = mysqli_query($mysqli, $sql_lietke_banner);
                    while($row = mysqli_fetch_array($query_lietke_banner)) {
                        $i++;
                    ?>
                    <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $row["name"] ?></td>
                    <td><img src="./quanlybanner/uploads/<?php echo $row["image"] ?>" width="350px" height="150px"></td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="?action=quanlybanner&query=sua&id_banner=<?php echo $row['id_banner']; ?>" class="btn btn-warning me-2">Sửa</a>
                            <a href="quanlybanner/xuly.php?id_banner=<?php echo $row['id_banner']; ?>" class="btn btn-danger me-2">Xóa</a>
                        </div>
                    </td>

                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
                </table>
                
                                                                    
        </div>
    </div>
</div>