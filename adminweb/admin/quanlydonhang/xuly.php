<?php
    include("../connect.php");

    if(isset($_POST["xulydonhang"])) {
        $status = $_POST["status"];
        $id_order = $_GET["id_order"];

        $sql_update = "UPDATE tbl_order SET status = '$status' WHERE id_order = '$id_order'";
        mysqli_query($mysqli, $sql_update);

        header("location: ../admin.php?action=quanlydonhang&query=lietke");

    }

?>