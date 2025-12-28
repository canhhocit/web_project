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
require_once __DIR__ . "/../Model/DAO/vehicleDAO.php";
require_once __DIR__ . "/../Model/DAO/favoriteVehicleDAO.php";
require_once __DIR__ . "/../Model/Object/taikhoan.php";
require_once __DIR__ . "/../Model/Object/anhxe.php";
require_once __DIR__ . "/../Model/Object/xe.php";
require_once __DIR__ . "/../Model/Object/thongtintaikhoan.php";


class taikhoanController
{
    private $Adao;
    private $Vdao;
    private $Fdao;
    public function __construct()
    {
        global $conn;
        $this->Adao = new taikhoanDAO($conn);
        $this->Vdao = new vehicleDAO($conn);
        $this->Fdao = new favoriteVehicleDAO($conn);
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
            if ($this->Adao->checkExist($username)) {
                echo "<script>
                    alert('Tên đăng nhập này đã tồn tại!');
                    window.location='/web_project/View/taikhoan/register.php';
                    </script>";
                exit;
            }
            $taikhoan = new taikhoan(0, $username, $password);
            if ($this->Adao->addTaiKhoan($taikhoan)) {
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

            if ($this->Adao->checkLogin($username, $password)) {
                $idtaikhoan = $this->Adao->getIdtaikhoan($username);
                $_SESSION["idtaikhoan"] = $idtaikhoan;
                //insert dulieu mau
                if (!$this->Adao->checkIDtaikhoaninThongtin($idtaikhoan)) {
                    $anhdaidien = "default-avt.jpg";
                    $kq = $this->Adao->addThongTinTaiKhoan($idtaikhoan, $anhdaidien);
                    if (!$kq) {
                        echo "<script>alert('Lỗi tkController dòng 86!'); 
        window.location='/web_project/View/taikhoan/login.php';</script>";
                        exit;
                    }
                }
                //
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
            $idthongtin = isset($_POST["idthongtin"]) ? (int)$_POST["idthongtin"] : 0;
            $hoten = trim($_POST["hoten"] ?? "");
            $sdt = trim($_POST["sdt"] ?? "");
            $email = trim($_POST["email"] ?? "");
            $cccd = trim($_POST["cccd"] ?? "");

            if ($hoten === "" || $sdt === "" || $email === "" || $cccd === "") {
                echo "<script>alert('Vui lòng điền đầy đủ thông tin!'); history.back();</script>";
                exit;
            }

            $anhdaidien = "default-avt.jpg";

            $thongtinCu = $this->Adao->getThongTinTaiKhoanbyID($idtaikhoan);
            if ($thongtinCu && $thongtinCu->get_anhdaidien() != "") {
                // if k co anh ->default-img
                $anhdaidien = $thongtinCu->get_anhdaidien();
            }

            if (isset($_FILES['anhdaidien']) && $_FILES['anhdaidien']['error'] === UPLOAD_ERR_OK) {
                //xoa anh cu tru mac dinh
                if ($anhdaidien != "default-avt.jpg") {
                    $oldImagePath = __DIR__ . "/../View/image/" . $anhdaidien;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $filename = time() . "_" . basename($_FILES["anhdaidien"]["name"]);
                $tmpname = $_FILES["anhdaidien"]["tmp_name"];
                $path = __DIR__ . "/../View/image/" . $filename;

                if (move_uploaded_file($tmpname, $path)) {
                    $anhdaidien = $filename;
                } else {
                    echo "<script>alert('Upload ảnh thất bại vào thư mục!'); history.back();</script>";
                    exit;
                }
            }

            $thongtin = new thongtintaikhoan($idthongtin, $idtaikhoan, $hoten, $sdt, $email, $cccd, $anhdaidien);
            //var_dump($thongtin);
            // echo '<pre>';
            // print_r($thongtin);
            // echo '</pre>';
            try {
                $daCoThongTin = $this->Adao->getThongTinTaiKhoanbyID($idtaikhoan);

                if ($daCoThongTin) {
                    if ($this->Adao->updateThongTinTaiKhoan($thongtin)) {
                        echo "<script>alert('Cập nhật thành công!'); window.location='/web_project/index.php?controller=taikhoan&action=personal';</script>";
                    } else {
                        echo "<script>alert('Cập nhật thất bại!'); history.back();</script>";
                    }
                } else {
                    // if ($this->Adao->addThongTinTaiKhoan($thongtin)) {
                    //     header("Location: /web_project/index.php");
                    //     exit;
                    // } else {
                    echo "<script>alert('Update thất bại!'); history.back();</script>";
                    //}
                }
            } catch (Exception $e) {
                echo "<script>alert('Lỗi: " . $e->getMessage() . "'); history.back();</script>";
            }
            exit;
        }
    }
    public function deleteAvatar()
    {
        if (!isset($_SESSION['idtaikhoan'])) {
                echo "<script>alert('Vui lòng đăng nhập!'); window.location='/web_project/View/taikhoan/login.php';</script>";
                exit;
            }

            $idtaikhoan = $_SESSION['idtaikhoan'];
        if($this->Adao->delAvatar($idtaikhoan)){
             echo "<script>alert('Xóa ảnh thành công!');
              window.location='/web_project/index.php?controller=taikhoan&action=personal';</script>";
            exit;
        }
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

            if (!$this->Adao->checkOldPassword($idtaikhoan, $oldpass)) {
                echo "<script>alert('Mật khẩu cũ không đúng!'); history.back();</script>";
                exit;
            }

            try {
                if ($this->Adao->updatePassword($idtaikhoan, $newpass)) {
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
    public function favoriteVehicle()
    {
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            if (!isset($_SESSION['idtaikhoan'])) {
                echo "<script>alert('Vui lòng đăng nhập!'); 
                window.location='/web_project/View/taikhoan/login.php';</script>";
                exit;
            }

            if (!isset($_GET['id'])) {
                echo "<script>alert('Không lấy được idxe ồi!'); 
                history.back();</script>";
                exit;
            }

            $idtaikhoan = $_SESSION['idtaikhoan'];
            $idxe = $_GET['id'];

            if ($this->Fdao->checkExistsVehicle($idtaikhoan, $idxe)) {
                if ($this->Fdao->delFavorite($idtaikhoan, $idxe)) {
                    echo "<script> alert('Đã xóa khỏi yêu thích!');</script>";
                    if (isset($_GET['option'])) { //link ben favorite
                        echo "<script>
                    window.location='/web_project/index.php?controller=taikhoan&action=personal&selection=favorite';
                    </script>";
                    }
                    echo "<script>
                    window.location='/web_project/index.php?controller=car&action=detail&id=$idxe';
                    </script>";
                } else {
                    echo "<script>alert('Lỗi delF!'); 
                history.back();</script>";
                    exit;
                }
            } else {
                if ($this->Fdao->addFavorite($idtaikhoan, $idxe)) {
                    echo " <script>alert('Đã thêm vào yêu thích!'); </script>";
                    if (isset($_GET['option'])) {
                        echo "<script>
                    window.location='/web_project/index.php?controller=taikhoan&action=personal&selection=favorite';
                    </script>";
                    }
                    echo "<script>
                    window.location='/web_project/index.php?controller=car&action=detail&id=$idxe';
                    </script>";
                } else {
                    echo "<script>alert('Lỗi addF!'); 
                history.back();</script>";
                    exit;
                }
            }
        }
    }

    public function personal()
    {
        if (!isset($_SESSION['idtaikhoan'])) {
            echo "<script>alert('Vui lòng đăng nhập!'); 
        window.location='/web_project/View/taikhoan/login.php';</script>";
            exit;
        }

        $idtaikhoan = $_SESSION['idtaikhoan'];
        $thongtin = $this->Adao->getThongTinTaiKhoanbyID($idtaikhoan);
        $defaultAvatar= $this->Adao->checkdefaultAvatar($idtaikhoan);

        //  selection = myvehicle
        $xeWithImages = [];
        if (isset($_GET['selection']) && $_GET['selection'] === 'myvehicle') {
            $danhsachxe = $this->Vdao->getXebyIdChuxe($idtaikhoan);

            foreach ($danhsachxe as $xe) {
                $idxe = $xe->get_idxe();
                $anhxe = $this->Vdao->getAnhxebyIdxe($idxe);
                $trangthai = 'Chưa có người thuê';
                $status = false;
                if ($this->Vdao->checktrangthaithue($idxe)) {
                    $trangthai = 'Đã được thuê';
                    $status = true;
                }
                $xeWithImages[] = [
                    'xe' => $xe,
                    'images' => $anhxe,
                    'trangthai' => $trangthai,
                    'status' => $status
                ];
            }
        }
        //selection = favorite
        $favotiteCars = [];
        if (isset($_GET['selection']) && $_GET['selection'] === 'favorite') {
            $listFavorite = $this->Vdao->getFavoritebyIdChuxe($idtaikhoan);

            foreach ($listFavorite as $xe) {
                $idxe = $xe->get_idxe();
                $imgs = $this->Vdao->getAnhxebyIdxe($idxe);
                $favotiteCars[] = [
                    'xe' => $xe,
                    'images' => $imgs
                ];
            }
        }



        include_once __DIR__ . "/../View/taikhoan/personal.php";
    }
}
