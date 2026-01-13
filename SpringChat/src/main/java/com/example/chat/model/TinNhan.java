package com.example.chat.model;

import jakarta.persistence.*;
import lombok.Data;
import java.time.LocalDateTime;

@Entity
@Table(name = "tin_nhan")
@Data
public class TinNhan {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "id_tin_nhan") 
    private Long idTinNhan;

    @Column(name = "id_cuoc_tc")
    private Long idCuocTc;

    @Column(name = "id_nguoi_gui")
    private Long idNguoiGui;

    @Column(name = "noi_dung")
    private String noiDung;

    @Column(name = "thoi_gian_gui")
    private LocalDateTime thoiGianGui;

    @Column(name = "da_xem")
    private Integer daXem;

    @PrePersist
    protected void onCreate() {
        if (thoiGianGui == null) {
            thoiGianGui = LocalDateTime.now();
        }
        if (daXem == null) {
            daXem = 0;
        }
    }
}
