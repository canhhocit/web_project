<?php
session_start();
define('ACCESS_HOPLE', true); 
include "Model/Database/dbconnect.php";
include "Model/DAO/CarDAO.php";
//sd session
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

if($controller === 'thanhtoan' && ($action === 'getChiTietHoaDon' || $action === 'xacNhanTraXe')) {
    require_once "Controller/ThanhToanController.php";
    $thanhtoan = new ThanhToanController($conn);
    $thanhtoan->$action();
    exit();
}

include "header.php";
echo '<div class="container" style="min-height: 500px; padding-top: 20px;">';

if($controller === 'home') { 
    echo "<h1>Chào mừng đến với Chợ Thuê Xe</h1>";
    echo "<p>Hãy tưởng tượng một ngày bạn và người yêu đi chơi nhưng bị vợ phát hiện, bạn không biết phải thuê xe hay đi xe của người khác để trốn tránh</p>";
    echo "<h3>Ôi đừng lo vì đã có chợ thuê xe - nơi mà tốc độ cho thuê xe nhanh hơn độ ghen của vợ bạn >v<</h3>";
}


echo '<div class="container">';
switch ($controller) {
    case 'home':
        include_once "./Controller/HomeController.php";

        break;
    case 'car':
        include_once "Controller/CarController.php";

        break;
    case 'vehicle':
        require_once "Controller/VehicleController.php";
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
    case 'thanhtoan':
        require_once "Controller/ThanhToanController.php";
        $thanhtoan = new ThanhToanController($conn);
        if(method_exists($thanhtoan, $action)) {
            $thanhtoan->$action();
        } else {
            $thanhtoan->index();
        }
        break;
    case 'thongke':
        require_once "Controller/ThongKeController.php";
        $thongke = new ThongKeController($conn);
        if(method_exists($thongke, $action)) {
            $thongke->$action();
        } else {
            $thongke->index();
        }
        break;
    default:
        echo "<h1>Làm gì có trang này</h1>";
        break;
}
echo '</div>';

include "footer.php";
