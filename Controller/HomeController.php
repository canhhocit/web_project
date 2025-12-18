<?php
    $carDAO = new CarDAO();
    $listCar = $carDAO->getAllCar(); 

    include "./web_project/View/Home/home.php";
?> 