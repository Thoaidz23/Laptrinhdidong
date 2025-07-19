<?php
    include("./admin/connect.php");
    $sql_baiviet = "SELECT * FROM tbl_baiviet WHERE id_baiviet = '$_GET[id_baiviet]'";
    $query_baiviet = mysqli_query($mysqli, $sql_baiviet);
    $row_baiviet = mysqli_fetch_array($query_baiviet);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Chi tiết bài viết</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    }

    .article-detail {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: #fff;
        border-radius: 8px;
    }

    .article-detail h2 {
        color: #333;
    }

    .article-detail img {
        width: 100%;
        height: auto;
        margin-top: 1rem;
    }

    .article-detail .content {
        margin-top: 1.5rem;
        line-height: 1.6;
    }

</style>
<body>
    <?php
        include("includes/header.php");
        include("includes/menubar.php");
    ?>
    <div class="article-detail">
        <h1 style="padding-bottom: 20px"><?php echo $row_baiviet["tieude"] ?></h1>
        <img src="./admin/quanlybaiviet/uploads/<?php echo $row_baiviet["hinhanh"] ?>">
        <div class="content">
            <p><?php echo $row_baiviet["noidung"] ?></p>
        </div>
    </div>
    <?php
        include("includes/footer.php");
    ?>

</body>
</html>
