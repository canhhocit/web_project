<?php
include "Model/Database/dbconnect.php";
include "Model/DAO/vehicleDAO.php";
include "header.php";
//include "Model/Object/xe.php";
// include "Model/Object/hangxe.php";
// include "Model/Object/anhxe.php";
// include "Model/Object/taikhoan.php";


$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

echo '<div class="container" style="min-height: 500px; padding-top: 20px;">';
echo "<h1>Chào mừng đến với Chợ Thuê Xe</h1>";
echo "<p>Hãy tưởng tượng một ngày bạn và người yêu đi chơi nhưng bị vợ phát hiện, bạn không biết phải thuê xe hay đi xe của người khác để trốn tránh</p>";
echo "<h3>Ôi đừng lo vì đã có chợ thuê xe - nơi mà tốc độ cho thuê xe nhanh hơn độ ghen của vợ bạn >v<</h3>";


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

