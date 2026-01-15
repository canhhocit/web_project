<?php

class chatDAO
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    // ============================================
    private function executeQueryGetList($sql)
    {
        $result = mysqli_query($this->conn, $sql);
        $list = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = $row;
        }
        return $list;
    }

    
    private function executeQueryGetOne($sql)
    {
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_assoc($result);
    }
    // ============================================
    // SQL helper
    
    private function getSQLNguoiKia($idtaikhoan)
    {
        return "
            IF(c.id_nguoi_thue = '$idtaikhoan', 
                IFNULL(tt_chuxe.hoten, 'Ẩn danh'), 
                IFNULL(tt_nguoithue.hoten, 'Ẩn danh')
            ) as ten_nguoi_kia,
            
            IF(c.id_nguoi_thue = '$idtaikhoan', 
                IFNULL(tt_chuxe.anhdaidien, ''), 
                IFNULL(tt_nguoithue.anhdaidien, '')
            ) as anhdaidien
        ";
    }

    // get tn cuoi
    private function getSQLTinNhanCuoi()
    {
        return "(
            SELECT noi_dung 
            FROM tin_nhan 
            WHERE id_cuoc_tc = c.id_cuoc_tc 
            ORDER BY thoi_gian_gui DESC 
            LIMIT 1
        ) as tin_nhan_cuoi";
    } 

     
    private function getSQLTinNhanChuaDoc($idtaikhoan)
    {
        return "(
            SELECT COUNT(*) 
            FROM tin_nhan 
            WHERE id_cuoc_tc = c.id_cuoc_tc 
                AND da_xem = 0 
                AND id_nguoi_gui != '$idtaikhoan'
        ) as chua_doc";
    }

    private function getSQLTongTinNhan()
    {
        return "(
            SELECT COUNT(*) 
            FROM tin_nhan 
            WHERE id_cuoc_tc = c.id_cuoc_tc
        ) as tong_tin_nhan";
    }

    //Điều kiện xóa mềm
    private function getSQLDieuKienXoaMem($idtaikhoan)
    {
        return "AND (
            (c.id_nguoi_thue = '$idtaikhoan' AND c.nguoi_thue_da_xoa = 0)
            OR
            (c.id_chu_xe = '$idtaikhoan' AND c.chu_xe_da_xoa = 0)
        )";
    }

    //JOIN  xe & thongtintaikhoan   
    private function getSQL_join()
    {
        return "
            JOIN xe ON c.id_xe = xe.idxe
            LEFT JOIN thongtintaikhoan tt_chuxe 
                ON c.id_chu_xe = tt_chuxe.idtaikhoan
            LEFT JOIN thongtintaikhoan tt_nguoithue 
                ON c.id_nguoi_thue = tt_nguoithue.idtaikhoan
        ";
    }

    // ============================================
    // functions

    public function getDanhSachCuocTroChuyenByUser($idtaikhoan)
    {
        $sql = "
            SELECT 
                c.id_cuoc_tc,
                c.id_xe,
                c.id_nguoi_thue,
                c.id_chu_xe,
                c.cap_nhat_cuoi,
                xe.tenxe,
                
                {$this->getSQLNguoiKia($idtaikhoan)},
                {$this->getSQLTinNhanCuoi()},
                {$this->getSQLTinNhanChuaDoc($idtaikhoan)},
                {$this->getSQLTongTinNhan()},
                
                DATE_FORMAT(c.cap_nhat_cuoi, '%d/%m %H:%i') as thoi_gian_cuoi
                
            FROM cuoc_tro_chuyen c
            {$this->getSQL_join()}
            WHERE (c.id_nguoi_thue = '$idtaikhoan' OR c.id_chu_xe = '$idtaikhoan')
            {$this->getSQLDieuKienXoaMem($idtaikhoan)}
            HAVING tong_tin_nhan > 0
            ORDER BY c.cap_nhat_cuoi DESC
        ";

        return $this->executeQueryGetList($sql);
    }

    
    public function getThongTinCuocTroChuyenById($idCuocTC, $idtaikhoan)
    {
        $sql = "
            SELECT 
                c.*,
                xe.tenxe,
                xe.idxe,
                {$this->getSQLNguoiKia($idtaikhoan)}
            FROM cuoc_tro_chuyen c
            {$this->getSQL_join()}
            WHERE c.id_cuoc_tc = '$idCuocTC'
                AND (c.id_nguoi_thue = '$idtaikhoan' OR c.id_chu_xe = '$idtaikhoan')
        ";

        return $this->executeQueryGetOne($sql);
    }

    // check exists
    public function getCuocTroChuyenByXe($idNguoiThue, $idChuXe, $idXe)
    {
        $sql = "
            SELECT id_cuoc_tc 
            FROM cuoc_tro_chuyen 
            WHERE id_nguoi_thue = '$idNguoiThue' 
                AND id_chu_xe = '$idChuXe' 
                AND id_xe = '$idXe'
        ";

        $result = mysqli_query($this->conn, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row ? $row['id_cuoc_tc'] : false;
    }

    public function taoCuocTroChuyenMoi($idNguoiThue, $idChuXe, $idXe)
    {
        $sql = "
            INSERT INTO cuoc_tro_chuyen (id_nguoi_thue, id_chu_xe, id_xe) 
            VALUES ('$idNguoiThue', '$idChuXe', '$idXe')
        ";

        if (mysqli_query($this->conn, $sql)) {
            return mysqli_insert_id($this->conn);
        }
        return false;
    }

    // msg ============================================
    

    
    //  Lấy ds msg cua 1 cuoc TC
    //  mình (is_mine = 1) và người khác (is_mine = 0)
    //  sx theo time  old ->new

    public function getDanhSachTinNhanByCuocTC($idCuocTC, $idtaikhoan)
    {
        $sql = "
            SELECT 
                *,
                IF(id_nguoi_gui = '$idtaikhoan', 1, 0) as is_mine,
                DATE_FORMAT(thoi_gian_gui, '%H:%i %d/%m') as thoi_gian
            FROM tin_nhan
            WHERE id_cuoc_tc = '$idCuocTC'
            ORDER BY thoi_gian_gui ASC
        ";

        return $this->executeQueryGetList($sql);
    }

 
    // new msg
    // khoi phuc neu da bi xoa o 1 phia
   
    public function guiTinNhan($idCuocTC, $idNguoiGui, $noiDung)
    {
        $this->khoiPhucCuocTroChuyenKhiCoTinNhanMoi($idCuocTC, $idNguoiGui);
        // ins new msg
        $sql = "
            INSERT INTO tin_nhan (id_cuoc_tc, id_nguoi_gui, noi_dung) 
            VALUES ('$idCuocTC', '$idNguoiGui', '$noiDung')
        ";
        return mysqli_query($this->conn, $sql);
    }

    
    public function markAsRead($idCuocTC, $idtaikhoan)
    {
        $sql = "
            UPDATE tin_nhan 
            SET da_xem = 1 
            WHERE id_cuoc_tc = '$idCuocTC' 
                AND id_nguoi_gui != '$idtaikhoan' 
                AND da_xem = 0
        ";

        return mysqli_query($this->conn, $sql);
    }

    // ============================= DELETE
    
    public function xoaMem($idCuocTC, $idtaikhoan)
    {
        $thongTin = $this->getInf_cuocTC_del($idCuocTC);
        if (!$thongTin) return false;

        // Xác định người xóa là ai
        $column = $this->xacDinhCotXoaMem($thongTin, $idtaikhoan);
        if (!$column) return false;

        // Update cờ xóa
        $result = $this->capNhatCoXoa($idCuocTC, $column);

        // if(deuxoa) xoa all
        if ($result) {
            $this->del_Vinhvien($idCuocTC);
        }

        return $result;
    }

    
    private function getInf_cuocTC_del($idCuocTC)
    {
        $sql = "
            SELECT 
                id_nguoi_thue, 
                id_chu_xe, 
                nguoi_thue_da_xoa, 
                chu_xe_da_xoa
            FROM cuoc_tro_chuyen 
            WHERE id_cuoc_tc = '$idCuocTC'
        ";

        return $this->executeQueryGetOne($sql);
    }

    
    //  Xác định cột 
    private function xacDinhCotXoaMem($thongTin, $idtaikhoan)
    {
        if ($thongTin['id_nguoi_thue'] == $idtaikhoan) {
            return 'nguoi_thue_da_xoa';
        } else if ($thongTin['id_chu_xe'] == $idtaikhoan) {
            return 'chu_xe_da_xoa';
        }
        return false;
    }

    //update cờ
    private function capNhatCoXoa($idCuocTC, $column)
    {
        $sql = "
            UPDATE cuoc_tro_chuyen 
            SET $column = 1 
            WHERE id_cuoc_tc = '$idCuocTC'
        ";

        return mysqli_query($this->conn, $sql);
    }
    private function del_Vinhvien($idCuocTC)
    {
        $thongTin = $this->getInf_cuocTC_del($idCuocTC);

        if ($thongTin && 
            $thongTin['nguoi_thue_da_xoa'] == 1 && 
            $thongTin['chu_xe_da_xoa'] == 1) {
            
            $this->delMsgandConver($idCuocTC);
        }
    }

  
    private function delMsgandConver($idCuocTC)
    {
        mysqli_query($this->conn, "DELETE FROM tin_nhan WHERE id_cuoc_tc = '$idCuocTC'");

        $sql = "DELETE FROM cuoc_tro_chuyen WHERE id_cuoc_tc = '$idCuocTC'";
        return mysqli_query($this->conn, $sql);
    }

    public function xoaCuocTroChuyenRong()
    {
        $sql = "
            DELETE FROM cuoc_tro_chuyen 
            WHERE id_cuoc_tc NOT IN (
                SELECT DISTINCT id_cuoc_tc 
                FROM tin_nhan
            )
        ";

        return mysqli_query($this->conn, $sql);
    }

    public function khoiPhucCuocTroChuyenKhiCoTinNhanMoi($idCuocTC, $idNguoiGui)
    {
        $sql = "
            UPDATE cuoc_tro_chuyen 
            SET nguoi_thue_da_xoa = 0, 
                chu_xe_da_xoa = 0 
            WHERE id_cuoc_tc = '$idCuocTC'
        ";

        mysqli_query($this->conn, $sql);
    }

    public function getIdNguoiConLai($idCuocTC, $idNguoiGui){
    $sql = "
        SELECT id_nguoi_thue, id_chu_xe
        FROM cuoc_tro_chuyen
        WHERE id_cuoc_tc = '$idCuocTC'
        LIMIT 1
    ";

    $row = $this->executeQueryGetOne($sql);

    if (!$row) return false;

    if ($row['id_nguoi_thue'] == $idNguoiGui) {
        return $row['id_chu_xe'];
    }

    if ($row['id_chu_xe'] == $idNguoiGui) {
        return $row['id_nguoi_thue'];
    }

    return false;
    }

}