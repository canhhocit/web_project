        <link rel="stylesheet" href="../CSS/nguyen_css_thueXe.css" />

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
<script>
    var RENT_PRICE = 0;
    var MAINTAIN_FEE = 0;
    var INSURANCE_FEE = 0;
    var TOTAL_COST = 0;

    // --- 2. HÀM MỞ MODAL (Hứng sự kiện onclick từ HTML) ---
    function openRentalModal(xeId) {
        // Kiểm tra xem modal đã có trong trang chưa
        const modalElement = document.getElementById("modalOverlay");

        if (!modalElement) {
            // Chưa có -> Tải file HTML về
            // LƯU Ý: Sửa đường dẫn 'View/nguyen_modal_thueXe.html' cho đúng thư mục của bạn
            fetch("../../components/nguyen_modal_thueXe.html") 
                .then((res) => {
                    if (!res.ok) throw new Error("Không tải được modal HTML");
                    return res.text();
                })
                .then((html) => {
                    document.body.insertAdjacentHTML("beforeend", html);
                    const modal = document.getElementById("modalOverlay");
                    document.body.appendChild(modal);
                    requestAnimationFrame(() => {
                        initModal(xeId);
                    
                        if (typeof initThueXeEvents === "function") {
                            initThueXeEvents();
                        }
                    });
                })
                .catch(err => console.error(err));
        } else {
            // Đã có -> Mở lên và cập nhật ID
            modalElement.dataset.xeId = xeId; 
            initModal(xeId);
        }
    }
    function initModal(xeId) {
                const modal = document.getElementById("modalOverlay");
                const btnClose = document.getElementById("closeModal");

                modal.dataset.xeId = xeId;
                openModal(xeId);
                btnClose.onclick = () => closeModal();
                // btnClose.addEventListener("click", () => {
                //     closeModal();
                // });

                // modal.click = (e) => {
                //     if (e.target === modal) closeModal();
                // };
                modal.addEventListener("click", (e) => {
                    if (e.target === modal) closeModal();
                });
            }

            function openModal(xeId) {
                const modal = document.getElementById("modalOverlay");

                modal.classList.add("active");
                document.body.style.overflow = "hidden"; // chặn cuộn trang chính

                loadProductData(xeId);
            }
            function closeModal() {
                const modal = document.getElementById("modalOverlay");
                modal.classList.remove("active");
                document.body.style.overflow = "auto";
            }
            function loadProductData(xeId) {
                fetch("../../Controller/nguyen_thueXe_Controller.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        action: "openModal",
                        id: xeId,
                    }),
                })
                    .then((res) => {
                        if (!res.ok) throw new Error("HTTP " + res.status);
                        return res.json();
                    })
                    .then((data) => {
                        // console.log("DATA:", data);
                        document.getElementById("title_xe_thuexe").innerText =
                            data.xe.name + " - " + xeId;
                        // console.log("Ảnh xe:", data.anhxe.duongdan);

                        document.getElementById("price_thuexe").innerText =
                            formatVND(data.xe.price);
                        document.getElementById("anhxe_thuexe").src =
                            data.anhxe.duongdan;

                        RENT_PRICE = data.xe.price;

                        if (data.xe.type === "car") {
                            MAINTAIN_FEE = 100000;
                            INSURANCE_FEE = 100000;
                        } else {
                            MAINTAIN_FEE = 50000;
                            INSURANCE_FEE = 50000;
                        }
                        TOTAL_COST = RENT_PRICE + MAINTAIN_FEE + INSURANCE_FEE;
                        document.getElementById("feeBaoHiem_thuexe").innerText =
                            formatVND(INSURANCE_FEE);
                        document.getElementById(
                            "feeMaintain_thuexe"
                        ).innerText = formatVND(MAINTAIN_FEE);
                        document.getElementById("sumprice_thuexe").innerText =
                            formatVND(TOTAL_COST);
                    })
                    .catch((err) => console.error("Fetch lỗi:", err));
            }
            function formatVND(number) {
                return number.toLocaleString("vi-VN") + " đ";
            }
</script>
        <script src="../JS/nguyen_js_thuexe.js"></script>
        <script src="../JS/nguyen_js_xacNhan.js"></script>