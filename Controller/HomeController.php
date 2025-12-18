<?php
    $carDAO = new CarDAO();
    $listCar = $carDAO->getAllCar(); 

    include "./View/Home/home.php";
?> 