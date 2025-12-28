<?php
require_once __DIR__ . "/../Database/dbconnect.php";
require_once __DIR__ . "/../Object/nguyen_hoadon.php";

class nguyen_hoadonDAO {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAll() {
        $sql = "SELECT * FROM hoadon";
        $result = $this->conn->query($sql);
        $list = [];

        while ($row = $result->fetch_assoc()) {
            $list[] = new nguyen_hoadon(
                $row['idhoadon'],
                $row['idtaikhoan'],
                $row['idxe'],
                $row['diemlay'],
                $row['diemtra'],
                $row['ngaymuon'],
                $row['ngaytra'],
                $row['hoten'],
                $row['email'],
                $row['sdt'],
                $row['cccd'],
                $row['trangthai'],
                $row['ghichu'],
                $row['tongtien']
            );
        }
        return $list;
    }

    public function gethoadonById($idhoadon) {
        $sql = "SELECT * FROM hoadon WHERE idhoadon = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idhoadon);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            return new nguyen_hoadon(
                $row['idhoadon'],
                $row['idtaikhoan'],
                $row['idxe'],
                $row['diemlay'],
                $row['diemtra'],
                $row['ngaymuon'],
                $row['ngaytra'],
                $row['hoten'],
                $row['email'],
                $row['sdt'],
                $row['cccd'],
                $row['ghichu'],
                $row['tongtien']
            );
        }
        return null;
    }

    public function insert(nguyen_hoadon $hd)
{
    $sql = "INSERT INTO hoadon (
                idtaikhoan,
                idxe,
                diemlay,
                diemtra,
                ngaymuon,
                ngaytra,
                hoten,
                email,
                sdt,
                cccd,
                trangthai,
                ghichu,
                tongtien
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $this->conn->prepare($sql);

    // lấy dữ liệu ra biến
    $idtaikhoan = $hd->get_idtaikhoan();
    $idxe       = $hd->get_idxe();
    $diemlay    = $hd->get_diemlay();
    $diemtra    = $hd->get_diemtra();
    $ngaymuon   = $hd->get_ngaymuon();
    $ngaytra    = $hd->get_ngaytra();
    $hoten      = $hd->get_hoten();
    $email      = $hd->get_email();
    $sdt        = $hd->get_sdt();
    $cccd       = $hd->get_cccd();
    $trangthai  = 0;
    $ghichu     = $hd->get_ghichu();
    $tongtien   = $hd->get_tongtien();

    $stmt->bind_param(
        "iissssssssisi",
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
        $trangthai,
        $ghichu,
        $tongtien
    );
    return $stmt->execute();
}

    public function update(nguyen_hoadon $hd) {
        $sql = "UPDATE hoadon SET
                    idtaikhoan = ?,
                    idxe = ?,
                    diemlay = ?,
                    diemtra = ?,
                    ngaymuon = ?,
                    ngaytra = ?,
                    hoten = ?,
                    email = ?,
                    sdt = ?,
                    cccd = ?,
                    trangthai = ?,
                    ghichu = ?,
                    tongtien = ?
                WHERE idhoadon = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "iissssssssisdi",
            $hd->get_idtaikhoan(),
            $hd->get_idxe(),
            $hd->get_diemlay(),
            $hd->get_diemtra(),
            $hd->get_ngaymuon(),
            $hd->get_ngaytra(),
            $hd->get_hoten(),
            $hd->get_email(),
            $hd->get_sdt(),
            $hd->get_cccd(),
            $hd->get_trangthai(),
            $hd->get_ghichu(),
            $hd->get_tongtien(),
            $hd->get_idhoadon()
        );
        return $stmt->execute();
    }

    public function delete($idhoadon) {
        $sql = "DELETE FROM hoadon WHERE idhoadon = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idhoadon);
        return $stmt->execute();
    }

    public function isXeDangDuocThue($idxe) {
        $sql = "SELECT COUNT(*) AS total 
                FROM hoadon 
                WHERE idxe = ? AND trangthai = 0";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idxe);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['total'] > 0;
    }

    public function getthanhtoanbyidhoadon($idhoadon){
        $sql = "SELECT * FROM thanhtoan WHERE idhoadon = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idhoadon);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
