const API_BASE_URL = 'http://localhost:8080/api/chat';
const WS_ENDPOINT = 'http://localhost:8080/ws';

let stompClient = null;         
let currentChatId = null;    
let currentSubscription = null;

document.addEventListener('DOMContentLoaded', () => {
    if (!CURRENT_USER_ID) {
        alert('Vui lòng đăng nhập!');
        window.location = '/web_project/View/taikhoan/login.php';
        return;
    }

    loadConversations(); 
    setupEventListeners(); // addEvent

    const urlParams = new URLSearchParams(window.location.search);
    const conversationId = urlParams.get('conversationId');
    const autoCreate = urlParams.get('autoCreate');
    
    if (conversationId) {
        setTimeout(() => selectConversation(conversationId), 500);
    } else if (autoCreate === 'true') {
        const idXe = urlParams.get('idXe');
        const idChuXe = urlParams.get('idChuXe');
        if (idXe && idChuXe) createConversation(idXe, idChuXe);
    }
});


// Lấy/Gửi dữ liệu

async function loadConversations() {
    try {
        const res = await fetch(`${API_BASE_URL}/conversations?userId=${CURRENT_USER_ID}`);
        const data = await res.json();
        renderConversations(data); // Vẽ giao diện
    } catch (e) {
        console.error("Lỗi tải danh sách chat:", e);
    }
}

async function loadMessages(conversationId) {
    try {
        const res = await fetch(`${API_BASE_URL}/messages?conversationId=${conversationId}&userId=${CURRENT_USER_ID}`);
        const messages = await res.json();
        
        renderMessages(messages); 
        scrollToBottom(); 
        
        // mark read
        fetch(`${API_BASE_URL}/mark-read?conversationId=${conversationId}&userId=${CURRENT_USER_ID}`, { method: 'POST' });
    } catch (e) {
        console.error("Lỗi tải tin nhắn:", e);
    }
}

async function sendMessage(content) {
    if (!currentChatId) return;
    try {
        await fetch(`${API_BASE_URL}/send`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                idCuocTc: currentChatId,
                idNguoiGui: CURRENT_USER_ID,
                noiDung: content
            })
        });
    } catch (e) {
        alert('Gửi tin nhắn lỗi!');
    }
}

async function createConversation(idXe, idChuXe) {
    try {
        const res = await fetch(`${API_BASE_URL}/create`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ idNguoiThue: CURRENT_USER_ID, idChuXe: idChuXe, idXe: idXe })
        });
        const newId = await res.json();
        selectConversation(newId); // Tạo xong mở luôn
    } catch (e) {
        alert('Không thể tạo cuộc trò chuyện!');
    }
}

async function deleteConversation(conversationId) {
    if (!confirm('Bạn có chắc muốn xóa?')) return;
    try {
        await fetch(`${API_BASE_URL}/conversation/${conversationId}?userId=${CURRENT_USER_ID}`, { method: 'DELETE' });
        loadConversations(); 
        
        document.querySelector('.chat-placeholder').style.display = 'flex';
        document.getElementById('chatbox-area').style.display = 'none';
    } catch (e) {
        alert('Xóa thất bại!');
    }
}


//UI
// list Conver
function renderConversations(list) {
    const container = document.querySelector('.chat-list');
    container.innerHTML = ''; // Làm sạch danh sách cũ

    if (list.length === 0) {
        container.innerHTML = '<div class="empty-state"><p>Chưa có tin nhắn nào</p></div>';
        return;
    }

    list.forEach(c => {
        const div = document.createElement('div');
        div.className = 'chat-item';
        div.onclick = () => selectConversation(c.idCuocTc); // Bấm vào để mở chat
        
        const avatar = c.anhDaiDien ? `<img src="/web_project/View/image/${c.anhDaiDien}">` : `<i class="fa-solid fa-user"></i>`;
        const badge = c.chuaDoc > 0 ? `<span class="badge-unread">${c.chuaDoc}</span>` : '';

        div.innerHTML = `
            <div class="chat-item-avatar">${avatar}</div>
            <div class="chat-item-info">
                <h5>${c.tenNguoiKia || 'Ẩn danh'}</h5>
                <p class="chat-preview">${c.tinNhanCuoi || '...'}</p>
                ${badge}
            </div>
            <div class="chat-item-footer">
                <button class="btn-delete-chat" onclick="event.stopPropagation(); deleteConversation(${c.idCuocTc})">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(div);
    });
}

// list msg
function renderMessages(messages) {
    const container = document.getElementById('chatMessages');
    container.innerHTML = ''; 
    messages.forEach(m => appendMessage(m));
}

//1 msg
function appendMessage(msg) {
    const container = document.getElementById('chatMessages');
    
    // Kiểm tra trùng tin nhắn (quan trọng, vì socket có thể gửi trùng)
    if (document.querySelector(`.message[data-id="${msg.idTinNhan}"]`)) return;

    const div = document.createElement('div');
    const isMine = (msg.idNguoiGui == CURRENT_USER_ID); // Kiem tra tin cua minh hay ko
    
    div.className = `message ${isMine ? 'message-mine' : 'message-other'}`;
    div.setAttribute('data-id', msg.idTinNhan);
    div.innerHTML = `<div class="message-content"><p>${escapeHtml(msg.noiDung)}</p></div>`;
    
    container.appendChild(div);
    container.scrollTop = container.scrollHeight; // Cuộn xuống tin mới nhất
}

async function selectConversation(id) {
    document.querySelector('.chat-placeholder').style.display = 'none';
    document.getElementById('chatbox-area').style.display = 'flex';
    
    document.querySelectorAll('.chat-item').forEach(el => el.classList.remove('active'));
    event?.currentTarget?.classList.add('active');

    //Tải dữ liệu và kết nối Socket
    currentChatId = id;
    loadMessages(id);
    connectToChat(id);

    //Cập nhật Tiêu đề (Tên người kia, Tên xe)
    const res = await fetch(`${API_BASE_URL}/conversation/${id}?userId=${CURRENT_USER_ID}`);
    const info = await res.json();
    document.querySelector('.chatbox-header h5').textContent = info.tenNguoiKia;
    const subtitle = document.querySelector('.chatbox-header small');
    if (subtitle) subtitle.innerHTML = `Xe: <a href="/web_project/index.php?controller=car&action=detail&id=${info.idXe}">${info.tenXe}</a>`;
}


// connect ws

function connectToChat(conversationId) {
    // Nếu chưa có kết nối -> Tạo mới
    if (!stompClient) {
        const socket = new SockJS(WS_ENDPOINT);
        stompClient = Stomp.over(socket);
        stompClient.debug = null; // Tắt log debug cho gọn
        stompClient.connect({}, () => subscribeToConversation(conversationId));
    } else {
        // Nếu có rồi -> Chỉ cần đổi kênh
        subscribeToConversation(conversationId);
    }
}

function subscribeToConversation(id) {
    // Hủy đăng ký kênh cũ (nếu có)
    if (currentSubscription) currentSubscription.unsubscribe();

    // Đăng ký kênh mới: /topic/conversation/{ID_CUOC_TRO_CHUYEN}
    currentSubscription = stompClient.subscribe(`/topic/conversation/${id}`, (payload) => {
        const msg = JSON.parse(payload.body);
        appendMessage(msg); // Có tin mới -> Vẽ ngay lập tức
    });
}


// ==========================================================
function setupEventListeners() {
    const form = document.getElementById('formSendMessage');
    if(!form) return;

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const input = document.getElementById('messageInput');
        const text = input.value.trim();
        if (text) {
            sendMessage(text);
            input.value = ''; 
        }
    });
    document.getElementById('messageInput').addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            form.dispatchEvent(new Event('submit'));
        }
    });
}

function scrollToBottom() {
    const c = document.getElementById('chatMessages');
    if(c) c.scrollTop = c.scrollHeight;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

window.createChatFromCarDetail = createConversation;