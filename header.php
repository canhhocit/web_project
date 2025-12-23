<?php
$anhdaidien = "";
$hoten = "User";
$sdt = "";
$email = "";
$cccd = "";
$idthongtin = 0;

if (isset($_SESSION['idtaikhoan'])) {
    require_once __DIR__ . "/Model/DAO/taikhoanDAO.php";
    global $conn;
    $dao = new taikhoanDAO($conn);
    $thongtin = $dao->getThongTinTaiKhoanbyID($_SESSION['idtaikhoan']);

    if ($thongtin && $thongtin !== null) {
        $idthongtin = $thongtin->get_idthongtin();
        $anhdaidien = $thongtin->get_anhdaidien();
        $hoten = $thongtin->get_hoten();
        $sdt = $thongtin->get_sdt();
        $email = $thongtin->get_email();
        $cccd = $thongtin->get_cccd();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thuê Xe Giá Rẻ Nhất Việt Nam</title>

    <link rel="stylesheet" href="View/CSS/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="View/CSS/modal.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?controller=home"><i class="fa-solid fa-car-side"></i>Thuê Xe
                Đi Bro</a>
            <button class="navbar-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainMenu">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=home">Trang Chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=about">Về Chúng Tôi</a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="btn btn-dark text-bg-light fw-bold me-2" href="/web_project/index.php?controller=vehicle&action=checkLogin">
                            <i class="fa-solid fa-plus">
                            </i>
                            Đăng bài thuê xe
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <?php if (isset($_SESSION['idtaikhoan']) && $anhdaidien): ?>
                                <img src="View/image/<?php echo $anhdaidien ?>" alt="Avatar"
                                    style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover; margin-right: 3px;">
                                <?php echo $hoten ?>
                            <?php else: ?>
                                <i class="fa-solid fa-user fa-user-circle"></i>
                                <?php echo isset($_SESSION['idtaikhoan']) ? $hoten : 'Tài Khoản' ?>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <?php if (!isset($_SESSION['idtaikhoan'])): ?>
                                <li><a class="dropdown-item" href="View/taikhoan/login.php"><i class="fa-solid fa-right-to-bracket me-2"></i> Đăng Nhập</a></li>
                                <li><a class="dropdown-item" href="View/taikhoan/register.php"><i class="fa-solid fa-user-plus me-2"></i> Đăng Ký</a></li>
                            <?php else: ?>
                                <li>
                                    <a class="dropdown-item" href="index.php?controller=taikhoan&action=personal">
                                        <i class="fa-solid fa-user me-2"></i> Thông tin cá nhân
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="index.php?controller=taikhoan&action=personal&selection=cars">
                                        <i class="fa-solid fa-car me-2"></i> Quản lý xe của tôi
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="index.php?controller=thanhtoan&action=index">
                                        <i class="fa-solid fa-file-invoice-dollar me-2"></i> Hóa đơn / Lịch sử
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="index.php?controller=taikhoan&action=changePassword">
                                        <i class="fa-solid fa-key me-2"></i> Đổi mật khẩu
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="index.php?controller=taikhoan&action=logout">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i> Đăng xuất
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>
            </div>
        </div>
    </nav>