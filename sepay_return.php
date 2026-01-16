<?php
session_start();

define('ACCESS_HOPLE', true);
require_once("./config.php");

$paymentSuccess = isset($_GET['status']) && $_GET['status'] == 'success';
$order_id = $_GET['order_id'] ?? '';
$amount = $_GET['amount'] ?? 0;
$transaction_time = date('d/m/Y H:i:s');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kết quả thanh toán SePay</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        
        .badge {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 20px;
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
            <div class="badge">SePay</div>
            <p class="subtitle">Cảm ơn bạn đã hoàn tất thanh toán. Giao dịch của bạn đã được xử lý thành công.</p>
        <?php else: ?>
            <div class="error-icon">
                <svg viewBox="0 0 52 52">
                    <path d="M16 16l20 20M36 16l-20 20"/>
                </svg>
            </div>
            <h1 class="error">Thanh toán chưa hoàn tất!</h1>
            <div class="badge">SePay</div>
            <p class="subtitle">Giao dịch chưa được xác nhận. Vui lòng kiểm tra lại hoặc liên hệ hỗ trợ.</p>
        <?php endif; ?>
        
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Mã đơn hàng:</span>
                <span class="info-value"><?php echo htmlspecialchars($order_id); ?></span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Số tiền:</span>
                <span class="info-value amount"><?php echo number_format($amount, 0, ',', '.'); ?> VNĐ</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Phương thức:</span>
                <span class="info-value">SePay</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Thời gian:</span>
                <span class="info-value"><?php echo $transaction_time; ?></span>
            </div>
        </div>
        
        <a href="/web_project/index.php?controller=thanhtoan&action=index&t=<?php echo time(); ?>" class="back-button">
            Quay lại trang quản lý
        </a>
    </div>
</body>
</html>