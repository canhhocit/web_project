<?php
define('ACCESSED_FROM_CONTROLLER', true);
require_once __DIR__ . "/../../config.php"; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bạn muốn cho thuê xe?</title>
    <link rel="stylesheet" href="../CSS/addVehicle.css">
</head>
<body>

<div class="container">
    <h2>Thông tin xe</h2>

    <form action="/web_project/index.php?controller=vehicle&action=add" method="post" enctype="multipart/form-data">

        <label>Tên xe<span style="color: red">*</span></label>
        <input type="text" name="tenxe" required>

        <label>Hãng xe<span style="color: red">*</span></label>
        <input type="text" name="hangxe" required>

        <label>Loại xe<span style="color: red">*</span></label>
        <div class="radio-group">
            <label><input type="radio" name="loaixe" value="Xe đạp" required> Xe đạp</label>
            <label><input type="radio" name="loaixe" value="Xe máy điện"> Xe máy điện</label>
        </div>

        <label>Giá thuê (VNĐ / ngày)<span style="color: red">*</span></label>
        <input type="number" name="giathue" min="0" required>

        <label>Mô tả</label>
        <textarea name="mota" rows="4"></textarea>

        <label>Ảnh mô tả<span style="color: red">*(1 hoặc nhiều)</span></label>
        <input type="file" id="anhxe" name="anhxe[]" multiple accept="image/*">

        <!-- Preview  -->
        <div id="preview"></div>

        <button type="submit">Đăng</button>
    </form>
</div>

<script src="../JS/addVehicle.js"></script>
</body>
</html>
