let currentTab = 2; // trang này không có tab, nhưng để giữ logic giống quanly.js (tab2 = đã thuê). Bạn có thể đổi nếu cần.

console.log("liên kết file thành công");

async function renderTabNguoiThue() {
    const content = document.getElementById("content");
    content.innerHTML =
        "<div style='padding:20px;text-align:center'>Đang tải dữ liệu...</div>";

    try {
        const data = await fetchNguoiThue();
        console.log("API trả về:", data);
        if (!data?.success) {
            content.innerHTML = `<div style='color:red;text-align:center'>${
                data?.message || "Không lấy được dữ liệu"
            }</div>`;
            return;
        }

        const list = data.data;
        renderListNguoiThue(list);
    } catch (err) {
        console.error(err);
        document.getElementById("content").innerHTML =
            "<div style='color:red;text-align:center'>Lỗi kết nối server</div>";
    }
}

async function fetchNguoiThue() {
    const response = await fetch(
        "/web_project/Controller/nguyen_thueXe_Controller.php",
        {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ action: "laythongtinnguoithue" }),
        }
    );
    return await response.json();
}

function renderListNguoiThue(list) {
    const content = document.getElementById("content");
    content.innerHTML = "";

    if (!Array.isArray(list) || list.length === 0) {
        content.innerHTML =
            "<div style='padding:20px;text-align:center'>Chưa có ai thuê xe.</div>";
        return;
    }

    // Map từ cấu trúc API -> đúng format renderRow() đang dùng
    list.forEach((raw) => {
        const hd = raw.hoadon || {};
        const xe0 = Array.isArray(raw.xe) ? raw.xe[0] : raw.xe; // bạn đang trả xe là mảng 1 phần tử
        const img0 = Array.isArray(raw.anhxe) ? raw.anhxe[0] : raw.anhxe;

        const item = {
            idhoadon: raw.idhoadon ?? hd.idhoadon,
            idxe: raw.idxe ?? raw.idxe ?? hd.idxe ?? raw.idxe, // không bắt buộc
            name: xe0?.tenxe ?? `Xe #${raw.idxe ?? raw.idxe ?? raw.idxe ?? ""}`,
            image: img0?.duongdan
                ? `/web_project/View/image/${img0.duongdan}`
                : "/web_project/View/image/none_image.png",
            price: hd.tongtien ?? 0,
            status: Number(hd.trangthai) === 0 ? "Đang thuê" : "Đã trả",
            statusClass: Number(hd.trangthai) === 0 ? "renting" : "returned",
        };

        content.insertAdjacentHTML("beforeend", renderRow(item));
    });
}

function renderRow(item) {
    const priceText = item.priceText ?? formatVND(item.price);

    return `
    <div class="row" data-idhoadon="${item.idhoadon}">
        <div class="image">
            <img
                src="${item.image}"
                alt="${item.name}"
                onerror="this.onerror=null; this.src='/web_project/View/image/none_image.png'"
            >
        </div>

        <div class="info">
            <div class="name" style="font-weight:bold">${item.name}</div>
            <div class="sub-info" style="font-size:0.9em; color:#666">
                Mã HĐ: ${item.idhoadon}
            </div>
            <div class="price" style="color:red; font-weight:bold">${priceText}</div>
            <span class="status ${item.statusClass}">${item.status}</span>
            
        </div>
            <div class="actions">
            <button class="btn-view" style="margin-right:5px">Chi tiết</button>
            </div>
    </div>
    `;
}

// ===================== EVENT DELEGATION (giống quanly.js) =====================
document.addEventListener("DOMContentLoaded", () => {
    renderTabNguoiThue();

    document.getElementById("content").addEventListener("click", (e) => {
        const row = e.target.closest(".row");
        if (!row) return;

        const idhoadon = row.dataset.idhoadon;

        // Trang này chỉ cần xem chi tiết
        if (e.target.classList.contains("btn-view")) {
            xemChiTietHoaDon(idhoadon);
        }
    });
});
function xemChiTietHoaDon() {
    alert("Chức năng xem chi tiết hóa đơn chưa được triển khai.");
}
function formatVND(amount) {
    const n = Number(amount ?? 0);
    return n.toLocaleString("vi-VN") + " ₫";
}
