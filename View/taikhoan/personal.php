<?php
require_once __DIR__ . "/../../config.php";


require_once __DIR__ . "/../../Model/Database/dbconnect.php";
require_once __DIR__ . "/../../Model/DAO/taikhoanDAO.php";

global $conn;
$dao = new taikhoanDAO($conn);
$thongtin = $dao->getThongTinTaiKhoanbyID($_SESSION['idtaikhoan']);

$section = $_GET['section'] ?? 'info';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cá nhân hóa</title>
    <link rel="stylesheet" href="/web_project/View/CSS/personal/account-personal.css">
</head>
<body>

<div class="personal-container">
    <div class="sidebar">
        <nav class="menu">
            <a href="?controller=taikhoan&action=personal&section=info" 
               class="menu-item <?php echo $section === 'info' ? 'active' : ''; ?>">
                <i class="fa-solid fa-id-card"></i>
                Thông tin tài khoản
            </a>
            <a href="?controller=taikhoan&action=personal&section=password" 
               class="menu-item <?php echo $section === 'password' ? 'active' : ''; ?>">
                <i class="fa-solid fa-key"></i>
                Đổi mật khẩu
            </a>
            <a href="?controller=taikhoan&action=personal&section=cars" 
               class="menu-item <?php echo $section === 'cars' ? 'active' : ''; ?>">
                <i class="fa-solid fa-car"></i>
                Xe của tôi
            </a>
            <a href="?controller=taikhoan&action=personal&section=favorite" 
               class="menu-item <?php echo $section === 'favorite' ? 'active' : ''; ?>">
                <i class="fa-solid fa-heart"></i>
                Xe yêu thích
            </a>
        </nav>
    </div>

    <!-- Content -->
    <div class="content">
        <?php
        switch ($section) {
            case 'info':
                include __DIR__ . "/personal/info.php";
                break;
            case 'password':
                include __DIR__ . "/personal/changepass.php";
                break;
            case 'cars':
                include __DIR__ . "/personal/mycars.php";
                break;
            case 'favorite':
                include __DIR__ . "/personal/favoritecars.php";
                break;
            default:
                include __DIR__ . "/personal/info.php";
        }
        ?>
    </div>
</div>

</body>
</html>