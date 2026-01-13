package com.example.chat.repository;

import com.example.chat.dto.ConversationDTO;
import com.example.chat.model.CuocTroChuyen;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import java.util.List;

public interface CuocTroChuyenRepository extends JpaRepository<CuocTroChuyen, Long> {

    @Query("SELECT new com.example.chat.dto.ConversationDTO(" +
            "c.idCuocTc, c.idXe, x.tenXe, " +
            "CASE WHEN c.idNguoiThue = :userId THEN ttCX.idTaiKhoan ELSE ttNT.idTaiKhoan END, " +
            "CASE WHEN c.idNguoiThue = :userId THEN ttCX.hoTen ELSE ttNT.hoTen END, " +
            "CASE WHEN c.idNguoiThue = :userId THEN ttCX.anhDaiDien ELSE ttNT.anhDaiDien END, " +
            "c.capNhatCuoi) " +
            "FROM CuocTroChuyen c " +
            "JOIN Xe x ON c.idXe = x.idXe " +
            "LEFT JOIN ThongTinTaiKhoan ttNT ON c.idNguoiThue = ttNT.idTaiKhoan " +
            "LEFT JOIN ThongTinTaiKhoan ttCX ON c.idChuXe = ttCX.idTaiKhoan " +
            "WHERE (c.idNguoiThue = :userId AND c.nguoiThueDaXoa = 0) OR (c.idChuXe = :userId AND c.chuXeDaXoa = 0) " +
            "ORDER BY c.capNhatCuoi DESC")
    List<ConversationDTO> findConversationsByUser(@Param("userId") Long userId);

    @Query("SELECT new com.example.chat.dto.ConversationDTO(" +
            "c.idCuocTc, c.idXe, x.tenXe, " +
            "CASE WHEN c.idNguoiThue = :userId THEN ttCX.idTaiKhoan ELSE ttNT.idTaiKhoan END, " +
            "CASE WHEN c.idNguoiThue = :userId THEN ttCX.hoTen ELSE ttNT.hoTen END, " +
            "CASE WHEN c.idNguoiThue = :userId THEN ttCX.anhDaiDien ELSE ttNT.anhDaiDien END, " +
            "c.capNhatCuoi) " +
            "FROM CuocTroChuyen c " +
            "JOIN Xe x ON c.idXe = x.idXe " +
            "LEFT JOIN ThongTinTaiKhoan ttNT ON c.idNguoiThue = ttNT.idTaiKhoan " +
            "LEFT JOIN ThongTinTaiKhoan ttCX ON c.idChuXe = ttCX.idTaiKhoan " +
            "WHERE c.idCuocTc = :idCuocTc")
    ConversationDTO findConversationDTOById(@Param("idCuocTc") Long idCuocTc, @Param("userId") Long userId);

    @Query("SELECT c FROM CuocTroChuyen c WHERE c.idNguoiThue = :idNguoiThue AND c.idChuXe = :idChuXe AND c.idXe = :idXe")
    CuocTroChuyen findExistingConversation(@Param("idNguoiThue") Long idNguoiThue,
            @Param("idChuXe") Long idChuXe,
            @Param("idXe") Long idXe);
}
