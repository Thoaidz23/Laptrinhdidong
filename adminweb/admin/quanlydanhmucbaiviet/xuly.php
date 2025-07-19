<?php
    include("../connect.php");

    if(isset($_POST["themdanhmucbaiviet"])) {

        $ten_dmbv = $_POST["ten_dmbv"];
        $thutu = $_POST["thutu"];

        $sql_themdmbv = "INSERT INTO tbl_danhmucbaiviet(ten_dmbv, thutu) VALUE('$ten_dmbv', '$thutu')";
        mysqli_query($mysqli, $sql_themdmbv);
        header("location: ../admin.php?action=quanlydanhmucbaiviet&query=them");
    }
    elseif(isset($_POST["suadmbv"])) {
        $ten_dmbv = $_POST["ten_dmbv"];
        $thutu = $_POST["thutu"];

        $sql_update = "UPDATE tbl_danhmucbaiviet SET ten_dmbv = '$ten_dmbv', thutu = '$thutu' WHERE id_dmbv = '$_GET[id_dmbv]'";
        
        mysqli_query($mysqli, $sql_update);
        header("location: ../admin.php?action=quanlydanhmucbaiviet&query=them");
    }
    

    elseif(isset($_GET["id_dmbv"])) {
        $id = $_GET["id_dmbv"];

        $sql_delete = "DELETE FROM tbl_danhmucbaiviet WHERE id_dmbv = $id";
        mysqli_query($mysqli, $sql_delete);
        header("location: ../admin.php?action=quanlydanhmucbaiviet&query=them");
    }
?>