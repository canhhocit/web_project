<?php require_once __DIR__ . "/../../config.php"; ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/web_project/View/CSS/chat.css">
</head>

<body>
    <div class="chat-container">

        <!-- SIDEBAR  left -->
        <div class="chat-sidebar">

            <div class="chat-sidebar-header">
                <h3><i class="fa-solid fa-message"></i> Tin nhắn</h3>
            </div>

            <!-- List-->
            <div class="chat-list">
                <?php if (empty($danhSachCuocTC)): ?>
                    <div class="empty-state">
                        <i class="fa-solid fa-inbox"></i>
                        <p>Chưa có tin nhắn nào</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($danhSachCuocTC as $cuocTC): ?>
                        <?php
                        // Kiểm tra xem đang xem cuộc trò chuyện này không
                        $isActive = (isset($currentChatId) && $currentChatId == $cuocTC['id_cuoc_tc']);
                        $activeClass = $isActive ? 'active' : '';
                        ?>

                        <a href="/web_project/index.php?controller=chat&action=view&id=<?php echo $cuocTC['id_cuoc_tc']; ?>"
                            class="chat-item <?php echo $activeClass; ?>">

                            <!-- Avatar -->
                            <div class="chat-item-avatar">
                                <?php if (!empty($cuocTC['anhdaidien'])): ?>
                                    <img src="/web_project/View/image/<?php echo $cuocTC['anhdaidien']; ?>" alt="Avatar">
                                <?php else: ?>
                                    <i class="fa-solid fa-user"></i>
                                <?php endif; ?>
                            </div>

                            <!-- --------------- -->
                            <div class="chat-item-info">
                                <div class="chat-item-top">
                                    <h5><?php echo $cuocTC['ten_nguoi_kia']; ?></h5>
                                    <span class="chat-time"><?php echo $cuocTC['thoi_gian_cuoi']; ?></span>
                                </div>

                                <!-- preview last msg & count tin chua doc-->
                                <div class="chat-item-bottom">
                                    <p class="chat-preview <?php echo ($cuocTC['chua_doc'] > 0) ? 'unread' : ''; ?>">
                                        <?php echo $cuocTC['tin_nhan_cuoi']; ?>
                                    </p>
                                    <?php if ($cuocTC['chua_doc'] > 0): ?>
                                        <span class="badge-unread"><?php echo $cuocTC['chua_doc']; ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="chat-item-footer">
                                    <small class="text-muted">Xe: <?php echo $cuocTC['tenxe']; ?></small>

                                    <!-- del -->
                                    <button class="btn-delete-chat"
                                        onclick="deleteChatConfirm(<?php echo $cuocTC['id_cuoc_tc']; ?>, '<?php echo addslashes($cuocTC['ten_nguoi_kia']); ?>');"
                                        title="Xóa :))">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- chat box -->
        <div class="chat-main">
            <?php if (!isset($currentChatId)): ?>
                <!-- chua chon gi -->
                <div class="chat-placeholder">
                    <i class="fa-solid fa-comments"></i>
                    <h4>Chọn một cuộc trò chuyện để bắt đầu</h4>
                </div>
            <?php else: ?>
                <!-- show chatbox  -->
                <?php include_once __DIR__ . "/chatbox.php"; ?>
            <?php endif; ?>
        </div>

    </div>

    <script>
        function deleteChatConfirm(idCuocTC, tenNguoi) {
            event.preventDefault();
            event.stopPropagation();
            if (confirm('Xóa cuộc trò chuyện với ' + tenNguoi + '?')) {
                window.location.href = '/web_project/index.php?controller=chat&action=delete&id=' + idCuocTC;
            }
        }
    </script>
</body>

</html>