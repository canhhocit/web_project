<?php
class ThongKeDAO {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // 1. Tổng doanh thu tích lũy của chủ xe
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
    
    // 2. Doanh thu tháng hiện tại
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
    
    // 3. Tỷ lệ loại xe của chủ xe
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
    
    // 4. Doanh thu theo các tháng trong năm
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
    
    // 5. Thống kê phụ (số xe đang thuê, tổng số xe)
    public function getStatsPhu($idchuxe) {
        // Số xe đang được thuê
        $query1 = "SELECT COUNT(*) as dang_thue 
                   FROM hoadon hd 
                   JOIN xe x ON hd.idxe = x.idxe 
                   WHERE x.idchuxe = ? AND hd.trangthai = 0";
        
        // Tổng số xe
        $query2 = "SELECT COUNT(*) as tong_xe 
                   FROM xe 
                   WHERE idchuxe = ?";
        
        // Thực thi query 1
        $stmt1 = mysqli_prepare($this->conn, $query1);
        mysqli_stmt_bind_param($stmt1, "i", $idchuxe);
        mysqli_stmt_execute($stmt1);
        $result1 = mysqli_stmt_get_result($stmt1);
        $dang_thue = mysqli_fetch_assoc($result1)['dang_thue'];
        
        // Thực thi query 2
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
    
    // 6. Kiểm tra user có phải là chủ xe không
    public function isChuXe($idtaikhoan) {
        $query = "SELECT lachuxe FROM taikhoan WHERE idtaikhoan = ?";
        
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $idtaikhoan);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        return ($row && $row['lachuxe'] == 1);
    }
    
    // 7. Lấy thông tin role của user
    public function getUserRole($idtaikhoan) {
        $query = "SELECT lachuxe, languoithue FROM taikhoan WHERE idtaikhoan = ?";
        
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $idtaikhoan);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }
}
?>