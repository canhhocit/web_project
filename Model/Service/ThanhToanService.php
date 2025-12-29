<?php
require_once __DIR__ . '/../hd_tk/hoadonModel.php';

class ThanhToanService {
    private $hoadonModel;
    
    public function __construct($db) {
        $this->hoadonModel = new HoaDonModel($db);
    }
    
    /**
     * Tính ngày quá hạn và tổng tiền (logic nghiệp vụ)
     */
    public function tinhTongTienVaNgayQuaHan($hoadon) {
        if (empty($hoadon)) {
            return ['ngay_qua_han' => 0, 'tong_tien' => 0];
        }
        
        $ngay_hien_tai = date('Y-m-d');
        $ngay_tra_du_kien = $hoadon['ngaytra'];
        
        // Tính ngày quá hạn
        $diff = strtotime($ngay_hien_tai) - strtotime($ngay_tra_du_kien);
        $ngay_qua_han = max(0, floor($diff / (60 * 60 * 24)));
        
        // Tính tổng tiền: (số ngày thuê dự kiến + ngày quá hạn) * đơn giá
        $ngay_thue_du_kien = $hoadon['ngay_thue_du_kien'] ?? 0;
        $don_gia = $hoadon['giathue'] ?? 0;
        $tong_tien = ($ngay_thue_du_kien + $ngay_qua_han) * $don_gia;
        
        return [
            'ngay_qua_han' => $ngay_qua_han,
            'tong_tien' => $tong_tien
        ];
    }
    
    /**
     * Lấy chi tiết hóa đơn với tính toán nghiệp vụ
     */
    public function getChiTietHoaDonVoiTinhToan($idhoadon) {
        $hoadon = $this->hoadonModel->getChiTietHoaDon($idhoadon);
        
        if (!$hoadon) {
            return null;
        }
        
        $tinh_toan = $this->tinhTongTienVaNgayQuaHan($hoadon);
        
        return array_merge($hoadon, $tinh_toan);
    }
    
    /**
     * Xác nhận thanh toán (gói gọi nghiệp vụ)
     */
    public function xacNhanThanhToan($idhoadon, $phuongthuc) {
        // Lấy chi tiết với tính toán
        $hoadon = $this->getChiTietHoaDonVoiTinhToan($idhoadon);
        
        if (!$hoadon) {
            return false;
        }
        
        // Gọi model để lưu vào database
        return $this->hoadonModel->xacNhanTraXe(
            $idhoadon,
            $hoadon['ngay_qua_han'],
            $hoadon['tong_tien'],
            $phuongthuc
        );
    }
    
    /**
     * Lấy danh sách hóa đơn chưa thanh toán
     */
    public function getHoaDonChuaThanhToan() {
        return $this->hoadonModel->getHoaDonChuaThanhToan();
    }
}