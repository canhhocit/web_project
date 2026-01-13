<?php if (isset($currentChatId) && isset($thongTinChat)): ?>
<div class="chatbox">
    <!-- HEADER -->
    <div class="chatbox-header">
        <div class="d-flex align-items-center">
            <a href="/web_project/index.php?controller=chat&action=index" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            
            <!-- Avatar -->
            <div class="chat-avatar">
                <?php if (!empty($thongTinChat['anhdaidien'])): ?>
                    <img src="/web_project/View/image/<?php echo $thongTinChat['anhdaidien']; ?>" alt="Avatar">
                <?php else: ?>
                    <i class="fa-solid fa-user"></i>
                <?php endif; ?>
            </div>
            
            <!-- Tên và thông tin xe -->
            <div>
                <h5 class="mb-0"><?php echo $thongTinChat['ten_nguoi_kia']; ?></h5>
                <small class="text-muted">
                    Xe: 
                    <a href="/web_project/index.php?controller=car&action=detail&id=<?php echo $thongTinChat['idxe']; ?>">
                        <?php echo $thongTinChat['tenxe']; ?>
                    </a>
                </small>
            </div>
        </div>
    </div>

    <!-- KHUNG TIN NHẮN -->
    <div class="chatbox-messages" id="chatMessages">
        <?php if (empty($danhSachTinNhan)): ?>
            <!-- Chưa có tin nhắn -->
            <div class="no-messages">
                <i class="fa-solid fa-comment-slash"></i>
                <p>Chưa có tin nhắn. Hãy bắt đầu trò chuyện!</p>
            </div>
        <?php else: ?>
            <!-- List msg -->
            <?php foreach ($danhSachTinNhan as $tin): ?>
                <?php
                // check 
                $isMine = ($tin['is_mine'] == 1);
                $messageClass = $isMine ? 'message-mine' : 'message-other';
                ?>
                
                <div class="message <?php echo $messageClass; ?>">
                    <div class="message-content">
                        <p><?php echo nl2br(htmlspecialchars($tin['noi_dung'])); ?></p>
                        <span class="message-time"><?php echo $tin['thoi_gian']; ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- form sendMessage -->
    <form class="chatbox-input" id="formSendMessage" method="POST" 
          action="/web_project/index.php?controller=chat&action=sendMessage">
        <input type="hidden" name="id_cuoc_tc" value="<?php echo $currentChatId; ?>">
        
        <textarea name="noi_dung" 
                  id="messageInput" 
                  placeholder="Nhập tin nhắn..." 
                  rows="1" 
                  required></textarea>
        
        <button type="submit" class="btn-send">
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </form>
</div>

<script>
// Tự động cuộn xuống tin nhắn mới nhất
const chatMessages = document.getElementById('chatMessages');
if (chatMessages) {
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Tự động điều chỉnh chiều cao textarea khi nhập
const messageInput = document.getElementById('messageInput');
messageInput.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
});

// Nhấn Enter để gửi (Shift+Enter để xuống dòng)
messageInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.getElementById('formSendMessage').submit();
    }
});
</script>
<?php endif; ?>