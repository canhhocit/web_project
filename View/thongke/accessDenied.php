<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Không có quyền truy cập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="fas fa-lock fa-5x text-danger mb-3"></i>
                        <h1 class="h3 font-weight-bold text-danger">QUYỀN TRUY CẬP BỊ HẠN CHẾ</h1>
                    </div>
                    
                    <div class="alert alert-warning border-start border-warning border-5">
                        <h4 class="alert-heading">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Trang này chỉ dành cho Chủ xe
                        </h4>
                        <p class="mb-0">
                            Bạn cần có tài khoản <strong>Chủ xe</strong> để xem thống kê doanh thu và quản lý xe.
                        </p>
                    </div>
                    
                    <div class="mt-4">
                        <p class="text-muted">
                            Nếu bạn là chủ xe, vui lòng liên hệ quản trị viên để được cấp quyền.
                        </p>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                            <a href="index.php" class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-home me-2"></i>Về Trang chủ
                            </a>
                            <a href="index.php?controller=xe&action=danhsach" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="fas fa-car me-2"></i>Xem danh sách xe
                            </a>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Nếu đây là lỗi hệ thống, vui lòng liên hệ hỗ trợ.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>