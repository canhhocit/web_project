<?php if (empty($xeWithImages)): ?>
    <div class="no-vehicle">
        <i class="fa-solid fa-car"></i>
        <h3>Bạn chưa đăng bài viết nào</h3>
        <a href="/web_project/index.php?controller=vehicle&action=checkLogin" class="btn-add-vehicle">
            <i class="fa-solid fa-plus"></i> Thêm xe đầu tiên
        </a>
    </div>
<?php else: ?>
    <link rel="stylesheet" href="/web_project/View/CSS/taikhoan/personal/mycars.css">
    
    <div class="vehicle-list">
        <?php foreach ($xeWithImages as $item): ?>
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
                    </p>
                    <small>
                        <i class="fa-solid fa-images"></i> 
                        <?php echo count($images); ?> ảnh
                    </small>
                    
                    <div class="button-group">
                        <a href="/web_project/index.php?controller=vehicle&action=editV&id=<?php echo $xe->get_idxe(); ?>" 
                           class="btn btn-edit">
                            <i class="fa-solid fa-edit"></i> Sửa
                        </a>
                        <a href="/web_project/index.php?controller=vehicle&action=deleteV&id=<?php echo $xe->get_idxe(); ?>" 
                           onclick="return confirm('Bạn có chắc muốn xóa xe này?')"
                           class="btn btn-delete">
                            <i class="fa-solid fa-trash"></i> Xóa
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <script src="/web_project/View/JS/taikhoan/personal/mycars.js"></script>
<?php endif; ?>