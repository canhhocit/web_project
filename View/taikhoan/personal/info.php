<?php require_once __DIR__ . "/../../../config.php"; ?>

<link rel="stylesheet" href="/web_project/View/CSS/taikhoan/personal/info.css">

<div class="page-header">
    <h3>Thông tin tài khoản</h3>
    <p>Cập nhật thông tin cá nhân của bạn</p>
</div>

<form action="/web_project/index.php?controller=taikhoan&action=updateinforUser" method="post" enctype="multipart/form-data">
    <input type="hidden" name="idthongtin" value="<?php echo $thongtin ? $thongtin->get_idthongtin() : 0; ?>">

    <div class="form-grid">
        <div class="form-fields">
            <div class="field">
                <label for="hoten">Họ tên <span class="required">*</span></label>
                <input type="text" name="hoten" id="hoten" placeholder="Nguyễn Văn A"
                    value="<?php echo $thongtin ? $thongtin->get_hoten() : ''; ?>" required>
            </div>

            <div class="field">
                <label for="sdt">Số điện thoại <span class="required">*</span></label>
                <input type="tel" name="sdt" id="sdt" placeholder="0123456789"
                    value="<?php echo $thongtin ? $thongtin->get_sdt() : ''; ?>" required>
            </div>

            <div class="field">
                <label for="email">Email <span class="required">*</span></label>
                <input type="email" name="email" id="email" placeholder="email@example.com"
                    value="<?php echo $thongtin ? $thongtin->get_email() : ''; ?>" required>
            </div>

            <div class="field">
                <label for="cccd">Số CCCD <span class="required">*</span></label>
                <input type="text" name="cccd" id="cccd" placeholder="001234567890"
                    value="<?php echo $thongtin ? $thongtin->get_cccd() : ''; ?>" required>
            </div>
        </div>

        <!-- Right side - Avatar -->
        <div class="avatar-section">
            <label>Ảnh đại diện</label>
            <div class="avatar-box">
                <?php if ($thongtin && $thongtin->get_anhdaidien()): ?>
                    <img src="View/image/<?php echo $thongtin->get_anhdaidien(); ?>" 
                         alt="Avatar" id="avatar-preview">
                <?php else: ?>
                    <div class="avatar-placeholder">
                    </div>
                <?php endif; ?>
            </div>
            <input type="file" name="anhdaidien" id="avatar-input" accept="image/*">
        </div>
    </div>

    <div class="divider"></div>

    <div class="form-actions">
        <button type="submit" class="btn-save">
            <i class="fa-solid fa-save"></i> Lưu thay đổi
        </button>
        <a href="/web_project/index.php" class="btn-cancel">
            <i class="fa-solid fa-times"></i> Hủy
        </a>
    </div>
</form>

<script src="/web_project/View/JS/taikhoan/personal/info.js"></script>