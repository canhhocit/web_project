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

    // show List
    public function index()
    {
        if (!isset($_SESSION['idtaikhoan'])) {
            echo "<script>alert('Vui lòng đăng nhập!'); window.location='/web_project/View/taikhoan/login.php';</script>";
            exit;
        }

        $idtaikhoan = $_SESSION['idtaikhoan'];
        $currentUserId = $idtaikhoan;

        include_once __DIR__ . "/../View/chat/index.php";
    }

    // show detail
    public function view()
    {
        $id = $_GET['id'] ?? 0;
        if ($id > 0) {
            header("Location: /web_project/index.php?controller=chat&conversationId=" . $id);
            exit;
        }
        $this->index();
    }

    // open from carDetail
    public function openChat()
    {
        $idxe = $_GET['idxe'] ?? 0;
        $idchuxe = $_GET['idchuxe'] ?? 0;

        header("Location: /web_project/index.php?controller=chat&autoCreate=true&idXe=$idxe&idChuXe=$idchuxe");
        exit;
    }

    public function sendMessage()
    {
    }     public function delete()
    {
    } 
}
