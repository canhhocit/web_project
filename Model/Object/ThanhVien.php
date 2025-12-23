<?php
class ThanhVien {
    private $id;
    private $ten_thanhvien;
    private $mssv;
    private $cong_viec;

    public function __construct($id, $ten_thanhvien, $mssv, $cong_viec) {
        $this->id = $id;
        $this->ten_thanhvien = $ten_thanhvien;
        $this->mssv = $mssv;
        $this->cong_viec = $cong_viec;
    }

    public function get_id() { 
        return $this->id; 
    }

    public function get_ten() { 
        return $this->ten_thanhvien; 
    }


    public function get_mssv() { 
        return $this->mssv; 
    }

    public function get_congviec() { 
        return $this->cong_viec; 
    }
}
?>