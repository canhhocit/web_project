<?php
    class CarDAO {
        // lấy tất cả xe
        // private static $instance = null;
        public function getAllCar(){
            global $conn;
            $sql = "SELECT x.*, h.tenhang, a.duongdan FROM xe x 
            JOIN hangxe h ON x.idhangxe = h.idhangxe
            LEFT JOIN (SELECT * FROM anhxe GROUP BY idxe) a ON x.idxe = a.idxe";
            $pstm = mysqli_query($conn, $sql);
            return mysqli_fetch_all($pstm, MYSQLI_ASSOC);
        }

        // cái này lấy 1 xe cho mục chi tiết 
        public function getOneCarById($id){
            global $conn;
            $sql = "SELECT x.*, h.tenhang, a.duongdan FROM xe x 
            JOIN hangxe h ON x.idhangxe = h.idhangxe
            LEFT JOIN (SELECT * FROM anhxe GROUP BY idxe) a ON x.idxe = a.idxe
            WHERE x.idxe = ?";
            $pstm = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($pstm, "i", $id);
            mysqli_stmt_execute($pstm);
            $result = mysqli_stmt_get_result($pstm);
            return mysqli_fetch_array($result, MYSQLI_ASSOC);
        }
    }

?>