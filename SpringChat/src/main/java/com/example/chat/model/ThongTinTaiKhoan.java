package com.example.chat.model;

import jakarta.persistence.*;
import lombok.Data;

@Entity
@Table(name = "thongtintaikhoan")
@Data
public class ThongTinTaiKhoan {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "idthongtin")
    private Long idThongTin;

    @Column(name = "idtaikhoan")
    private Long idTaiKhoan;

    @Column(name = "hoten")
    private String hoTen;

    @Column(name = "anhdaidien")
    private String anhDaiDien;

}
