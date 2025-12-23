<?php
require_once __DIR__ . "/../Database/dbconnect.php";
require_once __DIR__ . "/../Object/nguyen_hoadon.php";

class nguyen_hoadonDAO {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /* ================= GET ALL ================= */
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

    /* ================= GET BY ID ================= */
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
                $row['hoten'],
                $row['email'],
                $row['sdt'],
                $row['cccd'],
                $row['trangthai'],
                $row['ghichu'],
                $row['tongtien']
            );
        }

        return null;
    }

    /* ================= INSERT ================= */
    public function insert(nguyen_hoadon $hd) {

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

        // trangthai mặc định = 0 (chưa thanh toán / đang thuê)
        $trangthai = 0;

        $stmt->bind_param(
            "iissssssssisd",
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
            $trangthai,
            $hd->get_ghichu(),
            $hd->get_tongtien()
        );

        return $stmt->execute();
    }

    /* ================= UPDATE ================= */
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

    /* ================= DELETE ================= */
    public function delete($idhoadon) {
        $sql = "DELETE FROM hoadon WHERE idhoadon = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idhoadon);
        return $stmt->execute();
    }
}
