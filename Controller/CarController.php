<?php 

   require_once "Model/DAO/vehicleDAO.php";
   require_once "Model/Database/dbconnect.php";
   class CarController {
    private $vehicleDAO;
    public function __construct()
    {
        global $conn;
        $this->vehicleDAO = new vehicleDAO($conn);
    }

    public function detail(){
        if(isset($_GET["id"])){
            $id = $_GET['id'];
            $xe = $this->vehicleDAO->getChiTietXe($id);
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