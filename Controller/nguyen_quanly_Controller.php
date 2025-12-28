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
                hd.tongtien,
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
            "price" => $row['tongtien'],
            "statusClass"    => "yellow",
            "status"   => "Đang thuê",
            "name"     => $row['tenxe'],
            "image"    => $finalImage
        ];
    }
    
    echo json_encode($data);
    exit; 
} else if ($tab === 2) {
    $sql = "SELECT 
            hd.idhoadon,
            hd.tongtien,
            x.tenxe,
            (SELECT ax.duongdan FROM anhxe ax WHERE ax.idxe = x.idxe LIMIT 1) AS image
            FROM hoadon hd
            JOIN xe x ON hd.idxe = x.idxe
            WHERE hd.idtaikhoan = $idtaikhoan
            AND hd.trangthai = 1";

    $res = mysqli_query($conn, $sql);
    if (!$res) {
    echo json_encode(["status" => "error", "message" => "Lỗi SQL Tab 2: " . mysqli_error($conn)]);
    exit;
    }

    while ($row = mysqli_fetch_assoc($res)) {
    $finalImage = empty($row['image'])
        ? "/web_project/View/image/none_image.png"
        : "/web_project/View/image/" . $row['image'];

    $data[] = [
        "idhoadon" => $row['idhoadon'],
        "price" => $row['tongtien'],
        "priceText" => "Tổng: " . number_format((float)$row['tongtien'], 0, ',', '.') . "đ",
        "statusClass" => "green",
        "status" => "Đã trả xe",
        "name" => $row['tenxe'],
        "image" => $finalImage,
        "showReturn" => false
    ];
    }

    echo json_encode($data);
    exit;
}

echo json_encode(["status" => "error", "message" => "Tab không hợp lệ."]);
exit;
?>