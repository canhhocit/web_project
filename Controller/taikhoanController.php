<?php
require_once __DIR__ . "/../Model/Database/dbconnect.php";
require_once __DIR__ . "/../Model/DAO/taikhoanDAO.php";
require_once __DIR__ . "/../Model/Object/taikhoan.php";
require_once __DIR__ . "/../Model/Object/thongtintaikhoan.php";


class taikhoanController
{
    private $dao;

    public function __construct()
    {
        global $conn;
        $this->dao = new taikhoanDAO($conn);
    }

    public function index()
    {
        //$vehicles = $this->dao->getAll();
        include_once "../View/vehicle/index.php";
    }
    public function add()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $username = trim($_POST["username"] ?? "");
            $password = trim($_POST["password"] ?? "");
            $confpassword = trim($_POST["confpassword"] ?? "");
            if ($username === "" || $password === "" || $confpassword === "") {
                echo "Thiếu thông tin!";
                return;
            }
            if ($password !== $confpassword) {
                echo "Mật khẩu không khớp!";
                return;
            }
            $taikhoan = new taikhoan(0, $username, $password);

            if ($this->dao->addTaiKhoan($taikhoan)) {
                //header("Location: index.php?controller=taikhoan&action=login");
                echo "<script>
                    alert('Đăng ký thành công!');
                    window.location='/web_project/View/taikhoan/login.html';
                    </script>";
                exit;
            } else {
                echo "<script>
                    alert('Đăng ký thất bại!');
                    </script>";
                echo "Đăng ký thất bại!";
            }
        }
    }

}