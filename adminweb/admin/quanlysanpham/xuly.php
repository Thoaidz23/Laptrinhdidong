<?php
    include("../connect.php");

    if(isset($_POST["themsanpham"])) {

        $tensp = $_POST["tensp"];
        $giasp = $_POST["giasp"];
        $soluong = $_POST["soluong"];
        $hinhanh = $_FILES["hinhanh"]["name"];
        $hinhanh_tmp = $_FILES["hinhanh"]["tmp_name"];
        $hinhanh = time()."_".$hinhanh;
        $noidung = $_POST["noidung"];
        $dmsp = $_POST["dmsp"];

        $sql_themsp = "INSERT INTO tbl_product(name, price, quantity, image, content, id_category_product) VALUE('$tensp', '$giasp', '$soluong', '$hinhanh','$noidung', '$dmsp')";
        mysqli_query($mysqli, $sql_themsp);
        move_uploaded_file($hinhanh_tmp, "uploads/".$hinhanh);

        $product_id = mysqli_insert_id($mysqli);
        if (isset($_FILES['file']['name']) && is_array($_FILES['file']['name'])) {
            foreach ($_FILES['file']['name'] as $key => $image_name) {
                $image_tmp_name = $_FILES['file']['tmp_name'][$key];
                $image_name = time() . "_" . $image_name;
                
                move_uploaded_file($image_tmp_name, "uploads_chitiet/" . $image_name);
                
                $sql_image = "INSERT INTO tbl_product_images (id_product, name) VALUES ('$product_id', '$image_name')";
                mysqli_query($mysqli, $sql_image);
            }
        }

        // if (isset($_POST['thuoc_tinh']) && isset($_POST['gia_tri'])) {
        //     $thuoc_tinh = $_POST['thuoc_tinh'];
        //     $gia_tri = $_POST['gia_tri'];

        //     foreach ($thuoc_tinh as $key => $value) {
        //         $sql_tskt = "INSERT INTO tbl_thongsokythuat (id_sanpham, thuoctinh, giatri) VALUES ('$product_id', '$thuoc_tinh[$key]', '$gia_tri[$key]')";
        //         mysqli_query($mysqli, $sql_tskt);
        //     }
        // }

        header("location: ../admin.php?action=quanlysanpham&query=lietke");
    }

    elseif(isset($_POST["suasanpham"])) {

            $tensp = $_POST["tensp"];
            $giasp = $_POST["giasp"];
            $soluong = $_POST["soluong"];
            $noidung = $_POST["noidung"];
            $id_dmsp = $_POST["id_dmsp"];

        if(!empty($_FILES["hinhanh"]["name"])) {
            $hinhanh = $_FILES["hinhanh"]["name"];
            $hinhanh_tmp = $_FILES["hinhanh"]["tmp_name"];
            $hinhanh = time()."_".$hinhanh;
    
            $sql = "SELECT * FROM tbl_product WHERE id_product = '$_GET[id_sanpham]'";
            $query = mysqli_query($mysqli, $sql);
            while($row = mysqli_fetch_array($query)) {
                unlink("uploads/".$row["image"]);
            }
    
            move_uploaded_file($hinhanh_tmp, "uploads/".$hinhanh);
            
            $sql_update = "UPDATE tbl_product SET name = '$tensp', price = '$giasp', quantity = '$soluong', image = '$hinhanh', content = '$noidung', id_category_product = '$id_dmsp' WHERE id_product = '$_GET[id_sanpham]'";
        }
        else {
            $sql_update = "UPDATE tbl_product SET name = '$tensp', price = '$giasp', quantity = '$soluong', content = '$noidung', id_category_product = '$id_dmsp' WHERE id_product = '$_GET[id_sanpham]'";
        }
        mysqli_query($mysqli, $sql_update);



        if(isset($_FILES['file']['name']) && array_filter($_FILES['file']['name'])) {
            $id_sanpham = $_GET['id_sanpham'];

            $sql_images = "SELECT * FROM tbl_product_images WHERE id_product = '$id_sanpham'";
            $query_images = mysqli_query($mysqli, $sql_images);
            while ($row_image = mysqli_fetch_array($query_images)) {
                $file_path = "uploads_chitiet/" . $row_image['name'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }

            $sql_delete_images = "DELETE FROM tbl_product_images WHERE id_product = '$id_sanpham'";
            mysqli_query($mysqli, $sql_delete_images);

            foreach ($_FILES['file']['name'] as $key => $image_name) {
                $image_tmp_name = $_FILES['file']['tmp_name'][$key];
                $new_image_name = time() . "_" . $image_name;

                move_uploaded_file($image_tmp_name, "uploads_chitiet/" . $new_image_name);

                $sql_insert_image = "INSERT INTO tbl_product_images (id_product, name) VALUES ('$id_sanpham', '$new_image_name')";
                mysqli_query($mysqli, $sql_insert_image);
            }
        }

        // if (isset($_POST['thuoc_tinh']) && isset($_POST['gia_tri'])) {
        //     $thuoc_tinh = $_POST['thuoc_tinh'];
        //     $gia_tri = $_POST['gia_tri'];

        //     $sql_delete_tskt = "DELETE FROM tbl_thongsokythuat WHERE id_sanpham = '$_GET[id_sanpham]'";
        //     mysqli_query($mysqli, $sql_delete_tskt);

        //     foreach ($thuoc_tinh as $key => $value) {
        //         $value = mysqli_real_escape_string($mysqli, $value);
        //         $id_sanpham = $_GET['id_sanpham'];
        //         $sql_insert_tskt = "INSERT INTO tbl_thongsokythuat (id_sanpham, thuoctinh, giatri) VALUES ('$id_sanpham', '$value', '" . mysqli_real_escape_string($mysqli, $gia_tri[$key]) . "')";
        //         mysqli_query($mysqli, $sql_insert_tskt);
        //     }
        // }

        header("location: ../admin.php?action=quanlysanpham&query=lietke");
    }
    


    elseif(isset($_GET["id_sanpham"]) && $_GET['query'] == 'xoa') {
        $id = $_GET["id_sanpham"];

        $sql_unlink = "SELECT * FROM tbl_product WHERE id_product = $id";
        $query_unlink = mysqli_query($mysqli, $sql_unlink);
        while($row = mysqli_fetch_array($query_unlink)) {
            unlink("uploads/".$row['image']);
        };

        $sql_unlink_chitiet = "SELECT * FROM tbl_product_images WHERE id_product = $id";
        $query_unlink_chitiet = mysqli_query($mysqli, $sql_unlink_chitiet);
        while($row = mysqli_fetch_array($query_unlink_chitiet)) {
            unlink("uploads_chitiet/".$row['name']);
        }

        $sql_delete = "DELETE FROM tbl_product WHERE id_product = $id";
        mysqli_query($mysqli, $sql_delete);
        $sql_delete_chitiet = "DELETE FROM tbl_product_images WHERE id_product = $id";
        mysqli_query($mysqli, $sql_delete_chitiet);
        // $sql_delete_tskt = "DELETE FROM tbl_thongsokythuat WHERE id_sanpham = $id";
        // mysqli_query($mysqli, $sql_delete_tskt);

        header("location: ../admin.php?action=quanlysanpham&query=lietke");
    }

    

?>