<?php
session_start();
define('ACCESSED_FROM_CONTROLLER', true); 
define('ACCESS_HOPLE', true);
include "Model/Database/dbconnect.php";
require_once "Model/DAO/vehicleDAO.php";

$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

if ($controller === 'taikhoan') {
    require_once "Controller/taikhoanController.php";
    require_once "Model/Object/taikhoan.php";
    require_once "Model/Object/thongtintaikhoan.php";
    $taikhoan = new taikhoanController();

    if ($action === 'checklogin' || $action === 'add' || $action === 'logout') {
        $taikhoan->$action();
        exit();
    }
}

include "header.php";


if ($controller == 'home') {
    echo '<div class="container" style="min-height: 500px; padding-top: 20px;">';
    echo "<h1>Chào mừng đến với Chợ Thuê Xe</h1>";
} else {
    echo '<div class="container" style="padding-top: 20px;">';
}

echo '<div class="container">';

switch ($controller) {
    case 'home':
        include_once "./Controller/HomeController.php";
        break;

    case 'car':
        require_once "Controller/CarController.php";
        $carCtrl = new CarController();
        if (method_exists($carCtrl, $action)) {
            $carCtrl->$action();
        } else {
            header("Location: index.php");
        }
        break;

    case 'vehicle':
        require_once "Controller/vehicleController.php"; 
        require_once "Model/Object/xe.php";
        require_once "Model/Object/anhxe.php";
        $vehicle = new vehicleController();
        if (method_exists($vehicle, $action)) {
            $vehicle->$action();
        } else {
            $vehicle->index();
        }
        break;

    case 'taikhoan':
        if (method_exists($taikhoan, $action)) {
            $taikhoan->$action();
        } else {
            $taikhoan->index();
        }
        break;

    default:
        echo "<h1>404 - Không tìm thấy trang</h1>";
        break;
}

echo '</div>'; 
echo '</div>'; 

include "footer.php";
?>