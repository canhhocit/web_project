<?php require_once __DIR__ . "/../../../config.php"; ?>

<link rel="stylesheet" href="/web_project/View/CSS/taikhoan/personal/changepass.css">

<div class="page-header">
    <h3>Đổi mật khẩu</h3>
</div>

<div class="password-container">
    <div class="password-form">
        <form action="/web_project/index.php?controller=taikhoan&action=changePassword" method="post">
            <div class="field">
                <label for="oldpass">Mật khẩu cũ <span class="required">*</span></label>
                <input type="password" name="oldpass" id="oldpass" placeholder="Nhập mật khẩu cũ" required>
            </div>

            <div class="field">
                <label for="newpass">Mật khẩu mới <span class="required">*</span></label>
                <input type="password" name="newpass" id="newpass" placeholder="Nhập mật khẩu mới" required>
            </div>

            <div class="field">
                <label for="confnewpass">Xác nhận mật khẩu mới <span class="required">*</span></label>
                <input type="password" name="confnewpass" id="confnewpass" placeholder="Nhập lại mật khẩu mới" required>
            </div>

            <div class="notice">
                <i class="fa-solid fa-info-circle"></i>
                <span><strong>Lưu ý:</strong> Mật khẩu mới phải khác mật khẩu cũ</span>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-key"></i> Đổi mật khẩu
                </button>
            </div>
        </form>
    </div>

    <!-- HD -->
    <div class="security-tips">
        <div class="tips-box">
            <h4><i class="fa-solid fa-shield-alt"></i> Bảo mật tài khoản</h4>
            <ul>
                <li>Sử dụng mật khẩu mạnh (khuyên dùng 8 ký tự)</li>
                <li>Kết hợp chữ hoa, chữ thường và số</li>
                <li>Sử dụng kết hợp với ký tự đặc biệt</li>
                <li>Thay đổi mật khẩu định kỳ</li>
                <li>Không chia sẻ mật khẩu với người khác</li>
            </ul>
        </div>
    </div>
</div>