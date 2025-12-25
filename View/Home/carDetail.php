
<link rel="stylesheet" href="/web_project/View/CSS/nguyen_css_thueXe.css" />
<link rel="stylesheet" href="/web_project/View/CSS/nguyen_css_popupXacNhan.css" />

<!-- Phần hiển thị chi tiết xe (giữ nguyên) -->
<div class="container mt-4 mb-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $xe['tenxe']; ?></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <?php if (!empty($xe['ds_anh'])): ?>
                    <div id="carouselCar" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" style="border-radius: 5px; overflow: hidden;">
                            <?php foreach ($xe['ds_anh'] as $index => $anh): ?>
                                <div class="carousel-item <?php echo ($index == 0) ? 'active' : ''; ?>">
                                    <img src="View/image/<?php echo $anh; ?>" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Ảnh xe">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($xe['ds_anh']) > 1): ?>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselCar" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselCar" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <img src="https://via.placeholder.com/800x500?text=Chua+co+anh" class="img-fluid rounded" alt="Không có ảnh">
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="text-primary fw-bold mb-1">
                        <?php echo $xe['tenxe']; ?>
                    </h2>
                    <!-- Nút Yêu thích -->
                    <a href="/web_project/index.php?controller=taikhoan&action=favoriteVehicle&id=<?= $idxe ?>"

                        class="btn btn-outline-secondary rounded-circle p-2" title="Yêu thích xe">
                        <span style="font-size: 24px;">
                            <?php echo $exists ? "❤️" : "🤍" ?>
                        </span>
                    </a>
                </div>

                <p class="text-muted"><i class="fa-solid fa-tag"></i> Hãng: <?php echo $xe['tenhangxe']; ?></p>

                <h3 class="text-danger fw-bold my-3">
                    <?php echo number_format($xe['giathue'], 0, ',', '.'); ?> đ/ngày
                </h3>

                <div class="mb-3">
                    <span class="badge bg-info text-dark p-2"><i class="fa-solid fa-car"></i> <?php echo $xe['loaixe']; ?></span>
                    <span class="badge bg-success p-2"><i class="fa-solid fa-check"></i> Xe hoạt động tốt</span>
                </div>

                <hr>

                <div class="thong-tin-chu-xe mb-4">
                    <p class="fw-bold mb-1">Chủ xe:</p>
                    <div class="d-flex align-items-center">
                        <div class="bg-secondary rounded-circle d-flex justify-content-center align-items-center text-white" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div class="ms-2">
                            <h6 class="mb-0"><?php echo !empty($xe['tenchuxe']) ? $xe['tenchuxe'] : 'Ẩn danh'; ?></h6>
                            <small class="text-muted">SĐT: <?php echo !empty($xe['sdt']) ? $xe['sdt'] : '***'; ?></small>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <?php if (!isset($_SESSION['idtaikhoan'])): ?>
                        <!-- Chưa đăng nhập -->
                        <a href="index.php?controller=taikhoan&action=login" class="btn btn-warning btn-lg fw-bold">
                            <i class="fa-solid fa-right-to-bracket"></i> Đăng nhập để thuê xe
                        </a>
                    <?php elseif (isset($isOwner) && $isOwner): ?>
                        <!-- Xe của chính mình -->
                        <button class="btn btn-secondary btn-lg fw-bold" disabled style="cursor: not-allowed;">
                            <i class="fa-solid fa-user-check"></i> Đây là xe của bạn
                        </button>
                    <?php else: ?>
                        <!-- Đã đăng nhập và không phải xe của mình -->
                        <button onclick="openRentalModal(<?php echo $xe['idxe']; ?>)" class="btn btn-primary btn-lg fw-bold">
                            <i class="fa-regular fa-calendar-check"></i> Thuê Xe Ngay
                        </button>
                    <?php endif; ?>

                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="fw-bold border-bottom pb-2">Mô tả xe</h4>
                <p class="mt-3" style="white-space: pre-line;">
                    <?php echo !empty($xe['mota']) ? $xe['mota'] : 'Chủ xe chưa thêm mô tả chi tiết.'; ?>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- ========== MODAL THUÊ XE (Tách riêng ở đây) ========== -->
<div class="modal-overlay" id="modalOverlay">
    <div class="container_modalThuexe">
        <span id="closeModal" class="close-btn">&times;</span>
        <div class="container_contentLeft_mdThuexe">
            <h2 class="section-title" id="title_xe_thuexe">
                Totoro my's neighborhood
            </h2>
            <hr />
            <br /><br />
            <div class="location-section">
                <h3 class="sub-title">Điểm lấy xe</h3>
                <div class="form-row">
                    <div class="input-group">
                        <select id="pickup_thuexe" required>
                            <option value="" disabled selected hidden></option>
                            <option value="office">Văn phòng cho thuê</option>
                            <option value="airport">Sân bay</option>
                            <option value="city">Trung tâm thành phố</option>
                        </select>
                        <label for="pickup">Nơi lấy xe</label>
                    </div>
                    <div class="input-group">
                        <input
                            type="date"
                            id="pickup_date_thuexe"
                            placeholder=" "
                        />
                        <label for="pickup_date_thuexe">Thời gian lấy xe</label>
                    </div>
                </div>
                <h3 class="sub-title" style="margin-top: 20px">Điểm trả xe</h3>
                <div class="form-row">
                    <div class="input-group">
                        <select id="dropoff_thuexe" required>
                            <option value="" disabled selected hidden></option>
                            <option value="office">Văn phòng cho thuê</option>
                            <option value="airport">Sân bay</option>
                            <option value="city">Trung tâm thành phố</option>
                        </select>
                        <label for="dropoff">Nơi trả xe</label>
                    </div>
                    <div class="input-group">
                        <input
                            type="date"
                            id="return_date_thuexe"
                            placeholder=" "
                        />
                        <label for="return_date_thuexe">Thời gian trả xe</label>
                    </div>
                </div>
            </div>
            <div class="driver-details-section">
                <div class="useinf_titleu">
                    <h3 class="sub-title">Thông tin người lái chính</h3>
                    <label for="useinforable" class="custom-check-row">
                        <input type="checkbox" id="useinforable" />
                        <span>Sử dụng thông tin của tôi</span>
                    </label>
                </div>
                <div class="input-group">
                    <input
                        type="text"
                        id="fullname_modalThuexe"
                        class="input-field"
                        placeholder=" "
                        autocomplete="off"
                    />
                    <label for="fullname_modalThuexe">Full name*</label>
                </div>
                <div class="input-group">
                    <input type="text" id="email_modalThuexe" placeholder=" " />
                    <label for="email_modalThuexe">Email*</label>
                </div>
                <div class="input-group">
                    <input
                        type="tel"
                        id="phone_modalThuexe"
                        placeholder=" "
                        autocomplete="tel"
                    />
                    <label for="phone_modalThuexe">Contact phone number*</label>
                </div>
                <div class="input-group">
                    <input
                        type="text"
                        id="cccd_modalThuexe"
                        class="input-field"
                        placeholder=" "
                        autocomplete="off"
                    />
                    <label for="cccd_modalThuexe">Cccd*</label>
                </div>
                <div class="input-group" style="margin-top: 20px">
                    <textarea
                        id="note_modalThuexe"
                        class="input-field"
                        placeholder=" "
                        rows="4"
                    ></textarea>
                    <label for="note_modalThuexe">Comment</label>
                </div>
                <div class="terms-container">
                    <label class="custom-check-row">
                        <input type="checkbox" id="terms_thuexe" />
                        <span class="checkmark"></span>
                        <span
                            >I accept
                            <a href="#" class="link-highlight"
                                >the Terms of use</a
                            ></span
                        >
                    </label>
                    <label class="custom-check-row">
                        <input type="checkbox" id="policy_thuexe" />
                        <span class="checkmark"></span>
                        <span
                            >I have read
                            <a href="#" class="link-highlight"
                                >the Privacy policy</a
                            ></span
                        >
                    </label>
                    <div class="scroll-spacer"></div>
                </div>
            </div>
        </div>
        <div class="container_contentRight_mdThuexe">
            <div class="img_contentRight">
                <img
                    id="anhxe_thuexe"
                    src="../image/camera.png"
                    alt="Car Image"
                />
            </div>
            <div class="sticky-wrapper">
                <h3 class="sub-title">Thời gian</h3>
                <div class="cost-row">
                    <span>Ngày thuê xe</span>
                    <span id="rent_date_thuexe">dd/mm/yyyy</span>
                </div>
                <div class="cost-row">
                    <span>Ngày trả xe</span>
                    <span id="unrent_date_thuexe">dd/mm/yyyy</span>
                </div>
                <div class="cost-row">
                    <span>Số ngày thuê</span>
                    <span id="songaythue_thuexe">0</span>
                </div>
                <div class="divider"></div>
                <h3 class="sub-title">Chi phí</h3>
                <div class="cost-row">
                    <span>Giá thuê xe / ngày</span>
                    <span id="price_thuexe">200.000đ</span>
                </div>
                <div class="cost-row">
                    <span>Phí bảo trì</span>
                    <span id="feeMaintain_thuexe">50.000đ</span>
                </div>
                <div class="cost-row">
                    <span>Phí bảo hiểm</span>
                    <span id="feeBaoHiem_thuexe">50.000đ</span>
                </div>
                <div class="cost-row">
                    <span style="color: red; font-size: 16px; font-weight: bold"
                        >Chi phí dự kiến</span
                    >
                    <span
                        id="sumprice_thuexe"
                        style="color: red; font-size: 16px; font-weight: bold"
                        >300.000đ</span
                    >
                </div>
                <div class="btn_pay_container">
                    <button id="btnthue_thuexe" type="button" class="btn-pay">
                        Thuê ngay
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="container_xacnhanthanhtoan" class="container_xacnhanthanhtoan">
    <div class="modal_xacnhanthanhtoan">
        <h1 id="title_xacnhan">Xác nhận thanh toán với chủ thuê?</h1>
        <div class="div_button_xacnhanthanhtoan">
            <button id="btnhuy_xacnhan" type="button">Hủy</button>
            <button id="btnxacnhan_xacnhan" type="button">Xác nhận</button>
        </div>
    </div>
</div>

<!-- ========== JAVASCRIPT ========== -->
<script>
var RENT_PRICE = 0;
var MAINTAIN_FEE = 0;
var INSURANCE_FEE = 0;
var TOTAL_COST = 0;
var CURENTUSERID = 0;

// Mở modal
function openRentalModal(xeId) {
    const modal = document.getElementById("modalOverlay");
    modal.classList.add("active");
    document.body.style.overflow = "hidden";
    console.log("Xe ID:", xeId);
    modal.dataset.xeId = xeId;

    document.getElementById("closeModal").onclick = closeModal;
    modal.onclick = function(e) {
        if (e.target === modal) closeModal();
    };

    loadProductData(xeId);
}

// Đóng modal
function closeModal() {
    const modal = document.getElementById("modalOverlay");
    modal.classList.remove("active");
    document.body.style.overflow = "auto";
}

// Load thông tin xe
function loadProductData(xeId) {
    fetch("/web_project/Controller/nguyen_thueXe_Controller.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action: "openModal", id: xeId })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById("title_xe_thuexe").innerText = data.xe.name + " - " + xeId;
        document.getElementById("price_thuexe").innerText = formatVND(data.xe.price);
        document.getElementById("anhxe_thuexe").src = "/web_project/View/image/" + data.anhxe.duongdan;

        CURENTUSERID = data.curentUserID;//tìm cách lấy id uuser
        window.CURRENT_USER_INFO = data.user; 

        RENT_PRICE = data.xe.price;
        MAINTAIN_FEE = data.xe.type === "car" ? 100000 : 50000;
        INSURANCE_FEE = data.xe.type === "car" ? 100000 : 50000;
        TOTAL_COST = RENT_PRICE + MAINTAIN_FEE + INSURANCE_FEE;
        
        document.getElementById("feeBaoHiem_thuexe").innerText = formatVND(INSURANCE_FEE);
        document.getElementById("feeMaintain_thuexe").innerText = formatVND(MAINTAIN_FEE);
        document.getElementById("sumprice_thuexe").innerText = formatVND(TOTAL_COST);
    })
    .catch(err => console.error("Lỗi:", err));
}

function formatVND(number) {
    return number.toLocaleString("vi-VN") + " đ";
}
</script>

<script src="/web_project/View/JS/nguyen_js_thuexe.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        initThueXeEvents();
    });
</script>