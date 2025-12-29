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

$is_chuxe = false; 
if (isset($_SESSION['idtaikhoan'])) {
    require_once __DIR__ . "/Model/DAO/taikhoanDAO.php";
    global $conn;
    $dao = new taikhoanDAO($conn);
    
    $sql_role = "SELECT lachuxe FROM taikhoan WHERE idtaikhoan = " . $_SESSION['idtaikhoan'];
    $res_role = mysqli_query($conn, $sql_role);
    if ($row_role = mysqli_fetch_assoc($res_role)) {
        if ($row_role['lachuxe'] == 1) {
            $is_chuxe = true;
        }
    }
}


$current_controller = $_GET['controller'] ?? 'home';
$is_finance_active = ($current_controller === 'thanhtoan' || $current_controller === 'thongke');

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
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?controller=home"><i class="fa-solid fa-car-side me-2"></i>Thuê Xe</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
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
                    <li class="nav-item d-flex align-items-center">
                        <a class="btn btn-light fw-bold me-2" href="/web_project/index.php?controller=vehicle&action=checkLogin">
                            <i class="fa-solid fa-plus me-1"></i>
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
                                <i class="fa-solid fa-circle-user me-1"></i>
                                <?php echo isset($_SESSION['idtaikhoan']) ? $hoten : 'Tài Khoản' ?>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <?php if (!isset($_SESSION['idtaikhoan'])): ?>
                                <li><a class="dropdown-item" href="View/taikhoan/login.php"><i class="fa-solid fa-right-to-bracket me-2"></i> Đăng Nhập</a></li>
                                <li><a class="dropdown-item" href="View/taikhoan/register.php"><i class="fa-solid fa-user-plus me-2"></i> Đăng Ký</a></li>
                            <?php else: ?>
                                <!-- Thông tin cá nhân -->
                                <li>
                                    <a class="dropdown-item" href="index.php?controller=taikhoan&action=personal">
                                        <i class="fa-solid fa-user me-2"></i> Cá nhân hóa
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="index.php?controller=taikhoan&action=personal&selection=myvehicle">
                                        <i class="fa-solid fa-car me-2"></i> Quản lý xe của tôi
                                    </a>
                                </li>
                                
                                <li><hr class="dropdown-divider"></li>

                                <!-- Tài chính - Submenu -->
                                <li>
                                    <a class="dropdown-item d-flex justify-content-between align-items-center" 
                                       data-bs-toggle="collapse" 
                                       href="#submenuTaiChinh" 
                                       role="button" 
                                       aria-expanded="<?php echo $is_finance_active ? 'true' : 'false'; ?>">
                                        <span><i class="fa-solid fa-wallet me-2"></i> Tài chính</span>
                                        <i class="fa-solid fa-chevron-down fa-xs"></i>
                                    </a>

                                    <div class="collapse <?php echo $is_finance_active ? 'show' : ''; ?>" id="submenuTaiChinh">
                                        <ul class="list-unstyled mb-0">
                                            <li>
                                                <a class="dropdown-item ps-5" href="index.php?controller=thanhtoan&action=index">
                                                    <i class="fa-solid fa-file-invoice-dollar me-2"></i> Hóa đơn / Lịch sử
                                                </a>
                                            </li>
                                          
                                            <?php if ($is_chuxe): ?>
                                                <li>
                                                    <a class="dropdown-item ps-5" href="index.php?controller=thongke&action=index"> 
                                                    <i class="fa-solid fa-chart-line me-2"></i> Thống kê 
                                                    </a>
                                                </li>
                                                <?php endif; ?>

                                            <li>
                                                <a class="dropdown-item ps-5" href="index.php?controller=lichsutt&action=index">
                                                    <i class="fa-solid fa-clock-rotate-left me-2"></i> Lịch sử giao dịch
                                                </a>
                                             </li>

                                        </ul>
                                    </div>
                                </li>

                                <li><hr class="dropdown-divider"></li>

                                <!-- Đăng xuất -->
                                <li>
                                    <a class="dropdown-item text-danger" href="index.php?controller=taikhoan&action=logout">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i> Đăng xuất
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnTaiChinh = document.querySelector('[href="#submenuTaiChinh"]');
        const submenu = document.getElementById('submenuTaiChinh');

        if (btnTaiChinh && submenu) {
            btnTaiChinh.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); 
                
                const isShown = submenu.classList.contains('show');
                if (isShown) {
                    submenu.classList.remove('show');
                    this.setAttribute('aria-expanded', 'false');
                } else {
                    submenu.classList.add('show');
                    this.setAttribute('aria-expanded', 'true');
                }
            });

            submenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    });
    </script>
