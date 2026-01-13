package com.example.chat.dto;

import lombok.Data;
import java.time.LocalDateTime;

@Data
public class MessageDTO {
    private Long idTinNhan;
    private Long idCuocTc;
    private Long idNguoiGui;
    private String noiDung;
    private LocalDateTime thoiGianGui;
    private String thoiGian; 
    private boolean isMine;

    public MessageDTO(Long idTinNhan, Long idCuocTc, Long idNguoiGui, String noiDung, LocalDateTime thoiGianGui,
            boolean isMine) {
        this.idTinNhan = idTinNhan;
        this.idCuocTc = idCuocTc;
        this.idNguoiGui = idNguoiGui;
        this.noiDung = noiDung;
        this.thoiGianGui = thoiGianGui;
        this.isMine = isMine;
        this.thoiGian = thoiGianGui.toString(); 
    }
}
