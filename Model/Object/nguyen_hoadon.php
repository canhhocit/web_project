<?php
class nguyen_hoadon {

    private $idhoadon;
    private $idtaikhoan;
    private $idxe;

    private $diemlay;
    private $diemtra;
    private $ngaymuon;
    private $ngaytra;

    private $hoten;
    private $email;
    private $sdt;
    private $cccd;

    private $trangthai; // 0 = đang thuê / chưa thanh toán
    private $ghichu;
    private $tongtien;

    public function __construct(
        $idhoadon,
        $idtaikhoan,
        $idxe,
        $diemlay,
        $diemtra,
        $ngaymuon,
        $ngaytra,
        $hoten,
        $email,
        $sdt,
        $cccd,
        $trangthai = 0,
        $ghichu = null,
        $tongtien = 0
    ) {
        $this->idhoadon   = $idhoadon;
        $this->idtaikhoan = $idtaikhoan;
        $this->idxe       = $idxe;
        $this->diemlay    = $diemlay;
        $this->diemtra    = $diemtra;
        $this->ngaymuon   = $ngaymuon;
        $this->ngaytra    = $ngaytra;
        $this->hoten      = $hoten;
        $this->email      = $email;
        $this->sdt        = $sdt;
        $this->cccd       = $cccd;
        $this->trangthai  = $trangthai;
        $this->ghichu     = $ghichu;
        $this->tongtien   = $tongtien;
    }

    /* ===== GETTER ===== */
    public function get_idhoadon()   { return $this->idhoadon; }
    public function get_idtaikhoan() { return $this->idtaikhoan; }
    public function get_idxe()       { return $this->idxe; }

    public function get_diemlay()  { return $this->diemlay; }
    public function get_diemtra()  { return $this->diemtra; }
    public function get_ngaymuon() { return $this->ngaymuon; }
    public function get_ngaytra()  { return $this->ngaytra; }

    public function get_hoten() { return $this->hoten; }
    public function get_email() { return $this->email; }
    public function get_sdt()   { return $this->sdt; }
    public function get_cccd()  { return $this->cccd; }

    public function get_trangthai() { return $this->trangthai; }
    public function get_ghichu()    { return $this->ghichu; }
    public function get_tongtien()  { return $this->tongtien; }

    /* ===== SETTER ===== */
    public function set_idhoadon($idhoadon)     { $this->idhoadon = $idhoadon; }
    public function set_idtaikhoan($idtaikhoan) { $this->idtaikhoan = $idtaikhoan; }
    public function set_idxe($idxe)             { $this->idxe = $idxe; }

    public function set_diemlay($diemlay)     { $this->diemlay = $diemlay; }
    public function set_diemtra($diemtra)     { $this->diemtra = $diemtra; }
    public function set_ngaymuon($ngaymuon)   { $this->ngaymuon = $ngaymuon; }
    public function set_ngaytra($ngaytra)     { $this->ngaytra = $ngaytra; }

    public function set_hoten($hoten) { $this->hoten = $hoten; }
    public function set_email($email) { $this->email = $email; }
    public function set_sdt($sdt)     { $this->sdt = $sdt; }
    public function set_cccd($cccd)   { $this->cccd = $cccd; }

    public function set_trangthai($trangthai) { $this->trangthai = $trangthai; }
    public function set_ghichu($ghichu)       { $this->ghichu = $ghichu; }
    public function set_tongtien($tongtien)   { $this->tongtien = $tongtien; }
}
