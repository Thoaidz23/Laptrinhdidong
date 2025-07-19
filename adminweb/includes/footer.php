<?php
    include("./admin/connect.php");
?>
<br>
<div class="containert">
    <div class="container-information">
        <?php
            $sql = "SELECT * FROM tbl_footer";
            $query = mysqli_query($mysqli, $sql);
            while($row = mysqli_fetch_array($query)) {

        ?>
        <div class="column">
            <h3><?php echo $row["title"] ?></h3>
            <?php echo $row["content"] ?>
        </div>
        <?php
            }
        ?>
    </div>
</div>