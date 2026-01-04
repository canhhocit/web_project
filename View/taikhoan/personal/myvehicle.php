<?php if (empty($xeWithImages)): ?>
    <div class="no-vehicle">
        <i class="fa-solid fa-car"></i>
        <h3>Bạn chưa đăng bài viết nào</h3>
        <a href="/web_project/index.php?controller=vehicle&action=checkLogin" class="btn-add-vehicle">
            <i class="fa-solid fa-plus"></i> Thêm xe đầu tiên
        </a>
    </div>
<?php else: ?>
    <link rel="stylesheet" href="/web_project/View/CSS/taikhoan/personal/myvehicle.css">

    <div class="vehicle-list">
        <?php foreach ($xeWithImages as $item): ?>
            <?php
            $xe = $item['xe'];
            $images = $item['images'];
            $trangthai = $item['trangthai'];
            $status = $item['status'];
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
                    <h4><b><i><?php echo $xe->get_tenxe(); ?></i></b></h4>
                    <p>
                        <strong>Hãng:</strong> <?php echo $xe->get_hangxe(); ?><br>
                        <strong>Loại:</strong> <?php echo $xe->get_loaixe(); ?><br>
                        <strong>Giá thuê:</strong> <?php echo number_format($xe->get_giathue()); ?> VND/ngày <br>
                        <strong>Ngày đăng:</strong>
                        <?php echo date('d/m/Y', strtotime($xe->get_ngaydang())); ?><br>
                        <strong>Trạng thái: </strong><?php echo $trangthai ?>
                    </p>
                    <small>
                        <i class="fa-solid fa-images"></i>
                        <?php echo count($images); ?> ảnh
                    </small>
                    <?php if (!$status) : ?>
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
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <a href="?controller=taikhoan&action=personal&selection=myvehicle&page=<?= $currentPage - 1 ?>" class="page-btn">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?controller=taikhoan&action=personal&selection=myvehicle&page=<?= $i ?>"
                    class="page-btn <?= $i === $currentPage ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <a href="?controller=taikhoan&action=personal&selection=myvehicle&page=<?= $currentPage + 1 ?>" class="page-btn">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <script src="/web_project/View/JS/taikhoan/personal/myvehicle.js"></script>
<?php endif; ?>