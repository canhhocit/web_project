case 'chat':
    if (!isset($_SESSION['idtaikhoan'])) {
        // Chưa đăng nhập → Chuyển đến trang login
        echo "<script>alert('Vui lòng đăng nhập!'); 
              window.location='/web_project/View/taikhoan/login.php';</script>";
        exit;
    }

    require_once "Controller/chatController.php";
    $chat = new chatController();

    // ===== XỬ LÝ TRƯỚC KHI INCLUDE HEADER =====
    // (Các action cần redirect - có header("Location: ..."))
    if ($action === 'openChat' || $action === 'sendMessage' || $action === 'delete') {
        $chat->$action();
        exit();
    }
    // ==========================================

    // SAU khi include header
    // Xử lý các action hiển thị view (index, view)
```

---

## 6. GIẢI THÍCH QUAN TRỌNG

### ❓ Tại sao phải tách xử lý TRƯỚC và SAU header?
```
TRƯỚC header (openChat, sendMessage, delete):
- Các action này cần redirect: header("Location: ...")
- PHP không cho phép gửi header sau khi đã có HTML output
- Nên phải xử lý TRƯỚC khi include "header.php"

SAU header (index, view):
- Các action này hiển thị giao diện
- Cần có header, navbar, footer
- Xử lý SAU khi include "header.php"

//--------------------------
-- Trong SQL query:
IF(id_nguoi_gui = '$idtaikhoan', 1, 0) as is_mine

-- Trong PHP:
if ($tin['is_mine'] == 1) {
    // Tin của mình → class "message-mine" → Hiển thị bên phải
} else {
    // Tin của người khác → class "message-other" → Hiển thị bên trái
}
```

### ❓ Tại sao cần bảng `cuoc_tro_chuyen` riêng?
```
- Quản lý CUỘC TRÒ CHUYỆN giữa 2 người về 1 xe cụ thể
- Mỗi cặp (người thuê + chủ xe + xe) chỉ có 1 cuộc trò chuyện
- Lưu thời gian cập nhật cuối → Sắp xếp danh sách
- Dễ dàng lấy thông tin: ai chat với ai về xe nào
```

---

## 7. TỔNG KẾT FLOW HOÀN CHỈNH
```
USER ACTION                    CONTROLLER                 DAO                      DATABASE
─────────────────────────────────────────────────────────────────────────────────────────────

1. Click chat icon        →   openChat()            →    getCuocTroChuyenByXe   → SELECT
   (carDetail.php)                                        taoCuocTroChuyenMoi    → INSERT
                                                          (nếu chưa có)
                          ←   Redirect to view()

2. Vào trang chat         →   index()               →    getDanhSachCuocTC      → SELECT
                                                                                    (JOIN 3 bảng)
                          ←   Render chat/index.php

3. Click vào 1 cuộc chat  →   view()                →    getThongTinCuocTC     → SELECT
                                                          getDanhSachTinNhan     → SELECT
                                                          markAsRead             → UPDATE
                          ←   Render index.php + chatbox.php

4. Gửi tin nhắn          →   sendMessage()          →    guiTinNhan            → INSERT
                          ←   Redirect to view()

5. Click nút xóa         →   delete()               →    xoaCuocTroChuyenVaTinNhan
                                                          - Xóa tin nhắn         → DELETE
                                                          - Xóa cuộc trò chuyện  → DELETE
                          ←   Redirect to index()