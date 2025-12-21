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

    /*  XE  */

    public function addXe($xe)
    {
        $tenxe = $xe->get_tenxe();
        $hangxe = $xe->get_hangxe();
        $giathue = $xe->get_giathue();
        $mota  = $xe->get_mota();
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

        $sql = "UPDATE xe SET tenxe='$tenxe',hangxe='$hangxe', giathue='$giathue',mota='$mota', loaixe='$loaixe'
            WHERE idxe='$idxe'
        ";

        return mysqli_query($this->conn, $sql);
    }

    private function fetchXeList($sql)
    {
        $result = mysqli_query($this->conn, $sql);
        $list = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = new xe(
                $row['idxe'],
                $row['tenxe'],
                $row['hangxe'],
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

    public function getXebyLoaixe($loaixe)
    {
        return $this->fetchXeList("SELECT * FROM xe WHERE loaixe='$loaixe'");
    }
    public function getXebyHangxe($hangxe)
    {
        return $this->fetchXeList("SELECT * FROM xe WHERE hangxe='$hangxe'");
    }

    public function getXebyLoaixeAndHangxe($loaixe, $hangxe)
    {
        return $this->fetchXeList(
            "SELECT * FROM xe WHERE loaixe='$loaixe' AND hangxe='$hangxe'"
        );
    }

    public function getXebyTenxe($tenxe)
    {
        return $this->fetchXeList(
            "SELECT * FROM xe WHERE tenxe LIKE '%$tenxe%'"
        );
    }

    /*  ẢNH XE  */

    public function addAnhxe($anhxe)
    {
        $idxe = $anhxe->get_idxe();
        $duongdan = $anhxe->get_duongdan();

        $sql = "
            INSERT INTO anhxe (idxe, duongdan)
            VALUES ( '$idxe', '$duongdan')
        ";

        return mysqli_query($this->conn, $sql);
    }

    public function deleteAnhxebyIdXe($idxe)
    {
        return mysqli_query(
            $this->conn,
            "DELETE FROM anhxe WHERE idxe='$idxe'"
        );
    }


    public function getAnhxebyIdxe($idxe)
    {
        $sql = "SELECT * FROM anhxe WHERE idxe='$idxe'";
        $result = mysqli_query($this->conn, $sql);
        $list = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = new anhxe(
                $row['idanh'],
                $row['idxe'],
                $row['duongdan']
            );
        }
        return $list;
    }
}
