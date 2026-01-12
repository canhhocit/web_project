<?php
require_once 'Model/DAO/ThongKeDAO.php';

class ThongKeModel {
    private $thongKeDAO;
    
    public function __construct($db) {
        $this->thongKeDAO = new ThongKeDAO($db);
    }
    
    public function checkPermission($idtaikhoan) {
        return $this->thongKeDAO->isChuXe($idtaikhoan);
    }
    
    public function getAllStatistics($idtaikhoan) {
        if (!$this->checkPermission($idtaikhoan)) {
            return [
                'success' => false,
                'error' => 'ACCESS_DENIED',
                'message' => 'Chỉ chủ xe mới có quyền xem thống kê'
            ];
        }
        
        try {
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
    
}
?>