window.isModalOpen = false;

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
                window.isModalOpen = true;
            } catch (e) {
                alert("Lỗi xử lý dữ liệu từ server!");
            }
        }
    });
}

window.addEventListener('beforeunload', function (e) {
    if (isModalOpen) {
        e.preventDefault();
        e.returnValue = ''; 
    }
});

function thanhToanVNPay() {
    window.isModalOpen = false; 

    let idhoadon = $('#idhoadon').val();
    let tong_tien = $('#tong_tien').val().replace(/\D/g, '');
    let tenxe = $('#tenxe').val();
    
    let form = document.createElement('form');
    form.method = 'POST';
    form.action = '/web_project/vnpay_create_payment.php';
    
    let fields = {
        'order_id': idhoadon,
        'order_desc': 'Thanh toan tien thue: ' + tenxe,
        'order_type': 'billpayment',
        'amount': tong_tien,
        'language': 'vn',
        'bank_code': '',
        'txtexpire': '',
        'txt_billing_mobile': '',
        'txt_billing_email': '',
        'txt_billing_fullname': '',
        'txt_inv_addr1': '',
        'txt_bill_city': '',
        'txt_bill_country': '',
        'txt_bill_state': '',
        'txt_inv_mobile': '',
        'txt_inv_email': '',
        'txt_inv_customer': '',
        'txt_inv_company': '',
        'txt_inv_taxcode': '',
        'cbo_inv_type': '',
        'redirect': 'true'
    };
    
    for (let key in fields) {
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = fields[key];
        form.appendChild(input);
    }
    
    document.body.appendChild(form);
    form.submit();
}


function thanhToanSePay() {
    window.isModalOpen = false;

    let idhoadon = $('#idhoadon').val();
    let tong_tien = $('#tong_tien').val().replace(/\D/g, '');
    let tenxe = $('#tenxe').val();
    
    let form = document.createElement('form');
    form.method = 'POST';
    form.action = '/web_project/sepay_create_payment.php';
    
    let fields = {
        'order_id': idhoadon,
        'order_desc': 'Thanh toan tien thue: ' + tenxe,
        'amount': tong_tien
    };
    
    for (let key in fields) {
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = fields[key];
        form.appendChild(input);
    }
    
    document.body.appendChild(form);
    form.submit();
}

function huyTT() {
    if (confirm("Bạn chắc chắn muốn hủy giao dịch này?")) {
        window.isModalOpen = false;
        var modalElement = document.getElementById('modalTraXe');
        var modalInstance = bootstrap.Modal.getInstance(modalElement);
        if (modalInstance) {
            modalInstance.hide();
        }
    }
}



