<div class="row">
    <div class="col-12">
        <form action="index.php" method="GET" class="mb-4">
            <input type="hidden" name="controller" value="home">
            <div class="input-group">
                <input type="text" name="keyword" class="form-control" placeholder="Tìm tên xe bạn thích...">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>
    </div>

    <?php
    if (empty($listCar)) {
        echo '<div class="col-12">';
        echo '<div class="alert alert-warning text-center p-5">';
        echo '<h4 class="mb-0">Chưa có xe nào phù hợp!</h4>';
        echo '</div>';
        echo '</div>';
    } else {
        foreach ($listCar as $car) {
            $idxe = $car->get_idxe();
            $tenxe = $car->get_tenxe();
            $hangxe = $car->get_hangxe();
            $loaixe = $car->get_loaixe();
            $giathue = number_format($car->get_giathue(), 0, ',', '.');
            
            // Lấy ảnh từ mảng đã chuẩn bị sẵn (tối ưu hơn)
            $hinhAnh = 'https://via.placeholder.com/300x200?text=No+Image';
            if (isset($listCarImages[$idxe]) && !empty($listCarImages[$idxe])) {
                $hinhAnh = 'View/image/' . $listCarImages[$idxe][0]->get_duongdan();
            }
    ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm border-0 hover-card">
                    <div class="position-relative" style="height: 200px; overflow: hidden;">
                        <img src="<?php echo $hinhAnh; ?>" class="card-img-top w-100 h-100" style="object-fit: cover;" alt="<?php echo $tenxe; ?>">
                        <span class="position-absolute top-0 end-0 badge bg-danger m-2">Hot</span>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-truncate fw-bold text-primary" title="<?php echo $tenxe; ?>">
                            <?php echo $tenxe; ?>
                        </h5>

                        <div class="mb-3">
                            <small class="text-muted"><i class="fa-solid fa-tag"></i> <?php echo $hangxe; ?></small>
                            <small class="text-muted ms-2"><i class="fa-solid fa-car"></i> <?php echo $loaixe; ?></small>
                        </div>

                        <h5 class="card-text text-danger fw-bold mb-3">
                            <?php echo $giathue; ?> đ <small class="text-dark fs-6 fw-normal">/ngày</small>
                        </h5>

                        <a href="index.php?controller=car&action=detail&id=<?php echo $idxe; ?>" class="btn btn-outline-primary mt-auto fw-bold">
                            Xem Chi Tiết <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

    <?php
        }
    }
    ?>
</div>
<?php if (isset($tongTrang) && $tongTrang > 1): ?>
<div class="col-12 mt-4">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="index.php?controller=home&page=<?php echo $page - 1; ?>">Trước</a>
            </li>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="index.php?controller=home&page=<?php echo $i; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>

            <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                <a class="page-link" href="index.php?controller=home&page=<?php echo $page + 1; ?>">Sau</a>
            </li>
        </ul>
    </nav>
</div>
<?php endif; ?>