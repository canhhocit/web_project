<?php

    require_once __DIR__ . "/../Object/ThanhVien.php";
    class thanhvienDAO{
        private $conn;
        public function __construct($conn){
            $this->conn = $conn;
    }
    public function getTV(){
        $sql = "SELECT * FROM thanhvien_nhom ORDER BY id DESC";
        $result = $this->conn->query($sql);
        $listTV = [];
        while($row = $result->fetch_assoc()){
            $listTV[] = new ThanhVien($row['id'], $row['ten_thanhvien'], $row['mssv'], $row['cong_viec']);
        }
        return $listTV;
    }

    public function addTV($ten_thanhvien, $mssv, $cong_viec){
        $sql = "INSERT INTO thanhvien_nhom (ten_thanhvien, mssv, cong_viec) VALUES (?,?,?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $ten_thanhvien, $mssv, $cong_viec);
        return $stmt->execute();
    }

    public function del($id){
        $sql = "DELETE FROM thanhvien_nhom WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>