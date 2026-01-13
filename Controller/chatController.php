<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../Model/Database/dbconnect.php";
require_once __DIR__ . "/../Model/DAO/chatDAO.php";

class chatController
{
    private $dao;

    public function __construct()
    {
        global $conn;
        $this->dao = new chatDAO($conn);
    }

    // show List conver
    public function index()
    {
        if (!isset($_SESSION['idtaikhoan'])) {
            echo "<script>alert('Vui lòng đăng nhập!'); window.location='/web_project/View/taikhoan/login.php';</script>";
            exit;
        }

        $idtaikhoan = $_SESSION['idtaikhoan'];
        $this->dao->xoaCuocTroChuyenRong();
        //list conver
        $danhSachCuocTC = $this->dao->getDanhSachCuocTroChuyenByUser($idtaikhoan);

        include_once __DIR__ . "/../View/chat/index.php";
    }

    // show detail
    public function view()
    {
        if (!isset($_SESSION['idtaikhoan'])) {
            echo "<script>alert('Vui lòng đăng nhập!'); window.location='/web_project/View/taikhoan/login.php';</script>";
            exit;
        }

        $idCuocTC = $_GET['id'] ?? 0;
        $idtaikhoan = $_SESSION['idtaikhoan'];

        //list conver cho side bar
        $danhSachCuocTC = $this->dao->getDanhSachCuocTroChuyenByUser($idtaikhoan);

        $thongTinChat = $this->dao->getThongTinCuocTroChuyenById($idCuocTC, $idtaikhoan);

        if (!$thongTinChat) {
            echo "<script>alert('Không tìm thấy cuộc trò chuyện!'); window.location='/web_project/index.php?controller=chat';</script>";
            exit;
        }

        // list msg
        $danhSachTinNhan = $this->dao->getDanhSachTinNhanByCuocTC($idCuocTC, $idtaikhoan);

        $this->dao->markAsRead($idCuocTC, $idtaikhoan);

        $currentChatId = $idCuocTC;

        include_once __DIR__ . "/../View/chat/index.php";
        include_once __DIR__ . "/../View/chat/chatbox.php";
    }

    // open from carDetail
    public function openChat()
    {
        $idxe = $_GET['idxe'] ?? 0;
        $idchuxe = $_GET['idchuxe'] ?? 0;
        $idtaikhoan = $_SESSION['idtaikhoan'];

        // check exists
        $idCuocTC = $this->dao->getCuocTroChuyenByXe($idtaikhoan, $idchuxe, $idxe);

        if (!$idCuocTC) {
            $idCuocTC = $this->dao->taoCuocTroChuyenMoi($idtaikhoan, $idchuxe, $idxe);
        }

        header("Location: /web_project/index.php?controller=chat&action=view&id=" . $idCuocTC);
        exit;
    }

    public function sendMessage()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $idCuocTC = $_POST['id_cuoc_tc'] ?? 0;
            $noiDung = trim($_POST['noi_dung'] ?? '');
            $idNguoiGui = $_SESSION['idtaikhoan'];

            if ($noiDung === '' || $idCuocTC == 0) {
                header("Location: /web_project/index.php?controller=chat&action=view&id=" . $idCuocTC);
                exit;
            }

            if ($this->dao->guiTinNhan($idCuocTC, $idNguoiGui, $noiDung)) {
                header("Location: /web_project/index.php?controller=chat&action=view&id=" . $idCuocTC);
            } else {
                echo "<script>alert('Gửi tin nhắn thất bại!'); history.back();</script>";
            }
            exit;
        }
    }
    public function delete()
    {
        if (!isset($_SESSION['idtaikhoan'])) {
            echo "<script>alert('Vui lòng đăng nhập!'); window.location='/web_project/View/taikhoan/login.php';</script>";
            exit;
        }

        $idCuocTC = $_GET['id'] ?? 0;
        $idtaikhoan = $_SESSION['idtaikhoan'];

        if ($this->dao->xoaMem($idCuocTC, $idtaikhoan)) {
            echo "<script>alert('Đã xóa cuộc trò chuyện!'); window.location='/web_project/index.php?controller=chat';</script>";
        } else {
            echo "<script>alert('Xóa thất bại!'); window.location='/web_project/index.php?controller=chat';</script>";
        }
        exit;
    }

    public function sendQuickQuestion(){
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'invalid_method']);
            exit;
        }

        $idCuocTC = $_POST['id_cuoc_tc'] ?? 0;
        $key = $_POST['question_key'] ?? '';
        $idNguoiGui = $_SESSION['idtaikhoan'] ?? 0;

        $questionText = $_POST['question_text'] ?? '';
        $answerText = $this->getAutoReply($key);

        if (!$answerText) {
            echo json_encode(['status' => 'no_answer', 'key' => $key]);
            exit;
        }

        $ok1 = $this->dao->guiTinNhan($idCuocTC, $idNguoiGui, $questionText);
        // sleep(1);
        $idNguoiNhan = $this->dao->getIdNguoiConLai($idCuocTC, $idNguoiGui);
        $ok2 = $this->dao->guiTinNhan($idCuocTC, $idNguoiNhan, $answerText);

        echo json_encode(['status' => 'success', 'sent' => [$ok1, $ok2]]);
        exit;
}

    public function getAutoReply($key)
    {
        $qa = [
            'con_xe_khong' => 'Xe vẫn còn nhé bạn.',
            'da_dang_ky' => 'Xe đã đăng ký đầy đủ giấy tờ.',
            'thuong_luong' => 'Giá còn thương lượng trong mức hợp lý.',
            'xem_hom_nay' => 'Bạn có thể qua xem xe hôm nay được nhé.'
        ];

        return $qa[$key] ?? null;
    }
}
