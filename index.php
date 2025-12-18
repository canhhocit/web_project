<?php
include "Model/Database/dbconnect.php";
include "Model/DAO/CarDAO.php";
include "header.php";


$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

echo '<div class="container" style="min-height: 500px; padding-top: 20px;">';
echo "<h1>Chào mừng đến với Chợ Thuê Xe</h1>";
echo "<p>ở góc trên bên phải để xem menu xổ</p>";

echo '<div class="container">';
   switch ($controller){
        case 'home':
            include_once "./Controller/HomeController.php";
            
            break;
        case 'car':
            include_once "./Controller/CarController.php";
            break;
        default:
            echo "<h1>Làm gì có trang này</h1>";
            break;
   }
   echo '</div>';

include "footer.php";
?>

