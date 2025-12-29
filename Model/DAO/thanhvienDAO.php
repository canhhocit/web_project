<?php
    require_once __DIR__ . "/../Object/ThanhVien.php";
    class thanhvienDAO{
        private $conn; // biến lưu trữ kết nối db.
        public function __construct($conn) // nhận tham số kết nối db
        {
            $this->conn = $conn;
        }

        public function getTV(){
            $sql = "SELECT * FROM thanhvien_nhom ORDER BY id DESC";
            $result = $this->conn->query($sql);// biến chứa kết quả truy vấn
            $listTV = []; 
            while($row = $result->fetch_assoc()){ // "$result->fetch_assoc()" có nghĩa là: Lấy 1 dòng dữ liệu dạng mảng kết hợp, duyệt qua từng dòng của DB
                $listTV[] = new ThanhVien($row ['id'], $row ['ten_thanhvien'], $row['mssv'], $row['cong_viec']);
            }
            return $listTV;
        }

        public function addTV($ten_thanhvien, $mssv, $cong_viec){ // 3 tham số cần để add vào db
            $sql = "INSERT INTO thanhvien_nhom (ten_thanhvien, mssv, cong_viec) VALUES (?,?,?)"; 
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sss", $ten_thanhvien, $mssv, $cong_viec); //sss tương ứng với 3 dấu ? s là string
            return $stmt->execute();
        }
        
        public function delTV($id){
            $sql = "DELETE FROM thanhvien_nhom where id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }
    }
?>

<!-- /**
//UPDATE
    public function upTV($id){
        $sql = "UPDATE thanhvien_nhom SET ten_thanhvien = ? where id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $ten_thanhvien, $id);
        return $stmt->execute();
    }
// get by id
    public function getbyID($id){
        $sql = "SELECT * FROM thanhvien_nhom where id = ?";
        $stmt = this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
        $result = $stmt->get_result();
        $row = $result -> fetch_assoc();
            if ($row){
                return new ThanhVien($row['id'], $row['ten_thanhvien'], $row['mssv'], $row['cong_viec']);
            }
            return null;
    }
// search name
    public function sTV($name){
        $sql = "SELECT * FROM thanhvien_nhom where ten_thanhvien like ?  ";
        $stmt = $this->conn->prepare($sql);
        $searchh = "%" .$name . "%";
        $stmt->bind_param("s", $searchh);
        return $stmt->execute();
        $result = $stmt -> get_result();
        $listTV = [];
        while($row = $result->fetch_assoc()){
        $listTV[] = new ThanhVien($row['id'], $row['ten_thanhvien'], $row['mssv'], $row['cong_viec']);
    }
        return $listTV;
    }
**/ -->
