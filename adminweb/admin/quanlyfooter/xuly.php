<?php
    session_start();
    include("../connect.php");


    if(isset($_POST["suafooter"])) {
        $title = $_POST["tieude"];
        $content = $_POST["noidung"];
        $id_user = $_SESSION["admin_id_user"];

        $sql_update = "UPDATE tbl_footer SET title = '$title', content = '$content', id_user = '$id_user' WHERE id_footer = '$_GET[id_footer]'";
        
        mysqli_query($mysqli, $sql_update);
        header("location: ../admin.php?action=quanlyfooter&query=lietke");
    }
?>