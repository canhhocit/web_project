<?php
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
            alert('Vui lòng đăng nhập trước!');
            window.location='/web_project/View/taikhoan/login.php';
        </script>";
            exit;
        }

        // check infor
        $idtaikhoan = $_SESSION['idtaikhoan'];
        if ($this->Acdao->checkThongtinTK_isNULL($idtaikhoan)) {
            echo "<script>
            alert('Vui lòng cập nhật thông tin cá nhân trước khi đăng xe!');
            window.location='/web_project/index.php?controller=taikhoan&action=personal';
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
            $xe = new xe(0, $tenxe, $hangxe, $giathue, $mota, $loaixe, $idchuxe, 1);
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
    

    public function editV()
    {

        $idxe = $_GET['id'] ?? 0;
        $idchuxe = $_SESSION['idtaikhoan'];
        if ($idxe == 0) {
            echo "<script>alert('Không lấy được id'); history.back();</script>";
            exit;
        }


        $xeList = $this->Vdao->getXebyIdxe($idxe); // do tận dụng getAll, but now chỉ có 1
        if (empty($xeList) || $xeList[0]->get_idchuxe() != $idchuxe) {
            echo "<script>alert('Không có quyền sửa xe này!'); history.back();</script>";
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            return [
                'xe' => $xeList[0],
                'anhxe' => $this->Vdao->getAnhxebyIdxe($idxe)
            ];
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $idxe = trim($_POST["idxe"] ?? "");
            $tenxe = trim($_POST["tenxe"] ?? "");
            $hangxe = trim($_POST["hangxe"] ?? "");
            $loaixe = $_POST["loaixe"] ?? "";
            $giathue = $_POST["giathue"] ?? 0;
            $mota = trim($_POST["mota"] ?? "");

            if ($tenxe === "" || $hangxe === "" || $loaixe === "" || $giathue <= 0) {
                echo "<script>alert('Vui lòng điền đầy đủ thông tin!'); history.back();</script>";
                exit;
            }

            $xe = new xe($idxe, $tenxe, $hangxe, $giathue, $mota, $loaixe, $idchuxe, 1);

            try {
                if (!$this->Vdao->updateXe($xe)) {
                    throw new Exception("Cập nhật thông tin xe thất bại!");
                }

                if (isset($_FILES['anhxe']) && !empty($_FILES['anhxe']['name'][0])) {
                    $oldImages = $this->Vdao->getAnhxebyIdxe($idxe);
                    foreach ($oldImages as $img) {
                        $imagePath = __DIR__ . "/../View/image/" . $img->get_duongdan();
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }
                    $this->Vdao->deleteAnhxebyidXe($idxe);

                    $uploadPath = __DIR__ . "/../View/image/";
                    foreach ($_FILES['anhxe']['tmp_name'] as $key => $tmpname) {
                        if ($_FILES['anhxe']['error'][$key] === 0) {
                            $filename = $_FILES['anhxe']['name'][$key];
                            $path = $uploadPath . $filename;
                            if (move_uploaded_file($tmpname, $path)) {
                                $anhxe = new anhxe(0, $idxe, $filename);
                                $this->Vdao->addAnhxe($anhxe);
                            }
                        }
                    }
                }

                echo "<script>alert('Cập nhật xe thành công!'); window.location='/web_project/index.php?controller=taikhoan&action=personal&selection=myvehicle';</script>";
            } catch (Exception $e) {
                echo "<script>alert('Lỗi: " . $e->getMessage() . "'); history.back();</script>";
            }
            exit;
        }
    }

    public function deleteV()
    {

        $idxe = $_GET['id'] ?? 0;
        $idchuxe = $_SESSION['idtaikhoan'];
        if ($idxe == 0) {
            echo "<script>alert('Không lấy được id'); history.back();</script>";
            exit;
        }

        //sẽ check ở đây

        try {
            $listImgV = $this->Vdao->getAnhxebyIdxe($idxe);
            foreach ($listImgV as $anhxe) {
                $imagePath = __DIR__ . "/../View/image/" . $anhxe->get_duongdan();
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $this->Vdao->deleteAnhxebyIdXe($idxe);

            if ($this->Vdao->deleteXe($idxe)) {
                echo "<script>alert('Xóa thành công!'); window.location='/web_project/index.php?controller=taikhoan&action=personal&selection=myvehicle';</script>";
            } else {
                throw new Exception("Xóa xe thất bại!");
            }
        } catch (Exception $e) {
            echo "<script>alert('Lỗi: " . $e->getMessage() . "'); history.back();</script>";
        }
        exit;
    }
}
