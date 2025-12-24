<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

$path_db = __DIR__ . "/../Model/Database/dbconnect.php";

if (!file_exists($path_db)) {
    echo json_encode(["status" => "error", "message" => "Lỗi: Không tìm thấy file dbconnect.php"]);
    exit;
}
require_once $path_db;

// Lấy dữ liệu từ JS gửi lên
$input = json_decode(file_get_contents("php://input"), true);
$tab = (int)($input['tab'] ?? 0);
$idtaikhoan = (int)($_SESSION['idtaikhoan'] ?? 0);

// Kiểm tra đăng nhập
if ($idtaikhoan === 0) {
    echo json_encode(["status" => "error", "message" => "Mất session, vui lòng đăng nhập lại."]);
    exit;
}

$data = [];

if ($tab === 1) {
    $sql = "SELECT 
                hd.idhoadon, 
                x.tenxe, 
                x.giathue, 
                (SELECT ax.duongdan FROM anhxe ax WHERE ax.idxe = x.idxe LIMIT 1) AS image 
            FROM hoadon hd 
            JOIN xe x ON hd.idxe = x.idxe 
            WHERE hd.idtaikhoan = $idtaikhoan 
            AND hd.trangthai = 0"; // Chỉ lấy xe chưa trả

    $res = mysqli_query($conn, $sql);

    if (!$res) {
        echo json_encode(["status" => "error", "message" => "Lỗi SQL Tab 1: " . mysqli_error($conn)]);
        exit;
    }

    while ($row = mysqli_fetch_assoc($res)) {
        $linkAnh = $row['image'];

        if (empty($linkAnh)) {
            $finalImage = '/web_project/View/image/none_image.png';
        } else {
            $finalImage = '/web_project/View/image/' . $linkAnh;
        }

        $data[] = [
            "idhoadon" => $row['idhoadon'],
            "name"     => $row['tenxe'],
            "price"    => $row['giathue'], 
            "image"    => $finalImage
        ];
    }
    
    echo json_encode($data);
    exit; 
} 
?>