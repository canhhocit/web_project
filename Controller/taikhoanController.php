<?php
require_once "../Model/Database/dbconnect.php";
require_once "../Model/DAO/taikhoanDAO.php";
require_once "../Model/Object/taikhoan.php";
require_once "../Model/Object/thongtintaikhoan.php";

class taikhoanController
{
    private $dao;

    public function __construct()
    {
        global $conn;
        $this->dao = new taikhoanDAO($conn);
    }

    public function index()
    {
        //$vehicles = $this->dao->getAll();
        include_once "../View/vehicle/index.php";
    }
}