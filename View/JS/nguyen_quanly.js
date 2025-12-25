async function renderTab1(tabIndex) {
    if (tabIndex !== 1) return;

    const content = document.getElementById("content");
    content.innerHTML =
        "<div style='padding:20px;text-align:center'>Đang tải dữ liệu...</div>";

    try {
        const list = await fetchDataTab(tabIndex);

        if (list.status === "error") {
            content.innerHTML = `<div style='color:red;text-align:center'>${list.message}</div>`;
            return;
        }

        renderList(list, tabIndex);
    } catch (err) {
        console.error(err);
        content.innerHTML =
            "<div style='color:red;text-align:center'>Lỗi kết nối server</div>";
    }
}

async function fetchDataTab(tabIndex) {
    const response = await fetch(
        "/web_project/Controller/nguyen_quanly_Controller.php",
        {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ tab: tabIndex }),
        }
    );
    return await response.json();
}

function renderList(list, tabIndex) {
    const content = document.getElementById("content");
    content.innerHTML = "";

    if (!Array.isArray(list) || list.length === 0) {
        content.innerHTML =
            "<div style='padding:20px;text-align:center'>Bạn chưa thuê xe nào.</div>";
        return;
    }

    list.forEach((item) => {
        content.insertAdjacentHTML("beforeend", renderRow(item));
    });
}

function renderRow(item) {
    return `
    <div class="row" data-idhoadon="${item.idhoadon}">
        <div class="image">
            <img 
                src="${item.image}" 
                alt="${item.name}" 
                onerror="this.onerror=null; this.src='/web_project/View/image/none_image.png'" 
                style="width:120px; height:80px; object-fit:cover; border-radius:5px;"
            >
        </div>

        <div class="info">
            <div class="name" style="font-weight:bold">${item.name}</div>
            <div class="sub-info" style="font-size:0.9em; color:#666">Mã HĐ: ${item.idhoadon}</div>
            <div class="price" style="color:red; font-weight:bold">${item.price}</div>
            <span class="status ${item.statusClass}" style="padding: 2px 8px; border-radius:10px; font-size:0.8em; background:#fff3cd; color:#856404">${item.status}</span>
        </div>
        
        <div class="actions">
            <button class="btn-view" style="margin-right:5px">Chi tiết</button>
            
            <button class="btn-return">Trả xe</button> 
        </div>
    </div>
    `;
}

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
function xemChiTietHoaDon(idhoadon) {
    console.log("Xem chi tiết hoá đơn:", idhoadon);

    const existingModal = document.getElementById("modalOverlay_xemhd");

    if (!existingModal) {
        fetch("/web_project/components/nguyen_modal_xemhd.html")
            .then((res) => {
                if (!res.ok) throw new Error("Không tải được file modal");
                return res.text();
            })
            .then((html) => {
                document.body.insertAdjacentHTML("beforeend", html);

                initModal(idhoadon);
            })
            .catch((err) => console.error("Lỗi tải modal:", err));
    } else {
        openModal(idhoadon);
    }
}
// bên dưới là logic hiển thị ở nguyen_test khi nhấn thuê thì hiện model
// ở đây là xem chi tiết khi nhấn xem chi tiết khi đang thuê và đã thuê
// =========================== logic mới ==================================================
function initModal(idhoadon) {
    const modal = document.getElementById("modalOverlay_xemhd");
    const btnClose = document.getElementById("closeModal_xemhd");

    modal.dataset.xeId = idhoadon;
    openModal(idhoadon);
    btnClose.onclick = () => closeModal();

    modal.addEventListener("click", (e) => {
        if (e.target === modal) closeModal();
    });
}

function openModal(idhoadon) {
    const modal = document.getElementById("modalOverlay_xemhd");

    modal.classList.add("active");
    document.body.style.overflow = "hidden";

    loadProductData(idhoadon);
}

function closeModal() {
    const modal = document.getElementById("modalOverlay_xemhd");
    modal.classList.remove("active");
    document.body.style.overflow = "auto";
}

function loadProductData(idhoadon) {
    fetch("/web_project/Controller/nguyen_thueXe_Controller.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            action: "layhoadon",
            idhoadon: idhoadon,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (!data.success) {
                alert("Lỗi: " + (data.message || "Không lấy được dữ liệu"));
                return;
            }

            const hd = data.hoadon;
            const anh = data.anhxe;

            document.getElementById("title_xe_xemhd").innerText =
                "Chi tiết hóa đơn #" + hd.idhoadon;

            document.getElementById("anhxe_xemhd").src = anh.duongdan;

            document.getElementById("pickup_xemhd").value = hd.diemlay || "";
            document.getElementById("dropoff_xemhd").value = hd.diemtra || "";
            document.getElementById("pickup_date_xemhd").value = hd.ngaymuon;
            document.getElementById("return_date_xemhd").value = hd.ngaytra;

            document.getElementById("fullname_modalXemhd").value = hd.hoten;
            document.getElementById("email_modalXemhd").value = hd.email;
            document.getElementById("phone_modalXemhd").value = hd.sdt;
            document.getElementById("cccd_modalXemhd").value = hd.cccd;
            document.getElementById("note_modalXemhd").value = hd.ghichu;

            document.getElementById("rent_date_xemhd").innerText = formatDate(
                hd.ngaymuon
            );
            document.getElementById("unrent_date_xemhd").innerText = formatDate(
                hd.ngaytra
            );

            const days = Math.ceil(
                (new Date(hd.ngaytra) - new Date(hd.ngaymuon)) /
                    (1000 * 60 * 60 * 24)
            );
            document.getElementById("songaythue_xemhd").innerText = days;

            document.getElementById("sumprice_xemhd").innerText = formatVND(
                hd.tongtien
            );

            const giaMotNgay = hd.tongtien / days;
            document.getElementById("price_xemhd").innerText =
                formatVND(giaMotNgay);

            const btnThue = document.getElementById("btnthue_xemhd");
            if (btnThue) btnThue.style.display = "none";

            disableInputs();
            adjustUIForViewMode();
        })
        .catch((err) => console.error("Fetch lỗi:", err));
}

function disableInputs() {
    const inputs = document.querySelectorAll(
        "#modalOverlay_xemhd input, #modalOverlay_xemhd select, #modalOverlay_xemhd textarea"
    );
    inputs.forEach((input) => {
        input.disabled = true;
        input.style.backgroundColor = "#2a2a2a";
        input.style.color = "#bbbbbb";
    });
}

function formatDate(dateStr) {
    if (!dateStr) return "";
    const d = new Date(dateStr);
    return d.toLocaleDateString("vi-VN");
}
function adjustUIForViewMode() {
    const useInfoRow = document.querySelector(
        ".useinf_titleu .custom-check-row"
    );
    if (useInfoRow) useInfoRow.style.display = "none";

    const termsContainer = document.querySelector(".terms-container");
    if (termsContainer) termsContainer.style.display = "none";

    const btnPay = document.getElementById("btnthue_xemhd");
    if (btnPay) {
        btnPay.innerText = "Quay về";
        btnPay.style.display = "block";
        btnPay.style.backgroundColor = "#6c757d";
        btnPay.onclick = closeModal;
    }
}
// =========================================================================================
