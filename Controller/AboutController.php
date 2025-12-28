<?php
    require_once __DIR__ . "/../Model/DAO/thanhvienDAO.php";
    require_once "Model/Database/dbconnect.php";
    require_once __DIR__ . "/../Model/Object/ThanhVien.php";

    class AboutController{
        private $dao; // lưu các object để dùng trong class này
        public function __construct() // hàm tự động chạy khi new đối tượng
        {
            global $conn;
            $this->dao = new thanhvienDAO($conn); // taoj 1 object mới lưu vào this-dao để dùng cho các hàm khác 
            // vd có thể dùng this->dao->getTV();
        }

        public function index(){
            $listMember = $this->dao->getTV(); // kết quả sexc trả về mảng vì hàm getTV return list;
            include "View/About/index.php";
        }
        public function addWork(){
            if ($_SERVER['REQUEST_METHOD'] === 'POST'){ // REQUEST_METHOD cho biết cách dữ liệu được gửi lên server.
                //GET → gửi dữ liệu qua URL
                //POST → gửi dữ liệu ẩn, thường dùng cho form, đăng nhập, thêm dữ liệu
                $ten = $_POST['ten_thanhvien'] ?? '';
                $mssv = $_POST['mssv'] ?? '';
                $congviec = $_POST['cong_viec'] ?? '';
                if (!empty($ten) && !empty($congviec)){
                    $this->dao->addTV($ten, $mssv, $congviec); // Thêm thành viên mới vào database
                    echo "<script>alert('Đã cập nhật công ');window.location='index.php?controller=about';</script>";
                    // hiển thị thông báo và chuyển hướng về trang about khi click 
                }else {
                    echo "<script>alert('Vui lòng nhập đủ thông tin');history.back();</script>";
                    //hiển thị lỗi và quay lại form nhập 
                }
            }
        }

    }
?>


/**
     LUỒNG HOẠT ĐỘNG (FLOW)

User truy cập:
index.php?controller=about
        ↓
index.php đọc $_GET['controller']
        ↓
index.php chạy:
$about = new AboutController();
        ↓
PHP tự động gọi:
__construct()
        ↓
$this->dao = new ThanhVienDAO($conn);
        ↓
index.php gọi:
$about->index();
        ↓
index() gọi DAO + include View

*/