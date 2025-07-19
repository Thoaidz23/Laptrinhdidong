<?php
    include("../connect.php");

    if(isset($_POST["themdanhmucsanpham"])) {

        $ten_dmsp = $_POST["ten_dmsp"];

        $sql_themdmsp = "INSERT INTO tbl_category_product(name) VALUE('$ten_dmsp')";
        mysqli_query($mysqli, $sql_themdmsp);
        header("location: ../admin.php?action=quanlydanhmucsanpham&query=them");
    }
    elseif(isset($_POST["suadmsp"])) {
        $ten_dmsp = $_POST["ten_dmsp"];

        $sql_update = "UPDATE tbl_category_product SET name = '$ten_dmsp' WHERE id_category_product = '$_GET[id_category_product]'";
        
        mysqli_query($mysqli, $sql_update);
        header("location: ../admin.php?action=quanlydanhmucsanpham&query=them");
    }
    

    elseif(isset($_GET["id_category_product"])) {
        $id = $_GET["id_category_product"];

        $sql_delete = "DELETE FROM tbl_category_product WHERE id_category_product = $id";
        mysqli_query($mysqli, $sql_delete);
        header("location: ../admin.php?action=quanlydanhmucsanpham&query=them");
    }
?>