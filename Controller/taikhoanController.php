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
                return;
            }
            if ($password !== $confpassword) {
                return;
            }
            if ($this->dao->checkExist($username)) {
                echo "<script>
                    alert('Tên đăng nhập này đã tồn tại!');
                    window.location='/web_project/View/taikhoan/register.html';
                    </script>";
                exit;
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
            }
        }
    }

    public function checklogin()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {


            $username = $_POST["username"];
            $password = $_POST["password"];

            if ($this->dao->checkLogin($username, $password)) {
                $id = $this->dao->getId($username);
                $_SESSION["idtaikhoan"] = $id;
                header("Location: /web_project/index.php?status=1");
                exit;
            } else {
                echo "<script>alert('Sai tài khoản hoặc mật khẩu');history.back();</script>";
                exit;
            }
        }
    }
    //xóa sesion
    public function logout()
    {
        session_unset();
        session_destroy();
        header("Location: /web_project/index.php");
        exit();

    }

}