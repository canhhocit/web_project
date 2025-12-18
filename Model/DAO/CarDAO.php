<?php
    class CarDAO {
        // lấy tất cả xe
        // private static $instance = null;
        public function getAllCar(){
            global $conn;
            $sql = "SELECT * FROM xe JOIN loaixe ON xe.idloai = loaixe.idloai";
            $pstm = mysqli_query($conn, $sql);
            return mysqli_fetch_all($pstm, MYSQLI_ASSOC);
        }

        // cái này lấy 1 xe cho mục chi tiết 
        public function getOneCarById($id){
            global $conn;
            $sql = "SELECT * FROM xe JOIN loaixe ON xe.idloai = loaixe.idloai WHERE idxe = $id";
            $pstm = mysqli_query($conn, $sql);
            return mysqli_fetch_array($pstm, MYSQLI_ASSOC);
        }
    }

?>