<?php
    session_start();
    include("./connect.php");

    if (isset($_SESSION["admin_id_user"])) {
        $admin_id_user = $_SESSION["admin_id_user"];
        
        $sql = "SELECT * FROM tbl_user WHERE id_user = '$admin_id_user' LIMIT 1";
        $query = mysqli_query($mysqli, $sql);
        $user_data = mysqli_fetch_array($query);
    } else {
        header("location: ../dangnhap.php");
        exit;
    }
?>

<?php

    $admin_id = $_SESSION['admin_id_user'];

    $sql_admin_info = "SELECT name, email, phone, address FROM tbl_user WHERE id_user = ?";
    $stmt = $mysqli->prepare($sql_admin_info);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="icon" href="../img/avatar.png" type="image/x-icon"/ class="circle-favicon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/bootstrap/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="/bootstrap/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="./admin.css">
    <link rel="stylesheet" href="/bootstrap/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="/bootstrap/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/bootstrap/summernote/summernote-bs4.min.css">
    <link rel="stylesheet" href="./adminlte.min.css">
    <link rel='stylesheet' type='text/css' property='stylesheet' href='/_debugbar/assets/stylesheets?v=1712920837&theme=auto' data-turbolinks-eval='false' data-turbo-eval='false'>
    <!-- Link CKEditor -->
    <script src="https://cdn.ckeditor.com/4.21.0/full-all/ckeditor.js"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Trang chủ </a>
                </li>
            </ul>
        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="#" class="brand-link">
                <img src="img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">TTS-SHOP</span>
            </a>

            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="https://img.freepik.com/free-vector/illustration-businessman_53876-5856.jpg?t=st=1695654153~exp=1695654753~hmac=ccdb7ab9b584ef4ad693ca318468fa2a00e1fb54c36baee3dac67355bccaf946" onerror="this.src='https://tse3.mm.bing.net/th?id=OIP.R97hFhAyScVK0EsD5seM6wHaHa&pid=Api&P=0&h=180';" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="./admin.php?action=quanlytaikhoan&query=xem" class="d-block">
                            <?php
                                echo $_SESSION["admin_name"];
                            ?>
                        </a>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="./admin.php?action=thongke" class="nav-link">
                                <i class="nav-icon bi bi-house"></i>
                                <p>
                                    Thống kê
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./admin.php?action=quanlydonhang&query=lietke" class="nav-link">
                                <i class="fa-solid fa-receipt" style="margin-left: 4px;"></i>
                                <p style="margin-left: 7px;">
                                    Đơn hàng
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./admin.php?action=quanlybanner&query=them" class="nav-link">
                                <i class="fa-solid fa-rectangle-ad" style="margin-left: 2px;"></i>
                                <p style="margin-left: 4px;">
                                    Banner
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./admin.php?action=quanlydanhmucsanpham&query=them" class="nav-link">
                                <i class="fa-solid fa-list" style="margin-left: 3px;"></i>
                                <p style="margin-left: 5px;">
                                    Danh mục sản phẩm
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./admin.php?action=quanlysanpham&query=lietke" class="nav-link">
                                <i class="fa-solid fa-mobile-screen" style="margin-left: 4px;"></i>
                                <p style="margin-left: 8px;">
                                    Sản phẩm
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./admin.php?action=quanlydanhmucbaiviet&query=them" class="nav-link">
                                <i class="fa-solid fa-list" style="margin-left: 3px;"></i>
                                <p style="margin-left: 4px;">
                                    Danh mục bài viết
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./admin.php?action=quanlybaiviet&query=lietke" class="nav-link">
                                <i class="fa-regular fa-newspaper" style="margin-left: 4px;"></i>
                                <p style="margin-left: 3px;">
                                    Bài viết
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./admin.php?action=quanlyfooter&query=lietke" class="nav-link">
                                <i class="fa-solid fa-phone" style="margin-left: 4px;"></i>
                                <p style="margin-left: 4px;">
                                    Footer
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../dangxuat.php">
                                <button type="submit" class="btn btn-primary nav-link bg-transparent">
                                    <i class="nav-icon bi bi-box-arrow-left"></i>
                                    <p>Đăng xuất
                                    </p>
                                </button>
                            </a>
                        </li>
                    </ul>
                </nav>

            </div>

        </aside>
        <div class="content-wrapper">
            <div class="content-header"></div>
            <section class="content">
                <div class="container-fluid">
                <div class="container mt-5">
        <div class="card">
            <div style="text-align: center" class="card-header bg-primary text-white">
                <h4>Thông tin Admin</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Họ và Tên</th>
                            <td><?php echo htmlspecialchars($admin['name']); ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo htmlspecialchars($admin['email']); ?></td>
                        </tr>
                        <tr>
                            <th>Số Điện Thoại</th>
                            <td><?php echo htmlspecialchars($admin['phone']); ?></td>
                        </tr>
                        <tr>
                            <th>Địa Chỉ</th>
                            <td><?php echo htmlspecialchars($admin['address']); ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="d-flex justify-content-center" style="margin-top: 20px">
                    <a href="capnhatthongtinadmin.php" class="btn btn-warning me-2">Sửa Thông Tin</a>
                    <a href="doimatkhauadmin.php" class="btn btn-danger">Đổi Mật Khẩu</a>
                </div>
            </div>
        </div>
    </div>

                </div>
            </section>
        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>

    <script>
        CKEDITOR.replace('noidung');
    </script>


    <script src="admin.js"></script>
</body>
</html>