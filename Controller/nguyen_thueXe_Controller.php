<?php
session_start();

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
                $curentUserID = isset($_SESSION['idtaikhoan']) ? $_SESSION['idtaikhoan'] : 0;
                $item_user_info = $this->laythongtinnguoidung($curentUserID);
                $user_info = [
                    "idtaikhoan"     => $item_user_info->get_idtaikhoan(),
                    "name"  => $item_user_info->get_hoten(),
                    "email" => $item_user_info->get_email(),
                    "phone" => $item_user_info->get_sdt(),
                    "cccd"  => $item_user_info->get_cccd()
                ];

                $xe = $this->layxe($data_post['id'] ?? 0);
                $xe_arr = [];

                $anhxe = $this->layanhxe($data_post['id'] ?? 0);
                $anhxe_arr = [];
                foreach ($anhxe as $item) {
                    $anhxe_arr[] = [
                        "id"     => $item->get_idanh(),
                        "idxe"   => $item->get_idxe(),
                        "duongdan"   => $item->get_duongdan()
                    ];
                }

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
                    "xe" => $xe_arr[0], // chỉ 1 mà thôi
                    "anhxe" => $anhxe_arr[0],
                    "curentUserID" => $curentUserID,
                    "infouser" => $user_info
                ]);
                exit;
            case 'taohoadon':
                $data = $data_post['data'] ?? [];

                $hoadon = new nguyen_hoadon(
                    null,
                    $data['idtaikhoan'] ?? 0,
                    $data['idxe'] ?? 0,

                    $data['diemlay'] ?? '',
                    $data['diemtra'] ?? '',
                    $data['ngaymuon'] ?? '',
                    $data['ngaytra'] ?? '',

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

                $item = $this->layhoadonbyid($data_post['idhoadon']);

                if (!$item) {
                    echo json_encode(["success" => false, "message" => "Không tìm thấy hóa đơn"]);
                    exit;
                }

                $hoadon_arr = [
                    "idhoadon"   => $item->get_idhoadon(),
                    "idtaikhoan" => $item->get_idtaikhoan(),
                    "idxe"       => $item->get_idxe(),
                    "diemlay"    => $item->get_diemlay(),
                    "diemtra"    => $item->get_diemtra(),
                    "ngaymuon"   => $item->get_ngaymuon(),
                    "ngaytra"    => $item->get_ngaytra(),
                    "hoten"      => $item->get_hoten(),
                    "email"      => $item->get_email(),
                    "sdt"        => $item->get_sdt(),
                    "cccd"       => $item->get_cccd(),
                    "trangthai"  => $item->get_trangthai(),
                    "ghichu"     => $item->get_ghichu(),
                    "tongtien"   => $item->get_tongtien()
                ];

                $anhxe = $this->layanhxe($item->get_idxe());
                $item_anhxe = $anhxe[0];

                $anhxe_arr = [
                    "id"     => $item_anhxe->get_idanh(),
                    "idxe"   => $item_anhxe->get_idxe(),
                    "duongdan"   => "/web_project/View/image/" . $item_anhxe->get_duongdan()
                ];

                $anhxe = $this->vehicle_dao->getAnhxebyIdxe($item->get_idxe());

                $xe = $this->layxe($item->get_idxe());
                $item_xe = $xe[0];

                $xe_arr = [
                    "giathue"     => $item_xe->get_giathue(),
                    "tenxe"     => $item_xe->get_tenxe(),
                    "loai"   => $item_xe->get_loaixe()
                ];

                $list = $this->hoadon_dao->getthanhtoanbyidhoadon($item->get_idhoadon());

                echo json_encode([
                    "success" => true,
                    "hoadon" => $hoadon_arr,
                    "anhxe" => $anhxe_arr,
                    "xe" => $xe_arr,
                    "thanhtoan" => $list
                ]);
                exit;
            case 'checkRented':
                $state = $this->hoadon_dao->isXeDangDuocThue($data_post['xeId'] ?? 0);
                echo json_encode([
                    "isRented" => $state
                ]);
                exit;
            case 'laythongtinnguoithue':
            
                $curentUserID = $_SESSION['idtaikhoan'] ?? 0;
                $thongtin = $this->getthongtinhoadonnguoithue($curentUserID);
            
                $result = [];
            
                foreach ($thongtin as $row) {
                    $idhoadon = $row['idhoadon'];
                    $idxe     = $row['idxe'];
                
                    $hd = $this->layhoadonbyid($idhoadon); // object nguyen_hoadon
                    $xeImages = $this->layanhxe($idxe);   // array object ảnh
                    $xe = $this->layxe($idxe);           //  array object xe

                    $anhxe_arr = [];
                    if (is_array($xeImages)) {
                        foreach ($xeImages as $img) {
                            $anhxe_arr[] = [
                                "id"       => (int)$img->get_idanh(),
                                "idxe"     => (int)$img->get_idxe(),
                                "duongdan" => $img->get_duongdan(),
                            ];
                        }
                    }
                
                    $xe_arr = [];
                    if (is_array($xe)) {
                        foreach ($xe as $item) {
                            $xe_arr[] = [
                                "giathue"     => $item->get_giathue(),
                                "tenxe"     => $item->get_tenxe(),
                                "loai"   => $item->get_loaixe()
                            ];
                        }
                    }
                    $hd_arr = null;
                    if ($hd) {
                        $hd_arr = [
                            "idhoadon"   => $hd->get_idhoadon(),
                            "idtaikhoan" => $hd->get_idtaikhoan(),
                            "idxe"       => $hd->get_idxe(),
                            "diemlay"    => $hd->get_diemlay(),
                            "diemtra"    => $hd->get_diemtra(),
                            "ngaymuon"   => $hd->get_ngaymuon(),
                            "ngaytra"    => $hd->get_ngaytra(),
                            "hoten"      => $hd->get_hoten(),
                            "email"      => $hd->get_email(),
                            "sdt"        => $hd->get_sdt(),
                            "cccd"       => $hd->get_cccd(),
                            "trangthai"  => $hd->get_trangthai(),
                            "ghichu"     => $hd->get_ghichu(),
                            "tongtien"   => $hd->get_tongtien()
                        ];
                    }
                
                    //  push item 
                    $result[] = [
                        "idhoadon" => $idhoadon,
                        "idxe"     => $idxe,
                        "hoadon"   => $hd_arr,
                        "anhxe"    => $anhxe_arr,
                        "xe"       => $xe_arr
                    ];
                }
            
                echo json_encode([
                    "success" => true,
                    "data" => $result,
                    "debug_id" => $curentUserID
                ]);
                exit;

            default:
                http_response_code(400);
                echo json_encode(["error" => "Hành động không hợp lệ"]);
                exit;
        }
    }

    function layxe($idxe)
    {
        if ($idxe <= 0) {
            http_response_code(400);
            echo json_encode(["error" => "ID xe không hợp lệ"]);
            exit;
        }
        $xe = $this->vehicle_dao->getXebyIdxe($idxe);
        return $xe;
    }

    function layanhxe($idxe)
    {
        if ($idxe <= 0) {
            http_response_code(400);
            echo json_encode(["error" => "ID xe không hợp lệ"]);
            exit;
        }
        $anhxe = $this->vehicle_dao->getAnhxebyIdxe($idxe);
        return $anhxe;
    }

    function layhoadonbyidtaikhoan()
    {
        $idtaikhoan = $_SESSION['idtaikhoan'] ?? 0;
        if ($idtaikhoan <= 0) {
            http_response_code(400);
            echo json_encode(["error" => "ID tài khoản không hợp lệ"]);
            exit;
        }
        $hoadon = $this->hoadon_dao->gethoadonById($idtaikhoan);
        return $hoadon;
    }

    function layhoadonbyid($idhoadon)
    {
        $hoadon = $this->hoadon_dao->gethoadonById($idhoadon);
        return $hoadon;
    }

    function laythongtinnguoidung($curentUserID)
    {
        // $idtaikhoan = $_SESSION['idtaikhoan'] ?? 0; cho sang bên kia đê
        if ($curentUserID <= 0) {
            http_response_code(400);
            echo json_encode(["error" => "ID tài khoản không hợp lệ"]);
            exit;
        }
        // getThongTinTaiKhoanbyID
        $user_info = $this->user_dao->getThongTinTaiKhoanbyID($curentUserID);
        return $user_info;
    }

    function laythanhtoanbyidhoadon($idhoadon)
    {
        $thanhtoan = $this->hoadon_dao->getthanhtoanbyidhoadon($idhoadon);
        return $thanhtoan;
    }

    function getthongtinhoadonnguoithue($idtaikhoan)
    {
        $list = $this->hoadon_dao->gethoadonnguoithue($idtaikhoan);
        return $list;
    }
}

$controller = new nguyen_thueXe_Controller();
$controller->handleRequest($action, $data_post);
