function showModal(idhoadon) {
    $.ajax({
        url: 'index.php?controller=thanhtoan&action=getChiTietHoaDon&idhoadon=' + idhoadon,
        type: 'GET',
        success: function(data) {
            try {
                let res = JSON.parse(data);
                
                $('#idhoadon').val(res.idhoadon);
                $('#tenxe').val(res.tenxe);
                $('#giathue').val(parseFloat(res.giathue).toLocaleString('vi-VN'));
                $('#ngay_thue_du_kien').val(res.ngay_thue_du_kien);
                $('#ngay_qua_han').val(res.ngay_qua_han);
                $('#tong_tien').val(parseFloat(res.tong_tien).toLocaleString('vi-VN'));

                var myModal = new bootstrap.Modal(document.getElementById('modalTraXe'));
                myModal.show();
            } catch (e) {
                alert("Lỗi xử lý dữ liệu từ server!");
            }
        }
    });
}

function xacNhanTraXe() {
    let formData = {
        idhoadon: $('#idhoadon').val(),
        ngay_qua_han: $('#ngay_qua_han').val(),
        // Xóa dấu chấm phân cách nghìn trước khi gửi
        tong_tien: $('#tong_tien').val().replace(/\D/g, ''),
        phuongthuc: $('#phuongthuc').val()
    };
    
    $.ajax({
        url: 'index.php?controller=thanhtoan&action=xacNhanTraXe',
        type: 'POST',
        data: formData,
        success: function(response) {
            let res = JSON.parse(response);
            if (res.success) {
                alert('Thanh toán thành công!');
                location.reload(); 
            } else {
                alert('Có lỗi xảy ra khi lưu dữ liệu!');
            }
        }
    });
}

function huyTT() {
    if (confirm("Xác nhận hủy thanh toán?")) {
        var modalElement = document.getElementById('modalTraXe');
        var modalInstance = bootstrap.Modal.getInstance(modalElement);
        if (modalInstance) {
            modalInstance.hide();
        }
    }
}



