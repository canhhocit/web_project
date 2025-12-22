<?php
// if (!defined('ACCESS_HOPLE')) {
//     die('<script>
//         alert("Truy cập không hợp lệ!");
//         window.location="/web_project/index.php";
//     </script>');
// }
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../Model/Database/dbconnect.php";
require_once __DIR__ . "/../Model/DAO/vehicleDAO.php";
require_once __DIR__ . "/../Model/Object/xe.php";
require_once __DIR__ . "/../Model/Object/anhxe.php";
require_once __DIR__ . "/../Model/DAO/taikhoanDAO.php";


class vehicleController
{
    private $Vdao;
    private $Acdao;

    public function __construct()
    {
        global $conn;
        $this->Vdao = new vehicleDAO($conn);
        $this->Acdao = new taikhoanDAO($conn);
    }

    public function index()
    {
        //$vehicles = $this->dao->getAll();
        //include_once "../View/vehicle/index.php";
    }
    public function checkLogin()
    {
        // check login
        if (!isset($_SESSION['idtaikhoan'])) {
            echo "<script>
            alert('Vui lòng đăng nhập để đăng xe!');
            window.location='/web_project/View/taikhoan/login.php';
        </script>";
            exit;
        }

        // check infor
        $idtaikhoan = $_SESSION['idtaikhoan'];
        $thongtin = $this->Acdao->getThongTinTaiKhoanbyID($idtaikhoan);

        if (!$thongtin || $thongtin === null) {
            echo "<script>
            alert('Vui lòng cập nhật thông tin cá nhân trước khi đăng xe!');
            window.location='/web_project/index.php';
        </script>";
            exit;
        }

        echo "<script>
            window.location='/web_project/View/xe/addVehicle.php';
        </script>";
    }
    public function add()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (!isset($_SESSION['idtaikhoan'])) {
                echo "<script>alert('Vui lòng đăng nhập!'); window.location='/web_project/View/taikhoan/login.php';</script>";
                exit;
            }

            $idchuxe = $_SESSION['idtaikhoan'];
            $tenxe = trim($_POST["tenxe"] ?? "");
            $hangxe = trim($_POST["hangxe"] ?? ""); 
            $loaixe = $_POST["loaixe"] ?? "";
            $giathue = $_POST["giathue"] ?? 0;
            $mota = trim($_POST["mota"] ?? "");

            // Validate
            if ($tenxe === "" || $hangxe === "" || $loaixe === "" || $giathue <= 0) {
                echo "<script>alert('Vui lòng điền đầy đủ thông tin!'); history.back();</script>";
                exit;
            }

            $xe = new xe(0, $tenxe, $hangxe, $giathue, $mota, $loaixe, $idchuxe);

            try {
                if ($this->Vdao->addXe($xe)) {
                    // Lấy ID xe vừa thêm
                    $idxe = mysqli_insert_id($this->Vdao->conn);

                    // img 
                    if (isset($_FILES['anhxe']) && !empty($_FILES['anhxe']['name'][0])) {
                        $uploadPath = __DIR__ . "/../View/image/";

                        foreach ($_FILES['anhxe']['tmp_name'] as $key => $tmpname) {
                            if ($_FILES['anhxe']['error'][$key] === 0) {
                                $filename = $_FILES['anhxe']['name'][$key];
                                $path = $uploadPath . $filename;

                                if (move_uploaded_file($tmpname, $path)) {
                                    // save img to db
                                    $anhxe = new anhxe(0, $idxe, $filename);
                                    $this->Vdao->addAnhxe($anhxe);
                                }
                            }
                        }
                    }

                    // is chuxe =1
                    $this->Acdao->updateisChuxe($idchuxe);
                    echo "<script>alert('Đăng xe thành công!'); window.location='/web_project/index.php';</script>";
                } else {
                    echo "<script>alert('Đăng xe thất bại!'); history.back();</script>";
                }
            } catch (Exception $e) {
                echo "<script>alert('Lỗi: " . $e->getMessage() . "'); history.back();</script>";
            }
            exit;
        }
    }
}
