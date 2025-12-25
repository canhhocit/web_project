<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $databaseName = "web_project";
    $conn = mysqli_connect($servername, $username, $password, $databaseName);
    if ($conn->connect_error){
        die("có lỗi rồi" . $conn->connect_error);
    }
?>