<?php
require_once 'Model/Service/ThanhToanService.php'; 

class ThanhToanController {
    private $service;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->service = new ThanhToanService($db); 
    }

    public function checkLogin() {
        if(!isset($_SESSION['idtaikhoan'])) {
            echo "<script>
                alert('Vui lòng đăng nhập!');
                window.location.href='/web_project/View/taikhoan/login.php';
            </script>";
            exit();
        }
    }

    public function index() {
        $this->checkLogin();

        $idtaikhoan = $_SESSION['idtaikhoan'];
        $hoadon = $this->service->getHoaDonChuaThanhToan($idtaikhoan);
        
        include_once 'View/thanhtoan/quanly.php';
    }

    public function xacNhanTraXe() {
         $this->checkLogin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idhoadon = $_POST['idhoadon'];
            $phuongthuc = $_POST['phuongthuc'];
            
            $result = $this->service->xacNhanThanhToan($idhoadon, $phuongthuc);
            
            echo json_encode(['success' => $result]);
        }
    }

    public function getChiTietHoaDon() {
         $this->checkLogin();

        if (isset($_GET['idhoadon'])) {
            $idhoadon = $_GET['idhoadon'];
            $idtaikhoan =$_SESSION['idtaikhoan'];
            
            $hoadon = $this->service->getChiTietHoaDonVoiTinhToan($idhoadon);
            
            if ($hoadon && $hoadon['idtaikhoan'] == $idtaikhoan) {
                echo json_encode($hoadon);
                exit();
            } else {
                echo json_encode(['error' => 'Không có quyền xem hóa đơn này!']);
                exit();
            }
        }
        
        echo json_encode(['error' => 'Không tìm thấy hóa đơn']);
    }
}