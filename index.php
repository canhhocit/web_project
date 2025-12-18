<?php
include "./web_project/Model/Database/dbconnect.php";
include "./web_project/Model/DAO/CarDAO.php";
include "header.php";

$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

echo '<div class="container">';
   switch ($controller){
        case 'home':
            include "./web_project/Controler/HomeController.php";
            break;
        case 'car':
            include "./web_project/Controler/CarController.php";
            break;
        default:
            echo "<h1>Làm gì có trang này</h1>";
            break;
   }
   echo '</div>';

include "footer.php";
?>

