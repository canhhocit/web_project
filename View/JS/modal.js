const openBtns = document.querySelectorAll(".open-modal-btn");
const closeIcons = document.querySelectorAll(".close");
const closeBtns = document.querySelectorAll(".close-btn");

// Mở modal
openBtns.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    e.preventDefault();
    const modalId = btn.getAttribute("data-modal");
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.add("active");
    }
  });
});

// Đóng modal khi click nút X
closeIcons.forEach((icon) => {
  icon.addEventListener("click", () => {
    const modal = icon.closest(".modal");
    if (modal) {
      modal.classList.remove("active");
    }
  });
});

// Đóng modal khi click nút Đóng
closeBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
    const modal = btn.closest(".modal");
    if (modal) {
      modal.classList.remove("active");
    }
  });
});

// Đóng modal khi click ra ngoài
document.querySelectorAll(".modal").forEach((modal) => {
  modal.addEventListener("click", (e) => {
    if (e.target === modal) {
      let oldpass = document.getElementById("oldpass").value.trim();
      let newpass = document.getElementById("newpass").value.trim();
      let confpass = document.getElementById("confnewpass").value.trim();
      if (oldpass !== "" || newpass !== "" || confpass !== "") {
        if (
          confirm("Dữ liệu sẽ mất khi bạn chưa lưu. Bạn có chắc muốn đóng?")
        ) {
          modal.classList.remove("active");
        }
        return; // Không đóng nếu user chọn Cancel
      }
      modal.classList.remove("active");
    }
  });
});
