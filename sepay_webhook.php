<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

$log_file = __DIR__ . '/webhook_logs.txt';

function wlog($msg) {
    global $log_file;
    file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] " . $msg . "\n", FILE_APPEND);
}

function jsonResponse(int $code, array $data) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function getAuthHeader(): string {
    $auth = '';

    if (function_exists('getallheaders')) {
        $h = getallheaders();
        if (isset($h['Authorization'])) $auth = $h['Authorization'];
        if (!$auth && isset($h['authorization'])) $auth = $h['authorization'];
    }

    if (!$auth && isset($_SERVER['HTTP_AUTHORIZATION'])) $auth = $_SERVER['HTTP_AUTHORIZATION'];
    if (!$auth && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) $auth = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];

    return trim((string)$auth);
}

wlog("=== HIT ===");
wlog("METHOD=" . ($_SERVER['REQUEST_METHOD'] ?? ''));
wlog("UA=" . ($_SERVER['HTTP_USER_AGENT'] ?? ''));
wlog("CT=" . ($_SERVER['CONTENT_TYPE'] ?? ''));

// Chỉ nhận POST
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    wlog("ERROR: Method not POST");
    jsonResponse(405, ['status' => 'error', 'message' => 'Method not allowed']);
}

// Load config
define('ACCESS_HOPLE', true);
require_once __DIR__ . '/config.php';

$received = getAuthHeader();
$expected = 'Apikey ' . ($sepay_apiKey ?? '');

wlog("HAS_AUTH=" . ($received ? "1" : "0"));

if (!$received || $received !== $expected) {
    wlog("ERROR: Unauthorized");
    jsonResponse(401, ['status' => 'error', 'message' => 'Unauthorized']);
}

$raw = file_get_contents('php://input');
wlog("HAS_JSON=" . ($raw ? "1" : "0"));
wlog("RAW=" . $raw);

if (!$raw) {
    jsonResponse(400, ['status' => 'error', 'message' => 'Empty body']);
}

$data = json_decode($raw, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    wlog("ERROR: Invalid JSON " . json_last_error_msg());
    jsonResponse(400, ['status' => 'error', 'message' => 'Invalid JSON']);
}

$p = $data['data'] ?? $data;
$content = (string)($p['transaction_content'] ?? $p['content'] ?? $p['description'] ?? '');
$amount  = floatval($p['amount_in'] ?? $p['transferAmount'] ?? $p['amount'] ?? 0);

wlog("CONTENT=" . $content);
wlog("AMOUNT=" . $amount);

// Lấy order_id từ mã HD{id}T{timestamp}
if (!preg_match('/HD(\d+)/', $content, $m)) {
    wlog("INFO: No payment code found -> ignore");
    // Trả 200 để SePay không retry spam
    jsonResponse(200, ['status' => 'ignored', 'message' => 'No payment code found']);
}

$order_id = (int)$m[1];
$code = $m[0];

wlog("ORDER_ID=" . $order_id . " CODE=" . $code);

if ($order_id <= 0) {
    jsonResponse(200, ['status' => 'ignored', 'message' => 'Invalid order_id']);
}

// Update DB (idempotent)
try {
    require_once __DIR__ . '/Model/Database/dbconnect.php';
    require_once __DIR__ . '/Model/Service/ThanhToanService.php';

    if (!isset($conn) || !$conn) {
        wlog("ERROR: DB connection missing");
        jsonResponse(500, ['status' => 'error', 'message' => 'DB connection missing']);
    }

    $service = new ThanhToanService($conn);

    // Nếu hóa đơn không tồn tại -> ignore
    $hoadon = $service->getChiTietHoaDonVoiTinhToan($order_id);
    if (!$hoadon) {
        wlog("ERROR: Order not found in DB");
        jsonResponse(200, ['status' => 'ignored', 'message' => 'Order not found', 'order_id' => $order_id]);
    }

    // Nếu đã paid -> OK luôn
    if ((int)$hoadon['trangthai'] === 1) {
        wlog("INFO: Already paid");
        jsonResponse(200, ['status' => 'success', 'message' => 'Already paid', 'order_id' => $order_id]);
    }

    // Nếu đã có record thanhtoan -> OK luôn
    $qC = "SELECT 1 FROM thanhtoan WHERE idhoadon = ? LIMIT 1";
    if ($stmtC = mysqli_prepare($conn, $qC)) {
        mysqli_stmt_bind_param($stmtC, "i", $order_id);
        mysqli_stmt_execute($stmtC);
        $rsC = mysqli_stmt_get_result($stmtC);
        if (mysqli_fetch_row($rsC)) {
            wlog("INFO: thanhtoan record exists -> mark paid");
            $qU = "UPDATE hoadon SET trangthai = 1 WHERE idhoadon = ?";
            if ($stmtU = mysqli_prepare($conn, $qU)) {
                mysqli_stmt_bind_param($stmtU, "i", $order_id);
                mysqli_stmt_execute($stmtU);
            }
            jsonResponse(200, ['status' => 'success', 'message' => 'Already recorded', 'order_id' => $order_id]);
        }
    }

    // Check amount (cho phép lệch <= 1000)
    $expected_amount = floatval($hoadon['tong_tien']);
    $diff = abs($amount - $expected_amount);
    wlog("EXPECTED=" . $expected_amount . " DIFF=" . $diff);

    if ($amount > 0 && $diff > 1000) {
        wlog("WARNING: Amount mismatch -> ignore update");
        jsonResponse(200, [
            'status' => 'warning',
            'message' => 'Amount mismatch',
            'order_id' => $order_id,
            'expected' => $expected_amount,
            'received' => $amount
        ]);
    }

    // Confirm payment
    $ok = $service->xacNhanThanhToan($order_id, 'SEPAY');
    if ($ok) {
        wlog("SUCCESS: Updated DB");
        jsonResponse(200, [
            'status' => 'success',
            'message' => 'Payment confirmed',
            'order_id' => $order_id,
            'amount' => $amount,
            'code' => $code
        ]);
    }

    wlog("ERROR: Service update failed");
    jsonResponse(500, ['status' => 'error', 'message' => 'DB update failed']);

} catch (Throwable $e) {
    wlog("EXCEPTION: " . $e->getMessage());
    jsonResponse(500, ['status' => 'error', 'message' => $e->getMessage()]);
}
