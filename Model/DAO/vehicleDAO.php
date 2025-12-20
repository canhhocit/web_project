<?php
require_once(__DIR__ . "/../Object/xe.php");
require_once(__DIR__ . "/../Object/anhxe.php");
require_once(__DIR__ . "/../Object/hangxe.php");

class vehicleDAO
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function addHangxe($hangxe)
    {
        $tenhangxe = $hangxe->get_tenhang();
        $sql = "INSERT INTO hangxe (tenhangxe) VALUES ('$tenhangxe')";
        return mysqli_query($this->conn, $sql);
    }

    public function deleteHangxe($idhangxe)
    {
        $sql = "DELETE FROM hangxe WHERE idhangxe = '$idhangxe'";
        return mysqli_query($this->conn, $sql);
    }

    public function updateHangxe($hangxe)
    {
        $idhangxe = $hangxe->get_idhangxe();
        $tenhangxe = $hangxe->get_tenhang();
        $sql = "UPDATE hangxe SET tenhangxe = '$tenhangxe' WHERE idhangxe = '$idhangxe'";
        return mysqli_query($this->conn, $sql);
    }

    public function getAllHangxe()
    {
        $sql = "SELECT * FROM hangxe";
        $result = mysqli_query($this->conn, $sql);
        $list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = new hangxe($row['idhangxe'], $row['tenhangxe']);
        }
        return $list;
    }
    private function getHangxebyDK($dk)
    {
        $sql = "SELECT * FROM hangxe WHERE $dk";
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }
    public function getTenhangxebyIdhangxe($idhangxe)
    {
        $result = $this->getHangxebyDK("idhangxe = '$idhangxe'");
        $row = mysqli_fetch_assoc($result);
        return $row["tenhangxe"];
    }
    public function getIDhangxebyTenhangxe($tenhangxe)
    {
        $result = $this->getHangxebyDK("tenhangxe = '$tenhangxe'");
        $row = mysqli_fetch_assoc($result);
        return $row["idhangxe"];
    }

    public function addXe($xe)
    {
        $idhangxe = $xe->get_idhangxe();
        $tenxe = $xe->get_tenxe();
        $giathue = $xe->get_giathue();
        $loaixe = $xe->get_loaixe();
        $sql = "INSERT INTO xe (idhangxe, tenxe, giathue, loaixe) VALUES ('$idhangxe', '$tenxe', '$giathue', '$loaixe')";
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
        $idhangxe = $xe->get_idhangxe();
        $tenxe = $xe->get_tenxe();
        $giathue = $xe->get_giathue();
        $loaixe = $xe->get_loaixe();
        $sql = "UPDATE xe SET idhangxe='$idhangxe', tenxe='$tenxe', giathue='$giathue', loaixe='$loaixe' WHERE idxe='$idxe'";
        return mysqli_query($this->conn, $sql);
    }

    private function fetchXeList($sql)
    {
        $result = mysqli_query($this->conn, $sql);
        $list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = new xe($row['idxe'], $row['idhangxe'], $row['tenxe'], $row['giathue'], $row['mota'], $row['loaixe'], $row['idchuxe']);
        }
        return $list;
    }

    public function getAllXe()
    {
        return $this->fetchXeList("SELECT * FROM xe");
    }
    public function getXebyIdxe($idxe)
    {
        return $this->fetchXeList("SELECT * FROM xe WHERE idxe = '$idxe'");
    }
    public function getXebyLoaixe($loaixe)
    {
        return $this->fetchXeList("SELECT * FROM xe WHERE loaixe = '$loaixe'");
    }
    public function getXebyHangxe($idhangxe)
    {
        return $this->fetchXeList("SELECT * FROM xe WHERE idhangxe = '$idhangxe'");
    }
    public function getXebyLoaixeAndHangxe($loaixe, $idhangxe)
    {
        return $this->fetchXeList("SELECT * FROM xe WHERE loaixe = '$loaixe' AND idhangxe = '$idhangxe'");
    }
    public function getXebyTenxe($tenxe)
    {
        return $this->fetchXeList("SELECT * FROM xe WHERE tenxe LIKE '%$tenxe%'");
    }

    public function addAnhxe($anhxe)
    {
        $idanh = $anhxe->get_idanh();
        $idxe = $anhxe->get_idxe();
        $duongdan = $anhxe->get_duongdan();
        $sql = "INSERT INTO anhxe VALUES ('$idanh', '$idxe', '$duongdan')";
        return mysqli_query($this->conn, $sql);
    }

    public function deleteAnhxe($idanh)
    {
        $sql = "DELETE FROM anhxe WHERE idanh = '$idanh'";
        return mysqli_query($this->conn, $sql);
    }

    public function updateAnhxe($anhxe)
    {
        $idanh = $anhxe->get_idanh();
        $idxe = $anhxe->get_idxe();
        $duongdan = $anhxe->get_duongdan();
        $sql = "UPDATE anhxe SET idxe='$idxe', duongdan='$duongdan' WHERE idanh='$idanh'";
        return mysqli_query($this->conn, $sql);
    }

    public function getAnhxebyIdxe($idxe)
    {
        $sql = "SELECT * FROM anhxe WHERE idxe = '$idxe'";
        $result = mysqli_query($this->conn, $sql);
        $list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = new anhxe($row['idanh'], $row['idxe'], $row['duongdan']);
        }
        return $list;
    }

    public function getDanhSachXeHienThi() {
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
    $sql = "SELECT x.*, h.tenhangxe AS tenhang, a.duongdan AS hinh_anh
            FROM xe x 
            LEFT JOIN hangxe h ON x.idhangxe = h.idhangxe
            LEFT JOIN (SELECT * FROM anhxe GROUP BY idxe) a ON x.idxe = a.idxe 
            WHERE x.tenxe LIKE '%$keyword%' 
            ORDER BY x.idxe DESC";
            
    $result = mysqli_query($this->conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
}
?>