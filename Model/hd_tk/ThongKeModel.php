<?php
class ThongKeModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    private function executeParamQuery($query, $idchuxe) {
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $idchuxe);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    public function getTongDoanhThu($idchuxe) {
        $query = "SELECT SUM(tt.sotien) as tong_doanh_thu 
                  FROM thanhtoan tt 
                  JOIN hoadon hd ON tt.idhoadon = hd.idhoadon
                  JOIN xe x ON hd.idxe = x.idxe
                  WHERE x.idchuxe = ?";
        $result = $this->executeParamQuery($query, $idchuxe);
        return mysqli_fetch_assoc($result);
    }

    public function getDoanhThuThang($idchuxe) {
        $query = "SELECT SUM(tt.sotien) as doanh_thu 
                  FROM thanhtoan tt
                  JOIN hoadon hd ON tt.idhoadon = hd.idhoadon
                  JOIN xe x ON hd.idxe = x.idxe
                  WHERE x.idchuxe = ? 
                  AND MONTH(tt.ngaythanhtoan) = MONTH(CURRENT_DATE()) 
                  AND YEAR(tt.ngaythanhtoan) = YEAR(CURRENT_DATE())";
        $result = $this->executeParamQuery($query, $idchuxe);
        return mysqli_fetch_assoc($result);
    }

    public function getTyLeLoaiXe($idchuxe) {
        $query = "SELECT x.loaixe, COUNT(x.idxe) as so_luong 
                  FROM xe x 
                  WHERE x.idchuxe = ?
                  GROUP BY x.loaixe";
        $result = $this->executeParamQuery($query, $idchuxe);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getDoanhThuCacThang($idchuxe) {
        $query = "SELECT MONTH(tt.ngaythanhtoan) as thang, SUM(tt.sotien) as doanh_thu 
                  FROM thanhtoan tt
                  JOIN hoadon hd ON tt.idhoadon = hd.idhoadon
                  JOIN xe x ON hd.idxe = x.idxe
                  WHERE x.idchuxe = ? AND YEAR(tt.ngaythanhtoan) = YEAR(CURRENT_DATE())
                  GROUP BY MONTH(tt.ngaythanhtoan) 
                  ORDER BY thang ASC";
        $result = $this->executeParamQuery($query, $idchuxe);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getStatsPhu($idchuxe) {
        $q1 = "SELECT COUNT(*) as dang_thue FROM hoadon hd 
               JOIN xe x ON hd.idxe = x.idxe 
               WHERE x.idchuxe = ? AND hd.trangthai = 0";
        
        $q2 = "SELECT COUNT(*) as tong_xe FROM xe WHERE idchuxe = ?";
        
        $res_xe = $this->executeParamQuery($q1, $idchuxe);
        $res_tong = $this->executeParamQuery($q2, $idchuxe);
        
        return [
            'dang_thue' => mysqli_fetch_assoc($res_xe)['dang_thue'],
            'tong_xe' => mysqli_fetch_assoc($res_tong)['tong_xe']
        ];
    }
}