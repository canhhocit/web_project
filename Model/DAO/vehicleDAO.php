<?php
require_once __DIR__ . "/../Object/xe.php";
require_once __DIR__ . "/../Object/anhxe.php";
// Bỏ require hangxe.php để tránh lỗi thiếu file

class vehicleDAO
{
    public $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // =========================================================
    // PHẦN 1: CRUD XE (Đã sửa về idhangxe cho khớp DB của bạn)
    // =========================================================

    public function addXe($xe)
    {
        $tenxe = $xe->get_tenxe();
        // Lấy thông tin hãng (ID)
        $idhangxe = $xe->get_hangxe(); 
        $giathue = $xe->get_giathue();
        $mota    = $xe->get_mota();
        $loaixe  = $xe->get_loaixe();
        $idchuxe = $xe->get_idchuxe();

        // Sửa: Insert vào cột idhangxe
        $sql = "INSERT INTO xe (tenxe, idhangxe, giathue, mota, loaixe, idchuxe)
                VALUES ('$tenxe', '$idhangxe', '$giathue', '$mota', '$loaixe', '$idchuxe')";

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
        $idhangxe = $xe->get_hangxe();
        $giathue = $xe->get_giathue();
        $mota = $xe->get_mota();
        $loaixe = $xe->get_loaixe();

        // Sửa: Update cột idhangxe
        $sql = "UPDATE xe SET tenxe='$tenxe', idhangxe='$idhangxe', giathue='$giathue', mota='$mota', loaixe='$loaixe'
            WHERE idxe='$idxe'";

        return mysqli_query($this->conn, $sql);
    }

    private function fetchXeList($sql)
    {
        $result = mysqli_query($this->conn, $sql);
        $list = [];

        while ($row = mysqli_fetch_assoc($result)) {
            // Map dữ liệu: Nếu DB trả về idhangxe thì đưa vào object
            // Xử lý an toàn: Ưu tiên idhangxe, nếu không có thì tìm hangxe
            $val_hangxe = isset($row['idhangxe']) ? $row['idhangxe'] : (isset($row['hangxe']) ? $row['hangxe'] : 0);
            
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
    // PHẦN 2: ẢNH XE (Giữ nguyên)
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
    // PHẦN 3: HIỂN THỊ (QUAN TRỌNG: Khôi phục JOIN bảng để sửa lỗi)
    // =========================================================
    
    public function getDanhSachXeHienThi() {
        // Sửa: Dùng JOIN bảng hangxe như cũ vì DB bạn vẫn còn bảng này (chỉ mất file PHP thôi)
        // Nếu DB cũng mất bảng hangxe, báo mình sửa lại dòng này thành lấy idhangxe
        $sql = "SELECT x.*, h.tenhangxe AS tenhang, a.duongdan AS hinh_anh
                FROM xe x 
                LEFT JOIN hangxe h ON x.idhangxe = h.idhangxe
                LEFT JOIN (
                    SELECT * FROM anhxe GROUP BY idxe
                ) a ON x.idxe = a.idxe 
                ORDER BY x.idxe DESC";
                
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getChiTietXe($idxe)
    {
        // Sửa: Dùng JOIN bảng hangxe
        $sql = "SELECT x.*, h.tenhangxe, t.hoten as tenchuxe, t.sdt 
                FROM xe x 
                LEFT JOIN hangxe h ON x.idhangxe = h.idhangxe
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
        // Sửa: Tìm theo tên hãng từ bảng hangxe
        $sql = "SELECT x.*, h.tenhangxe AS tenhang, a.duongdan AS hinh_anh
                FROM xe x 
                LEFT JOIN hangxe h ON x.idhangxe = h.idhangxe
                LEFT JOIN (SELECT * FROM anhxe GROUP BY idxe) a ON x.idxe = a.idxe 
                WHERE x.tenxe LIKE '%$keyword%' OR h.tenhangxe LIKE '%$keyword%'
                ORDER BY x.idxe DESC";
                
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
?>