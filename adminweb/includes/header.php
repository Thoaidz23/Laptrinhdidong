<nav>
    <div class="container">
    <ul>
        <li><a href="home.php"><img style="width: 220px; height: 50px;" src="assets/img/logo1.jpg" alt=""></a></li>
        <li>
            <input id="search-input" placeholder="Bạn tìm gì..." type="text" id="search-query">
            <a href="resultsearch.php?query=" id="search-link">
                <i class="fa-solid fa-magnifying-glass" id="search-icon"></i>
            </a>
        </li>
        <li>
            <?php
                if(isset($_SESSION["id_user"])) {
            ?>
                <a href="purchasehistory.php">
                    <button>
                        <i class="fa-solid fa-receipt"></i>LỊCH SỬ ĐƠN HÀNG
                    </button>
                </a>
            <?php
            }
            else {
            ?>
                <a href="dangnhap.php">
                    <button>
                        <i class="fa-solid fa-receipt"></i>LỊCH SỬ ĐƠN HÀNG
                    </button>
                </a>
            <?php
            }
            ?>
        </li>
        <li>
            <?php
                if(isset($_SESSION["id_user"])) {
            ?>
                <a href="giohang.php">
                    <button>
                        <i class="fa-solid fa-cart-shopping"></i>GIỎ HÀNG
                    </button>
                </a>
            <?php
            }
            else {
            ?>
                <a href="dangnhap.php">
                    <button>
                        <i class="fa-solid fa-cart-shopping"></i>GIỎ HÀNG
                    </button>
                </a>
            <?php
            }
            ?>
        </li>
        <li>
            <?php
                if(isset($_SESSION["name"])) {
                    $hoten = explode(" ", $_SESSION["name"]);
                    $ten = end($hoten);
            ?>
                <a href="taikhoan.php">
                    <button><i class="fa-solid fa-user"></i>
                        <?php echo $ten ?>
                    </button>
                </a>
            <?php
                }
                else {
            ?>
            <a href="dangnhap.php">
                <button><i class="fa-solid fa-user"></i>
                    ĐĂNG NHẬP
                </button>
            </a>
            <?php
                }
            ?>
        </li>

    </ul>
    </div>
</nav>
<script>
    // Lắng nghe sự kiện nhấn phím Enter
    document.getElementById('search-input').addEventListener('keydown', function(event) {
        if (event.key === "Enter") {
            var query = document.getElementById('search-input').value;
            var targetUrl = "resultsearch.php?query=" + encodeURIComponent(query);
            window.location.href = targetUrl;
        }
    });

    // Tim kiem
    document.getElementById('search-link').addEventListener('click', function(event) {
    var query = document.getElementById('search-input').value;
    if (query) {
        this.href = 'resultsearch.php?query=' + encodeURIComponent(query);
    } else {
        event.preventDefault();
    }
    });


</script>