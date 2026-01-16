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
    public function checkExist($username)
    {
        $rs = mysqli_query($this->conn, "select * from taikhoan where username = '$username'");
        return mysqli_num_rows($rs) > 0;
    }
    public function checkLogin($username, $pass)
    {
        $result = mysqli_query($this->conn, "select * from taikhoan where username = '$username' and pass = '$pass'");
        return mysqli_num_rows($result) > 0;
    }
    public function getIdtaikhoan($username)
    {
        $rs = mysqli_query($this->conn, "select idtaikhoan from taikhoan where username = '$username'");
        $row = mysqli_fetch_assoc($rs);
        return $row["idtaikhoan"];
    }
    public function checkIDtaikhoaninThongtin($idtaikhoan)
    {
        $rs = mysqli_query($this->conn, "select * from thongtintaikhoan where idtaikhoan = '$idtaikhoan'");
        return mysqli_num_rows($rs) > 0;
    }
    public function checkdefaultAvatar($idtaikhoan)
    {
        $rs = mysqli_query($this->conn, "SELECT anhdaidien FROM thongtintaikhoan WHERE idtaikhoan = '$idtaikhoan'");
        $row = mysqli_fetch_assoc($rs);
        $avt = $row['anhdaidien'];
        if ($avt === 'default-avt.jpg') {
            return true;
        }
        return false;
    }
    public function delAvatar($idtaikhoan)
    {
        return mysqli_query($this->conn, "update thongtintaikhoan set anhdaidien='default-avt.jpg' where idtaikhoan = '$idtaikhoan'");
    }
    public function checkThongtinTK_isNULL($idtaikhoan)
    {
        $rs = mysqli_query($this->conn, "SELECT hoten, email,sdt,cccd FROM thongtintaikhoan where idtaikhoan = '$idtaikhoan'");
        $row = mysqli_fetch_assoc($rs);
        if (empty($row['hoten']) || empty($row['email']) || empty($row['sdt']) || empty($row['cccd'])) {
            return true;
        }

        return false;
    }
    public function checkUserforgot($username)
    {
        $rs = mysqli_query($this->conn, "SELECT * FROM taikhoan WHERE username = '$username'");
        if (mysqli_num_rows($rs) > 0) {
            return true;
        }
        return false;
    }

    public function addThongTinTaiKhoan($idtaikhoan, $anhdaidien)
    {
        $sql = "INSERT INTO thongtintaikhoan (idtaikhoan, anhdaidien) VALUES ('$idtaikhoan', '$anhdaidien')";
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }
    // private function delTTTK($idtaikhoan)
    // {
    //     $sql = "DELETE FROM thongtintaikhoan WHERE idtaikhoan = '$idtaikhoan'";
    //     $result = mysqli_query($this->conn, $sql);
    //     return $result;
    // }
    public function updateThongTinTaiKhoan($thongtin)
    {
        $idtaikhoan = $thongtin->get_idtaikhoan();
        $hoten = $thongtin->get_hoten();
        $sdt = $thongtin->get_sdt();
        $email = $thongtin->get_email();
        $cccd = $thongtin->get_cccd();
        $anhdaidien = $thongtin->get_anhdaidien();
        $sql = "UPDATE thongtintaikhoan SET hoten = '$hoten', sdt = '$sdt', email = '$email', cccd = '$cccd', anhdaidien = '$anhdaidien' WHERE idtaikhoan = '$idtaikhoan'";
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }
    public function getThongTinTaiKhoanbyID($idtaikhoan)
    {
        $sql = "SELECT * FROM thongtintaikhoan WHERE idtaikhoan = '$idtaikhoan'";
        $result = mysqli_query($this->conn, $sql);
        $row = mysqli_fetch_assoc($result);
        if (!$row) {
            return null;
        }
        $thongtin = new thongtintaikhoan($row['idthongtin'], $row['idtaikhoan'], $row['hoten'], $row['sdt'], $row['email'], $row['cccd'], $row['anhdaidien']);
        return $thongtin;
    }
    //====== pass
    public function checkOldPassword($idtaikhoan, $oldpass)
    {
        $sql = "SELECT * FROM taikhoan WHERE idtaikhoan = '$idtaikhoan' AND pass = '$oldpass'";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_num_rows($result) > 0;
    }
    public function updateForgotpass($username, $newpass)
    {
        $sql = "UPDATE taikhoan SET pass = '$newpass' WHERE username = '$username'";
        return mysqli_query($this->conn, $sql);
    }
    public function updatePassword($idtaikhoan, $newpass)
    {
        $sql = "UPDATE taikhoan SET pass = '$newpass' WHERE idtaikhoan = '$idtaikhoan'";
        return mysqli_query($this->conn, $sql);
    }
    // page-- Canh
    // public function getXebyIdChuxePaging($id, $limit, $offset)
    // {
    //     $sql = "SELECT * FROM xe 
    //         WHERE id_chuxe = ?
    //         LIMIT ? OFFSET ?";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute([$id, $limit, $offset]);
    //     return $stmt->fetchAll();
    // }
    // public function countXeByChuxe($id)
    // {
    //     $sql = "SELECT COUNT(*) FROM xe WHERE id_chuxe = ?";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute([$id]);
    //     return (int) $stmt->fetchColumn();
    // }

    //-----------------

    //-----------------
    public function updateisChuxe($idtaikhoan)
    {
        return mysqli_query($this->conn, "UPDATE taikhoan SET lachuxe = '1' WHERE idtaikhoan = '$idtaikhoan'");
    }
    public function updateisNguoithue($idtaikhoan)
    {
        return mysqli_query($this->conn, "UPDATE taikhoan SET languoithue = '1' WHERE idtaikhoan = '$idtaikhoan'");
    }
}
