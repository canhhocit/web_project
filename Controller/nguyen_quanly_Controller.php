<?php
session_start();
require_once "/Model/Database/dbconnect.php";

header("Content-Type: application/json; charset=utf-8");

// Đọc JSON từ JS
$input = json_decode(file_get_contents("php://input"), true);
$tab = (int)($input['tab'] ?? 0);
$idtaikhoan = (int)($_SESSION['idtaikhoan'] ?? 0);

// Validate cơ bản
if ($idtaikhoan === 0 || $tab !== 1) {
    echo json_encode([]);
    exit;
}

$data = [];

// ===== TAB 1: ĐANG THUÊ =====
$sql = "
    SELECT 
        hd.idhoadon,
        x.tenxe,
        x.giathue,
        (
            SELECT ax.duongdan
            FROM anhxe ax
            WHERE ax.idxe = x.idxe
            ORDER BY ax.idanhxe ASC
            LIMIT 1
        ) AS image
    FROM hoadon hd
    JOIN xe x ON hd.idxe = x.idxe
    WHERE hd.idtaikhoan = $idtaikhoan
    AND hd.trangthai = 0
";

$res = mysqli_query($conn, $sql);

if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = [
            "idhoadon"    => $row['idhoadon'],
            "name"        => $row['tenxe'],
            "price"       => number_format($row['giathue'], 0, ',', '.') . "đ / ngày",
            "status"      => "Đang thuê",
            "statusClass" => "yellow",
            "image"       => !empty($row['image'])
                ? "/View/image/" . $row['image']
                : "/View/image/none_image.png",
            "success" => true
        ];
    }
}

echo json_encode($data);
