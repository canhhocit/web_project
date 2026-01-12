<?php
require_once 'Model/hd_tk/hoadonModel.php'; 

class LichSuTTController {
    private $model;

    public function __construct($db) {
        $this->model = new HoaDonModel($db);
    }

    public function index() {
        if (!isset($_SESSION['idtaikhoan'])) {
            echo "<script>
                alert('Vui lòng đăng nhập để xem lịch sử giao dịch!');
                window.location.href='/web_project/View/taikhoan/login.php';
            </script>";
            exit();
        }
        
        $idtaikhoan = $_SESSION['idtaikhoan'];
        
        $lichsu = $this->model->getLichSuThanhToan($idtaikhoan);
        
        require_once 'View/thanhtoan/lichsu_view.php';
    }
}
?>