//Hàm entry point được gọi từ switchTab(tabIndex)
async function renderTab1(tabIndex) {
    if (tabIndex !== 1 && tabIndex !== 2) return;

    const content = document.getElementById("content");
    content.innerHTML =
        "<div style='padding:20px;text-align:center'>Đang tải dữ liệu...</div>";

    try {
        const list = await fetchDataTab(tabIndex);

        console.log(list.success);

        renderList(list, tabIndex);
    } catch (err) {
        console.error(err);
        content.innerHTML =
            "<div style='padding:20px;text-align:center;color:red'>Lỗi tải dữ liệu</div>";
    }
}

// Hàm fetch dữ liệu từ server cho tab
async function fetchDataTab(tabIndex) {
    const response = await fetch("/Controller/nguyen_quanly_Controller.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            tab: tabIndex,
        }),
    });
    return await response.json();
}

// Hàm render danh sách item
function renderList(list, tabIndex) {
    const content = document.getElementById("content");
    content.innerHTML = "";

    if (!Array.isArray(list) || list.length === 0) {
        content.innerHTML =
            "<div style='padding:20px;text-align:center'>Không có dữ liệu</div>";
        return;
    }

    list.forEach((item) => {
        content.insertAdjacentHTML("beforeend", renderRow(item, tabIndex));
    });
}

// Hàm render một dòng item
function renderRow(item, tabIndex) {
    return `
    <div class="row" data-idhoadon="${item.idhoadon}">
        <div class="image">
            <img 
                src="${item.image}" 
                alt="${item.name}" 
                onerror="this.src='/View/image/car_default.png'">
        </div>

        <div class="info">
            <div class="name">${item.name} (HĐ: ${item.idhoadon})</div>
            <div class="price">${item.price}</div>
            <span class="status ${item.statusClass}">${item.status}</span>
        </div>
        <div class="actions">
            <button class="btn-view">Xem</button>
            ${
                tabIndex === 1
                    ? `<button class="btn-return">Trả xe</button>`
                    : ""
            }
        </div>
    </div>
    `;
}

// Xử lý sự kiện click trong vùng #content
// document.getElementById("content").addEventListener("click", (e) => {
// });
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("content").addEventListener("click", (e) => {
        const row = e.target.closest(".row");
        if (!row) return;

        const idhoadon = row.dataset.idhoadon;

        // Trả xe
        if (e.target.classList.contains("btn-return")) {
            showModal(idhoadon); // từ thanhtoan.js
            return;
        }

        // Xem chi tiết
        if (e.target.classList.contains("btn-view")) {
            xemChiTietHoaDon(idhoadon);
        }
    });
});
// Hàm xem chi tiết hoá đơn
function xemChiTietHoaDon(idhoadon) {
    console.log("Xem chi tiết hoá đơn:", idhoadon);

    fetch("/Controller/nguyen_thuexe_Controller.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            action: "layhoadon",
            idhoadon,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                alert("lấy thành công!");
            }
            // lấy được 2 dữ liệu
            // lưu ý trạng thái hóa đơn là đang thuê và đã thuê
            data.hoadon;
            data.anhxe;
        });
}
// bên dưới là logic hiển thị ở nguyen_test khi nhấn thuê thì hiện model
// ở đây là xem chi tiết khi nhấn xem chi tiết khi đang thuê và đã thuê
// =========================== logic mới ==================================================
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
    fetch("/Controller/nguyen_thueXe_Controller.php", {
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
            console.log("Ảnh xe:", data.anhxe.duongdan);

            document.getElementById("price_thuexe").innerText = formatVND(
                data.xe.price
            );
            document.getElementById("anhxe_thuexe").src = data.anhxe.duongdan;

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
            document.getElementById("feeMaintain_thuexe").innerText =
                formatVND(MAINTAIN_FEE);
            document.getElementById("sumprice_thuexe").innerText =
                formatVND(TOTAL_COST);
        })
        .catch((err) => console.error("Fetch lỗi:", err));
}
function formatVND(number) {
    return number.toLocaleString("vi-VN") + " đ";
}
// =========================================================================================
