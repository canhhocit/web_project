<?php

class thongtinxe
{
    private $idthongtin;
    private $idxe;
    private $mota;
    public function __construct($idthongtin, $idxe, $mota){
        $this->idthongtin = $idthongtin;
        $this->idxe = $idxe;
        $this->mota = $mota;
    }
    public function get_idthongtin(){
        return $this->idthongtin;
    }
    public function get_idxe(){
        return $this->idxe;
    }
    public function get_mota(){
        return $this->mota;
    }
    public function set_idthongtin($idthongtin){
        $this->idthongtin = $idthongtin;
    }
    public function set_idxe($idxe){
        $this->idxe = $idxe;
    }
    public function set_mota($mota){
        $this->mota = $mota;
    }
}

?>