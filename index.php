<?php
include "Model/Database/dbconnect.php";
include "Model/DAO/CarDAO.php";
include "header.php";

$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

echo '<div class="container">';
   switch ($controller){
        case 'home':
            include "Controller/HomeController.php";
            break;
        case 'car':
            include "Controller/CarController.php";
            break;
        default:
            echo "<h1>Làm gì có trang này</h1>";
            break;
   }
   echo '</div>';

include "footer.php";
?>

