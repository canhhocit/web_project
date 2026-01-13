package com.example.chat.model;

import jakarta.persistence.*;
import lombok.Data;

@Entity
@Table(name = "xe")
@Data
public class Xe {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "idxe")
    private Long idXe;

    @Column(name = "tenxe")
    private String tenXe;

}
