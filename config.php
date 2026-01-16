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


$sepay_apiKey = "GMYHCFMLHF0AM5RTINESAGYJQXG9W6IJO4ZX1ISDNR8NSR6SWUBEEV2ANF1JCGDR";
$sepay_accountNumber = "8841165866";
$sepay_accountName = "LO VAN DIEP";
$sepay_bankCode = "970418"; // Mã ngân hàng BIDV
$sepay_vaCode = "96247LVD02"; // mã tài khoản ảo chọn trong SePay
$sepay_bankName = "BIDV";
$sepay_template = "compact2"; // Template cho QR code
$sepay_returnUrl = "https://unrubrical-breann-unextortable.ngrok-free.dev/web_project/sepay_return.php";