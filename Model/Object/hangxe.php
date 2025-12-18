<?php

class hangxe{
    private $idhangxe;
    private $tenhang;
    
    public function __construct($idhangxe, $tenhang){
        $this->idhangxe = $idhangxe;
        $this->tenhang = $tenhang;
    }
    public function get_idhangxe(){
        return $this->idhangxe;
    }
    public function get_tenhang(){
        return $this->tenhang;
    }
    public function set_idhangxe($idhangxe){
        $this->idhangxe = $idhangxe;
    }
    public function set_tenhang($tenhang){
        $this->tenhang = $tenhang;
    }
}

?>