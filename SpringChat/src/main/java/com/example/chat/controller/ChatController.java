package com.example.chat.controller;

import com.example.chat.model.CuocTroChuyen;
import com.example.chat.model.TinNhan;
import com.example.chat.repository.CuocTroChuyenRepository;
import com.example.chat.repository.TinNhanRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.messaging.simp.SimpMessagingTemplate;
import org.springframework.web.bind.annotation.*;
import jakarta.transaction.Transactional;

import java.util.List;

@RestController 
@RequestMapping("/api/chat") // Tất cả các đường dẫn sẽ bắt đầu bằng /api/chat
@CrossOrigin(origins = "*") // Cho phép gọi API từ trang web khác (PHP đang chạy khác cổng với Java)
public class ChatController {

    // @Autowired: Spring Boot tự động tìm và "tiêm" (inject) cái Repository vào đây
    // để mình dùng.
    // Không cần phải new CuocTroChuyenRepository().
    @Autowired
    private CuocTroChuyenRepository conversationRepo; 

    @Autowired
    private TinNhanRepository messageRepo;

    @Autowired
    private SimpMessagingTemplate messagingTemplate; // Công cụ gửi tin nhắn Real-time (WebSocket)

    /**
     * 1. Lấy danh sách cuộc trò chuyện của một người.
     * URL: GET /api/chat/conversations?userId=1
     */
    @GetMapping("/conversations")
    public List<com.example.chat.dto.ConversationDTO> getConversations(@RequestParam Long userId) {
        // Bước 1: Lấy danh sách thô từ Database (đã được lọc xóa mềm trong Repository)
        List<com.example.chat.dto.ConversationDTO> list = conversationRepo.findConversationsByUser(userId);

        // Bước 2: Duyệt qua từng cuộc trò chuyện để bổ sung thông tin thiếu
        for (com.example.chat.dto.ConversationDTO dto : list) {

            // Tìm tin nhắn mới nhất để hiển thị ra ngoài (VD: "Chào bạn...")
            TinNhan lastMsg = messageRepo.findFirstByIdCuocTcOrderByThoiGianGuiDesc(dto.getIdCuocTc());
            if (lastMsg != null) {
                dto.setTinNhanCuoi(lastMsg.getNoiDung());
            } else {
                dto.setTinNhanCuoi("Chưa có tin nhắn"); // Nếu mới tạo chưa chat gì
            }

            // Đếm số tin nhắn CHƯA ĐỌC (mà người khác gửi cho mình)
            Long count = messageRepo.countUnreadMessages(dto.getIdCuocTc(), userId);
            dto.setChuaDoc(count); // Gán vào DTO để Frontend hiển thị số đỏ đỏ
        }

        // Bước 3: Trả về danh sách đã đầy đủ thông tin
        return list;
    }

    /**
     * 2. Lấy chi tiết các tin nhắn trong 1 cuộc trò chuyện.
     * URL: GET /api/chat/messages?conversationId=10&userId=1
     */
    @GetMapping("/messages")
    public List<com.example.chat.dto.MessageDTO> getMessages(@RequestParam Long conversationId,
            @RequestParam Long userId) {

        // Lấy tất cả tin nhắn của cuộc hội thoại này, sắp xếp cũ trước -> mới sau (ASC)
        List<TinNhan> messages = messageRepo.findByIdCuocTcOrderByThoiGianGuiAsc(conversationId);

        // Chuyển đổi từ Entity (TinNhan) sang DTO (MessageDTO)
        // Mục đích: Để tính toán xem tin nhắn là "Của mình" (isMine = true) hay "Của
        // họ"
        return messages.stream().map(msg -> new com.example.chat.dto.MessageDTO(
                msg.getIdTinNhan(),
                msg.getIdCuocTc(),
                msg.getIdNguoiGui(),
                msg.getNoiDung(),
                msg.getThoiGianGui(),
                msg.getIdNguoiGui().equals(userId) // So sánh ID người gửi với ID hiện tại -> True/False
        )).collect(java.util.stream.Collectors.toList());
    }

    /**
     * URL: DELETE /api/chat/conversation/10
     */
    @DeleteMapping("/conversation/{id}")
    @Transactional // Đảm bảo tính toàn vẹn dữ liệu (nếu lỗi thì rollback lại hết)
    public void deleteConversation(@PathVariable Long id, @RequestParam Long userId) {
        // Tìm cuộc trò chuyện, nếu không thấy thì báo lỗi
        CuocTroChuyen c = conversationRepo.findById(id)
                .orElseThrow(() -> new RuntimeException("Không tìm thấy cuộc trò chuyện"));

        if (c.getIdNguoiThue().equals(userId)) {
            c.setNguoiThueDaXoa(1); // Người thuê xóa
        } else if (c.getIdChuXe().equals(userId)) {
            c.setChuXeDaXoa(1); // Chủ xe xóa
        }

        // Kiểm tra trạng thái của cả 2 bên
        Integer ntDeleted = c.getNguoiThueDaXoa() == null ? 0 : c.getNguoiThueDaXoa();
        Integer cxDeleted = c.getChuXeDaXoa() == null ? 0 : c.getChuXeDaXoa();

        // Nếu CẢ HAI đều đã xóa 
        if (ntDeleted == 1 && cxDeleted == 1) {
            messageRepo.deleteByIdCuocTc(id);
            conversationRepo.delete(c);
        } else {
            // Nếu chỉ 1 người xóa
            conversationRepo.save(c);
        }
    }

    /**
     * 4. Tạo cuộc trò chuyện mới (Khi bấm nút "Chat ngay" trên web)
     */
    @PostMapping("/create")
    public Long createConversation(@RequestBody CreateConversationRequest req) {
        // Bước 1: Kiểm tra xem 2 người này đã từng chat về xe này chưa?
        CuocTroChuyen existing = conversationRepo.findExistingConversation(req.getIdNguoiThue(), req.getIdChuXe(),
                req.getIdXe());

        // Nếu đã có rồi -> Trả về ID cũ chứ không tạo mới (tránh trùng lặp)
        if (existing != null) {
            return existing.getIdCuocTc();
        }

        // Bước 2: Nếu chưa có -> Tạo mới
        CuocTroChuyen c = new CuocTroChuyen();
        c.setIdNguoiThue(req.getIdNguoiThue());
        c.setIdChuXe(req.getIdChuXe());
        c.setIdXe(req.getIdXe());

        // Mặc định ban đầu chưa ai xóa cả
        c.setNguoiThueDaXoa(0);
        c.setChuXeDaXoa(0);

        conversationRepo.save(c); // Lưu vào DB
        return c.getIdCuocTc(); // Trả về ID mới tạo
    }

    /**
     * 5. Gửi tin nhắn
     */
    @PostMapping("/send")
    public TinNhan sendMessageRest(@RequestBody TinNhan chatMessage) {
        // Bước 1: Lưu tin nhắn vào Database
        TinNhan saved = messageRepo.save(chatMessage);

        // Bước 2: Gửi thông báo Real-time cho những người đang xem cuộc này
        // (Ai đang subscribe vào /topic/conversation/ID sẽ nhận được ngay lập tức)
        messagingTemplate.convertAndSend("/topic/conversation/" + chatMessage.getIdCuocTc(), saved);

        // Bước 3: Cập nhật thời gian chat cuối cùng cho cuộc trò chuyện
        CuocTroChuyen c = conversationRepo.findById(chatMessage.getIdCuocTc()).orElse(null);
        if (c != null) {
            c.setCapNhatCuoi(java.time.LocalDateTime.now()); // Update time để nó nổi lên đầu danh sách

            // Nếu người kia lỡ xóa cuộc trò chuyện rồi, thì khôi phục lại (Set xóa = 0)
            // Để họ nhận được tin nhắn mới này
            if (c.getNguoiThueDaXoa() == 1)
                c.setNguoiThueDaXoa(0);
            if (c.getChuXeDaXoa() == 1)
                c.setChuXeDaXoa(0);

            conversationRepo.save(c);
        }

        return saved;
    }

    /**
     * 6. Đánh dấu đã đọc
     */
    @PostMapping("/mark-read")
    @Transactional
    public void markAsRead(@RequestParam Long conversationId, @RequestParam Long userId) {
        // Gọi hàm trong Repo để update tất cả tin nhắn của người kia gửi -> daXem = 1
        messageRepo.markMessagesAsRead(conversationId, userId);
    }

    /**
     * 7. Lấy thông tin 1 cuộc trò chuyện (Cho header chatbox)
     */
    @GetMapping("/conversation/{id}")
    public com.example.chat.dto.ConversationDTO getConversationInfo(@PathVariable Long id, @RequestParam Long userId) {
        return conversationRepo.findConversationDTOById(id, userId);
    }

    /**
     * Class phụ (Inner Class) để hứng dữ liệu gửi lên khi tạo chat mới
     * Dùng DTO giúp code gọn gàng, đón đúng dữ liệu cần thiết.
     */
    public static class CreateConversationRequest {
        private Long idNguoiThue;
        private Long idChuXe;
        private Long idXe;

        public Long getIdNguoiThue() {
            return idNguoiThue;
        }

        public void setIdNguoiThue(Long idNguoiThue) {
            this.idNguoiThue = idNguoiThue;
        }

        public Long getIdChuXe() {
            return idChuXe;
        }

        public void setIdChuXe(Long idChuXe) {
            this.idChuXe = idChuXe;
        }

        public Long getIdXe() {
            return idXe;
        }

        public void setIdXe(Long idXe) {
            this.idXe = idXe;
        }
    }
}
