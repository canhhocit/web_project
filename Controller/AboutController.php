<?php
require_once "Model/Database/dbconnect.php";
require_once "Model/DAO/ThanhVienDAO.php";

class AboutController {
    private $dao;

    public function __construct() {
        global $conn;
        $this->dao = new ThanhVienDAO($conn);
    }

    public function index() {
        $listMember = $this->dao->getTV();
        include "View/about/index.php";
    }

    public function addWork() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ten = $_POST['ten_thanhvien'] ?? '';
            $mssv = $_POST['mssv'] ?? '';
            $congviec = $_POST['cong_viec'] ?? '';

            if (!empty($ten) && !empty($congviec)) {
                $this->dao->addTV($ten, $mssv, $congviec);
                echo "<script>alert('Đã cập nhật công việc thành công!'); window.location='index.php?controller=about';</script>";
            } else {
                echo "<script>alert('Vui lòng nhập tên và công việc!'); history.back();</script>";
            }
        }
    }
}
?>