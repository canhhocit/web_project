<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Quản lý thuê xe</title>

    <link rel="stylesheet" href="View/CSS/thanhtoan.css" />
    <link rel="stylesheet" href="/web_project/View/CSS/nguyen_css_thueXe.css" />
    <link rel="stylesheet" href="/web_project/View/CSS/nguyen_css_quanly.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
    <div class="part_overlay_quanly">
        <div class="container_table_quanly">
            <div class="tabs">
                <div class="tab active" onclick="switchTab(1)">Đang thuê</div>
                <div id="id_content_tab" class="tab" onclick="switchTab(2)">Đã thuê</div>
            </div>
            <div class="content_container_quanly">
                <div class="content" id="content"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTraXe" data-bs-keyboard="false" data-bs-backdrop="static"  tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">THÔNG TIN THANH TOÁN</h5>
                </div>
                <div class="modal-body">
                    <form id="formTraXe">
                        <input type="hidden" id="idhoadon">
                        <div class="mb-3">
                            <label class="fw-bold">Tên xe:</label>
                            <input type="text" class="form-control bg-light" id="tenxe" readonly>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="fw-bold">Số ngày thuê:</label>
                                <input type="text" class="form-control bg-light" id="ngay_thue_du_kien" readonly>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-bold">Số ngày quá hạn:</label>
                                <input type="text" class="form-control bg-light" id="ngay_qua_han" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold text-danger">Tổng tiền:</label>
                            <input type="text" class="form-control fw-bold text-danger" id="tong_tien" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Ghi chú:</label>
                            <option value="Ghi chú" class="form-control bg-light" >Thanh toán tiền thuê xe</option>
                        </div>

                    </form>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: space-between; gap: 10px;">
                    <button type="button" class="btn btn-secondary" onclick="huyTT()">Hủy</button>
                    <div style="display: flex; gap: 10px;">
                        <button type="button" class="btn btn-success" onclick="thanhToanSePay()">
                            <i class="fas fa-qrcode"></i> SePay
                        </button>
                        <button type="button" class="btn btn-primary" onclick="thanhToanVNPay()">
                            <i class="fas fa-credit-card"></i> VNPay
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/web_project/View/JS/nguyen_quanly.js"></script>
    <script src="/web_project/View/JS/thanhtoan.js"></script> 
    <script>

        function switchTab(tabIndex) {
            // UI Tabs
            document.querySelectorAll(".tab").forEach(t => t.classList.remove("active"));
            const tabs = document.querySelectorAll(".tab");
            if(tabs[tabIndex-1]) tabs[tabIndex-1].classList.add("active");
            const content = document.getElementById("content");
            content.innerHTML = "";
            
            // Gọi JS fetch cho cả tab 1 và tab 2
            if (typeof renderTab === "function") {
                renderTab(tabIndex);
            } else {
                console.error("Chưa load được file nguyen_quanly.js");
            }
        }
        
        window.onload = () => switchTab(1);
    </script>
</body>
</html>