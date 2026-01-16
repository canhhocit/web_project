<?php
session_start();

define('ACCESS_HOPLE', true);
require_once("./config.php");

$vnp_SecureHash = $_GET['vnp_SecureHash'];
$inputData = array();
foreach ($_GET as $key => $value) {
    if (substr($key, 0, 4) == "vnp_") {
        $inputData[$key] = $value;
    }
}

unset($inputData['vnp_SecureHash']);
ksort($inputData);
$i = 0;
$hashData = "";
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
}

$secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

$paymentSuccess = false;
if ($secureHash == $vnp_SecureHash) {
    if ($_GET['vnp_ResponseCode'] == '00') {
        $paymentSuccess = true;
        if (isset($_SESSION['pending_payment'])) {
            $idhoadon = $_SESSION['pending_payment']['idhoadon'];
            $tong_tien = $_SESSION['pending_payment']['tong_tien'];
            
            require_once __DIR__ . '/Model/Database/dbconnect.php';
            require_once __DIR__ . '/Model/Service/ThanhToanService.php';

            $service = new ThanhToanService($conn);
            $service->xacNhanThanhToan($idhoadon, 'VNPAY');
            
            unset($_SESSION['pending_payment']);
        }
    }
}

// Format date
$payDate = $_GET['vnp_PayDate'];
$formattedDate = substr($payDate, 6, 2) . '/' . substr($payDate, 4, 2) . '/' . substr($payDate, 0, 4) . ' ' . substr($payDate, 8, 2) . ':' . substr($payDate, 10, 2) . ':' . substr($payDate, 12, 2);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kết quả thanh toán</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            padding: 40px 30px;
            text-align: center;
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: #4CAF50;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: scaleIn 0.5s ease-out;
        }
        
        .error-icon {
            width: 80px;
            height: 80px;
            background: #f44336;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: scaleIn 0.5s ease-out;
        }
        
        .success-icon svg,
        .error-icon svg {
            width: 45px;
            height: 45px;
            stroke: white;
            stroke-width: 3;
            fill: none;
        }
        
        @keyframes scaleIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        h1 {
            color: #4CAF50;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        h1.error {
            color: #f44336;
        }
        
        .subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
            line-height: 1.5;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .info-row:last-of-type {
            border-bottom: none;
        }
        
        .info-label {
            color: #666;
            font-size: 14px;
            text-align: left;
        }
        
        .info-value {
            color: #333;
            font-size: 14px;
            font-weight: 500;
            text-align: right;
        }
        
        .amount {
            color: #4CAF50;
            font-size: 16px;
            font-weight: 600;
        }
        
        .back-button {
            background: #2196F3;
            color: white;
            border: none;
            padding: 14px 40px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            margin-top: 30px;
            transition: background 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .back-button:hover {
            background: #1976D2;
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 20px;
            }
            
            .info-label,
            .info-value {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($paymentSuccess): ?>
            <div class="success-icon">
                <svg viewBox="0 0 52 52">
                    <path d="M14 27l7 7 16-16"/>
                </svg>
            </div>
            <h1>Thanh toán thành công!</h1>
            <p class="subtitle">Cảm ơn bạn đã hoàn tất thanh toán. Giao dịch của bạn đã được xử lý thành công.</p>
        <?php else: ?>
            <div class="error-icon">
                <svg viewBox="0 0 52 52">
                    <path d="M16 16l20 20M36 16l-20 20"/>
                </svg>
            </div>
            <h1 class="error">Thanh toán không thành công!</h1>
            <p class="subtitle">Rất tiếc, giao dịch của bạn không thể hoàn tất. Vui lòng thử lại.</p>
        <?php endif; ?>
        
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Mã giao dịch:</span>
                <span class="info-value"><?php echo $_GET['vnp_TransactionNo'] ?></span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Số tiền:</span>
                <span class="info-value amount"><?php echo number_format($_GET['vnp_Amount'] / 100, 0, ',', '.') ?> VNĐ</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Nội dung:</span>
                <span class="info-value"><?php echo $_GET['vnp_OrderInfo'] ?></span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Mã ngân hàng:</span>
                <span class="info-value"><?php echo $_GET['vnp_BankCode'] ?></span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Thời gian:</span>
                <span class="info-value"><?php echo $formattedDate ?></span>
            </div>
        </div>
        
        <a href="/web_project/index.php?controller=thanhtoan&action=index&t=<?php echo time(); ?>" class="back-button">
            Quay lại trang quản lý
        </a>
    </div>
</body>
</html>