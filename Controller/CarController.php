<?php 
require_once "Model/DAO/vehicleDAO.php";
require_once "Model/Database/dbconnect.php";

class CarController {
    private $vehicleDAO;
    public function __construct() {
        global $conn;
        $this->vehicleDAO = new vehicleDAO($conn);
    }

    public function detail(){
        if(isset($_GET["id"])){
            $id = $_GET['id'];
            $xe = $this->vehicleDAO->getChiTietXe($id);
            
            if ($xe){
                $currentUserId = isset($_SESSION['idtaikhoan']) ? $_SESSION['idtaikhoan'] : 0;
                $isOwner = ($currentUserId != 0 && $currentUserId == $xe['idchuxe']);

                include "./View/Home/carDetail.php";
            } else {
                header("Location: index.php");
            }
        }
    }
    
    public function mycars(){
         header("Location: index.php?controller=taikhoan&action=personal&selection=cars");
    }
}
?>