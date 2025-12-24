<?php
session_start();
//require_once "../config/db.php";
require_once "Model/Database/dbconnect.php";


header("Content-Type: application/json; charset=utf-8");

$input = json_decode(file_get_contents("php://input"), true);
$tab = (int)($input['tab'] ?? 0);
$idtaikhoan = (int)($_SESSION['idtaikhoan'] ?? 0);

if ($idtaikhoan === 0 || $tab === 0) {
    echo json_encode([]);
    exit;
}

$data = [];

if ($tab === 1) {
    $sql = "SELECT 
                hd.idhoadon,
                x.tenxe,
                x.giathue,
                (SELECT ax.duongdan
                FROM anhxe ax
                WHERE ax.idxe = x.idxe
                ORDER BY ax.idanhxe ASC
                LIMIT 1 ) AS image
            FROM hoadon hd
            JOIN xe x ON hd.idxe = x.idxe
            WHERE hd.idtaikhoan = $idtaikhoan AND hd.trangthai = 0";
}

if ($tab === 2) {
    $sql = "SELECT hd.idhoadon, x.tenxe, x.giathue
            FROM hoadon hd
            JOIN xe x ON hd.idxe = x.idxe
            WHERE x.idchuxe = $idtaikhoan AND hd.trangthai = 1";
}

if (isset($sql)) {
    $res = mysqli_query($conn, $sql);
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = [
                "idhoadon" => $row['idhoadon'],
                "name" => $row['tenxe'],
                "price" => number_format($row['giathue'], 0, ',', '.') . "đ / ngày",
                "status" => "Đang thuê",
                "statusClass" => "yellow",
                "image" => $row['image'] 
                ? "../View/image/" . $row['image']// đúng nếu db lưu tên ảnh
                : "../View/image/no-image.png"
            ];
        }
    }
}

echo json_encode($data);
