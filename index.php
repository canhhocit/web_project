<?php
session_start();

define('ACCESSED_FROM_CONTROLLER', true); // ngăn chặn truy cập trực tiếp từ trình duyệt
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

if ($controller === 'thanhtoan' && ($action === 'getChiTietHoaDon' || $action === 'xacNhanTraXe')) {
    require_once "Controller/ThanhToanController.php";
    if (file_exists("Controller/ThanhToanController.php")) {
        $thanhtoan = new ThanhToanController($conn);
        $thanhtoan->$action();
    }
    exit();
}


include "header.php";

if ($controller == 'home') {
    //cảnh sửa
    echo '<style>
    @import url("https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap");
    
    .home-welcome {
        min-height: 400px;
        padding: 60px 20px;
        text-align: center;
        background: linear-gradient(135deg, #88a398ff, #3f2c2cff);
        color: #f7ec9bff;
        border-radius: 0;
        margin-bottom: 40px;
        position: relative;
        font-family: "Quicksand", sans-serif;
    }
    .home-welcome h1 {
        font-weight: 700;
        margin-bottom: 30px;
        font-size: 2.5rem;
        letter-spacing: 2px;
        font-family: "Quicksand", sans-serif;
    }
    .home-welcome p {
        font-size: 1.1rem;
        margin-bottom: 15px;
        line-height: 1.6;
        font-weight: 500;
    }
    .home-welcome h4 {
        margin: 30px 0;
        font-weight: 600;
        font-size: 1.2rem;
    }
    .home-badge {
        display: inline-block;
        padding: 12px 30px;
        background: #ffc107;
        color: #000;
        font-weight: 700;
        border-radius: 50px;
        font-size: 1rem;
        margin-top: 20px;
    }
</style>';

    echo '<div class="home-welcome">';
    echo '<div class="container">';
    echo '<h1 style ="color:red;">WELCOME</h1>';
    echo '<p>Không phải chuyến đi nào cũng nên để lại dấu vết.</p>';
    echo '<p>Không phải chiếc xe nào cũng phù hợp cho mọi cuộc hẹn.</p>';
    echo '<h4>👉 <strong>Chợ Thuê Xe</strong> – nơi bạn chọn xe phù hợp cho từng tình huống <em>"nhạy cảm"</em>.</h4>';
    echo '<span class="home-badge">🚗 Xe đúng – chuyện êm</span>';
    echo '</div>';
    echo '</div>';
    //end
    echo '<div class="container" style="padding-top: 20px;">';
} else {
    echo '<div class="container" style="padding-top: 20px;">';
}

// Điều hướng
switch ($controller) {
    case 'home':
        if (file_exists("./Controller/HomeController.php")) {
            require_once "./Controller/HomeController.php";
            $home = new HomeController();
            $home->index();
        }
        break;

    case 'car':
        require_once "Controller/CarController.php";
        $carCtrl = new CarController();
        if (method_exists($carCtrl, $action)) {
            $carCtrl->$action();
        } else {
            echo "<script>window.location.href='index.php';</script>";
        }
        break;

    case 'vehicle':
        require_once "Controller/vehicleController.php";
        require_once "Model/Object/xe.php";
        require_once "Model/Object/anhxe.php";
        $vehicle = new vehicleController();

        if (method_exists($vehicle, $action)) {
            if ($action === 'editV' && $_SERVER["REQUEST_METHOD"] === "GET") {
                $data = $vehicle->editV();

                $xe = $data['xe'];
                $anhxe = $data['anhxe'];

                include_once "View/xe/editVehicle.php";
            } else {
                $vehicle->$action(); 
            }
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
        if (method_exists($thanhtoan, $action)) {
            $thanhtoan->$action();
        } else {
            $thanhtoan->index();
        }
        break;

    case 'thuexe':
        require_once "Controller/nguyen_nguoithue_Controller.php";
        $thuexe = new nguyen_nguoithue_Controller();

        if (method_exists($thuexe, $action)) {
            $thuexe->$action();
        }
        break;
    case 'thongke':
        require_once "Controller/ThongKeController.php";
        $thongke = new ThongKeController($conn);
        $allowedActions = ['index', 'apiGetStatistics'];
        $action = in_array($action, $allowedActions) ? $action : 'index';
        
        if (method_exists($thongke, $action)) {
            $thongke->$action();
        } else {
            $thongke->index();
        }
        break;

    case 'lichsutt':
        require_once "Controller/LichSuTTController.php";
        $lichsuCtrl = new LichSuTTController($conn);
        $lichsuCtrl->index();
        break;

    case 'about':
        require_once "Controller/AboutController.php";
        $about = new AboutController($conn);
        if ($action === 'addWork') {
            $about->addWork();
        } else {
            $about->index();
        }
        break;

    default:
        echo "<h1>404 - Không tìm thấy trang</h1>";
        break;
}

echo '</div>';
include "footer.php";
