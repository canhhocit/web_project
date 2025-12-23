<?php 
   require_once "Model/DAO/vehicleDAO.php";
   require_once __DIR__ . "/../Model/DAO/favoriteVehicleDAO.php";
   require_once "Model/Database/dbconnect.php";
   class CarController {
    private $vehicleDAO;
    private $Fdao;
    public function __construct()
    {
        global $conn;
        $this->vehicleDAO = new vehicleDAO($conn);
        $this->Fdao = new favoriteVehicleDAO($conn);
    }

    public function detail(){
        if(isset($_GET["id"])){
            $idxe = $_GET['id'];
            $xe = $this->vehicleDAO->getChiTietXe($idxe);
            //canh: ktra tontai trong DB
            $exists =false;
            if($this->Fdao->checkExistsVehicle($_SESSION['idtaikhoan'], $idxe)){
                $exists = true;
            }
            //het canh
            if ($xe){
                include "./View/Home/carDetail.php";
            }else{
                header("Location: index.php");
            }
        }
    }
        public function mycars(){
            echo "....";
        }
    }
?>