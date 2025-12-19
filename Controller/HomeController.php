<?php
    $vehicleDAO = new vehicleDAO($conn);
    $listCar = $vehicleDAO->getAllHangxe();

    include "./View/Home/home.php";
?> 