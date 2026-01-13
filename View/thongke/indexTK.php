<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê cá nhân</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="View/CSS/thongke.css">
</head>
<body class="bg-light">
<div class="container mt-4 pb-5">
    <h2 class="mb-4 text-primary fw-bold text-uppercase">
        <i class="fas fa-user-chart me-2"></i>Doanh thu cá nhân
    </h2>
    
    <?php if (isset($error) && $error): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white p-3 shadow-sm h-100">
                <h6>Tổng doanh thu tích lũy</h6>
                <h3><?= number_format($tongDoanhThu['tong_doanh_thu'] ?? 0) ?> đ</h3>
                <i class="fas fa-coins fa-3x stats-icon"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white p-3 shadow-sm h-100">
                <h6>Doanh thu tháng <?= date('m/Y') ?></h6>
                <h3><?= number_format($doanhThuThang['doanh_thu'] ?? 0) ?> đ</h3>
                <i class="fas fa-calendar-days fa-3x stats-icon"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark p-3 shadow-sm h-100">
                <h6>Tình trạng xe của tôi</h6>
                <p class="mb-1">Đang thuê: <strong><?= $statsPhu['dang_thue'] ?></strong></p>
                <p class="mb-0">Tổng số xe: <strong><?= $statsPhu['tong_xe'] ?></strong></p>
                <i class="fas fa-bicycle fa-3x stats-icon"></i>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card shadow-sm p-4 h-100 bg-white">
                <h5 class="fw-bold mb-3">Biểu đồ doanh thu theo tháng (<?= date('Y') ?>)</h5>
                <canvas id="barChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-4 h-100 bg-white text-center">
                <h5 class="fw-bold mb-3">Cơ cấu loại xe của tôi</h5>
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
const barCtx = document.getElementById('barChart').getContext('2d');
new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_map(function($t) { return "Tháng " . $t['thang']; }, $doanhThuCacThang ?? [])) ?>,
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: <?= json_encode(array_column($doanhThuCacThang ?? [], 'doanh_thu')) ?>,
            backgroundColor: '#0d6efd',
            borderRadius: 5
        }]
    },
    options: { 
        plugins: { 
            legend: { display: false } 
        },
        responsive: true,
        maintainAspectRatio: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString('vi-VN') + ' đ';
                    }
                }
            }
        }
    }
});

const pieCtx = document.getElementById('pieChart').getContext('2d');
new Chart(pieCtx, {
    type: 'doughnut',
    data: {
        labels: ['Xe đạp', 'Xe máy điện'],
        datasets: [{
            data: <?= json_encode(array_map(
                fn($t) => (int)$t['so_luong'],
                $tyLeLoaiXe ?? []
            )) ?>,
            backgroundColor: [
                '#ffc107',  '#fd3030'  
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        cutout: '65%', 
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    boxWidth: 14
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label;
                        const value = context.raw || 0;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percent = total ? Math.round((value / total) * 100) : 0;
                        return `${label}: ${value} xe (${percent}%)`;
                    }
                }
            }
        }
    }
});
</script>
</body>
</html>