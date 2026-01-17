<?php
    require_once "Model/DAO/vehicleDAO.php";
    require_once "Model/DAO/nguyen_hoadonDAO.php";
    require_once "Model/Database/dbconnect.php";
    require_once "Model/Object/xe.php";

    class HomeController{
        private $vehicleDAO;
        private $hoadonDAO;
        
        public function __construct(){
            global $conn;
            $this->vehicleDAO = new VehicleDAO($conn);
            $this->hoadonDAO = new nguyen_hoadonDAO($conn);
        }
        public function index(){
            $limit = 6;
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $page = (int)$page; 
            $offset = ($page -1) * $limit; 

            $tongSoXe = $this->vehicleDAO->countXe();
            $tongTrang = ceil($tongSoXe / $limit);

            // timf kiem xe 
            if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
                $keyword = $_GET['keyword'];
                $searchList = $this->vehicleDAO->timKiemXe($keyword);
                $listCar = $searchList;
                $tongTrang = 1; // Giới hạn kết quả tìm kiếm chỉ trong 1 trang

            } else {
                $searchList = $this->vehicleDAO->PhanTrang($offset, $limit);
                $listCar = $searchList;
            }
            $curentUserID = isset($_SESSION['idtaikhoan']) ? $_SESSION['idtaikhoan'] : 0;
            $listCar = [];
            $listCarImages = []; // Mảng chứa ảnh của từng xe
            
            if (!empty($searchList)) {
                foreach ($searchList as $car){
                    $idxe = $car->get_idxe();
                    
                    // Bỏ qua xe của chính mình và xe đang được thuê (trangthai = 0)
                    if ($car->get_idchuxe() != $curentUserID && !$this->hoadonDAO->isXeDangDuocThue($idxe)) {
                        $listCar[] = $car;
                        // Lấy ảnh cho xe này
                        $listCarImages[$idxe] = $this->vehicleDAO->getAnhxebyIdxe($idxe);
                    }
                }
            }
            include "./View/Home/home.php";
        }
    }

?>