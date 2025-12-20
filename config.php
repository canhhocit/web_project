<?php
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }
// if (!isset($_SESSION['idtaikhoan'])) {
//     die('<script>
//         alert("Truy cập không hợp lệ!");
//         window.location="/web_project/index.php";
//     </script>');
// }
// dịnh check session nhưng thôi
if (!defined('ACCESS_HOPLE')) {
    die('<script>
        alert("Truy cập không hợp lệ!");
        window.location="/web_project/index.php";
    </script>');
}
?>
