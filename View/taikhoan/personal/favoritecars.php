<?php require_once __DIR__ . "/../../../config.php"; ?>

<link rel="stylesheet" href="/web_project/View/CSS/personal/favorite-cars.css">

<div class="page-title">
    <h2>Xe yêu thích</h2>
    <p>Danh sách xe bạn đã lưu để tham khảo</p>
</div>

<div class="info-box">
    <i class="fa-solid fa-heart"></i>
    <span>Những chiếc xe bạn yêu thích sẽ được lưu tại đây</span>
</div>

<!-- Empty state -->
<div class="empty">
    <i class="fa-solid fa-heart-crack"></i>
    <h3>Chưa có xe yêu thích</h3>
    <p>Hãy khám phá và lưu những chiếc xe bạn thích!</p>
    <a href="/web_project/index.php" class="btn-explore">
        <i class="fa-solid fa-search"></i> Khám phá xe ngay
    </a>
</div>

<!-- Danh sách yêu thích - uncomment khi có dữ liệu -->
<!--
<div class="favorites-header">
    <span class="favorites-count">Đã lưu: <strong>3</strong> xe</span>
    <button class="btn-clear">
        <i class="fa-solid fa-broom"></i> Xóa tất cả
    </button>
</div>

<div class="favorites-list">
    <div class="favorite-item">
        <span class="heart-icon"><i class="fa-solid fa-heart"></i></span>
        <img src="/web_project/View/image/car1.jpg" alt="Car">
        <div class="favorite-info">
            <h4>Honda City 2023</h4>
            <p><i class="fa-solid fa-copyright"></i> Honda</p>
            <p><i class="fa-solid fa-car"></i> Sedan</p>
            <p><i class="fa-solid fa-location-dot"></i> Hà Nội</p>
            <p class="favorite-price"><i class="fa-solid fa-tag"></i> 500,000đ/ngày</p>
        </div>
        <div class="favorite-actions">
            <a href="#" class="btn-view"><i class="fa-solid fa-eye"></i> Xem</a>
            <button class="btn-remove"><i class="fa-solid fa-heart-crack"></i> Bỏ thích</button>
        </div>
    </div>
</div>
-->