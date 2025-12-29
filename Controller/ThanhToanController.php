<?php
require_once 'Model/Service/ThanhToanService.php'; 

class ThanhToanController {
    private $service;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->service = new ThanhToanService($db); 
    }

    public function index() {
        $idtaikhoan = $_SESSION['idtaikhoan'];
        $hoadon = $this->service->getHoaDonChuaThanhToan();
        
        include_once 'View/thanhtoan/quanly.php';
    }

    public function xacNhanTraXe() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idhoadon = $_POST['idhoadon'];
            $phuongthuc = $_POST['phuongthuc'];
            
            $result = $this->service->xacNhanThanhToan($idhoadon, $phuongthuc);
            
            echo json_encode(['success' => $result]);
        }
    }

    public function getChiTietHoaDon() {
        if (isset($_GET['idhoadon'])) {
            $idhoadon = $_GET['idhoadon'];
            
            $hoadon = $this->service->getChiTietHoaDonVoiTinhToan($idhoadon);
            
            if ($hoadon) {
                echo json_encode($hoadon);
                exit();
            }
        }
        
        echo json_encode(['error' => 'Không tìm thấy hóa đơn']);
    }
}