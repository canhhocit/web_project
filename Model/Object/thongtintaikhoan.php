<?php
class thongtintaikhoan
{
    private $idthongtin;
    private $idtaikhoan;
    private $hoten;
    private $sdt;
    private $email;
    private $cccd;
    private $anhdaidien;

    public function __construct($idthongtin, $idtaikhoan, $hoten, $sdt, $email, $cccd, $anhdaidien)
    {
        $this->idthongtin = $idthongtin;
        $this->idtaikhoan = $idtaikhoan;
        $this->hoten = $hoten;
        $this->sdt = $sdt;
        $this->email = $email;
        $this->cccd = $cccd;
        $this->anhdaidien = $anhdaidien;
    }

    public function get_idthongtin()
    {
        return $this->idthongtin;
    }
    public function get_idtaikhoan()
    {
        return $this->idtaikhoan;
    }
    public function get_hoten()
    {
        return $this->hoten;
    }
    public function get_sdt()
    {
        return $this->sdt;
    }
    public function get_email()
    {
        return $this->email;
    }
    public function get_cccd()
    {
        return $this->cccd;
    }
    public function get_anhdaidien()
    {
        return $this->anhdaidien;
    }
    public function set_idthongtin($idthongtin)
    {
        $this->idthongtin = $idthongtin;
    }
    public function set_idtaikhoan($idtaikhoan)
    {
        $this->idtaikhoan = $idtaikhoan;
    }
    public function set_hoten($hoten)
    {
        $this->hoten = $hoten;
    }
    public function set_sdt($sdt)
    {
        $this->sdt = $sdt;
    }
    public function set_email($email)
    {
        $this->email = $email;
    }
    public function set_cccd($cccd)
    {
        $this->cccd = $cccd;
    }
    public function set_anhdaidien($anhdaidien)
    {
        $this->anhdaidien = $anhdaidien;
    }
}
?>