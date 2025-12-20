<?php require_once __DIR__ . "/../../../config.php"; ?>

<h3 class="mb-4">Thông tin tài khoản</h3>
<form action="/web_project/index.php?controller=taikhoan&action=updateinforUser" method="post" enctype="multipart/form-data">
    <input type="hidden" name="idthongtin" value="<?php echo $thongtin ? $thongtin->get_idthongtin() : 0; ?>">

    <div class="row">
        <div class="col-md-8">
            <div class="mb-3">
                <label for="hoten" class="form-label">Họ tên <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="hoten" id="hoten"
                    value="<?php echo $thongtin ? $thongtin->get_hoten() : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="sdt" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="sdt" id="sdt"
                    value="<?php echo $thongtin ? $thongtin->get_sdt() : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" name="email" id="email"
                    value="<?php echo $thongtin ? $thongtin->get_email() : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="cccd" class="form-label">CCCD <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="cccd" id="cccd"
                    value="<?php echo $thongtin ? $thongtin->get_cccd() : ''; ?>" required>
            </div>
        </div>

        <div class="col-md-4">
            <div class="text-center">
                <label class="form-label">Ảnh đại diện</label>
                <div class="mb-3">
                    <?php if ($thongtin && $thongtin->get_anhdaidien()): ?>
                        <img src="View/image/<?php echo $thongtin->get_anhdaidien(); ?>"
                            alt="Avatar" class="img-thumbnail"
                            style="width: 200px; height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                            style="width: 200px; height: 200px; margin: 0 auto;">
                            <i class="fa-solid fa-user fa-5x"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <input type="file" class="form-control" name="anhdaidien" accept="image/*">
                <small class="text-muted">Chọn ảnh mới để thay đổi</small>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-save"></i> Lưu thay đổi
        </button>
        <a href="/web_project/index.php" class="btn btn-secondary">
            <i class="fa-solid fa-times"></i> Hủy
        </a>
    </div>
</form>