<?php

$selection = $_GET['selection'] ?? 'info';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cá nhân hóa</title>
    <link rel="stylesheet" href="/web_project/View/CSS/taikhoan/personal.css">
</head>

<body>

    <div class="personal-container">
        <!--  Menu  -->
        <div class="sidebar">
            <nav class="menu">
                <a href="?controller=taikhoan&action=personal&selection=info"
                    class="menu-item <?php echo $selection === 'info' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-id-card"></i>
                    <span>Thông tin tài khoản</span>
                </a>
                <a href="?controller=taikhoan&action=personal&selection=password"
                    class="menu-item <?php echo $selection === 'password' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-key"></i>
                    <span>Đổi mật khẩu</span>
                </a>
                <a href="/web_project/index.php?controller=taikhoan&action=personal&selection=myvehicle"
                    class="menu-item <?php echo $selection === 'cars' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-car"></i>
                    <span>Xe của tôi</span>
                </a>
                <a href="?controller=taikhoan&action=personal&selection=favorite"
                    class="menu-item <?php echo $selection === 'favorite' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-heart"></i>
                    <span>Xe yêu thích</span>
                </a>
            </nav>
        </div>

        <!-- Content -->
        <div class="content">
            <?php
            switch ($selection) {
                case 'info':
                    //nhúng bên controller ->personal-> đến đây
                    include __DIR__ . "/personal/info.php";
                    break;
                case 'password':
                    include __DIR__ . "/personal/changepass.php";
                    break;
                case 'myvehicle':
                    include __DIR__ . "/personal/myvehicle.php";
                    break;
                case 'favorite':
                    include __DIR__ . "/personal/favoriteVehilce.php";
                    break;
                default:
                    include __DIR__ . "/personal/info.php";
            }
            ?>
        </div>
    </div>

</body>

</html>