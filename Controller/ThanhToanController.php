<?php
require_once 'Model/Service/ThanhToanService.php'; // THÊM DÒNG NÀY

class ThanhToanController {
    private $service;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->service = new ThanhToanService($db); // SỬA DÒNG NÀY
    }

    public function index() {
        // GIỮ NGUYÊN
        $idtaikhoan = $_SESSION['idtaikhoan'];
        
        // SỬA: Gọi service thay vì model trực tiếp
        $hoadon = $this->service->getHoaDonChuaThanhToan();
        
        include_once 'View/thanhtoan/quanly.php';
    }

    public function xacNhanTraXe() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idhoadon = $_POST['idhoadon'];
            $phuongthuc = $_POST['phuongthuc'];
            
            // SỬA: Gọi service thay vì model trực tiếp
            $result = $this->service->xacNhanThanhToan($idhoadon, $phuongthuc);
            
            echo json_encode(['success' => $result]);
        }
    }

    public function getChiTietHoaDon() {
        if (isset($_GET['idhoadon'])) {
            $idhoadon = $_GET['idhoadon'];
            
            // SỬA: Gọi service thay vì tính toán trong controller
            $hoadon = $this->service->getChiTietHoaDonVoiTinhToan($idhoadon);
            
            if ($hoadon) {
                echo json_encode($hoadon);
                exit();
            }
        }
        
        // Nếu không tìm thấy
        echo json_encode(['error' => 'Không tìm thấy hóa đơn']);
    }
}