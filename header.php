<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ</title>
    <link rel="stylesheet" href="./web_project/View/CSS/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="View/CSS/modal.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?controller=home"><i class="fa-solid fa-car-side"></i>Thuê Xe Đi Chốn</a>
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
                        <a class="btn btn-warning text-dark fw-bold me-2" href="View/xe/addVehicle.php">
                            <i class="fa-solid fa-plus">
                            </i>
                            Đăng Xe Lên Diễn Đàn Ngay
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-user fa-user-circle"></i>Tài Khoản</a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="View/taikhoan/login.php">Đăng Nhập</a></li>
                            <li><a class="dropdown-item" href="View/taikhoan/register.php">Đăng Ký</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <h6 class="dropdown-header">Quản Lý(Login rồi mới cho vào đây)</h6>
                            </li>
                            <li>
                                <a class="dropdown-item open-modal-btn">
                                    <i class="fa-solid fa-gear"></i> Thông tin cá nhân
                                </a>
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

                            <li><a class="dropdown-item text-danger" href="/web_project/index.php?controller=taikhoan&action=logout">Đăng xuất</a></li>
                        </ul>
                    </li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- MODAL  của CẢnh-->
     <div class="modal">
      <div class="modal_inner">
        <div class="modal_header">
          <h3>Thông tin tài khoản</h3>
          <span class="close">x</span>
        </div>
        
        <div class="modal_body">
          <div class="input_infor">
            <label for="hoten">Họ tên<span style="color: red">*</span></label>
            <input type="text" name="hoten" placeholder="Nhập họ tên" />
            <label for="sdt">SĐT<span style="color: red">*</span></label>
            <input type="text" name="sdt" placeholder="Nhập SDT" />
            <label for="email">Email<span style="color: red">*</span></label>
            <input type="text" name="email" placeholder="Nhập Email" />
            <label for="cccd">CCCD<span style="color: red">*</span></label>
            <input type="text" name="cccd" placeholder="Nhập CCCD" />
            <label for="anhdaidien">Chọn ảnh upload</label>
            <input type="file" name="anhdaidien" />
          </div>
          <div class="sub_modal">
            <i>Các trường dữ liệu có dấu * là bắt buộc</i>
          </div>
        </div>

        <div class="modal_footer">
          <button class="acp-btn" id="btn_acp">Xác nhận</button>
          <button class="close-btn">Đóng</button>
        </div>
      </div>
    </div>