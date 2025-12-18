<?php 
$servername = "localhost";
$username = "root";
$password = "";
$databaseName = "web_project";
$conn = mysqli_connect($servername,$username,$password, $databaseName);
if ($conn->connect_error){
    die("Database đã ngủm rùi: " . $conn->connect_error);
}
// if($conn){
//     echo "done";
// }
?>