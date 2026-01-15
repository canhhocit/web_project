<?php

if (!defined('ACCESS_HOPLE')) {
    die('<script>
        alert("Truy cập không hợp lệ!");
        window.location="/web_project/index.php";
    </script>');
}

$vnp_TmnCode = "ZND9J2TF"; 
$vnp_HashSecret = "EO076MKYNRWBPL1D7R6H7BM9QT17X1TK"; 
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = "https://unrubrical-breann-unextortable.ngrok-free.dev/web_project/vnpay_return.php";   //ngrok
$vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html"; ///