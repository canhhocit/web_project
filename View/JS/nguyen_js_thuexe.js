function initThueXeEvents() {
    const btnThue = document.getElementById("btnthue_thuexe");
    const pickup = document.getElementById("pickup_date_thuexe");
    const ret = document.getElementById("return_date_thuexe");

    if (pickup && ret) {
        pickup.addEventListener("change", calculateRent);
        ret.addEventListener("change", calculateRent);
    }

    if (!btnThue) {
        console.warn("❌ Không tìm thấy nút Thuê ngay");
        return;
    }

    btnThue.onclick = () => {
        if (!validateTerms() || !validateRequiredFields()) return;

        if (!document.getElementById("container_xacnhanthanhtoan")) {
            fetch("../components/nguyen_popup_xacNhan.html")
                .then((res) => res.text())
                .then((html) => {
                    document.body.insertAdjacentHTML("beforeend", html);
                    initModalXacNhan();
                })
                .catch(console.error);
        } else {
            openModalXacNhan();
        }
    };
}

function initModalXacNhan() {
    const modal = document.getElementById("container_xacnhanthanhtoan");
    const btnClose = document.getElementById("btnhuy_xacnhan");
    const btnConfirm = document.getElementById("btnxacnhan_xacnhan");

    openModalXacNhan();

    btnClose.onclick = closeModalXacNhan;

    btnConfirm.onclick = async () => {
        const data = collectFormData();

        try {
            const res = await fetch(
                "../../Controller/nguyen_thueXe_Controller.php",
                {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        action: "confirmRent",
                        data,
                    }),
                }
            )
                .then((res) => res.json)
                .then((data) => {
                    if (data.success) {
                        alert("✅ Thuê xe thành công!");
                        closeModalXacNhan();
                        closeModal();
                    } else {
                        alert("❌ Thuê xe thất bại!");
                    }
                });
            // const result = await res.json();

            // if (result.success) {
            //     alert("✅ Thuê xe thành công!");
            //     closeModalXacNhan();
            //     closeModal();
            // } else {
            //     alert("❌ Thuê xe thất bại!");
            // }
        } catch (err) {
            console.error(err);
            alert("❌ Lỗi kết nối server!");
        }
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

    document.getElementById("rent_date_thuexe").innerText =
        formatDate(pickupVal);
    document.getElementById("unrent_date_thuexe").innerText =
        formatDate(returnVal);

    const days = Math.ceil((returnDate - pickupDate) / (1000 * 60 * 60 * 24));

    document.getElementById("songaythue_thuexe").innerText = days;

    const price = Number(
        document.getElementById("price_thuexe").innerText.replace(/\D/g, "")
    );
    const maintain = Number(
        document
            .getElementById("feeMaintain_thuexe")
            .innerText.replace(/\D/g, "")
    );
    const insurance = Number(
        document
            .getElementById("feeBaoHiem_thuexe")
            .innerText.replace(/\D/g, "")
    );

    const total = days * price + maintain + insurance;

    document.getElementById("sumprice_thuexe").innerText =
        total.toLocaleString("vi-VN") + "đ";
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
        idtaikhoan: 1,
        xeId: document.getElementById("modalOverlay").dataset.xeId,

        pickupDate: document.getElementById("pickup_date_thuexe").value,
        returnDate: document.getElementById("return_date_thuexe").value,
        rentDays: document.getElementById("songaythue_thuexe").innerText,

        fullName: document.getElementById("fullname_modalThuexe").value,
        email: document.getElementById("email_modalThuexe").value,
        phone: document.getElementById("phone_modalThuexe").value,
        cccd: document.getElementById("cccd_modalThuexe").value,
        comment: document.getElementById("note_modalThuexe").value,

        totalCost: document.getElementById("sumprice_thuexe").innerText,
    };
}

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
