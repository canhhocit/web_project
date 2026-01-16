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
@RequestMapping("/api/chat")
@CrossOrigin(origins = "*")
public class ChatController {

    @Autowired
    private CuocTroChuyenRepository conversationRepo;

    @Autowired
    private TinNhanRepository messageRepo;

    @Autowired
    private SimpMessagingTemplate messagingTemplate;

    @GetMapping("/conversations")
    public List<com.example.chat.dto.ConversationDTO> getConversations(@RequestParam Long userId) {
        List<com.example.chat.dto.ConversationDTO> list = conversationRepo.findConversationsByUser(userId);

        for (com.example.chat.dto.ConversationDTO dto : list) {

            TinNhan lastMsg = messageRepo.findFirstByIdCuocTcOrderByThoiGianGuiDesc(dto.getIdCuocTc());
            if (lastMsg != null) {
                dto.setTinNhanCuoi(lastMsg.getNoiDung());
            } else {
                dto.setTinNhanCuoi("Chưa có tin nhắn");
            }

            Long count = messageRepo.countUnreadMessages(dto.getIdCuocTc(), userId);
            dto.setChuaDoc(count);
        }
        return list;
    }

    @GetMapping("/messages")
    public List<com.example.chat.dto.MessageDTO> getMessages(@RequestParam Long conversationId,
            @RequestParam Long userId) {
        List<TinNhan> messages = messageRepo.findByIdCuocTcOrderByThoiGianGuiAsc(conversationId);
        return messages.stream().map(msg -> new com.example.chat.dto.MessageDTO(
                msg.getIdTinNhan(),
                msg.getIdCuocTc(),
                msg.getIdNguoiGui(),
                msg.getNoiDung(),
                msg.getThoiGianGui(),
                msg.getIdNguoiGui().equals(userId))).collect(java.util.stream.Collectors.toList());
    }

    @DeleteMapping("/conversation/{id}")
    @Transactional
    public void deleteConversation(@PathVariable Long id, @RequestParam Long userId) {
        CuocTroChuyen c = conversationRepo.findById(id).orElseThrow(() -> new RuntimeException("Not found"));

        if (c.getIdNguoiThue().equals(userId)) {
            c.setNguoiThueDaXoa(1);
        } else if (c.getIdChuXe().equals(userId)) {
            c.setChuXeDaXoa(1);
        }

        Integer ntDeleted = c.getNguoiThueDaXoa() == null ? 0 : c.getNguoiThueDaXoa();
        Integer cxDeleted = c.getChuXeDaXoa() == null ? 0 : c.getChuXeDaXoa();

        if (ntDeleted == 1 && cxDeleted == 1) {

            messageRepo.deleteByIdCuocTc(id);
            conversationRepo.delete(c);
        } else {

            conversationRepo.save(c);
        }
    }

    @PostMapping("/create")
    public Long createConversation(@RequestBody CreateConversationRequest req) {

        CuocTroChuyen existing = conversationRepo.findExistingConversation(req.getIdNguoiThue(), req.getIdChuXe(),
                req.getIdXe());
        if (existing != null) {
            return existing.getIdCuocTc();
        }

        CuocTroChuyen c = new CuocTroChuyen();
        c.setIdNguoiThue(req.getIdNguoiThue());
        c.setIdChuXe(req.getIdChuXe());
        c.setIdXe(req.getIdXe());

        c.setNguoiThueDaXoa(0);
        c.setChuXeDaXoa(0);

        conversationRepo.save(c);
        return c.getIdCuocTc();
    }

    @PostMapping("/send")
    public TinNhan sendMessageRest(@RequestBody TinNhan chatMessage) {
        TinNhan saved = messageRepo.save(chatMessage);
        messagingTemplate.convertAndSend("/topic/conversation/" + chatMessage.getIdCuocTc(), saved);

        CuocTroChuyen c = conversationRepo.findById(chatMessage.getIdCuocTc()).orElse(null);
        if (c != null) {
            c.setCapNhatCuoi(java.time.LocalDateTime.now());

            if (c.getNguoiThueDaXoa() == 1)
                c.setNguoiThueDaXoa(0);
            if (c.getChuXeDaXoa() == 1)
                c.setChuXeDaXoa(0);

            conversationRepo.save(c);
        }

        return saved;
    }

    @PostMapping("/mark-read")
    @Transactional
    public void markAsRead(@RequestParam Long conversationId, @RequestParam Long userId) {
        messageRepo.markMessagesAsRead(conversationId, userId);
    }

    @GetMapping("/conversation/{id}")
    public com.example.chat.dto.ConversationDTO getConversationInfo(@PathVariable Long id, @RequestParam Long userId) {
        return conversationRepo.findConversationDTOById(id, userId);
    }

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
