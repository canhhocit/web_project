<?php
require_once __DIR__ . '/../hd_tk/hoadonModel.php';

class ThanhToanService {
    private $hoadonModel;
    
    public function __construct($db) {
        $this->hoadonModel = new HoaDonModel($db);
    }
    
    public function tinhTongTienVaNgayQuaHan($hoadon) {
        if (empty($hoadon)) {
            return ['ngay_qua_han' => 0, 'tong_tien' => 0];
        }
        
        $ngay_hien_tai = date('Y-m-d');
        $ngay_tra_du_kien = $hoadon['ngaytra'];
        
        $diff = strtotime($ngay_hien_tai) - strtotime($ngay_tra_du_kien);
        $ngay_qua_han = max(0, floor($diff / (60 * 60 * 24)));
        
        $ngay_thue_du_kien = $hoadon['ngay_thue_du_kien'] ?? 0;
        $don_gia = $hoadon['giathue'] ?? 0;

        $phi_thue = ($ngay_thue_du_kien + $ngay_qua_han) * $don_gia;
        
        $loai_xe = $hoadon['loaixe'] ?? ''; 
        $phi_bao_hiem = ($loai_xe === 'Xe máy điện') ? 100000 : 50000;
        $phi_bao_tri = ($loai_xe === 'Xe máy điện') ? 100000 : 50000;
        
        $tong_tien = $phi_thue + $phi_bao_hiem + $phi_bao_tri;
        
        return [
            'ngay_qua_han' => $ngay_qua_han,
            'tong_tien' => $tong_tien,
            'phi_thue' => $phi_thue,
            'phi_bao_hiem' => $phi_bao_hiem,
            'phi_bao_tri' => $phi_bao_tri
        ];
    }
    
    public function getChiTietHoaDonVoiTinhToan($idhoadon) {
        $hoadon = $this->hoadonModel->getChiTietHoaDon($idhoadon);
        
        if (!$hoadon) {
            return null;
        }
        
        $tinh_toan = $this->tinhTongTienVaNgayQuaHan($hoadon);
        
        return array_merge($hoadon, $tinh_toan);
    }
    
    public function xacNhanThanhToan($idhoadon, $phuongthuc) {
        $hoadon = $this->getChiTietHoaDonVoiTinhToan($idhoadon);
        
        if (!$hoadon) {
            return false;
        }
        
        return $this->hoadonModel->xacNhanTraXe(
            $idhoadon,
            $hoadon['ngay_qua_han'],
            $hoadon['tong_tien'],
            $phuongthuc
        );
    }
    
    public function getHoaDonChuaThanhToan($idtaikhoan) {
        return $this->hoadonModel->getHoaDonChuaThanhToan($idtaikhoan);
    }
}