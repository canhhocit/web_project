<?php
    require_once "Model/DAO/vehicleDAO.php";
    require_once "Model/Database/dbconnect.php";
    require_once "Model/Object/xe.php";

    class HomeController{
        private $vehicleDAO;
        public function __construct(){
            global $conn;
            $this->vehicleDAO = new VehicleDAO($conn);
        }
        public function index(){
            // timf kiem xe 
            if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
                $keyword = $_GET['keyword'];
                $searchList = $this->vehicleDAO->timKiemXe($keyword);
            } else {
                $searchList = $this->vehicleDAO->getAllXe();
            }
            $curentUserID = isset($_SESSION['idtaikhoan']) ? $_SESSION['idtaikhoan'] : 0;
            $listCar = [];
            if (!empty($searchList)) {
                foreach ($searchList as $car){
                    if ($car->get_idchuxe() != $curentUserID) {
                        $listCar[] = $car;
                    }
                }
            }
            include "./View/Home/home.php";
        }
    }

?>