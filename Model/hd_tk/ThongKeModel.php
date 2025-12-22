<?php
class ThongKeModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    private function executeQuery($query) {
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    public function getDoanhThuTheoChuXe() {
        $query = "SELECT ttkh.hoten as chu_xe, SUM(tt.sotien) as tong_doanh_thu
                  FROM thanhtoan tt JOIN hoadon hd ON tt.idhoadon = hd.idhoadon
                  JOIN xe x ON hd.idxe = x.idxe
                  JOIN thongtintaikhoan ttkh ON x.idchuxe = ttkh.idtaikhoan
                  GROUP BY x.idchuxe
                  ORDER BY tong_doanh_thu DESC";
        $result = $this->executeQuery($query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getDoanhThuThang() {
        $query = "SELECT SUM(sotien) as doanh_thu FROM thanhtoan 
                  WHERE MONTH(ngaythanhtoan) = MONTH(CURRENT_DATE()) 
                  AND YEAR(ngaythanhtoan) = YEAR(CURRENT_DATE())";
        $result = $this->executeQuery($query);
        return mysqli_fetch_assoc($result);
    }

    public function getTyLeThueXe() {
        $query = "SELECT 
                    (SELECT COUNT(DISTINCT idxe) FROM hoadon) as so_xe_dang_thue,
                    (SELECT COUNT(*) FROM xe) as tong_so_xe";
        $result = $this->executeQuery($query);
        $data = mysqli_fetch_assoc($result);
        $data['ty_le'] = ($data['tong_so_xe'] > 0) ? round(($data['so_xe_dang_thue'] / $data['tong_so_xe']) * 100, 2) : 0;
        return $data;
    }

    public function getTongDoanhThu() {
        $query = "SELECT SUM(sotien) as tong_doanh_thu FROM thanhtoan";
        $result = $this->executeQuery($query);
        return mysqli_fetch_assoc($result);
    }

    public function getTyLeLoaiXe() {
        $query = "SELECT loaixe, COUNT(*) as so_luong 
                FROM hoadon hd JOIN xe x ON hd.idxe = x.idxe 
                GROUP BY loaixe";
        $result = $this->executeQuery($query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getDoanhThuCacThang() {
        $query = "SELECT MONTH(ngaythanhtoan) as thang, SUM(sotien) as doanh_thu 
                FROM thanhtoan 
                WHERE YEAR(ngaythanhtoan) = YEAR(CURRENT_DATE())
                GROUP BY MONTH(ngaythanhtoan) ORDER BY thang ASC";
        $result = $this->executeQuery($query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getStatsPhu() {
        $query_xe = "SELECT COUNT(*) as dang_thue FROM hoadon WHERE trangthai = 0";
        $query_kh = "SELECT COUNT(*) as tong_kh FROM taikhoan WHERE languoithue = 1";
        
        $res_xe = $this->executeQuery($query_xe);
        $res_kh = $this->executeQuery($query_kh);
        
        return [
            'dang_thue' => mysqli_fetch_assoc($res_xe)['dang_thue'],
            'tong_kh' => mysqli_fetch_assoc($res_kh)['tong_kh']
        ];
    }
}