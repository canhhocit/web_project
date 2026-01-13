package com.example.chat.model;

import jakarta.persistence.*;
import lombok.Data;

@Entity
@Table(name = "taikhoan")
@Data
public class TaiKhoan {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "idtaikhoan")
    private Long idTaiKhoan;

    @Column(name = "hoten")
    private String hoTen;

    @Column(name = "anhdaidien")
    private String anhDaiDien;

}
