<?php
session_start();
define('ACCESS_HOPLE', true);

require_once("./config.php");

// Luôn trả JSON
header('Content-Type: application/json; charset=utf-8');

// Log để debug (xem trong error_log của PHP/Apache/Nginx)
error_log("sepay_check_payment called. GET=" . json_encode($_GET) . " SESSION=" . json_encode($_SESSION ?? []));

if (!isset($_GET['order_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing order_id']);
    exit;
}

$order_id = (int)$_GET['order_id'];
if ($order_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid order_id']);
    exit;
}

require_once __DIR__ . '/Model/Database/dbconnect.php';

if (!isset($conn) || !$conn) {
    echo json_encode(['success' => false, 'message' => 'DB connection not found']);
    exit;
}

try {
    //Check hóa đơn đã thanh toán chưa
    $q1 = "SELECT trangthai FROM hoadon WHERE idhoadon = ? LIMIT 1";
    $stmt1 = mysqli_prepare($conn, $q1);
    if (!$stmt1) {
        throw new Exception("Prepare failed (q1): " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt1, "i", $order_id);
    mysqli_stmt_execute($stmt1);
    $rs1 = mysqli_stmt_get_result($stmt1);
    $row1 = mysqli_fetch_assoc($rs1);

    if (!$row1) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit;
    }

    if ((int)$row1['trangthai'] === 1) {
        // Đã thanh toán
        unset($_SESSION['pending_payment']); // nếu có
        echo json_encode(['success' => true, 'message' => 'Paid (hoadon.trangthai=1)']);
        exit;
    }

    //Check bảng thanh toán (phòng trường hợp bạn chưa update trangthai nhưng đã insert thanhtoan)
    $q2 = "SELECT 1 FROM thanhtoan WHERE idhoadon = ? LIMIT 1";
    $stmt2 = mysqli_prepare($conn, $q2);
    if (!$stmt2) {
        throw new Exception("Prepare failed (q2): " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt2, "i", $order_id);
    mysqli_stmt_execute($stmt2);
    $rs2 = mysqli_stmt_get_result($stmt2);
    $row2 = mysqli_fetch_row($rs2);

    if ($row2) {
        // Đã có record thanh toán
        unset($_SESSION['pending_payment']); // nếu có
        echo json_encode(['success' => true, 'message' => 'Paid (record exists in thanhtoan)']);
        exit;
    }

    // Chưa thanh toán
    echo json_encode([
        'success' => false,
        'message' => 'Not paid yet',
        'order_id' => $order_id
    ]);
    exit;

} catch (Exception $e) {
    error_log("sepay_check_payment exception: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error' => true
    ]);
    exit;
}
