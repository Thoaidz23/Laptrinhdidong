<section class="menu-bar">
    <div class="container">
        <div class="menu-bar-content">
            <ul>
                <?php
                include("./admin/connect.php");

                $query = "SELECT id_dmsp, ten_dmsp FROM tbl_danhmucsanpham ORDER BY thutu ASC";
                $result = mysqli_query($mysqli, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    $icons = [
                        '<i class="fas fa-mobile-alt"></i>',
                        '<i class="fa-solid fa-desktop"></i>',
                        '<i class="fas fa-laptop"></i>',
                        '<i class="fa-solid fa-tablet-screen-button"></i>',
                        '<i class="fas fa-headphones-alt"></i>',
                        '<i class="fa-solid fa-clock"></i>',
                        '<i class="fa-solid fa-tv"></i>'
                    ];
                    $index = 0;

                    while ($row = mysqli_fetch_assoc($result)) {
                        $id_dmsp = $row['id_dmsp'];
                        $ten_dmsp = $row['ten_dmsp'];

                        $icon = ($index < count($icons)) ? $icons[$index] : '';
                        $index++;

                        echo '
                        <li>
                            <a href="danhmucsanpham.php?id_dmsp=' . $id_dmsp . '">
                                ' . $icon . ' ' . htmlspecialchars($ten_dmsp) . '
                            </a>
                        </li>';
                    }
                } else {
                    echo '<li>Không có danh mục nào</li>';
                }
                ?>
            </ul>
        </div>
    </div>
</section>
