<?php
class xe
{
    private $idxe;
    private $tenxe;
    private $hangxe;
    private $giathue;
    private $mota;
    private $loaixe;
    private $idchuxe;
    private $ngaydang;


    public function __construct($idxe, $tenxe, $hangxe, $giathue, $mota, $loaixe, $idchuxe, $ngaydang)
    {
        $this->idxe = $idxe;
        $this->tenxe = $tenxe;
        $this->hangxe = $hangxe;
        $this->giathue = $giathue;
        $this->mota = $mota;
        $this->loaixe = $loaixe;
        $this->idchuxe = $idchuxe;
        $this->ngaydang = $ngaydang;
    }

    public function get_ngaydang()
    {
        return $this->ngaydang;
    }

    public function set_ngaydang($ngaydang)
    {
        $this->ngaydang = $ngaydang;
    }

    public function get_idxe()
    {
        return $this->idxe;
    }

    public function get_tenxe()
    {
        return $this->tenxe;
    }

    public function get_hangxe()
    {
        return $this->hangxe;
    }

    public function get_giathue()
    {
        return $this->giathue;
    }

    public function get_mota()
    {
        return $this->mota;
    }

    public function get_loaixe()
    {
        return $this->loaixe;
    }

    public function get_idchuxe()
    {
        return $this->idchuxe;
    }

    public function set_idxe($idxe)
    {
        $this->idxe = $idxe;
    }

    public function set_tenxe($tenxe)
    {
        $this->tenxe = $tenxe;
    }

    public function set_hangxe($hangxe)
    {
        $this->hangxe = $hangxe;
    }

    public function set_giathue($giathue)
    {
        $this->giathue = $giathue;
    }

    public function set_mota($mota)
    {
        $this->mota = $mota;
    }

    public function set_loaixe($loaixe)
    {
        $this->loaixe = $loaixe;
    }

    public function set_idchuxe($idchuxe)
    {
        $this->idchuxe = $idchuxe;
    }
}
