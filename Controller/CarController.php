<?php 
    if ($action == 'detail'){
        $id = $_GET['id'];
        $carDAO = new CarDAO();
        $xe = $carDAO->getOneCarById($id);

        include "../View/Home/carDetail.php";
    }
?>