<?php
class ThongKeDAO {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function getTongDoanhThu($idchuxe) {
        $query = "SELECT SUM(tt.sotien) as tong_doanh_thu 
                  FROM thanhtoan tt 
                  JOIN hoadon hd ON tt.idhoadon = hd.idhoadon
                  JOIN xe x ON hd.idxe = x.idxe
                  WHERE x.idchuxe = ?";
        
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $idchuxe);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
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
        
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $idchuxe);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }
    
    public function getTyLeLoaiXe($idchuxe) {
        $query = "SELECT x.loaixe, COUNT(x.idxe) as so_luong 
                  FROM xe x 
                  WHERE x.idchuxe = ?
                  GROUP BY x.loaixe";
        
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $idchuxe);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
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
        
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $idchuxe);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    public function getStatsPhu($idchuxe) {
        $query1 = "SELECT COUNT(*) as dang_thue 
                   FROM hoadon hd 
                   JOIN xe x ON hd.idxe = x.idxe 
                   WHERE x.idchuxe = ? AND hd.trangthai = 0";
        
        $query2 = "SELECT COUNT(*) as tong_xe 
                   FROM xe 
                   WHERE idchuxe = ?";
        

        $stmt1 = mysqli_prepare($this->conn, $query1);
        mysqli_stmt_bind_param($stmt1, "i", $idchuxe);
        mysqli_stmt_execute($stmt1);
        $result1 = mysqli_stmt_get_result($stmt1);
        $dang_thue = mysqli_fetch_assoc($result1)['dang_thue'];
        
        $stmt2 = mysqli_prepare($this->conn, $query2);
        mysqli_stmt_bind_param($stmt2, "i", $idchuxe);
        mysqli_stmt_execute($stmt2);
        $result2 = mysqli_stmt_get_result($stmt2);
        $tong_xe = mysqli_fetch_assoc($result2)['tong_xe'];
        
        return [
            'dang_thue' => $dang_thue,
            'tong_xe' => $tong_xe
        ];
    }
    
    public function isChuXe($idtaikhoan) {
        $query = "SELECT lachuxe FROM taikhoan WHERE idtaikhoan = ?";
        
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $idtaikhoan);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        return ($row && $row['lachuxe'] == 1);
    }
    
}
?>