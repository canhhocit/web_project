<?php 
// xem DL tới kh
// var_dump($hoadon); 
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý thuê xe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="View/CSS/thanhtoan.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* CSS từ file nguyen_comp_quanly.html của bạn */
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f5f5f5; padding: 20px; }
        .tabs { display: flex; background: #fff; border-radius: 10px 10px 0 0; overflow: hidden; }
        .tab { flex: 1; padding: 15px; text-align: center; cursor: pointer; font-weight: bold; border-bottom: 3px solid transparent; }
        .tab.active { border-bottom: 3px solid #007bff; color: #007bff; }
        .content { background: #fff; padding: 20px; border-radius: 0 0 10px 10px; }
        .row-item { display: flex; align-items: center; padding: 15px; border-bottom: 1px solid #eee; }
        .image-box { width: 80px; height: 60px; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; margin-right: 15px; font-size: 12px; color: #999; }
        .info { flex: 1; }
        .name { font-weight: bold; }
        .status { margin-top: 6px; display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 12px; color: #fff; }
        .yellow { background: #f1c40f; }
        .actions button { margin-left: 10px; padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer; }
        .btn-view { background: #3498db; color: #fff; }
        .btn-return { background: #e74c3c; color: #fff; }
    </style>
</head>
<body>
    <div class="tabs">
        <div class="tab" onclick="switchTab(1,this)">Đang thuê</div>
        <div class="tab" onclick="switchTab(2,this)">Cho thuê</div>
        <div class="tab" onclick="switchTab(2,this)">Đã thuê</div>
    </div>

    <div class="content">
        <div id="tab-content-1" class="tab-pane">
            <?php if (!empty($hoadon)): ?>
                <?php foreach ($hoadon as $hd): ?>
                <div class="row-item">
                    <div class="image-box">Ảnh xe</div>
                    <div class="info">
                        <div class="name"><?= $hd['tenxe'] ?> (Mã HĐ: <?= $hd['idhoadon'] ?>)</div>
                        <div class="price">Ngày mượn: <?= $hd['ngaymuon'] ?></div>
                        <span class="status yellow">Đang thuê</span>
                    </div>
                    <div class="actions">
                        <button class="btn-view">Xem chi tiết</button>
                        <button class="btn-return" onclick="showModal(<?= $hd['idhoadon'] ?>)">Trả xe</button>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center p-4">Không có hóa đơn nào đang thuê.</p>
            <?php endif; ?>
        </div>

        <div id="tab-content-2" class="tab-pane" style="display:none;">
            <p class="text-center p-4">Chưa có dữ liệu xe bạn đang cho thuê.</p>
        </div>

        <div id="tab-content-3" class="tab-pane" style="display:none;">
            <p class="text-center p-4">Chưa có lịch sử xe đã thuê.</p>
        </div>
    </div>

    <div class="modal fade" id="modalTraXe" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">XÁC NHẬN THANH TOÁN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formTraXe">
                        <input type="hidden" name="idhoadon" id="idhoadon">
                        <div class="mb-3">
                            <label class="fw-bold">Tên xe:</label>
                            <input type="text" class="form-control" id="tenxe" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Đơn giá thuê:</label>
                            <input type="text" class="form-control" id="giathue" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Số ngày thuê dự kiến:</label>
                            <input type="text" class="form-control" id="ngay_thue_du_kien" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Số ngày quá hạn:</label>
                            <input type="text" class="form-control" id="ngay_qua_han" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Phương thức thanh toán:</label>
                            <select class="form-select" id="phuongthuc">
                                <option value="Tiền mặt" selected>Tiền mặt</option>
                                <option value="Chuyển khoản">Chuyển khoản</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Tổng tiền:</label>
                            <input type="text" class="form-control" id="tong_tien" readonly>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="huyTT()">Hủy</button>
                    <button type="button" class="btn btn-primary" onclick="xacNhanTraXe()">Thanh toán</button>
                </div>
            </div>
        </div>
    </div>

       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
    <script src="View/JS/thanhtoan.js"></script>

</body>
</html>