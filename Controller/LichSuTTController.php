<?php
require_once 'Model/hd_tk/hoadonModel.php'; 

class LichSuTTController {
    private $model;

    public function __construct($db) {
        $this->model = new HoaDonModel($db);
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $idtaikhoan = $_SESSION['idtaikhoan'] ?? 0;
        
        $lichsu = $this->model->getLichSuThanhToan($idtaikhoan);
        
        require_once 'View/thanhtoan/lichsu_view.php';
    }
}
?>