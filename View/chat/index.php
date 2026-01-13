<?php require_once __DIR__ . "/../../config.php"; ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/web_project/View/CSS/chat.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sockjs-client/1.5.1/sockjs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/stomp.js/2.3.3/stomp.min.js"></script>

    <script>
        const CURRENT_USER_ID = <?php echo isset($currentUserId) ? $currentUserId : 0; ?>;
    </script>
</head>

<body>
    <div class="chat-container">

        <!-- SIDEBAR -->
        <div class="chat-sidebar">
            <div class="chat-sidebar-header">
                <h3><i class="fa-solid fa-message"></i> Tin nhắn</h3>
            </div>
            <div class="chat-list">
            </div>
        </div>

        <!-- CHAT -->
        <div class="chat-main">
            <!-- Placeholder -->
            <div class="chat-placeholder">
                <i class="fa-solid fa-comments"></i>
                <h4>Chọn một cuộc trò chuyện để bắt đầu</h4>
            </div>

            <div id="chatbox-area" class="chatbox" style="display: none; height: 100%; flex-direction: column;">
                <!-- HEADER -->
                <div class="chatbox-header">
                    <div class="d-flex align-items-center">
                        <div class="chat-avatar">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Loading...</h5>
                            <small class="text-muted">Xe: <a href="#">...</a></small>
                        </div>
                    </div>
                </div>

                <!-- MESSAGES -->
                <div class="chatbox-messages" id="chatMessages">
                    <!-- JS will populate this -->
                </div>

                <!-- INPUT -->
                <form class="chatbox-input" id="formSendMessage">
                    <textarea id="messageInput" placeholder="Nhập tin nhắn..." rows="1" required></textarea>
                    <button type="submit" class="btn-send">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>

    </div>

    </div>

    <script src="/web_project/View/JS/chat_spring.js"></script>
</body>

</html>