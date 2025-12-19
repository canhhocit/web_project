<?php
    $vehicleDAO = new vehicleDAO($conn);
    
    if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
        $keyword = $_GET['keyword'];
        $listCar = $vehicleDAO->timKiemXe($keyword);
    } else {
        $listCar = $vehicleDAO->getDanhSachXeHienThi();
    }

    include "./View/Home/home.php";
?>