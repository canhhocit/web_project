<?php if (empty($favotiteCars)): ?>
    <div class="no-vehicle">
        <i class="fa-solid fa-car"></i>
        <h3>Bạn chưa ưng xe nào cả...</h3>
        <a href="/web_project/index.php" class="btn-add-vehicle">
            <i class="fa-solid fa-plus"></i> Lướt xem xe
        </a>
    </div>
<?php else: ?>
    <link rel="stylesheet" href="/web_project/View/CSS/taikhoan/personal/myvehicle.css">

    <div class="vehicle-list">
        <?php foreach ($favotiteCars as $item): ?>
            <?php
            $xe = $item['xe'];
            $images = $item['images'];
            ?>
            <div class="vehicle-card">
                <div class="image-section">
                    <?php if (!empty($images)): ?>
                        <?php foreach ($images as $index => $image): ?>
                            <img src="/web_project/View/image/<?php echo $image->get_duongdan(); ?>"
                                alt="<?php echo $xe->get_tenxe(); ?>"
                                class="vehicle-image <?php echo $index === 0 ? 'active' : ''; ?>"
                                onerror="this.src='/web_project/View/image/placeholder.jpg'">
                        <?php endforeach; ?>

                        <?php if (count($images) > 1): ?>
                            <button class="btn-prev" onclick="changeImage(this, -1)">‹</button>
                            <button class="btn-next" onclick="changeImage(this, 1)">›</button>
                            <div class="image-counter">1 / <?php echo count($images); ?></div>
                        <?php endif; ?>
                    <?php else: ?>
                        <img src="/web_project/View/image/placeholder.jpg" alt="No image" class="vehicle-image active">
                    <?php endif; ?>
                </div>

                <div class="vehicle-info">
                    <h4><?php echo $xe->get_tenxe(); ?></h4>
                    <p>
                        <strong>Hãng:</strong> <?php echo $xe->get_hangxe(); ?><br>
                        <strong>Loại:</strong> <?php echo $xe->get_loaixe(); ?><br>
                        <strong>Giá thuê:</strong> <?php echo number_format($xe->get_giathue()); ?> VND/ngày
                        <strong>Ngày đăng:</strong>
                        <?php echo date('d/m/Y', strtotime($xe->get_ngaydang())); ?>
                    </p>
                    <small>
                        <i class="fa-solid fa-images"></i>
                        <?php echo count($images); ?> ảnh
                    </small>
                        
                    <div class="btn_tim">
                        <a href="/web_project/index.php?controller=taikhoan&action=favoriteVehicle&id=<?= $xe->get_idxe() ?>" 
                            class="heart-btn"
                            title="Bỏ yêu thích xe">
                            <span class="heart-icon">
                                <?php
                                echo "❤️";
                                 ?>
                            </span>
                        </a>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="/web_project/View/JS/taikhoan/personal/myvehicle.js"></script>
<?php endif; ?>