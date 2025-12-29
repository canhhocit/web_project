<?php
require_once 'Model/hd_tk/ThongKeModel.php';

class ThongKeController {
    private $model;
    
    public function __construct($db) {
        $this->model = new ThongKeModel($db);
    }
    
    // Action mặc định - hiển thị trang thống kê
    public function index() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['idtaikhoan'])) {
            $this->redirectToLogin();
            return;
        }
        
        $idtaikhoan = $_SESSION['idtaikhoan'];
        
        // Lấy dữ liệu thống kê từ Model
        $data = $this->model->getAllStatistics($idtaikhoan);
        
        // Kiểm tra kết quả
        if (!$data['success']) {
            if ($data['error'] === 'ACCESS_DENIED') {
                $this->showAccessDenied();
                return;
            } else {
                $this->showError($data['message']);
                return;
            }
        }
        
        // Render view
        $this->renderView('thongke/indexTK.php', $data);
    }
    
    // Action cho API (nếu cần)
    public function apiGetStatistics() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['idtaikhoan'])) {
            echo json_encode([
                'success' => false,
                'error' => 'NOT_LOGGED_IN'
            ]);
            return;
        }
        
        $idtaikhoan = $_SESSION['idtaikhoan'];
        $data = $this->model->getAllStatistics($idtaikhoan);
        
        echo json_encode($data);
    }
    
    // Private methods
    private function showAccessDenied() {
        $this->renderView('thongke/accessDenied.php');
    }
    
    private function showError($message) {
         echo '
            <!DOCTYPE html>
            <html>
            <head>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            </head>
            <body class="bg-light">
                <div class="container mt-5">
                    <div class="alert alert-danger">
                        <h4><i class="fas fa-exclamation-triangle me-2"></i>Lỗi</h4>
                        <p>' . htmlspecialchars($message) . '</p>
                        <hr>
                        <a href="index.php" class="btn btn-primary">Về trang chủ</a>
                    </div>
                </div>
            </body>
            </html>';
        exit();
    }
    
    private function redirectToLogin() {
        header('Location: index.php?controller=taikhoan&action=login');
        exit();
    }
    
    private function renderView($viewPath, $data = []) {
        // Truyền dữ liệu vào view
        extract($data);
        
        // Include view
        $fullPath = "View/" . $viewPath;
        if (file_exists($fullPath)) {
            require_once $fullPath;
        } else {
            die("View không tồn tại: " . $fullPath);
        }
    }
}
?>