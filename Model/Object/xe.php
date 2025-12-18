<?php 
class xe{
    private $idxe;
    private $idhangxe;
    private $tenxe;
    private $giathue;
    private $loaixe;
    
    public function __construct($idxe, $idhangxe, $tenxe, $giathue, $loaixe){
        $this->idxe = $idxe;
        $this->idhangxe = $idhangxe;
        $this->tenxe = $tenxe;
        $this->giathue = $giathue;
        $this->loaixe = $loaixe;
    }
    //get set
    public function get_idhangxe(){
        return $this->idhangxe;
    }
    public function get_idxe(){
        return $this->idxe;
    }
    public function get_tenxe(){
        return $this->tenxe;
    }
    public function get_giathue(){
        return $this->giathue;
    }
    public function get_loaixe(){
        return $this->loaixe;
    }

    public function set_idhangxe($idhangxe){
        $this->idhangxe = $idhangxe;
    }
    public function set_idxe($idxe){
        $this->idxe = $idxe;
    }
    public function set_tenxe($tenxe){
        $this->tenxe = $tenxe;
    }
    public function set_giathue($giathue){
        $this->giathue = $giathue;
    }
    public function set_loaixe($loaixe){
        $this->loaixe = $loaixe;
    }
}


?>