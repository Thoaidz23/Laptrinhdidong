<div class="col-lg-12">
    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">Liệt kê footer  </h3>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover" style="width: 100%">
                <thead>
                    <tr>
                    <th>STT</th>
                    <th>Tiêu đề</th>
                    <th>Nội dung</th>
                    <th>Quản lý</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    $sql_lietke = "SELECT * FROM tbl_footer";
                    $query_lietke = mysqli_query($mysqli, $sql_lietke);
                    while($row = mysqli_fetch_array($query_lietke)) {
                        $i++;
                    ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo $row["title"] ?></td>
                        <td><?php echo $row["content"] ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="?action=quanlyfooter&query=sua&id_footer=<?php echo $row['id_footer']; ?>" class="btn btn-warning me-2">Sửa</a>
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