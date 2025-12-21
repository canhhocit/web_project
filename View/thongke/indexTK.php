<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê hệ thống</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stykesheet" href="View/CSS/thongke.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body class="bg-light">
<div class="container mt-4 pb-5">
    <h2 class="mb-4 text-primary fw-bold text-uppercase"><i class="fas fa-chart-line me-2"></i>Báo cáo hệ thống</h2>
    
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white p-3 shadow-sm h-100">
                <h6>Tổng doanh thu</h6>
                <h3><?= number_format($tongDoanhThu['tong_doanh_thu'] ?? 0) ?> đ</h3>
                <i class="fas fa-money-bill-wave fa-3x stats-icon"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white p-3 shadow-sm h-100">
                <h6>Doanh thu tháng <?= date('m/Y') ?></h6>
                <h3><?= number_format($doanhThuThang['doanh_thu'] ?? 0) ?> đ</h3>
                <i class="fas fa-calendar-check fa-3x stats-icon"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white p-3 shadow-sm h-100">
                <h6>Xe đang được thuê</h6>
                <h3><?= $statsPhu['dang_thue'] ?> xe</h3>
                <i class="fas fa-bicycle fa-3x stats-icon"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white p-3 shadow-sm h-100">
                <h6>Tổng khách hàng</h6>
                <h3><?= $statsPhu['tong_kh'] ?> khách</h3>
                <i class="fas fa-users fa-3x stats-icon"></i>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card shadow-sm p-4 h-100 bg-white">
                <h5 class="fw-bold mb-3">Doanh thu theo tháng (năm <?= date('Y') ?>)</h5>
                <canvas id="barChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-4 h-100 bg-white text-center">
                <h5 class="fw-bold mb-3">Tỷ lệ loại xe</h5>
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm p-4 bg-white">
                <h5 class="fw-bold mb-3">Xếp hạng doanh thu chủ xe</h5>
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Chủ xe</th>
                            <th class="text-end">Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($doanhThuTheoChuXe as $index => $chuxe): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td class="fw-bold"><?= $chuxe['chu_xe'] ?></td>
                            <td class="text-end text-success fw-bold"><?= number_format($chuxe['tong_doanh_thu']) ?> VNĐ</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
const barCtx = document.getElementById('barChart').getContext('2d');
new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_map(fn($t) => "Tháng ".$t['thang'], $doanhThuCacThang)) ?>,
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: <?= json_encode(array_column($doanhThuCacThang, 'doanh_thu')) ?>,
            backgroundColor: '#05b396ff',
            borderRadius: 5
        }]
    },
    options: { plugins: { legend: { display: false } } }
});

const pieCtx = document.getElementById('pieChart').getContext('2d');
new Chart(pieCtx, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_column($tyLeLoaiXe, 'loaixe')) ?>,
        datasets: [{
            data: <?= json_encode(array_column($tyLeLoaiXe, 'so_luong')) ?>,
            backgroundColor: ['#ffc107', '#17a2b8', '#dc3545', '#28a745']
        }]
    }
});
</script>
</body>
</html>