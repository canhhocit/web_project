<div class="container mt-4 mb-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $xe['tenxe']; ?></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <?php if (!empty($xe['ds_anh'])): ?>
                    <div id="carouselCar" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" style="border-radius: 5px; overflow: hidden;">
                            <?php foreach ($xe['ds_anh'] as $index => $anh): ?>
                                <div class="carousel-item <?php echo ($index == 0) ? 'active' : ''; ?>">
                                    <img src="View/image/<?php echo $anh; ?>" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Ảnh xe">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($xe['ds_anh']) > 1): ?>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselCar" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselCar" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <img src="https://via.placeholder.com/800x500?text=Chua+co+anh" class="img-fluid rounded" alt="Không có ảnh">
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow p-4">
                <h2 class="text-primary fw-bold mb-1"><?php echo $xe['tenxe']; ?></h2>
                <p class="text-muted"><i class="fa-solid fa-tag"></i> Hãng: <?php echo $xe['tenhangxe']; ?></p>
                
                <h3 class="text-danger fw-bold my-3">
                    <?php echo number_format($xe['giathue'], 0, ',', '.'); ?> đ/ngày
                </h3>

                <div class="mb-3">
                    <span class="badge bg-info text-dark p-2"><i class="fa-solid fa-car"></i> <?php echo $xe['loaixe']; ?></span>
                    <span class="badge bg-success p-2"><i class="fa-solid fa-check"></i> Xe hoạt động tốt</span>
                </div>

                <hr>

                <div class="thong-tin-chu-xe mb-4">
                    <p class="fw-bold mb-1">Chủ xe:</p>
                    <div class="d-flex align-items-center">
                        <div class="bg-secondary rounded-circle d-flex justify-content-center align-items-center text-white" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div class="ms-2">
                            <h6 class="mb-0"><?php echo !empty($xe['tenchuxe']) ? $xe['tenchuxe'] : 'Ẩn danh'; ?></h6>
                            <small class="text-muted">SĐT: <?php echo !empty($xe['sdt']) ? $xe['sdt'] : '***'; ?></small>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-primary btn-lg fw-bold">
                        <i class="fa-regular fa-calendar-check"></i> Đặt Xe Ngay
                    </a>
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="fw-bold border-bottom pb-2">Mô tả xe</h4>
                <p class="mt-3" style="white-space: pre-line;">
                    <?php echo !empty($xe['mota']) ? $xe['mota'] : 'Chủ xe chưa thêm mô tả chi tiết.'; ?>
                </p>
            </div>
        </div>
    </div>
</div>