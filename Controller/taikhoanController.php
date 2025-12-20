<?php
// if (!defined('ACCESS_HOPLE')) {
//     die('<script>
//         alert("Truy cập không hợp lệ!");
//         window.location="/web_project/index.php";
//     </script>');
// }
require_once __DIR__ . "/../config.php";
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
                    window.location='/web_project/View/taikhoan/register.php';
                    </script>";
                exit;
            }
            $taikhoan = new taikhoan(0, $username, $password);
            if ($this->dao->addTaiKhoan($taikhoan)) {
                //header("Location: index.php?controller=taikhoan&action=login");
                echo "<script>
                    alert('Đăng ký thành công!');
                    window.location='/web_project/View/taikhoan/login.php';
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

    public function logout()
    {
        session_unset();
        session_destroy();
        header("Location: /web_project/index.php");
        exit();
    }
    public function updateinforUser()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (!isset($_SESSION['idtaikhoan'])) {
                echo "<script>alert('Vui lòng đăng nhập!'); 
            window.location='/web_project/View/taikhoan/login.php';</script>";
                exit;
            }

            $idtaikhoan = $_SESSION['idtaikhoan'];
            $idthongtin = $_POST["idthongtin"] ?? 0;
            $hoten = trim($_POST["hoten"] ?? "");
            $sdt = trim($_POST["sdt"] ?? "");
            $email = trim($_POST["email"] ?? "");
            $cccd = trim($_POST["cccd"] ?? "");

            if ($hoten === "" || $sdt === "" || $email === "" || $cccd === "") {
                echo "<script>alert('Vui lòng điền đầy đủ thông tin!'); history.back();</script>";
                exit;
            }

            // old img
            $anhdaidien = "";
            $thongtinCu = $this->dao->getThongTinTaiKhoanbyID($idtaikhoan);
            if ($thongtinCu) {
                $anhdaidien = $thongtinCu->get_anhdaidien();
            }

            // update img
            if (isset($_FILES['anhdaidien']) && $_FILES['anhdaidien']['error'] === 0) {
                // del old img
                if ($anhdaidien != "") {
                    $oldImagePath = __DIR__ . "/../View/image/" . $anhdaidien;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath); //del
                    }
                }

                // new img
                $filename = $_FILES["anhdaidien"]["name"];
                $tmpname = $_FILES["anhdaidien"]["tmp_name"];
                $path = __DIR__ . "/../View/image/" . $filename;
                move_uploaded_file($tmpname, $path);
                $anhdaidien = $filename;
            }

            $thongtin = new thongtintaikhoan($idthongtin, $idtaikhoan, $hoten, $sdt, $email, $cccd, $anhdaidien);

            try {
                if ($idthongtin > 0) {
                    if ($this->dao->updateThongTinTaiKhoan($thongtin)) {
                        echo "<script>alert('Cập nhật thành công!'); window.location='/web_project/index.php';</script>";
                    } else {
                        echo "<script>alert('Cập nhật thất bại!'); history.back();</script>";
                    }
                } else {
                    if ($this->dao->addThongTinTaiKhoan($thongtin)) {
                        echo "<script>alert('Thêm thông tin thành công!'); window.location='/web_project/index.php';</script>";
                    } else {
                        echo "<script>alert('Thêm thất bại!'); history.back();</script>";
                    }
                }
            } catch (Exception $e) {
                echo "<script>alert('Lỗi: " . $e->getMessage() . "'); history.back();</script>";
            }
            exit;
        }
    }
    public function deleteAccount()
    {
        if (!isset($_SESSION['idtaikhoan'])) {
            echo "<script>alert('Vui lòng đăng nhập!'); window.location='/web_project/View/taikhoan/login.php';</script>";
            exit;
        }

        $idtaikhoan = $_SESSION['idtaikhoan'];

        $thongtin = $this->dao->getThongTinTaiKhoanbyID($idtaikhoan);
        if ($thongtin) {
            $anhdaidien = $thongtin->get_anhdaidien();
            if ($anhdaidien != "") {
                $imagePath = __DIR__ . "/../View/image/" . $anhdaidien;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }

        try {
            if ($this->dao->deleteTaikhoan($idtaikhoan)) {
                session_unset();
                session_destroy();
                echo "<script>alert('Xóa tài khoản thành công!'); window.location='/web_project/index.php';</script>";
            } else {
                echo "<script>alert('Xóa tài khoản thất bại!'); history.back();</script>";
            }
        } catch (Exception $e) {
            echo "<script>alert('Lỗi: " . $e->getMessage() . "'); history.back();</script>";
        }
        exit;
    }

    public function changePassword()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (!isset($_SESSION['idtaikhoan'])) {
                echo "<script>alert('Vui lòng đăng nhập!'); window.location='/web_project/View/taikhoan/login.php';</script>";
                exit;
            }

            $idtaikhoan = $_SESSION['idtaikhoan'];
            $oldpass = trim($_POST["oldpass"] ?? "");
            $newpass = trim($_POST["newpass"] ?? "");
            $confnewpass = trim($_POST["confnewpass"] ?? "");

            // Validate
            if ($oldpass === "" || $newpass === "" || $confnewpass === "") {
                echo "<script>alert('Vui lòng điền đầy đủ thông tin!'); history.back();</script>";
                exit;
            }

            if ($newpass !== $confnewpass) {
                echo "<script>alert('Mật khẩu mới không khớp!'); history.back();</script>";
                exit;
            }
            if ($newpass === $oldpass) {
                echo "<script>alert('Mật khẩu mới và cũ phải khác nhau!'); history.back();</script>";
                exit;
            }

            // if (strlen($newpass) < 6) {
            //     echo "<script>alert('Mật khẩu mới phải có ít nhất 6 ký tự!'); history.back();</script>";
            //     exit;
            // }

            if (!$this->dao->checkOldPassword($idtaikhoan, $oldpass)) {
                echo "<script>alert('Mật khẩu cũ không đúng!'); history.back();</script>";
                exit;
            }

            try {
                if ($this->dao->updatePassword($idtaikhoan, $newpass)) {
                    echo "<script>alert('Đổi mật khẩu thành công!'); window.location='/web_project/index.php';</script>";
                } else {
                    echo "<script>alert('Đổi mật khẩu thất bại!'); history.back();</script>";
                }
            } catch (Exception $e) {
                echo "<script>alert('Lỗi: " . $e->getMessage() . "'); history.back();</script>";
            }
            exit;
        }
    }
      public function personal(){
        if (!isset($_SESSION['idtaikhoan'])) {
            echo "<script>alert('Vui lòng đăng nhập!'); 
            window.location='/web_project/View/taikhoan/login.php';</script>";
            exit;
        }
        include_once __DIR__ . "/../View/taikhoan/personal.php";
    }
}
