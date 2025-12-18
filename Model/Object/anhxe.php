<?php
class anhxe{
    private $idanh;
    private $idxe;
    private $duongdan;
    
    public function __construct($idanh, $idxe, $duongdan){
        $this->idanh = $idanh;
        $this->idxe = $idxe;
        $this->duongdan = $duongdan;
    }


    public function get_idanh(){
        return $this->idanh;
    }
    public function get_idxe(){
        return $this->idxe;
    }
    public function get_duongdan(){
        return $this->duongdan;
    }
    public function set_idanh($idanh){
        $this->idanh = $idanh;
    }
    public function set_idxe($idxe){
        $this->idxe = $idxe;
    }
    public function set_duongdan($duongdan){
        $this->duongdan = $duongdan;
    }
}