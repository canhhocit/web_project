<?php
require_once 'Model/hd_tk/ThongKeModel.php';

class ThongKeController {
    private $model;
    
    public function __construct($db) {
        $this->model = new ThongKeModel($db);
    }
    
    public function index() {
        if (!isset($_SESSION['idtaikhoan'])) {
             echo "<script>
                alert('Vui lòng đăng nhập!');
                window.location.href='/web_project/View/taikhoan/login.php';
            </script>";
            exit();
        }
        
        $idtaikhoan = $_SESSION['idtaikhoan'];
        
        $data = $this->model->getAllStatistics($idtaikhoan);
        
        if (!$data['success']) {
            $message = ($data['error'] === 'ACCESS_DENIED') 
                ? 'Bạn không có quyền truy cập! Chức năng này chỉ dành cho chủ xe.'
                : 'Lỗi: ' . $data['message'];
            
            echo "<script>
                alert('" . addslashes($message) . "');
                window.location.href='index.php';
            </script>";
            exit(); 
        }
        
        $this->renderView('thongke/indexTK.php', $data);
    }
    
    private function renderView($viewPath, $data = []) {
        extract($data);
        
        $fullPath = "View/" . $viewPath;
        if (file_exists($fullPath)) {
            require_once $fullPath;
        } else {
            die("View không tồn tại: " . $fullPath);
        }
    }
}
?>