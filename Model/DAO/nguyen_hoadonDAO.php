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
                $row['trangthai'],
                $row['ghichu'],
                $row['tongtien']
            );
        }

        return $list;
    }

    public function getById($idhoadon) {
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
                $row['trangthai'],
                $row['ghichu'],
                $row['tongtien']
            );
        }

        return null;
    }

    public function insert(nguyen_hoadon $hd) {
        $sql = "INSERT INTO hoadon
                (idtaikhoan, idxe, diemlay, diemtra, ngaymuon, ngaytra, trangthai, ghichu, tongtien)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "iissssisd",
            $hd->get_idtaikhoan(),
            $hd->get_idxe(),
            $hd->get_diemlay(),
            $hd->get_diemtra(),
            $hd->get_ngaymuon(),
            $hd->get_ngaytra(),
            $hd->get_trangthai(),
            $hd->get_ghichu(),
            $hd->get_tongtien()
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
                    trangthai = ?,
                    ghichu = ?,
                    tongtien = ?
                WHERE idhoadon = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "iissssidsi",
            $hd->get_idtaikhoan(),
            $hd->get_idxe(),
            $hd->get_diemlay(),
            $hd->get_diemtra(),
            $hd->get_ngaymuon(),
            $hd->get_ngaytra(),
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
}
