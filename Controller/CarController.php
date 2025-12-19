<?php 

    $vehicleDAO = new vehicleDAO($conn);

    if (isset($_GET['action'])) {
        $action = $_GET['action'];
    } else {
        $action = 'home'; 
    }
    switch ($action) {
        case 'detail':
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                

                $xe = $vehicleDAO->getChiTietXe($id);

                if ($xe) {
                    include "./View/Home/carDetail.php";
                } else {
                    echo "<div class='alert alert-danger container mt-5'>Không tìm thấy xe này!</div>";
                }
            }
            break;
            
        case 'mycars':
            // Code cho phần quản lý xe của tôi (sau này làm)
            echo "Trang quản lý xe cá nhân";
            break;
            
        default:
            header("Location: index.php");
            break;
    }

?>