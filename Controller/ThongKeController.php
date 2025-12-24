<?php
require_once 'Model/hd_tk/ThongKeModel.php';
require_once 'Model/DAO/taikhoanDAO.php';

class ThongKeController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new ThongKeModel($db);
    }

    public function index() {

    $idtaikhoan = $_SESSION['idtaikhoan'];
    $sql = "SELECT lachuxe, languoithue FROM taikhoan WHERE idtaikhoan = '$idtaikhoan'";
    $result = mysqli_query($this->db, $sql);
    $userRole = mysqli_fetch_assoc($result);
        
    if (!$userRole || $userRole['lachuxe'] != 1) {
            echo "<div class='container mt-5'>
                    <div class='alert alert-danger shadow-sm border-start border-danger border-5'>
                        <h4 class='alert-heading'><i class='fas fa-lock me-2'></i>Quyền truy cập bị hạn chế</h4>
                        <p>Trang thống kê này <strong>chỉ dành cho Chủ xe</strong> để quản lý doanh thu.</p>
                        <hr>
                        <p class='mb-0'>Khách thuê vui lòng quay lại <a href='index.php' class='alert-link'>Trang chủ</a>.</p>
                    </div>
                  </div>";
            exit();
    }

    $data = [
            'tongDoanhThu' => $this->model->getTongDoanhThu($idtaikhoan),
            'doanhThuThang' => $this->model->getDoanhThuThang($idtaikhoan),
            'tyLeLoaiXe' => $this->model->getTyLeLoaiXe($idtaikhoan),
            'doanhThuCacThang' => $this->model->getDoanhThuCacThang($idtaikhoan),
            'statsPhu' => $this->model->getStatsPhu($idtaikhoan)
        ];
        
        extract($data);
        require 'View/thongke/indexTK.php';
    }
}
?>