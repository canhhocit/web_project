<?php
/* Payment Notify
 * IPN URL: Ghi nhận kết quả thanh toán từ VNPAY
 * Các bước thực hiện:
 * Kiểm tra checksum 
 * Tìm giao dịch trong database
 * Kiểm tra số tiền giữa hai hệ thống
 * Kiểm tra tình trạng của giao dịch trước khi cập nhật
 * Cập nhật kết quả vào Database
 * Trả kết quả ghi nhận lại cho VNPAY
 */

require_once("./config.php");
$inputData = array();
$returnData = array();
foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

$vnp_SecureHash = $inputData['vnp_SecureHash'];
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
$vnpTranId = $inputData['vnp_TransactionNo']; //Mã giao dịch tại VNPAY
$vnp_BankCode = $inputData['vnp_BankCode']; //Ngân hàng thanh toán
$vnp_Amount = $inputData['vnp_Amount']/100; // Số tiền thanh toán VNPAY phản hồi

$Status = 0; // Là trạng thái thanh toán của giao dịch chưa có IPN lưu tại hệ thống của merchant chiều khởi tạo URL thanh toán.
$orderId = $inputData['vnp_TxnRef'];

try {
    // Kiểm tra checksum của dữ liệu
    if ($secureHash == $vnp_SecureHash) {
        //  Kết nối DB và khởi tạo Service
        require_once __DIR__ . '/Model/Database/dbconnect.php'; // Đường dẫn tới file kết nối db của bạn
        require_once __DIR__ . '/Model/Service/ThanhToanService.php';
        
        $service = new ThanhToanService($conn);
        
        // Lấy thông tin đơn hàng từ Database thông qua Service
        $order = $service->getChiTietHoaDonVoiTinhToan($orderId);

        if ($order != NULL) {
            //  Kiểm tra số tiền (VNPAY gửi số tiền đã chia 100 ở trên)
            if($order["tong_tien"] == $vnp_Amount) 
            {
                //  Kiểm tra trạng thái đơn hàng (0: Chưa thanh toán)
                if ($order["trangthai"] !== NULL && $order["trangthai"] == 0) {
                    
                    // Kiểm tra mã phản hồi thành công từ VNPAY
                    if ($inputData['vnp_ResponseCode'] == '00' || $inputData['vnp_TransactionStatus'] == '00') {
                        
                        // Cập nhật kết quả thanh toán vào DB
                        // Sử dụng hàm xacNhanThanhToan đã có trong Service của bạn
                        $updateStatus = $service->xacNhanThanhToan($orderId, 'VNpay');
                        
                        if ($updateStatus) {
                            $returnData['RspCode'] = '00';
                            $returnData['Message'] = 'Confirm Success';
                        } else {
                            $returnData['RspCode'] = '99';
                            $returnData['Message'] = 'Update database failed';
                        }
                    } else {
                        // Giao dịch thất bại tại VNPAY
                        $returnData['RspCode'] = '00';
                        $returnData['Message'] = 'Confirm Success (Payment Failed)';
                    }
                } else {
                    $returnData['RspCode'] = '02';
                    $returnData['Message'] = 'Order already confirmed';
                }
            }
            else {
                $returnData['RspCode'] = '04';
                $returnData['Message'] = 'invalid amount';
            }
        } else {
            $returnData['RspCode'] = '01';
            $returnData['Message'] = 'Order not found';
        }
    } else {
        $returnData['RspCode'] = '97';
        $returnData['Message'] = 'Invalid signature';
    }
} catch (Exception $e) {
    $returnData['RspCode'] = '99';
    $returnData['Message'] = 'Unknow error';
}
//Trả lại VNPAY theo định dạng JSON
echo json_encode($returnData);
