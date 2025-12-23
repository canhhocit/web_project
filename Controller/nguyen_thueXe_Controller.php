<?php

header('Content-Type: application/json; charset=utf-8');
$raw = file_get_contents("php://input");
$data_post = json_decode($raw, true);
$action = $data_post['action'] ?? '';

require_once __DIR__ . "/../Model/DAO/nguyen_hoadonDAO.php";
require_once __DIR__ . "/../Model/Object/nguyen_hoadon.php";
require_once __DIR__ . "/../Model/DAO/vehicleDAO.php";
require_once __DIR__ . "/../Model/Object/xe.php";
require_once __DIR__ . "/../Model/DAO/taikhoanDAO.php";
    
class nguyen_thueXe_Controller
{
    private $vehicle_dao;
    private $hoadon_dao;
    private $user_dao;

    public function __construct()
    {
        global $conn;
        $this->vehicle_dao = new vehicleDAO($conn);
        $this->hoadon_dao = new nguyen_hoadonDAO($conn);
        $this->user_dao = new taikhoanDAO($conn);
    }
    public function handleRequest($action, $data_post)
    {
        switch ($action) {
            case 'openModal':
                $xe = $this->layxe($data_post['id'] ?? 0);
                $xe_arr = [];

                // lấy ảnh để trả về bên kia
                // $anhxe = $this->layanhxe($data_post['id'] ?? 0);
                // $anhxe_arr = [];
                // foreach ($anhxe as $item) {
                //     $anhxe_arr[] = [
                //         "id"     => $item->get_idanh(),
                //         "idxe"   => $item->get_idxe(),
                //         "link"   => $item->get_duongdan()
                //     ];
                // }

                foreach ($xe as $item) {
                    $xe_arr[] = [
                        "id"     => $item->get_idxe(),
                        "name"   => $item->get_tenxe(),
                        "brand"  => $item->get_hangxe(),
                        "price"  => (int)$item->get_giathue(),
                        "desc"   => $item->get_mota(),
                        "type"   => $item->get_loaixe(),
                        "owner"  => $item->get_idchuxe()
                    ];
                }

                echo json_encode([
                    "status" => "success",
                    "xe" => $xe_arr[0] // chỉ 1 mà thôi
                    // "anhxe" => $anhxe_arr[0]
                ]);
                exit;
            case 'taohoadon': 
                $data = $data_post['data'] ?? [];

                $hoadon = new nguyen_hoadon(
                    null,
                    $data['idtaikhoan'] ?? 0,
                    $data['xeId'] ?? 0,
                    $data['pickupLocation'] ?? '',
                    $data['dropoffLocation'] ?? '',
                    $data['pickupDate'] ?? '',
                    $data['returnDate'] ?? '',
                    $data['fullName'] ?? '',
                    $data['email'] ?? '',
                    $data['phone'] ?? '',
                    $data['cccd'] ?? '',
                    0,
                    $data['comment'] ?? '',
                    $data['totalCost'] ?? 0
                );
                $result = $this->hoadon_dao->insert($hoadon);

                echo json_encode([
                    "success" => true,
                    "received" => $result
                ]);
                exit;
            case 'layhoadon':
                return $this->layhoadon($data_post);

                exit;
            case 'taohoadon':

                return $this->taohoadon($data_post);

                exit;
            case 'laythongtinnguoidung':
                // $user = $this->laythongtinnguoidung($data_post['idtaikhoan'] ?? 0);

                //$user_info = [];
                // foreach ($user as $item) {
                //     $user_info[] = [
                //         "idtaikhoan"     => $item->get_idtaikhoan(),
                //         "username"   => $item->get_username(),
                //         "pass"  => $item->get_pass()
                //     ];
                // }
                // echo json_encode([
                //     "status" => "success",
                //     "user_info" => $user_info[0]
                // ]);
                exit;
            
            default:
                http_response_code(400);
                echo json_encode(["error" => "Hành động không hợp lệ"]);
                exit;
        }
    }

    function layxe($idxe){
        if ($idxe <= 0) {
            http_response_code(400);
            echo json_encode(["error" => "ID xe không hợp lệ"]);
            exit;
        }
        $xe = $this->vehicle_dao->getXebyIdxe($idxe);
        return $xe;
    }

    function layanhxe($idxe){
        if ($idxe <= 0) {
            http_response_code(400);
            echo json_encode(["error" => "ID xe không hợp lệ"]);
            exit;
        }
        $anhxe = $this->vehicle_dao->getAnhxebyIdxe($idxe);
        return $anhxe;
    }

    function layhoadon(){
        $idtaikhoan = $_SESSION['idtaikhoan'] ?? 0;
        if ($idtaikhoan <= 0) {
            http_response_code(400);
            echo json_encode(["error" => "ID tài khoản không hợp lệ"]);
            exit;
        }
        // $hoadon = $this->hoadon_dao->getHoaDonbyIdtaikhoan($idtaikhoan);
        // return $hoadon;
    }

    function taohoadon($data_post){
        // $idtaikhoan = $_SESSION['idtaikhoan'] ?? 0;
        if ($data_post['idtaikhoan'] <= 0) {
            http_response_code(400);
            echo json_encode(["error" => "ID tài khoản không hợp lệ"]);
            exit;
        }
        $idtaikhoan = $data_post['idtaikhoan'];
        $idxe = $data_post['idxe'];
        $diemlay = $data_post['diemlay'];
        $diemtra = $data_post['diemtra'];
        $ngaymuon = $data_post['ngaymuon'];
        $ngaytra = $data_post['ngaytra'];
        $trangthai = $data_post['trangthai'];
        $ghichu = $data_post['ghichu'];
        $tongtien = $data_post['tongtien'];

        
        // $hoadon = $this->hoadon_dao->getHoaDonbyIdtaikhoan($idtaikhoan);
        // $hoadon = new nguyen_hoadon();
        // return $hoadon;
    }
    function laythongtinnguoidung($data_post){
        // $idtaikhoan = $_SESSION['idtaikhoan'] ?? 0; cho sang bên kia đê
        if ($data_post['idtaikhoan'] <= 0) {
            http_response_code(400);
            echo json_encode(["error" => "ID tài khoản không hợp lệ"]);
            exit;
        }
        // getThongTinTaiKhoanbyID
        // $user_info = $this->user_dao->getThongTinTaiKhoanbyID($idtaikhoan);
        // return $user_info;
    }
}

$controller = new nguyen_thueXe_Controller();
$controller->handleRequest($action, $data_post);
?>