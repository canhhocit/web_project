<?php require_once __DIR__ . "/../../../config.php"; ?>

<h3 class="mb-4">Đổi mật khẩu</h3>

<div class="row">
    <div class="col-md-6">
        <form action="/web_project/index.php?controller=taikhoan&action=changePassword" method="post">
            <div class="mb-3">
                <label for="oldpass" class="form-label">Mật khẩu cũ <span class="text-danger">*</span></label>
                <input type="password" class="form-control" name="oldpass" id="oldpass" required>
            </div>

            <div class="mb-3">
                <label for="newpass" class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                <input type="password" class="form-control" name="newpass" id="newpass" required>
            </div>

            <div class="mb-3">
                <label for="confnewpass" class="form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                <input type="password" class="form-control" name="confnewpass" id="confnewpass" required>
            </div>

            <div class="alert alert-info">
                <i class="fa-solid fa-info-circle"></i> 
                <strong>Lưu ý:</strong> Mật khẩu mới phải khác mật khẩu cũ
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-key"></i> Đổi mật khẩu
                </button>
                <button type="reset" class="btn btn-secondary">
                    <i class="fa-solid fa-redo"></i> Làm mới
                </button>
            </div>
        </form>
    </div>
    
    <div class="col-md-6">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title"><i class="fa-solid fa-shield-alt"></i> Bảo mật tài khoản</h5>
                <ul class="mb-0">
                    <li>Sử dụng mật khẩu mạnh (ít nhất 8 ký tự)</li>
                    <li>Kết hợp chữ hoa, chữ thường và số</li>
                    <li>Không sử dụng thông tin cá nhân dễ đoán</li>
                    <li>Thay đổi mật khẩu định kỳ</li>
                    <li>Không chia sẻ mật khẩu với người khác</li>
                </ul>
            </div>
        </div>
    </div>
</div>