<?php
class favoriteVehicleDAO{
     private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function addFavorite($idUser, $idV){
        $sql = "insert into yeuthich(idtaikhoan,idxe) values('$idUser','$idV')";
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }
    public function delFavorite($idUser, $idV){
        $sql = "delete from yeuthich where idtaikhoan ='$idUser' and idxe='$idV'";
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }
    public function checkExistsVehicle($idUser, $idV){
         $result = mysqli_query($this->conn, "select * from yeuthich where idtaikhoan ='$idUser' and idxe='$idV'");
         $row= mysqli_num_rows($result);
         if($row> 0){
            return true;
         }
         return false;
    }

}

?>