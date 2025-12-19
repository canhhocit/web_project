<?php
require_once __DIR__ . "/../Object/taikhoan.php";
require_once __DIR__ . "/../Object/thongtintaikhoan.php";

class taikhoanDAO
{
    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    //Tai Khoan
    public function addTaiKhoan($taikhoan)
    {
        $username = $taikhoan->get_username();
        $pass = $taikhoan->get_pass();
        $sql = "INSERT INTO taikhoan (username, pass, lachuxe, languoithue) VALUES ('$username', '$pass', '0', '0')";
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }
    private function delTK($idtaikhoan)
    {
        $sql = "DELETE FROM taikhoan WHERE idtaikhoan = '$idtaikhoan'";
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }
    //THÔNG TIN TÀI KHOẢN
    //$idthongtin, $idtaikhoan, $hoten, $sdt, $email, $cccd, $anhdaidien
    public function addThongTinTaiKhoan($thongtin)
    {
        $idtaikhoan = $thongtin->getIdtaikhoan();
        $hoten = $thongtin->get_hoten();
        $sdt = $thongtin->get_sdt();
        $email = $thongtin->get_email();
        $cccd = $thongtin->get_cccd();
        $anhdaidien = $thongtin->get_anhdaidien();
        $sql = "INSERT INTO thongtin (idtaikhoan, hoten, sdt, email, cccd, anhdaidien) VALUES ('$idtaikhoan', '$hoten', '$sdt', '$email', '$cccd', '$anhdaidien')";
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }
    private function delTTTK($idtaikhoan)
    {
        $sql = "DELETE FROM thongtin WHERE idtaikhoan = '$idtaikhoan'";
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }
    public function updateThongTinTaiKhoan($thongtin)
    {
        $idthongtin = $thongtin->get_idthongtin();
        $hoten = $thongtin->get_hoten();
        $sdt = $thongtin->get_sdt();
        $email = $thongtin->get_email();
        $cccd = $thongtin->get_cccd();
        $anhdaidien = $thongtin->get_anhdaidien();
        $sql = "UPDATE thongtin SET hoten = '$hoten', sdt = '$sdt', email = '$email', cccd = '$cccd', anhdaidien = '$anhdaidien' WHERE idthongtin = '$idthongtin'";
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }
    public function getThongTinTaiKhoan($idtaikhoan)
    {
        $sql = "SELECT * FROM thongtin WHERE idtaikhoan = '$idtaikhoan'";
        $result = mysqli_query($this->conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $thongtin = new thongtintaikhoan($row['idthongtin'], $row['idtaikhoan'], $row['hoten'], $row['sdt'], $row['email'], $row['cccd'], $row['anhdaidien']);
        return $thongtin;
    }
    //-----------------
    public function deleteTaikhoan($idtaikhoan)
    {
        return $this->delTTTK($idtaikhoan) && $this->delTK($idtaikhoan);
    }
    //-----------------
    public function updateisChuxe($idtaikhoan){
        return mysqli_query($this->conn,"UPDATE taikhoan SET lachuxe = '1' WHERE idtaikhoan = '$idtaikhoan'");
    }
    public function updateisNguoithue($idtaikhoan){
        return mysqli_query($this->conn,"UPDATE taikhoan SET languoithue = '1' WHERE idtaikhoan = '$idtaikhoan'");
    }

}
?>