<?php
require_once "../Model/Database/dbconnect.php";
require_once "../Model/DAO/vehicleDAO.php";
require_once "../Model/Object/xe.php";
require_once "../Model/Object/hangxe.php";
require_once "../Model/Object/anhxe.php";

class vehicleController
{
    private $dao;

    public function __construct()
    {
        global $conn;
        $this->dao = new vehicleDAO($conn);
    }

    public function index()
    {
        //$vehicles = $this->dao->getAll();
        include_once "../View/vehicle/index.php";
    }
}
?>