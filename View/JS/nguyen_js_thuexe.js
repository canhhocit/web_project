function initThueXeEvents() {
    const btnThue = document.getElementById("btnthue_thuexe");
    const pickup = document.getElementById("pickup_date_thuexe");
    const ret = document.getElementById("return_date_thuexe");
    const chk = document.getElementById("useinforable");

    chk.addEventListener("change", function () {
        if (this.checked) {
            fillUserInfo();
        } else {
            clearUserInfo();
        }
    });

    console.log(CURENTUSERID);
    if (pickup && ret) {
        pickup.addEventListener("change", calculateRent);
        ret.addEventListener("change", calculateRent);
    }

    if (!btnThue) {
        console.warn("❌ Không tìm thấy nút Thuê ngay");
        return;
    }
    btnThue.addEventListener("click", () => {
        openModalXacNhan();
    });
    initModalXacNhan();
}

function initModalXacNhan() {
    const modal = document.getElementById("container_xacnhanthanhtoan");
    const btnClose = document.getElementById("btnhuy_xacnhan");
    const btnConfirm = document.getElementById("btnxacnhan_xacnhan");

    if (!modal || !btnClose || !btnConfirm) {
        console.error("❌ Modal xác nhận thiếu element");
        return;
    }

    btnClose.onclick = closeModalXacNhan;

    btnConfirm.onclick = () => {
        if (!validateTerms() || !validateRequiredFields()) return;
        const data = collectFormData();

        fetch("/web_project/Controller/nguyen_thueXe_Controller.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                action: "taohoadon",
                data,
            }),
        })
            .then((res) => res.json())
            .then((result) => {
                console.log("✅ Kết quả cuối cùng:", result); // In kết quả đã xử lý
                if (result.success) {
                    alert("✅ Thuê xe thành công!");
                    resetForm();
                    closeModalXacNhan();
                    closeModal();
                } else {
                    alert("❌ Thuê xe thất bại!");
                }
            })
            .catch(() => alert("❌ Lỗi kết nối server!"));
    };

    modal.onclick = (e) => {
        if (e.target === modal) closeModalXacNhan();
    };
}

function closeModalXacNhan() {
    const modal = document.getElementById("container_xacnhanthanhtoan");
    modal.classList.remove("active");
    // document.body.style.overflow = "auto"; // cho phép cuộn trang chính trở lại
}

function openModalXacNhan() {
    const modal = document.getElementById("container_xacnhanthanhtoan");
    modal.classList.add("active");
    // document.body.style.overflow = "hidden"; // chặn cuộn trang chính
}

function calculateRent() {
    const pickupVal = document.getElementById("pickup_date_thuexe").value;
    const returnVal = document.getElementById("return_date_thuexe").value;

    if (!pickupVal || !returnVal) return;
    const pickupDate = new Date(pickupVal);
    const returnDate = new Date(returnVal);

    if (returnDate <= pickupDate) {
        alert("Ngày trả phải sau ngày thuê!");
        return;
    }

    const days = Math.ceil((returnDate - pickupDate) / (1000 * 60 * 60 * 24));
    document.getElementById("songaythue_thuexe").innerText = days;

    document.getElementById("rent_date_thuexe").innerText =
        formatDate(pickupVal);

    document.getElementById("unrent_date_thuexe").innerText =
        formatDate(returnVal);

    TOTAL_COST = days * RENT_PRICE + MAINTAIN_FEE + INSURANCE_FEE;

    document.getElementById("sumprice_thuexe").innerText =
        formatVND(TOTAL_COST);
}

//2 checkbox
function canRent() {
    return (
        document.getElementById("terms_thuexe").checked &&
        document.getElementById("policy_thuexe").checked
    );
}

function collectFormData() {
    return {
        // idtaikhoan: CURENTUSERID, // nữa phải thay cái này
        idtaikhoan: 3, // nữa phải thay cái này
        idxe: document.getElementById("modalOverlay").dataset.xeId,

        diemlay: document.getElementById("pickup_thuexe").value,
        diemtra: document.getElementById("dropoff_thuexe").value,
        ngaymuon: document.getElementById("pickup_date_thuexe").value,
        ngaytra: document.getElementById("return_date_thuexe").value,
        // rentDays: document.getElementById("songaythue_thuexe").innerText,

        fullName: document.getElementById("fullname_modalThuexe").value,
        email: document.getElementById("email_modalThuexe").value,
        phone: document.getElementById("phone_modalThuexe").value,
        cccd: document.getElementById("cccd_modalThuexe").value,
        comment: document.getElementById("note_modalThuexe").value,

        totalCost: TOTAL_COST,
    };
    // dữ liệu trả về phải juan , nhiều ràng buộc khóa ngoại vc, check ỉa. idtaikhoan, idxe
}

function displayRentConfirmation(data) {}

function validateRequiredFields() {
    const REQUIRED_FIELDS = [
        "pickup_date_thuexe",
        "return_date_thuexe",
        "fullname_modalThuexe",
        "email_modalThuexe",
        "phone_modalThuexe",
        "cccd_modalThuexe",
    ];

    for (let id of REQUIRED_FIELDS) {
        const el = document.getElementById(id);
        if (!el || el.value.trim() === "") {
            alert("Vui lòng nhập đầy đủ thông tin!");
            el?.focus();
            return false;
        }
    }
    return true;
}

function validateTerms() {
    if (!canRent()) {
        alert("Vui lòng đồng ý điều lệ và chính sách!");
        return false;
    }
    return true;
}

function formatDate(dateStr) {
    const d = new Date(dateStr);
    return d.toLocaleDateString("vi-VN");
}

function formatVND(number) {
    return number.toLocaleString("vi-VN") + " đ";
}

function resetForm() {
    // 1. Reset input text & date
    const INPUT_IDS = [
        "pickup_thuexe",
        "dropoff_thuexe",
        "pickup_date_thuexe",
        "return_date_thuexe",
        "fullname_modalThuexe",
        "email_modalThuexe",
        "phone_modalThuexe",
        "cccd_modalThuexe",
        "note_modalThuexe",
    ];

    INPUT_IDS.forEach((id) => {
        const el = document.getElementById(id);
        if (el) el.value = "";
    });

    // 2. Reset checkbox
    const CHECKBOX_IDS = ["terms_thuexe", "policy_thuexe"];
    CHECKBOX_IDS.forEach((id) => {
        const cb = document.getElementById(id);
        if (cb) cb.checked = false;
    });

    // 3. Reset hiển thị ngày thuê & tiền
    document.getElementById("songaythue_thuexe").innerText = "0";
    document.getElementById("sumprice_thuexe").innerText = formatVND(0);

    // 4. Reset biến global
    TOTAL_COST = 0;
}

function fillUserInfo() {
    if (!window.CURRENT_USER_INFO) return;

    document.getElementById("input_name").value =
        window.CURRENT_USER_INFO.name || "";

    document.getElementById("input_phone").value =
        window.CURRENT_USER_INFO.phone || "";

    document.getElementById("input_cccd").value =
        window.CURRENT_USER_INFO.cccd || "";

    document.getElementById("input_address").value =
        window.CURRENT_USER_INFO.address || "";
}

function clearUserInfo() {
    document.getElementById("input_name").value = "";
    document.getElementById("input_phone").value = "";
    document.getElementById("input_cccd").value = "";
    document.getElementById("input_address").value = "";
}
