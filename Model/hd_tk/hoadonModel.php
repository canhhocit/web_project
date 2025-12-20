<?php
class HoaDonModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getHoaDonChuaThanhToan() {
        $query = "SELECT hd.*, x.tenxe, x.giathue, tt.hoten 
                  FROM hoadon hd JOIN xe x ON hd.idxe = x.idxe
                  JOIN thongtintaikhoan tt ON hd.idtaikhoan = tt.idtaikhoan
                  WHERE hd.trangthai = 0";

        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getChiTietHoaDon($idhoadon) {
        $query = "SELECT hd.*, x.tenxe, x.giathue, DATEDIFF(hd.ngaytra, hd.ngaymuon) as ngay_thue_du_kien
                  FROM hoadon hd JOIN xe x ON hd.idxe = x.idxe
                  WHERE hd.idhoadon = ?";
        
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $idhoadon);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public function xacNhanTraXe($idhoadon, $ngay_qua_han, $tong_tien, $phuongthuc) {

        $query_hd = "UPDATE hoadon SET trangthai = 1, tongtien = ? WHERE idhoadon = ?";
        $stmt1 = mysqli_prepare($this->conn, $query_hd);
        mysqli_stmt_bind_param($stmt1, "di", $tong_tien, $idhoadon); 
        $res1 = mysqli_stmt_execute($stmt1);

        $magiaodich = rand(1000000, 9999999);
        $query_tt = "INSERT INTO thanhtoan (idhoadon, idtaikhoan, sotien, phuongthuc, magiaodich) 
                    SELECT idhoadon, idtaikhoan, ?, ?, ? 
                    FROM hoadon WHERE idhoadon = ?";
        
        $stmt2 = mysqli_prepare($this->conn, $query_tt);
        mysqli_stmt_bind_param($stmt2, "dsii", $tong_tien, $phuongthuc, $magiaodich, $idhoadon);
        $res2 = mysqli_stmt_execute($stmt2);

        return ($res1 && $res2);
    }
}