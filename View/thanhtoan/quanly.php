<?php
global $conn; 

// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

$idtaikhoan = $_SESSION['idtaikhoan']; 

$data_from_db = [
    1 => [], // Đang thuê
    2 => []  // Đã thuê
];

if ($conn) {
    $sql1 = "SELECT hd.idhoadon, x.tenxe, x.giathue 
            FROM hoadon hd 
            JOIN xe x ON hd.idxe = x.idxe 
            WHERE hd.idtaikhoan = $idtaikhoan AND hd.trangthai = 0";
    $res1 = mysqli_query($conn, $sql1);
    while ($row = mysqli_fetch_assoc($res1)) {
        $data_from_db[1][] = [
            "idhoadon" => $row['idhoadon'],
            "name" => $row['tenxe'],
            "price" => number_format($row['giathue'], 0, ',', '.') . "đ / ngày",
            "status" => "Đang thuê",
            "statusClass" => "yellow",
            "showReturn" => true
        ];
    }


    $sql2 = "SELECT hd.idhoadon, x.tenxe, hd.tongtien 
            FROM hoadon hd 
            JOIN xe x ON hd.idxe = x.idxe 
            WHERE hd.idtaikhoan = $idtaikhoan AND hd.trangthai = 1";
    $res2 = mysqli_query($conn, $sql2);
    while ($res2 && $row = mysqli_fetch_assoc($res2)) {
        $data_from_db[2][] = [
            "idhoadon" => $row['idhoadon'],
            "name" => $row['tenxe'],
            "price" => "Tổng: " . number_format($row['tongtien'], 0, ',', '.') . "đ",
            "status" => "Đã trả xe",
            "statusClass" => "green",
            "showReturn" => false
        ];
    }
}

$jsonData = json_encode($data_from_db);
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Quản lý thuê xe</title>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="/web_project/View/CSS/nguyen_css_quanly.css" />
    <link rel="stylesheet" href="/web_project/View/CSS/nguyen_css_thueXe.css" />
    <link rel="stylesheet" href="View/CSS/thanhtoan.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
    <div class="part_overlay_quanly">
        <div class="container_table_quanly">
            <div class="tabs">
                <div class="tab active" onclick="switchTab(1)">Đang thuê</div>
                <div class="tab" onclick="switchTab(2)">Đã thuê</div>
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
                    <h5 class="modal-title">XÁC NHẬN THANH TOÁN</h5>
                </div>
                <div class="modal-body">
                    <form id="formTraXe">
                        <input type="hidden" id="idhoadon">
                        <div class="mb-3">
                            <label class="fw-bold">Tên xe:</label>
                            <input type="text" class="form-control bg-light" id="tenxe" readonly>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Số ngày thuê:</label>
                                <input type="text" class="form-control bg-light" id="ngay_thue_du_kien" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Số ngày quá hạn:</label>
                                <input type="text" class="form-control bg-light" id="ngay_qua_han" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Phương thức thanh toán:</label>
                            <select class="form-select" id="phuongthuc">
                                <option value="Tiền mặt">Tiền mặt</option>
                                <option value="Chuyển khoản">Chuyển khoản</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold text-danger">Tổng tiền:</label>
                            <input type="text" class="form-control fw-bold text-danger" id="tong_tien" readonly>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="huyTT()">Hủy</button>
                    <button type="button" class="btn btn-primary" onclick="xacNhanTraXe()">Xác nhận & Hoàn tất</button>
                </div>
            </div>
        </div>
    </div>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="/web_project/View/JS/nguyen_quanly.js"></script>
    <script src="View/JS/thanhtoan.js"></script> 
    <script>
        // Đổ dữ liệu thật từ PHP vào biến JS
        const data = <?php echo $jsonData; ?>;

        function switchTab(tabIndex) {
        // UI Tabs
            document.querySelectorAll(".tab").forEach(t => t.classList.remove("active"));
            const tabs = document.querySelectorAll(".tab");
            if(tabs[tabIndex-1]) tabs[tabIndex-1].classList.add("active");

            const content = document.getElementById("content");
            content.innerHTML = "";

            if (!data[tabIndex] || data[tabIndex].length === 0) {
                content.innerHTML = "<div class='text-center p-4'>Bạn không có lịch sử giao dịch nào ở mục này.</div>";
                return;
            }
            if(tabIndex === 1){
                if (typeof renderTab1 === "function") {
                    renderTab1(1); 
                } else {
                    console.error("Chưa load được file nguyen_quanly.js");
                }
                return;
            }
            
            data[tabIndex].forEach((item) => {
                        content.innerHTML += `
                        <div class="row">
                            <div class="image">
                                <img src="View/image/car_default.png" style="width:100px"> 
                            </div>
                            <div class="info">
                                <div class="name">${item.name} (Mã HĐ: ${item.idhoadon})</div>
                                <div class="price">${item.price}</div>
                                <span class="status ${item.statusClass}">${item.status}</span>
                            </div>
                            <div class="actions">
                                <button class="btn-view">Chi tiết</button>
                                ${item.showReturn ? `<button class="btn-return" onclick="showModal(${item.idhoadon})">Trả xe</button>` : ''}
                            </div>
                        </div>`;
                    });
                }

    window.onload = () => switchTab(1);
    </script>
</body>
</html>