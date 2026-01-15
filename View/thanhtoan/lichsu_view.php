<div class="my-4">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0 text-center"><i class="fa-solid fa-history me-2"></i>LỊCH SỬ GIAO DỊCH</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Mã GD</th>
                            <th>Tên Xe</th>
                            <th>Ngày Thuê - Trả</th>
                            <th>Tổng Tiền</th>
                            <th>Phương Thức</th>
                            <th>Ngày Thanh Toán</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($lichsu)): ?>
                            <?php foreach ($lichsu as $item): ?>
                            <tr>
                                <td class="fw-bold">#<?php echo $item['magiaodich']; ?></td>
                                <td><?php echo $item['tenxe']; ?></td>
                                <td class="small">
                                    <?php echo date('d/m/Y', strtotime($item['ngaymuon'])); ?> - 
                                    <?php echo date('d/m/Y', strtotime($item['ngaytra'])); ?>
                                </td>
                                <td class="text-danger fw-bold"><?php echo number_format($item['sotien'], 0, ',', '.'); ?> đ</td>
                                <td><span class="badge bg-info text-dark"><?php echo $item['phuongthuc']; ?></span></td>
                                <td><?php echo date('d/m/Y', strtotime($item['ngaythanhtoan'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center py-4">Bạn chưa có giao dịch nào hoàn tất.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>