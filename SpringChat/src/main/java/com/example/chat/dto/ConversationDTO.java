package com.example.chat.dto;

import lombok.Data;
import java.time.LocalDateTime;

@Data
public class ConversationDTO {
    private Long idCuocTc;
    private Long idXe;
    private String tenXe;
    private Long idNguoiKia;
    private String tenNguoiKia;
    private String anhDaiDien;
    private String tinNhanCuoi;
    private LocalDateTime thoiGianCuoi;
    private Long chuaDoc;

    public ConversationDTO(Long idCuocTc, Long idXe, String tenXe, Long idNguoiKia, String tenNguoiKia,
            String anhDaiDien, LocalDateTime thoiGianCuoi) {
        this.idCuocTc = idCuocTc;
        this.idXe = idXe;
        this.tenXe = tenXe;
        this.idNguoiKia = idNguoiKia;
        this.tenNguoiKia = tenNguoiKia;
        this.anhDaiDien = anhDaiDien != null ? anhDaiDien : "";
        this.thoiGianCuoi = thoiGianCuoi;
        this.chuaDoc = 0L;
    }
}
