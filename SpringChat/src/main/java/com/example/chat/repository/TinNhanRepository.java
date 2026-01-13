package com.example.chat.repository;

import com.example.chat.model.TinNhan;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Modifying;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;

import java.util.List;

public interface TinNhanRepository extends JpaRepository<TinNhan, Long> {
    List<TinNhan> findByIdCuocTcOrderByThoiGianGuiAsc(Long idCuocTc);

    TinNhan findFirstByIdCuocTcOrderByThoiGianGuiDesc(Long idCuocTc);

    @Query("SELECT COUNT(t) FROM TinNhan t WHERE t.idCuocTc = :idCuocTc AND t.idNguoiGui != :userId AND (t.daXem = 0 OR t.daXem IS NULL)")
    Long countUnreadMessages(@Param("idCuocTc") Long idCuocTc, @Param("userId") Long userId);

    @Modifying
    @Query("UPDATE TinNhan t SET t.daXem = 1 WHERE t.idCuocTc = :idCuocTc AND t.idNguoiGui != :userId AND (t.daXem = 0 OR t.daXem IS NULL)")
    void markMessagesAsRead(@Param("idCuocTc") Long idCuocTc, @Param("userId") Long userId);

    void deleteByIdCuocTc(Long idCuocTc);
}
