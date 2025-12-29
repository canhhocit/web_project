<?php
require_once 'Model/DAO/ThongKeDAO.php';

class ThongKeModel {
    private $thongKeDAO;
    
    public function __construct($db) {
        $this->thongKeDAO = new ThongKeDAO($db);
    }
    
    // Kiểm tra quyền truy cập
    public function checkPermission($idtaikhoan) {
        return $this->thongKeDAO->isChuXe($idtaikhoan);
    }
    
    // Lấy role của user
    public function getUserRole($idtaikhoan) {
        return $this->thongKeDAO->getUserRole($idtaikhoan);
    }
    
    // Lấy tất cả thống kê
    public function getAllStatistics($idtaikhoan) {
        // Kiểm tra quyền
        if (!$this->checkPermission($idtaikhoan)) {
            return [
                'success' => false,
                'error' => 'ACCESS_DENIED',
                'message' => 'Chỉ chủ xe mới có quyền xem thống kê'
            ];
        }
        
        try {
            // Lấy tất cả dữ liệu thống kê
            $tongDoanhThu = $this->thongKeDAO->getTongDoanhThu($idtaikhoan);
            $doanhThuThang = $this->thongKeDAO->getDoanhThuThang($idtaikhoan);
            $tyLeLoaiXe = $this->thongKeDAO->getTyLeLoaiXe($idtaikhoan);
            $doanhThuCacThang = $this->thongKeDAO->getDoanhThuCacThang($idtaikhoan);
            $statsPhu = $this->thongKeDAO->getStatsPhu($idtaikhoan);
            
            return [
                'success' => true,
                'tongDoanhThu' => $tongDoanhThu,
                'doanhThuThang' => $doanhThuThang,
                'tyLeLoaiXe' => $tyLeLoaiXe,
                'doanhThuCacThang' => $doanhThuCacThang,
                'statsPhu' => $statsPhu
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'DATABASE_ERROR',
                'message' => 'Lỗi khi truy vấn dữ liệu: ' . $e->getMessage()
            ];
        }
    }
    
    // Các phương thức riêng lẻ nếu cần
    public function getTongDoanhThuData($idtaikhoan) {
        return $this->thongKeDAO->getTongDoanhThu($idtaikhoan);
    }
    
    public function getDoanhThuThangData($idtaikhoan) {
        return $this->thongKeDAO->getDoanhThuThang($idtaikhoan);
    }
    
    public function getTyLeLoaiXeData($idtaikhoan) {
        return $this->thongKeDAO->getTyLeLoaiXe($idtaikhoan);
    }
    
    public function getDoanhThuCacThangData($idtaikhoan) {
        return $this->thongKeDAO->getDoanhThuCacThang($idtaikhoan);
    }
    
    public function getStatsPhuData($idtaikhoan) {
        return $this->thongKeDAO->getStatsPhu($idtaikhoan);
    }
}
?>