<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">VỀ CHÚNG TÔI</h2>
        <p class="text-muted">Đội ngũ phát triển dự án "Thuê Xe Đi Bro"</p>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa-solid fa-pen-to-square"></i> Báo cáo công việc</h5>
                </div>
                <div class="card-body">
                    <form action="index.php?controller=about&action=addWork" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và tên</label>
                            <input type="text" name="ten_thanhvien" class="form-control" placeholder="Ví dụ: Nguyễn Văn A" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mã sinh viên</label>
                            <input type="text" name="mssv" class="form-control" placeholder="Nếu có">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Công việc đã làm</label>
                            <textarea name="cong_viec" class="form-control" rows="4" placeholder="Mô tả các chức năng đã code, database..." required></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-bold">
                                <i class="fa-solid fa-paper-plane"></i> Gửi báo cáo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="row">
                <?php if (empty($listMember)): ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            Chưa có thông tin nào. Hãy là người đầu tiên điền form!
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($listMember as $mem): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 shadow-sm border-start border-4 border-primary">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-light rounded-circle p-2 me-3 text-primary">
                                            <i class="fa-solid fa-user-graduate fa-2x"></i>
                                        </div>
                                        <div>
                                            <h5 class="card-title fw-bold mb-0"><?php echo htmlspecialchars($mem->get_ten()); ?></h5>
                                            <small class="text-muted"><?php echo htmlspecialchars($mem->get_mssv()); ?></small>
                                        </div>
                                    </div>
                                    <h6 class="text-secondary fw-bold">Công việc:</h6>
                                    <p class="card-text text-dark" style="white-space: pre-line;">
                                        <?php echo nl2br(htmlspecialchars($mem->get_congviec())); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>