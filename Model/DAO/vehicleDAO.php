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
        $hangxe = $xe->get_hangxe(); 
        $giathue = $xe->get_giathue();
        $mota    = $xe->get_mota();
        $loaixe  = $xe->get_loaixe();
        $idchuxe = $xe->get_idchuxe();

        $sql = "INSERT INTO xe (tenxe, hangxe, giathue, mota, loaixe, idchuxe, ngaydang)

         VALUES ('$tenxe', '$hangxe', '$giathue', '$mota', '$loaixe', '$idchuxe', CURDATE())";


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
        $hangxe = $xe->get_hangxe();
        $giathue = $xe->get_giathue();
        $mota = $xe->get_mota();
        $loaixe = $xe->get_loaixe();

        $sql = "UPDATE xe SET tenxe='$tenxe', hangxe='$hangxe', giathue='$giathue', mota='$mota', loaixe='$loaixe'
            WHERE idxe='$idxe'";

        return mysqli_query($this->conn, $sql);
    }

    private function fetchXeList($sql)
    {
        $result = mysqli_query($this->conn, $sql);
        $list = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $val_hangxe = isset($row['hangxe']) ? $row['hangxe'] : "Chưa rõ";
            
            $list[] = new xe(
                $row['idxe'],
                $row['tenxe'],
                $val_hangxe,
                $row['giathue'],
                $row['mota'],
                $row['loaixe'],
                $row['idchuxe'],
                $row['ngaydang']
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
    public function getXebyIdChuxe($idchuxe)
    {
        return $this->fetchXeList("SELECT * FROM xe WHERE idchuxe='$idchuxe'");
    }


    public function addAnhxe($anhxe)
    {
        $idxe = $anhxe->get_idxe();
        $duongdan = $anhxe->get_duongdan();
        $sql = "INSERT INTO anhxe (idxe, duongdan) VALUES ('$idxe', '$duongdan')";
        return mysqli_query($this->conn, $sql);
    }

    public function deleteAnhxebyIdXe($idxe)
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

   
    public function getDanhSachXeHienThi() {

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