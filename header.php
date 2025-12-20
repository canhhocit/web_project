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
    <title>Trang Chủ</title>
    <link rel="stylesheet" href="./web_project/View/CSS/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="View/CSS/modal.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?controller=home"><i class="fa-solid fa-car-side"></i>Thuê Xe
                Đi Chốn</a>
            <button class="navbar-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainMenu">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=home">Trang Chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Về Chúng Tôi</a>
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
                                <!-- Chưa đăng nhập -->
                                <li><a class="dropdown-item" href="View/taikhoan/login.php">Đăng Nhập</a></li>
                                <li><a class="dropdown-item" href="View/taikhoan/register.php">Đăng Ký</a></li>
                            <?php else: ?>
                                <!-- Đã đăng nhập -->
                                <li>
                                    <h6 class="dropdown-header">Cá nhân hóa</h6>
                                </li>
                                <li>
                                    <a class="dropdown-item open-modal-btn" href="#" data-modal="modal-info">
                                        <i class="fa-solid fa-gear"></i> Thông tin cá nhân
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item open-modal-btn" href="#" data-modal="modal-changepass">
                                        <i class="fa-solid fa-key"></i> Đổi mật khẩu
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="index.php?controller=car&action=mycars">
                                        <i class="fa-solid fa-car"></i> Xe Của Tôi
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fa-solid fa-list-check"></i> Đơn khách thuê xe tôi
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fa-solid fa-clock-rotate-left"></i> Lịch sử tôi đi thuê
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#"
                                        onclick="if(confirm('Bạn có chắc chắn muốn xóa tài khoản? Hành động này cần cân nhắc!')) { window.location='/web_project/index.php?controller=taikhoan&action=deleteAccount'; }">
                                        <i class="fa-solid fa-trash"></i> Xóa tài khoản
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger"
                                        href="/web_project/index.php?controller=taikhoan&action=logout">
                                        Đăng xuất
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
    <!-- MODAL  của CẢnh -- cá nhân -->
    <div class="modal" id="modal-info">
        <form action="/web_project/index.php?controller=taikhoan&action=updateinforUser" method="post"
            enctype="multipart/form-data">
            <input type="hidden" name="idthongtin" value="<?php echo $idthongtin ?>">
            <div class="modal_inner">
                <div class="modal_header">
                    <h3>Thông tin tài khoản</h3>
                    <span class="close">x</span>
                </div>

                <div class="modal_body">
                    <div class="input_infor">
                        <label for="hoten">Họ tên<span style="color: red">*</span></label>
                        <input type="text" name="hoten" placeholder="Nhập họ tên"
                            value="<?php echo $hoten != 'Tài Khoản' ? $hoten : '' ?>" />

                        <label for="sdt">SĐT<span style="color: red">*</span></label>
                        <input type="text" name="sdt" placeholder="Nhập SDT" value="<?php echo $sdt ?>" />

                        <label for="email">Email<span style="color: red">*</span></label>
                        <input type="text" name="email" placeholder="Nhập Email" value="<?php echo $email ?>" />

                        <label for="cccd">CCCD<span style="color: red">*</span></label>
                        <input type="text" name="cccd" placeholder="Nhập CCCD" value="<?php echo $cccd ?>" />

                        <label for="anhdaidien">Chọn ảnh upload</label>
                        <input type="file" name="anhdaidien" />
                        <?php if ($anhdaidien): ?>
                            <small>Ảnh hiện tại: <img src="View/image/<?php echo $anhdaidien ?>"
                                    style="width: 50px; height: 50px; border-radius: 50%;"></small>
                        <?php endif; ?>
                    </div>
                    <div class="sub_modal">
                        <i>Các trường dữ liệu có dấu * là bắt buộc</i>
                    </div>
                </div>

                <div class="modal_footer">
                    <button class="acp-btn" id="btn_acp" type="submit">Xác nhận</button>
                    <button class="close-btn" type="button">Đóng</button>
                </div>
            </div>
        </form>
    </div>

    <!-- MODAL Đổi mật khẩu -->
    <div class="modal" id="modal-changepass">
        <form action="/web_project/index.php?controller=taikhoan&action=changePassword" method="post">
            <div class="modal_inner">
                <div class="modal_header">
                    <h3>Đổi mật khẩu</h3>
                    <span class="close">x</span>
                </div>

                <div class="modal_body">
                    <div class="input_infor">
                        <label for="oldpass">Mật khẩu cũ<span style="color: red">*</span></label>
                        <input type="password" name="oldpass" id="oldpass" placeholder="Nhập mật khẩu cũ" required />

                        <label for="newpass">Mật khẩu mới<span style="color: red">*</span></label>
                        <input type="password" name="newpass" id="newpass" placeholder="Nhập mật khẩu mới" required />

                        <label for="confnewpass">Xác nhận mật khẩu mới<span style="color: red">*</span></label>
                        <input type="password" name="confnewpass" id="confnewpass" placeholder="Nhập lại mật khẩu mới" required />
                    </div>
                </div>

                <div class="modal_footer">
                    <button class="acp-btn" type="submit">Xác nhận</button>
                    <button class="close-btn" type="button">Đóng</button>
                </div>
            </div>
        </form>
    </div>