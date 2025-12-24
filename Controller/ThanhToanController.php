<?php
require_once 'Model/hd_tk/hoadonModel.php'; 

class ThanhToanController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new HoaDonModel($db);
    }

    public function index() {
        $idtaikhoan = $_SESSION['idtaikhoan'];

        $hoadon = $this->model->getHoaDonChuaThanhToan();
        require_once 'View/thanhtoan/quanly.php';
    }

    public function xacNhanTraXe() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idhoadon = $_POST['idhoadon'];
            $ngay_qua_han = $_POST['ngay_qua_han'];
            $tong_tien = $_POST['tong_tien'];
            $phuongthuc = $_POST['phuongthuc'];

            $result = $this->model->xacNhanTraXe($idhoadon, $ngay_qua_han, $tong_tien, $phuongthuc);
            echo json_encode(['success' => $result]);
        }
    }

    public function getChiTietHoaDon() {
        if (isset($_GET['idhoadon'])) {
            $idhoadon = $_GET['idhoadon'];
            $hoadon = $this->model->getChiTietHoaDon($idhoadon);
            
            if ($hoadon) {
                $ngay_hien_tai = date('Y-m-d');
                $ngay_tra_du_kien = $hoadon['ngaytra'];
                
                $diff = strtotime($ngay_hien_tai) - strtotime($ngay_tra_du_kien);
                $ngay_qua_han = max(0, floor($diff / (60 * 60 * 24)));
                
                // (Số ngày thuê dự kiến + ngày quá hạn) * đơn giá
                $tong_tien = ($hoadon['ngay_thue_du_kien'] + $ngay_qua_han) * $hoadon['giathue'];
                
                $hoadon['ngay_qua_han'] = $ngay_qua_han;
                $hoadon['tong_tien'] = $tong_tien;
                
                echo json_encode($hoadon);
                exit();
            }
        }
    }

}