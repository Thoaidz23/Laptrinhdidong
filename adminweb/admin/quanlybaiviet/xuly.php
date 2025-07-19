<?php
    session_start();
    include("../connect.php");

    if(isset($_POST["thembaiviet"])) {

        $tieude = $_POST["tieude"];
        $hinhanh = $_FILES["hinhanh"]["name"];
        $hinhanh_tmp = $_FILES["hinhanh"]["tmp_name"];
        $hinhanh = time()."_".$hinhanh;
        $noidung = $_POST["noidung"];
        $tinhtrang = $_POST["tinhtrang"];
        $dmbv = $_POST["dmbv"];
        $id_user = $_SESSION["admin_id_user"];

        $sql_thembv = "INSERT INTO tbl_baiviet(tieude, hinhanh, noidung, tinhtrang, id_dmbv, id_user) VALUE('$tieude', '$hinhanh','$noidung', '$tinhtrang', '$dmbv', '$id_user')";
        mysqli_query($mysqli, $sql_thembv);
        move_uploaded_file($hinhanh_tmp, "uploads/".$hinhanh);
        header("location: ../admin.php?action=quanlybaiviet&query=lietke");
    }
    elseif(isset($_POST["suabaiviet"])) {

        $tieude = $_POST["tieude"];
        $noidung = $_POST["noidung"];
        $tinhtrang = $_POST["tinhtrang"];
        $id_dmbv = $_POST["id_dmbv"];
        $id_user = $_SESSION["admin_id_user"];

        if(!empty($_FILES["hinhanh"]["name"])) {
            $hinhanh = $_FILES["hinhanh"]["name"];
            $hinhanh_tmp = $_FILES["hinhanh"]["tmp_name"];
            $hinhanh = time()."_".$hinhanh;
    
            $sql = "SELECT * FROM tbl_baiviet WHERE id_baiviet = '$_GET[id_baiviet]'";
            $query = mysqli_query($mysqli, $sql);
            while($row = mysqli_fetch_array($query)) {
                unlink("uploads/".$row["hinhanh"]);
            }
    
            move_uploaded_file($hinhanh_tmp, "uploads/".$hinhanh);
            
            $sql_update = "UPDATE tbl_baiviet SET tieude = '$tieude', hinhanh = '$hinhanh', noidung = '$noidung', tinhtrang = '$tinhtrang', id_dmbv = '$id_dmbv', id_user = '$id_user' WHERE id_baiviet = '$_GET[id_baiviet]'";
        }
        else {
            $sql_update = "UPDATE tbl_baiviet SET tieude = '$tieude', noidung = '$noidung', tinhtrang = '$tinhtrang', id_dmbv = '$id_dmbv', id_user = '$id_user' WHERE id_baiviet = '$_GET[id_baiviet]'";
        }
        
        mysqli_query($mysqli, $sql_update);
        header("location: ../admin.php?action=quanlybaiviet&query=lietke");
    }
    
    else if(isset($_GET["id_baiviet"])) {
        $id = $_GET["id_baiviet"];

        $sql = "SELECT * FROM tbl_baiviet WHERE id_baiviet = $id";
        $query = mysqli_query($mysqli, $sql);
        while($row = mysqli_fetch_array($query)) {
            unlink("uploads/".$row['hinhanh']);
        };

        $sql_delete = "DELETE FROM tbl_baiviet WHERE id_baiviet = $id";
        mysqli_query($mysqli, $sql_delete);
        header("location: ../admin.php?action=quanlybaiviet&query=lietke");
    }
?>