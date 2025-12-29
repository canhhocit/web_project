<link rel="stylesheet" href="/web_project/View/CSS/vehicle/editVehicle.css">

<div class="edit-vehicle-box">
    <div class="container">
        <div class="page-header" style="margin-bottom: 20px;">
            <h3>Sửa thông tin xe</h3>
            <p>Cập nhật thông tin xe của bạn</p>
        </div>

        <form action="/web_project/index.php?controller=vehicle&action=editV&id=<?php echo $xe->get_idxe(); ?>"
            method="post" enctype="multipart/form-data">
            <input type="hidden" name="idxe" value="<?php echo $xe->get_idxe(); ?>">
            
            <label>Tên xe<span style="color: red">*</span></label>
            <input type="text" name="tenxe" value="<?php echo $xe->get_tenxe(); ?>" required>

            <label>Hãng xe<span style="color: red">*</span></label>
            <input type="text" name="hangxe" value="<?php echo $xe->get_hangxe(); ?>" required>

            <label>Loại xe<span style="color: red">*</span></label>
            <div class="radio-group">
                <label><input type="radio" name="loaixe" value="Xe đạp"
                        <?php echo $xe->get_loaixe() === 'Xe đạp' ? 'checked' : ''; ?> required> Xe đạp</label>
                <label><input type="radio" name="loaixe" value="Xe máy điện"
                        <?php echo $xe->get_loaixe() === 'Xe máy điện' ? 'checked' : ''; ?>> Xe máy điện</label>
            </div>

            <label>Giá thuê (VNĐ / ngày)<span style="color: red">*</span></label>
            <input type="number" name="giathue" value="<?php echo $xe->get_giathue(); ?>" min="0" required>

            <label>Mô tả</label>
            <textarea name="mota" rows="4"><?php echo $xe->get_mota(); ?></textarea>

            <label>Ảnh hiện tại</label>
            <div class="current-images" style="display: flex; gap: 10px; flex-wrap: wrap; margin: 10px 0;">
                <?php foreach ($anhxe as $img): ?>
                    <img src="/web_project/View/image/<?php echo $img->get_duongdan(); ?>"
                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 2px solid #ddd;">
                <?php endforeach; ?>
            </div>

            <label>Thay đổi ảnh (nếu có)<span style="color: red">*(Chọn ảnh mới sẽ xóa ảnh cũ)</span></label>
            <input type="file" id="anhxe" name="anhxe[]" multiple accept="image/*">

            <div id="preview"></div>

            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <button type="submit" style="flex: 1;">Cập nhật</button>
                <a href="/web_project/index.php?controller=taikhoan&action=personal&selection=myvehicle"
                    style="flex: 1; text-align: center; padding: 10px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px;">Hủy</a>
            </div>
        </form>
    </div>
</div>

<script src="View/JS/vehicle/addVehicle.js"></script>