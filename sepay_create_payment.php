<?php
session_start();

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
date_default_timezone_set('Asia/Ho_Noi');

define('ACCESS_HOPLE', true);
require_once("./config.php");

$order_id = $_POST['order_id'];
$amount = $_POST['amount'];
$order_desc = $_POST['order_desc'];

$transaction_code = 'HD' . $order_id . 'T' . time();

$vaCode = $sepay_vaCode ?? '96247LVD02';  
$addInfo = $vaCode . ' ' . $transaction_code;

// Lưu session
$_SESSION['pending_payment'] = array(
    'idhoadon' => $order_id,
    'tong_tien' => $amount,
    'method' => 'SEPAY',
    'transaction_code' => $transaction_code,
    'va_code' => $vaCode
);

// Lưu thời gian tạo giao dịch
$_SESSION['pending_payment']['created_at'] = time();
$_SESSION['pending_payment']['expires_at'] = time() + (10 * 60); // 10 phút


$sepay_va = '96247LVD02';

$qr_url = "https://qr.sepay.vn/img"
        . "?acc={$sepay_va}"
        . "&bank=BIDV"
        . "&amount={$amount}"
        . "&des=" . urlencode($transaction_code);

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán SePay - BIDV</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
            padding: 25px;
            display: flex;
            gap: 30px;
            animation: slideUp 0.5s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .left-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .right-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            width: 100%;
        }
        
        h1 {
            color: #333;
            font-size: 18px;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .bank-name {
            color: #2a5298;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .subtitle {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 15px;
        }
        
        .qr-container {
            text-align: center;
            width: 100%;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .qr-code {
            width: 350px;
            height: 350px;
            margin: 0 auto 15px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            padding: 10px;
        }
        
        .qr-code img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .qr-label {
            color: #2a5298;
            font-size: 16px;
            font-weight: 500;
            margin-top: 10px;
        }
        
        .info-box {
            background: white;
            margin-bottom: 20px;
        }
        
        .info-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eaeaea;
        }
        
        .info-header h2 {
            color: #333;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-header h2 i {
            color: #2a5298;
            font-size: 18px;
        }
        
        .info-row {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            color: #666;
            font-size: 16px;
            width: 140px;
            flex-shrink: 0;
        }
        
        .info-value {
            color: #333;
            font-weight: 500;
            font-size: 14px;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .amount {
            color: #e74c3c;
            font-size: 18px;
            font-weight: 600;
        }
        
        .copy-button {
            background: #2a5298;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .copy-button:hover {
            background: #1e3c72;
        }
        
        .status {
            background: #e8f4ff;
            border: 1px solid #c2e0ff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .status-text {
            color: #1976D2;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 500;
        }
        
        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid #c2e0ff;
            border-top-color: #1976D2;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 10px;
        }
        
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-secondary {
            background: #f5f5f5;
            color: #666;
            border: 1px solid #ddd;
        }
        
        .btn-secondary:hover {
            background: #e8e8e8;
        }
        
        .alert {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 10px;
            border-radius: 6px;
            font-size: 12px;
            display: none;
            margin-top: 10px;
            text-align: center;
        }
        
        .alert.show {
            display: block;
            animation: fadeIn 0.3s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Responsive cho màn hình nhỏ */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                max-width: 500px;
                gap: 20px;
            }
            
            .qr-code {
                width: 320px;
                height: 320px;
            }
            
            .info-label {
                width: 120px;
            }
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }
            
            .qr-code {
                width: 300px;
                height: 300px;
            }
            
            .info-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            
            .info-label {
                width: 100%;
            }
            
            .info-value {
                width: 100%;
                justify-content: space-between;
            }
            
            .buttons {
                justify-content: center;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Cột trái: QR Code -->
        <div class="left-panel">
            <div class="header">
                <h1>Thanh toán qua QR Code</h1>
                <div class="bank-name"><?php echo $sepay_bankName; ?></div>
                <p class="subtitle">Quét mã QR bằng app ngân hàng để thanh toán nhanh chóng</p>
            </div>
            
            <div class="qr-container">
                <div class="qr-code">
                    <img src="<?= $qr_url ?>" alt="QR thanh toán SePay" />

                </div>
                <div class="qr-label">
                    <i class="fas fa-mobile-alt"></i> Quét mã bằng app Banking
                </div>
            </div>
        </div>
        
        <!-- Cột phải: Thông tin chuyển khoản -->
        <div class="right-panel">
            <div class="info-box">
                <div class="info-header">
                    <h2><i class="fas fa-credit-card"></i> Thông tin chuyển khoản</h2>
                </div>
                
                <div class="info-row">
                    <span class="info-label">
                        Ngân hàng:
                    </span>
                    <span class="info-value">
                        <?php echo $sepay_bankName; ?>
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">
                        Số tài khoản:
                    </span>
                    <span class="info-value">
                        <?php echo $sepay_va; ?>
                        <button class="copy-button" onclick="copyText('<?php echo $sepay_accountNumber; ?>')">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">
                        Chủ tài khoản:
                    </span>
                    <span class="info-value">
                        <?php echo $sepay_accountName; ?>
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">
                        Số tiền:
                    </span>
                    <span class="info-value">
                        <span class="amount"><?php echo number_format($amount, 0, ',', '.'); ?> VNĐ</span>
                        <button class="copy-button" onclick="copyText('<?php echo $amount; ?>')">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">
                        Nội dung CK:
                    </span>
                    <span class="info-value">
                        <span style="font-family: monospace; font-size: 13px;"><?php echo $transaction_code; ?></span>
                        <button class="copy-button" onclick="copyText('<?php echo $transaction_code; ?>')">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">
                        Mã đơn hàng:
                    </span>
                    <span class="info-value">
                        <?php echo $order_id; ?>
                    </span>
                </div>
            </div>
            
            <div class="status">
                 <div class="status-text">
                    <div class="spinner"></div>
                    <span id="statusText">Đang xác nhận...</span>
                    </div>
                    <div style="margin-top: 10px; font-size: 13px; color: #666;">
                        Giao dịch hết hạn sau: <strong id="countdown">10:00</strong>
                    </div>
            </div>
            
            <div class="alert" id="copyAlert">
                <i class="fas fa-check-circle"></i> Đã sao chép vào clipboard!
            </div>
            
            <div class="buttons">
                <a href="/web_project/index.php?controller=thanhtoan&action=index" class="btn btn-secondary">
                    <i></i> HỦY THANH TOÁN
                </a>
            </div>
        </div>
    </div>
    
    <script>
        function copyText(text) {
            navigator.clipboard.writeText(text).then(function() {
                const alert = document.getElementById('copyAlert');
                alert.classList.add('show');
                setTimeout(() => {
                    alert.classList.remove('show');
                }, 2000);
            }).catch(function(err) {
                console.error('Copy failed:', err);
                // Fallback cho trình duyệt cũ
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                const alert = document.getElementById('copyAlert');
                alert.classList.add('show');
                setTimeout(() => {
                    alert.classList.remove('show');
                }, 2000);
            });
        }
        
let checkInterval = setInterval(checkPaymentStatus, 15000); 
let checkCount = 0;
const maxChecks = 120; // 30 phút 

function checkPaymentStatus() {
    checkCount++;
    
    console.log('Checking payment... Attempt:', checkCount); // Debug log
    
    if (checkCount >= maxChecks) {
        clearInterval(checkInterval);
        return;
    }
    
    fetch('/web_project/sepay_check_payment.php?order_id=<?php echo $order_id; ?>&t=' + Date.now())
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.text(); // Đổi từ .json() sang .text() để debug
        })
        .then(text => {
            console.log('Raw response:', text); // Debug - xem response thô
            
            try {
                const data = JSON.parse(text);
                console.log('Parsed data:', data); // Debug - xem data đã parse
                
                if (data.success) {
                    clearInterval(checkInterval);
                    alert('Thanh toán thành công!');
                    window.location.href = '/web_project/sepay_return.php?status=success&order_id=<?php echo $order_id; ?>&amount=<?php echo $amount; ?>&transaction_code=<?php echo $transaction_code; ?>';
                } else {
                    console.log('Payment not found yet:', data.message);
                    if (data.debug) {
                        console.log('Debug info:', data.debug);
                    }
                }
            } catch (e) {
                console.error('JSON parse error:', e);
                console.error('Response text:', text);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });
}

        let timeLeft = 10 * 60; // 10 phút = 600 giây
        const countdownEl = document.getElementById('countdown');
        const statusTextEl = document.getElementById('statusText');
        
        const countdownTimer = setInterval(() => {
            timeLeft--;
            
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            countdownEl.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                clearInterval(countdownTimer);
                clearInterval(checkInterval);
                
                // Hủy giao dịch
                statusTextEl.innerHTML = '<span style="color: #f44336;">Giao dịch đã hết hạn</span>';
                
                // Chuyển về trang thanh toán sau 3 giây
                setTimeout(() => {
                    window.location.href = '/web_project/index.php?controller=thanhtoan&action=index&expired=1';
                }, 3000);
            }
        }, 1000);

// Kiểm tra ngay khi load trang
setTimeout(checkPaymentStatus, 2000);

// Dừng kiểm tra khi rời trang
window.addEventListener('beforeunload', function() {
    clearInterval(checkInterval);
});

// Xử lý lỗi load ảnh QR
document.getElementById('qrImage').onerror = function() {
    console.error('QR image failed to load');
    this.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjgwIiBoZWlnaHQ9IjI4MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjgwIiBoZWlnaHQ9IjI4MCIgZmlsbD0iI2YwZjBmMCIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTYiIGZpbGw9IiM2NjYiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5Mb2FkaW5nIFFSLi4uPC90ZXh0Pjwvc3ZnPg==';
};
    </script>
</body>
</html>