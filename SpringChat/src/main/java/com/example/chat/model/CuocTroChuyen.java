package com.example.chat.model;

import jakarta.persistence.*;
import lombok.Data;
import java.time.LocalDateTime;

@Entity
@Table(name = "cuoc_tro_chuyen")
@Data
public class CuocTroChuyen {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "id_cuoc_tc")
    private Long idCuocTc;

    @Column(name = "id_nguoi_thue")
    private Long idNguoiThue;

    @Column(name = "id_chu_xe")
    private Long idChuXe;

    @Column(name = "id_xe")
    private Long idXe;

    @Column(name = "nguoi_thue_da_xoa")
    private Integer nguoiThueDaXoa;

    @Column(name = "chu_xe_da_xoa")
    private Integer chuXeDaXoa;

    @Column(name = "cap_nhat_cuoi")
    private LocalDateTime capNhatCuoi;

    @PrePersist
    protected void onCreate() {
        if (capNhatCuoi == null) {
            capNhatCuoi = LocalDateTime.now();
        }
    }

    @PreUpdate
    protected void onUpdate() {
        capNhatCuoi = LocalDateTime.now();
    }
}
