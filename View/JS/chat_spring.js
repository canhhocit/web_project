const API_BASE_URL = 'http://localhost:8080/api/chat';
const WS_ENDPOINT = 'http://localhost:8080/ws';

let stompClient = null;
let currentSubscription = null;
let currentChatId = null;


document.addEventListener('DOMContentLoaded', () => {
    if (!CURRENT_USER_ID) {
        alert('Vui lòng đăng nhập!');
        window.location = '/web_project/View/taikhoan/login.php';
        return;
    }

    loadConversations();
    setupEventListeners();
    
    const urlParams = new URLSearchParams(window.location.search);
    const conversationId = urlParams.get('conversationId');
    const autoCreate = urlParams.get('autoCreate');
    
    if (conversationId) {
        setTimeout(() => selectConversation(conversationId), 500);
    } else if (autoCreate === 'true') {
        const idXe = urlParams.get('idXe');
        const idChuXe = urlParams.get('idChuXe');
        if (idXe && idChuXe) {
           createConversation(idXe, idChuXe);
        }
    }
});

async function loadConversations() {
    showLoading();
    try {
        const res = await fetch(`${API_BASE_URL}/conversations?userId=${CURRENT_USER_ID}`);
        if (!res.ok) throw new Error('Failed to load conversations');
        
        const conversations = await res.json();
        renderConversations(conversations);
    } catch (error) {
        console.error('Error loading conversations:', error);
        showError('Không thể tải danh sách tin nhắn');
    }
}

async function loadMessages(conversationId) {
    try {
        const res = await fetch(`${API_BASE_URL}/messages?conversationId=${conversationId}&userId=${CURRENT_USER_ID}`);
        if (!res.ok) throw new Error('Failed to load messages');
        
        const messages = await res.json();
        renderMessages(messages);
        scrollToBottom();

        markAsRead(conversationId);
    } catch (error) {
        console.error('Error loading messages:', error);
    }
}

async function loadConversationInfo(conversationId) {
    try {
        const res = await fetch(`${API_BASE_URL}/conversation/${conversationId}?userId=${CURRENT_USER_ID}`);
        if (!res.ok) throw new Error('Failed to load info');
        
        const info = await res.json();
        updateChatHeader(info);
    } catch (error) {
        console.error('Error loading conversation info:', error);
    }
}

async function sendMessage(content) {
    if (!currentChatId) return;

    try {
        const res = await fetch(`${API_BASE_URL}/send`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                idCuocTc: currentChatId,
                idNguoiGui: CURRENT_USER_ID,
                noiDung: content
            })
        });

        if (!res.ok) throw new Error('Failed to send message');
        
    } catch (error) {
        console.error('Error sending message:', error);
        alert('Gửi tin nhắn thất bại!');
    }
}

async function createConversation(idXe, idChuXe) {
    try {
        const res = await fetch(`${API_BASE_URL}/create`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                idNguoiThue: CURRENT_USER_ID,
                idChuXe: idChuXe,
                idXe: idXe
            })
        });

        if (!res.ok) throw new Error('Failed to create conversation');
        
        const conversationId = await res.json();
        selectConversation(conversationId);
    } catch (error) {
        console.error('Error creating conversation:', error);
        alert('Không thể tạo cuộc trò chuyện!');
    }
}

async function markAsRead(conversationId) {
    try {
        await fetch(`${API_BASE_URL}/mark-read?conversationId=${conversationId}&userId=${CURRENT_USER_ID}`, {
            method: 'POST'
        });
    } catch (error) {
        console.error('Error marking as read:', error);
    }
}

async function deleteConversation(conversationId) {
    if (!confirm('Bạn có chắc muốn xóa cuộc trò chuyện này?')) return;

    try {
        const res = await fetch(`${API_BASE_URL}/conversation/${conversationId}?userId=${CURRENT_USER_ID}`, {
            method: 'DELETE'
        });

        if (!res.ok) throw new Error('Failed to delete');
        
        loadConversations();
        showPlaceholder();
    } catch (error) {
        console.error('Error deleting conversation:', error);
        alert('Xóa thất bại!');
    }
}

//  WEBSOCKET 

function connectToChat(conversationId) {
    if (currentChatId === conversationId && stompClient && stompClient.connected) return;

    if (currentSubscription) {
        currentSubscription.unsubscribe();
    }

    currentChatId = conversationId;
    
    if (!stompClient || !stompClient.connected) {
        const socket = new SockJS(WS_ENDPOINT);
        stompClient = Stomp.over(socket);
        stompClient.debug = null;

        stompClient.connect({}, () => {
            console.log('WebSocket connected');
            subscribeToConversation(conversationId);
        }, (error) => {
            console.error('STOMP error:', error);
        });
    } else {
        subscribeToConversation(conversationId);
    }
}

function subscribeToConversation(conversationId) {
    currentSubscription = stompClient.subscribe(`/topic/conversation/${conversationId}`, (messageOutput) => {
        const message = JSON.parse(messageOutput.body);
        appendMessage(message);
        scrollToBottom();
        
        updateSidebarPreview(conversationId, message.noiDung);
    });
}

//  UI RENDERING 

function renderConversations(list) {
    const container = document.querySelector('.chat-list');
    if (!container) return;
    
    container.innerHTML = '';

    if (list.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fa-solid fa-comment-slash"></i>
                <p>Chưa có tin nhắn</p>
            </div>
        `;
        return;
    }

    list.forEach(c => {
        const div = document.createElement('div');
        div.className = 'chat-item';
        div.onclick = () => selectConversation(c.idCuocTc);
        
        const avatarHtml = c.anhDaiDien 
            ? `<img src="/web_project/View/image/${c.anhDaiDien}" alt="Avatar">`
            : `<i class="fa-solid fa-user"></i>`;

        const unreadBadge = c.chuaDoc > 0 
            ? `<span class="badge-unread">${c.chuaDoc}</span>`
            : '';

        // Format time
        let timeDisplay = '';
        if (c.thoiGianCuoi) {
            const date = new Date(c.thoiGianCuoi);
            timeDisplay = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }

        div.innerHTML = `
            <div class="chat-item-avatar">${avatarHtml}</div>
            <div class="chat-item-info">
                <div class="chat-item-top">
                    <h5>${c.tenNguoiKia || 'Ẩn danh'}</h5>
                    <span class="chat-time">${timeDisplay}</span>
                </div>
                <div class="chat-item-bottom">
                    <p class="chat-preview ${c.chuaDoc > 0 ? 'unread' : ''}">
                        ${c.tinNhanCuoi || 'Chưa có tin nhắn'}
                    </p>
                    ${unreadBadge}
                </div>
                <div class="chat-item-footer">
                    <small>Xe: ${c.tenXe}</small>
                    <button class="btn-delete-chat" onclick="event.stopPropagation(); deleteConversation(${c.idCuocTc})">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(div);
    });
}

function selectConversation(conversationId) {
    // Highlight UI
    document.querySelectorAll('.chat-item').forEach(el => el.classList.remove('active'));
    event?.currentTarget?.classList.add('active');

    // Show chatbox
    showChatbox();
    
    // Load data
    clearMessages();
    loadConversationInfo(conversationId);
    loadMessages(conversationId);
    connectToChat(conversationId);
}

function renderMessages(messages) {
    const container = document.getElementById('chatMessages');
    if (!container) return;
    
    container.innerHTML = '';
    messages.forEach(m => appendMessage(m));
}

function appendMessage(msg) {
    const container = document.getElementById('chatMessages');
    if (!container) return;

    // Deduplication check
    if (document.querySelector(`.message[data-id="${msg.idTinNhan}"]`)) {
        return;
    }

    const div = document.createElement('div');
    // Compute isMine safely
    const isMine = msg.idNguoiGui == CURRENT_USER_ID; // loose equality for string/int mix
    
    div.className = `message ${isMine ? 'message-mine' : 'message-other'}`;
    div.setAttribute('data-id', msg.idTinNhan);
    
    // Format timestamp
    let timeStr = '';
    if (msg.thoiGianGui) {
        timeStr = new Date(msg.thoiGianGui).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    } else if (msg.thoiGian) {
        timeStr = msg.thoiGian;
    }

    div.innerHTML = `
        <div class="message-content">
            <p>${escapeHtml(msg.noiDung)}</p>
            <span class="message-time">${timeStr}</span>
        </div>
    `;
    
    container.appendChild(div);
}

function updateChatHeader(info) {
    const headerName = document.querySelector('.chatbox-header h5');
    const headerAvatar = document.querySelector('.chatbox-header .chat-avatar');
    const headerLink = document.querySelector('.chatbox-header small a');

    if (headerName) headerName.textContent = info.tenNguoiKia || 'Ẩn danh';
    
    if (headerAvatar) {
        headerAvatar.innerHTML = info.anhDaiDien 
            ? `<img src="/web_project/View/image/${info.anhDaiDien}" alt="Avatar">`
            : `<i class="fa-solid fa-user"></i>`;
    }

    
    const subtitle = document.querySelector('.chatbox-header small');
    if (subtitle) {
        // subtitle.innerHTML = `Xe: <a href="/web_project/index.php?controller=car&action=detail&id=${info.idXe}" target="_blank">${info.tenXe}</a>`;
        subtitle.innerHTML = `Xe: <a href="/web_project/index.php?controller=car&action=detail&id=${info.idXe}" >${info.tenXe}</a>`;
    }
}

function updateSidebarPreview(conversationId, text) {
}

//  HELPERS 

function showChatbox() {
    document.querySelector('.chat-placeholder')?.style.setProperty('display', 'none');
    document.getElementById('chatbox-area')?.style.setProperty('display', 'flex');
}

function showPlaceholder() {
    document.querySelector('.chat-placeholder')?.style.setProperty('display', 'flex');
    document.getElementById('chatbox-area')?.style.setProperty('display', 'none');
}

function clearMessages() {
    const container = document.getElementById('chatMessages');
    if (container) container.innerHTML = '';
}

function scrollToBottom() {
    const container = document.getElementById('chatMessages');
    if (container) container.scrollTop = container.scrollHeight;
}

function showLoading() {
    const container = document.querySelector('.chat-list');
    if (container) {
        container.innerHTML = '<div class="text-center mt-5"><i class="fa-solid fa-spinner fa-spin"></i> Đang tải...</div>';
    }
}

function showError(message) {
    const container = document.querySelector('.chat-list');
    if (container) {
        container.innerHTML = `<div class="text-center text-danger mt-5">${message}</div>`;
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function setupEventListeners() {
    const form = document.getElementById('formSendMessage');
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const input = document.getElementById('messageInput');
            const text = input.value.trim();
            if (text && currentChatId) {
                sendMessage(text);
                input.value = '';
            }
        });
    }

    // Auto-resize textarea
    const messageInput = document.getElementById('messageInput');
    if (messageInput) {
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });

        // Enter to send (Shift+Enter for new line)
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                form.dispatchEvent(new Event('submit'));
            }
        });
    }
}

window.createChatFromCarDetail = createConversation;