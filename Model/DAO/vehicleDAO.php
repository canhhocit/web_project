<?php
require_once __DIR__ . "/../Object/xe.php";
require_once __DIR__ . "/../Object/anhxe.php";

class vehicleDAO
{
    public $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function addXe($xe)
    {
        $tenxe = $xe->get_tenxe();
        // SỬA: Lấy tên hãng (string) thay vì ID, vì DB lưu tên
        $hangxe = $xe->get_hangxe(); 
        $giathue = $xe->get_giathue();
        $mota    = $xe->get_mota();
        $loaixe  = $xe->get_loaixe();
        $idchuxe = $xe->get_idchuxe();

        // SỬA: Thay idhangxe bằng hangxe, thêm ngaydang
        $sql = "INSERT INTO xe (tenxe, hangxe, giathue, mota, loaixe, idchuxe, ngaydang)
                VALUES ('$tenxe', '$hangxe', '$giathue', '$mota', '$loaixe', '$idchuxe', NOW())";

        return mysqli_query($this->conn, $sql);
    }

    public function deleteXe($idxe)
    {
        $sql = "DELETE FROM xe WHERE idxe = '$idxe'";
        return mysqli_query($this->conn, $sql);
    }

    public function updateXe($xe)
    {
        $idxe = $xe->get_idxe();
        $tenxe = $xe->get_tenxe();
        $hangxe = $xe->get_hangxe(); // SỬA: Lấy tên hãng
        $giathue = $xe->get_giathue();
        $mota = $xe->get_mota();
        $loaixe = $xe->get_loaixe();

        // SỬA: Update cột hangxe (thay vì idhangxe)
        $sql = "UPDATE xe SET tenxe='$tenxe', hangxe='$hangxe', giathue='$giathue', mota='$mota', loaixe='$loaixe'
            WHERE idxe='$idxe'";

        return mysqli_query($this->conn, $sql);
    }

    private function fetchXeList($sql)
    {
        $result = mysqli_query($this->conn, $sql);
        $list = [];

        while ($row = mysqli_fetch_assoc($result)) {
            // SỬA: DB chỉ có cột hangxe, lấy trực tiếp
            $val_hangxe = isset($row['hangxe']) ? $row['hangxe'] : "Chưa rõ";
            
            $list[] = new xe(
                $row['idxe'],
                $row['tenxe'],
                $val_hangxe,
                $row['giathue'],
                $row['mota'],
                $row['loaixe'],
                $row['idchuxe']
            );
        }
        return $list;
    }

    public function getAllXe()
    {
        return $this->fetchXeList("SELECT * FROM xe");
    }

    public function getXebyIdxe($idxe)
    {
        return $this->fetchXeList("SELECT * FROM xe WHERE idxe='$idxe'");
    }

    // =========================================================
    // PHẦN 2: ẢNH XE (Giữ nguyên - Không lỗi)
    // =========================================================

    public function addAnhxe($anhxe)
    {
        $idxe = $anhxe->get_idxe();
        $duongdan = $anhxe->get_duongdan();
        $sql = "INSERT INTO anhxe (idxe, duongdan) VALUES ('$idxe', '$duongdan')";
        return mysqli_query($this->conn, $sql);
    }

    public function deleteAnhxebyidXe($idxe)
    {
        return mysqli_query($this->conn, "DELETE FROM anhxe WHERE idxe='$idxe'");
    }

    public function getAnhxebyIdxe($idxe)
    {
        $sql = "SELECT * FROM anhxe WHERE idxe='$idxe'";
        $result = mysqli_query($this->conn, $sql);
        $list = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = new anhxe($row['idanh'], $row['idxe'], $row['duongdan']);
        }
        return $list;
    }

    // =========================================================
    // PHẦN 3: HIỂN THỊ (QUAN TRỌNG: Đã sửa bỏ JOIN bảng hangxe)
    // =========================================================
    
    public function getDanhSachXeHienThi() {
        // SỬA: Bỏ JOIN hangxe vì bảng này không tồn tại.
        // Lấy trực tiếp x.hangxe đặt alias là tenhang để khớp với View home.php
        $sql = "SELECT x.*, x.hangxe AS tenhang, a.duongdan AS hinh_anh
                FROM xe x 
                LEFT JOIN (
                    SELECT * FROM anhxe GROUP BY idxe
                ) a ON x.idxe = a.idxe 
                ORDER BY x.idxe DESC";
                
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getChiTietXe($idxe)
    {
        // SỬA: Bỏ JOIN hangxe.
        // Lấy trực tiếp x.hangxe đặt alias là tenhangxe để khớp với View carDetail.php
        $sql = "SELECT x.*, x.hangxe AS tenhangxe, t.hoten as tenchuxe, t.sdt 
                FROM xe x 
                LEFT JOIN thongtintaikhoan t ON x.idchuxe = t.idtaikhoan
                WHERE x.idxe = '$idxe'";
                
        $result = mysqli_query($this->conn, $sql);
        $xe = mysqli_fetch_assoc($result);

        if ($xe) {
            $sqlAnh = "SELECT * FROM anhxe WHERE idxe = '$idxe'";
            $resultAnh = mysqli_query($this->conn, $sqlAnh);
            
            $arrAnh = array();
            while ($row = mysqli_fetch_assoc($resultAnh)) {
                $arrAnh[] = $row['duongdan']; 
            }
            $xe['ds_anh'] = $arrAnh;
        }
        return $xe;
    }

    public function timKiemXe($keyword) {
        // SỬA: Tìm kiếm trực tiếp trên cột hangxe của bảng xe
        $sql = "SELECT x.*, x.hangxe AS tenhang, a.duongdan AS hinh_anh
                FROM xe x 
                LEFT JOIN (SELECT * FROM anhxe GROUP BY idxe) a ON x.idxe = a.idxe 
                WHERE x.tenxe LIKE '%$keyword%' OR x.hangxe LIKE '%$keyword%'
                ORDER BY x.idxe DESC";
                
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
?>